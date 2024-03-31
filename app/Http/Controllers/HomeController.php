<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
class HomeController extends Controller
{
    //
    function searchInvoice(Request $request): View
    {
        $request->validate([
            "searchValue" => 'required'
        ]);
        $query = $request->searchValue;
        $invoices = Invoice::where('invoice_number', 'like', "%$query%")->get();

        return view('invoices.invoices', compact('invoices'));
    }
    function searchUser(Request $request): View
    {
        $request->validate([
            "searchValue" => 'required'
        ]);
        $query = $request->searchValue;
        $users = User::where('email', 'like', "%$query%")->paginate(100);

        return view('users.users', compact('users'));
    }
    function searchCustomer(Request $request): View
    {
        $request->validate([
            "searchValue" => 'required'
        ]);
        $query = $request->searchValue;
        $customers = Customer::where('name', 'like', "%$query%")->get();

        return view('customers.customers', compact('customers'));
    }
}
