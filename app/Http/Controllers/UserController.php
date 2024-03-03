<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;


class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.users', [
            'users' => $users
        ]);
    }

    public function newuser()
    {
        $roles = Role::select("id", "name", "display_name")->get();
        return view('users.newuser', compact('roles'));
    }

    public function newuserstore(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'unique:users'],
            'role' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            //code...

            $user = User::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'picture' => "picture"
            ]);

            if ($user)
                $user->addRole($request->role);

            DB::commit();
            return redirect()->back()->with('feedback', 'User added successfully!');
        } catch (\Exception $e) {

            Log::info("failed to create a new user: " . $e->getMessage());
            DB::rollback();
            return back()->with("error", "Failed to create a new user");
        }
    }

    public function DeleteUser($id)
    {
        $user  = User::with(['customers', 'invoices'])->find($id);
        if (!$user) return back()->with('warning_feedback', "user not found");

        try {
            $user->invoices()->delete();

            // Delete associated customers
            $user->customers()->delete();
            //code...
            $user->delete();
            return redirect()->route("users")->with("feedback", "User deleted successfully");
        } catch (\Throwable $th) {
            LOG::error($th->getMessage());
            return back()->with('warning_feedback', "Failed to delete user");
        }
    }
    public function DeleteCustomer($id)
    {
        $user  = Customer::with('invoices')->find($id);
        if (!$user) return back()->with('warning_feedback', "Customer not found");

        try {
            $user->invoices()->delete();


            $user->delete();
            return redirect()->route("customers")->with("feedback", "Customer deleted successfully");
        } catch (\Throwable $th) {
            LOG::error($th->getMessage());
            return back()->with('warning_feedback', "Failed to delete a customer");
        }
    }
    // VIEW USER
    public function viewuser($id)
    {
        $invoices = [];

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $user = User::find($id);
        if (!$user) return back()->with("error", "User not found");
        $usercustomers  = Customer::where("user_id", $id)->count();


        $invoicewithuser = Invoice::where("user_id", $id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->select("amount", "credit_adjustment", "debit_adjustment", "amount_paid", "balance")
            ->get();


        $totalAmount = $invoicewithuser->sum('amount');
        $totalCreditAdjustment = $invoicewithuser->sum('credit_adjustment');
        $totalDebitAdjustment = $invoicewithuser->sum('debit_adjustment');

        $invoices["target"] = ($totalAmount + $totalDebitAdjustment) - $totalCreditAdjustment;
        $invoices["total_collected"] = $invoicewithuser->sum("amount_paid");
        $invoices["total_remaining"] = $invoices["target"] - $invoices["total_collected"]; // $invoices->sum("balance");
        $invoices["customers"] = $usercustomers;
        //dd($invoices);
        return view('users.viewuser', [
            'user' => $user,
            'invoices' => $invoices
        ]);
    }
    //VIEW USER

    //EDIT USER
    public function edituser($id)
    {
        $user = User::find($id);
        return view('users.edituser', [
            'user' => $user
        ]);
    }
    //EDIT USER

    //EDIT USER STORE
    public function edituserstore(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required'],
            'role' => ['required'],
        ]);

        //check email
        if ($user->email == $request->email) {
            //do nothing
        } else {
            $user->email = $request->email;
        }

        //check phone_number
        if ($user->phone_number == $request->phone_number) {
            //do nothing
        } else {
            $user->phone_number = $request->phone_number;
        }


        $user->name = $request->name;

        $old_role = $user->roles->first()->name;

        //remove old role
        $user->detachRole($old_role);

        $user->attachRole($request->role);

        $user->update();

        return redirect()->back()->with('feedback', 'User edited successfully!');
    }
    //EDIT USER STORE

    //BLOCK USER
    public function blockuser($id)
    {
        $user = User::find($id);
        return view('users.blockuser', [
            'user' => $user
        ]);
    }
    //BLOCK USER

    //BLOCK USER STORE
    public function blockuserstore($id)
    {
        $user = User::find($id);

        $user->status = "Blocked";

        $user->update();

        return redirect()->back()->with('feedback', 'User blocked successfully!');
    }
    //BLOCK USER STORE

    //ACTIVATE USER
    public function activateuser($id)
    {
        $user = User::find($id);
        return view('users.activateuser', [
            'user' => $user
        ]);
    }
    //ACTIVATE USER

    //ACTIVATE USER STORE
    public function activateuserstore($id)
    {
        $user = User::find($id);

        $user->status = "Active";

        $user->update();

        return redirect()->back()->with('feedback', 'User activated successfully!');
    }
    //ACTIVATE USER STORE


    //EDIT USER PASSWORD
    public function resetpassword($id)
    {
        $user = User::find($id);
        return view('users.resetpassword', [
            'user' => $user
        ]);
    }
    //EDIT USER PASSWORD

    //EDIT USER PASSWORD STORE
    public function resetpasswordstore(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);



        $user->password = Hash::make($request->password);


        $user->update();

        return redirect()->back()->with('feedback', 'User password has been reset!');
    }
    //EDIT USER PASSWORD STORE

}
