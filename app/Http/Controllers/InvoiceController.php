<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    //
    public function getCustomersByUserId($userId)
    {
        $customers = Customer::where('user_id', $userId)->get();
        return response()->json($customers);
    }

    public function index()
    {


        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if (auth()->user()->hasRole("admin")) {
            $invoices = Invoice::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->latest()->paginate(10);
        } else if (auth()->user()->hasRole("manager")) {

            $managerId = auth()->user()->id;
            // Assuming you want to retrieve the manager's location
            $managerLocation = Location::where('user_id', $managerId)->with('salesPersons')->first();

            // Assuming you want to get IDs of all salespersons associated with the manager's location
            $salesPersonIds = $managerLocation->salesPersons->pluck('id')->toArray();

            $invoices = Invoice::whereIn('user_id', $salesPersonIds)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->latest()
                ->paginate();
        } else if (auth()->user()->hasRole("Treasurer")) {

            $invoices = Invoice::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('is_reviewed', '=', '0')
                ->where('current_amount_collected', '>', '0')
                ->latest()->paginate(10);
        } else {
            $invoices = Invoice::where("user_id", auth()->user()->id)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->latest()
                ->paginate(10);
        }

        return view('invoices.invoices', [
            'invoices' => $invoices
        ]);
    }

    public function newinvoice()
    {

        $sales = Role::where('name', 'salesPerson')->first()->users;
        $customers = Customer::all();
        return view('invoices.newinvoice', compact('sales', 'customers'));
    }
    public function reviewinvoicestore(Request $request, $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            if (!$invoice) {
                return redirect()->back()->with('error', 'Invoice not found!');
            }
            $is_reviewed = $request->is_reviewed;
            if ($is_reviewed == 0) {
                $invoice->amount_paid += $request->current_amount_collected;
                $invoice->is_reviewed = 0;
                $invoice->current_amount_collected = 0;
                $invoice->remarks = $request->remarks;
                $invoice->save();
                return redirect()->back()->with('feedback', 'Invoice amount accepted successfully!');
            } else {
                $invoice->is_reviewed = 1;
                $invoice->remarks = $request->remarks;

                $invoice->save();
                return redirect()->back()->with('feedback', 'Invoice amount rejected successfully!');
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('error', 'An error occurred');
        }
    }
    public function newinvoicestore(Request $request)
    {

        $validatedData = $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'amount' => 'required|numeric',
            'status' => 'required',
            'amount_paid' => 'required|numeric',
            'credit_adjustment' => 'required|numeric',
            'debit_adjustment' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
        ]);
        $data = [];
        $data["amount"] = $request->amount;
        $data["status"] = $request->status;
        $data["amount_paid"] = $request->amount_paid;
        $data["credit_adjustment"] = $request->credit_adjustment;
        $data["debit_adjustment"] = $request->debit_adjustment;
        $data["user_id"] = $request->user_id;
        $data["customer_id"] = $request->customer_id;
        $data["invoice_number"] = $request->invoice_number;

        $target = ($data["amount"] + $data["debit_adjustment"]) - $data["credit_adjustment"];


        $data["balance"] = $target - $data["amount_paid"];
        try {
            //code...
            $invoice = Invoice::create($data);
            return redirect()->back()->with('feedback', 'invoice added successfully!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'error while adding a invoice!' . $th->getMessage());
        }
    }

    // VIEW invoice
    public function viewinvoice($id)
    {
        $invoice = Invoice::with("user")->find($id);
        return view('invoices.viewinvoice', [
            'invoice' => $invoice
        ]);
    }
    public function reviewinvoice($id)
    {
        $invoice = Invoice::with(["user", "customer"])->find($id);
        return view('invoices.reviewinvoice', [
            'invoice' => $invoice
        ]);
    }
    //VIEW invoice

    //EDIT invoice
    public function editinvoice($id)
    {
        $invoice = Invoice::with("user")->find($id);

        $sales = Role::where('name', 'salesPerson')->first()->users;

        return view('invoices.editinvoice', [
            'invoice' => $invoice,
            'sales' => $sales
        ]);
    }
    //EDIT invoice

    //EDIT invoice STORE
    public function editinvoicestore(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if (auth()->user()->hasRole("salesPerson")) {
            $validatedData = $request->validate([
                'status' => 'required',
                'amount_paid' => 'required|numeric',
            ]);

            $totalAmount = $invoice->amount;
            $totalCreditAdjustment = $invoice->credit_adjustment;
            $totalDebitAdjustment = $invoice->debit_adjustment;
            $target = ($totalAmount + $totalDebitAdjustment) - $totalCreditAdjustment;
            $total_collected = $request->amount_paid;
            $invoice->status  = $request->status;
            $balance = $target - $total_collected;
            $invoice->amount_paid = $total_collected;
            $invoice->balance = $balance;

            $invoice->save();
        } else {


            $validatedData = $request->validate([
                'invoice_number' => 'required',
                'amount' => 'required|numeric',
                'status' => 'required',
                'amount_paid' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'customer_id' => 'required|exists:customers,id',
                'debit_adjustment' => 'required',
                'credit_adjustment' => 'required',
            ]);

            $invoice->update($request->all());
        }




        return redirect()->back()->with('feedback', 'invoice edited successfully!');
    }
}
