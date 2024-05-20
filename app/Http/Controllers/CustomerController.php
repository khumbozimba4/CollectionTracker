<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index()
    {

        if (auth()->user()->hasRole("admin") || auth()->user()->hasRole("Head")) {
            $customers = Customer::latest()->paginate(10);
        } else if (auth()->user()->hasRole("manager")) {
            $managerId = auth()->user()->id;
            // Assuming you want to retrieve the manager's location
            $managerLocation = Location::where('user_id', $managerId)->with('salesPersons')->first();
            $salesPersonIds = $managerLocation->salesPersons->pluck('id')->toArray();

            $customers = Customer::whereIn('user_id', $salesPersonIds)->paginate(10);
        } else {
            $customers = Customer::where("user_id", auth()->user()->id)->latest()->paginate(10);
        }

        return view('customers.customers', [
            'customers' => $customers
        ]);
    }

    public function newcustomer()
    {
        $sales = Role::where('name', 'salesPerson')->first()->users;

        return view('customers.newcustomer', compact('sales'));
    }

    public function newcustomerstore(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required'],
            'user_id' => ['required'],
        ]);

        try {
            //code...
            $customer = Customer::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'user_id' => $request->user_id,

            ]);
            return redirect()->back()->with('feedback', 'customer added successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('feedback', 'erro while adding a customer!');
        }
    }

    // VIEW customer
    public function viewcustomer($id)
    {
        $customer = Customer::with("user")->find($id);
        return view('customers.viewcustomer', [
            'customer' => $customer
        ]);
    }
    //VIEW customer

    //EDIT customer
    public function editcustomer($id)
    {
        $customer = Customer::with("user")->find($id);

        $sales = Role::where('name', 'salesPerson')->first()->users;

        return view('customers.editcustomer', [
            'customer' => $customer,
            'sales' => $sales
        ]);
    }
    //EDIT customer

    //EDIT customer STORE
    public function editcustomerstore(Request $request, $id)
    {
        $customer = Customer::find($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone_number' => ['required'],
            'user_id' => ['required'],

        ]);

        //check email
        if ($customer->email == $request->email) {
            //do nothing
        } else {
            $customer->email = $request->email;
        }

        //check phone_number
        if ($customer->phone_number == $request->phone_number) {
            //do nothing
        } else {
            $customer->phone_number = $request->phone_number;
        }


        $customer->name = $request->name;
        $customer->user_id = $request->user_id;



        $customer->update();

        return redirect()->back()->with('feedback', 'customer edited successfully!');
    }
}
