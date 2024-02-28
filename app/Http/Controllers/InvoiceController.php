<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //
    public function getCustomersByUserId($userId) {
        $customers = Customer::where('user_id', $userId)->get();
        return response()->json($customers);
    }
    
    public function index(){

        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if(auth()->user()->hasRole("admin") || auth()->user()->hasRole("manager")){
            $invoices = Invoice::whereMonth('created_at', $currentMonth)
                                ->whereYear('created_at', $currentYear)
                                ->latest()->paginate(10); 

        }else{
            $invoices = Invoice::where("user_id",auth()->user()->id)
                                ->whereMonth('created_at', $currentMonth)
                                ->whereYear('created_at', $currentYear)
                                ->latest()
                                ->paginate(10); 

        }

        return view('invoices.invoices', [
            'invoices' => $invoices
        ]);
    }

    public function newinvoice(){
        $sales = Role::where('name', 'salesPerson')->first()->users;
        $customers = Customer::all();
        return view('invoices.newinvoice',compact('sales','customers'));
    }

    public function newinvoicestore(Request $request){

        $validatedData = $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'amount' => 'required|numeric',
            'status' => 'required',
            'amount_paid' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
        ]);

        try {
            //code...
            $invoice = Invoice::create($request->ALL());
            return redirect()->back()->with('feedback', 'invoice added successfully!');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'error while adding a invoice!'.$th->getMessage());

        }
            



    }

    // VIEW invoice
    public function viewinvoice($id){
        $invoice = Invoice::with("user")->find($id);
        return view('invoices.viewinvoice', [
            'invoice' => $invoice
        ]);
    }
    //VIEW invoice

    //EDIT invoice
    public function editinvoice($id){
        $invoice = Invoice::with("user")->find($id);
        
        $sales = Role::where('name', 'salesPerson')->first()->users;

        return view('invoices.editinvoice', [
            'invoice' => $invoice,
            'sales'=>$sales
        ]);
    }
    //EDIT invoice

    //EDIT invoice STORE
    public function editinvoicestore(Request $request, $id){
        $invoice = Invoice::find($id);

        if(auth()->user()->hasRole("salesPerson")){
            $validatedData = $request->validate([
                'status' => 'required',
                'amount_paid' => 'required|numeric',
            ]);

            $totalAmount = $invoice->amount;
            $totalCreditAdjustment = $invoice->credit_adjustment;
            $totalDebitAdjustment = $invoice->debit_adjustment;
            $target = ($totalAmount + $totalDebitAdjustment)-$totalCreditAdjustment;
            $total_collected = $request->amount_paid;
            $status  =$request->status;

            $balance = $target-$total_collected;
            $invoice->amount_paid = $total_collected;
            $invoice->balance = $balance;

            $invoice->save();

        }else{

        
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
