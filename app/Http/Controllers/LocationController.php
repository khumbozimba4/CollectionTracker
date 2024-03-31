<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    //
    public function index(): View
    {
        $locations = Location::with('manager')->get();
        return view('locations.locations', compact('locations'));
    }
    public function createLoaction(): View
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->select('id', 'name')->get();
        return view('locations.createlocation', compact('users'));
    }
    public function viewLocation($id): View
    {
        $location = Location::with('manager')->find($id);
        return view('locations.viewlocation', compact('location'));
    }
    public function editLocation($id): View
    {
        $location = Location::with('manager')->find($id);
        $users = User::select('id', 'name')->get();
        return view('locations.editlocation', compact('location', 'users'));
    }
    public function createLoactionstore(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'location_name' => 'required|string'
        ]);
        try {
            $created = Location::create($request->all());
            if ($created) {
                # code...
                return redirect()->route('locations')->with('feedback', 'Location added successfully!');
            } else {
                return back()->with('feedback_warning', 'failed to add a new location');
            }
        } catch (\Throwable $th) {
            return back()->with('feedback_warning', 'failed to add a new location due to internal server error');
        }
    }
    public function updateLoactionstore(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'location_name' => 'required|string'
        ]);
        try {
            $location = Location::find($id);
            if (!$location) {

                return back()->with('feedback_warning', 'Location not found');
            }
            $created = $location->update($request->all());
            if ($created) {
                # code...
                return redirect()->route('locations')->with('feedback', 'Location updated successfully!');
            } else {
                return back()->with('feedback_warning', 'failed to update a new location');
            }
        } catch (\Throwable $th) {
            return back()->with('feedback_warning', 'failed to update a new location due to internal server error');
        }
    }

    public function LocationSalesperson($id)
    {
        $location = Location::with('salesPersons')->find($id);
        $users = $location->salesPersons;

        return view('users.locationSalesPersons', compact('users'));
    }

    public function ManagerLocation($id)
    {
        $location_report = [];
        $location = Location::with('manager')->find($id);
        if (!$location) {
            return back()->with('warning_feedback', "location not found for this manager");
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $managerId = $location->manager->id;
        // Assuming you want to retrieve the manager's location
        $managerLocation = Location::where('user_id', $managerId)->with('salesPersons')->first();

        // Assuming you want to get IDs of all salespersons associated with the manager's location
        $salesPersonIds = $managerLocation->salesPersons->pluck('id')->toArray();

        $invoices = Invoice::whereIn('user_id', $salesPersonIds)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->select("amount", "credit_adjustment", "debit_adjustment", "amount_paid", "balance")
            ->get();


        $customers = Customer::whereIn('user_id', $salesPersonIds)->count();


        $totalSalespersons = $managerLocation->salespersons()->count();

        $totalAmount = $invoices->sum('amount');
        $totalCreditAdjustment = $invoices->sum('credit_adjustment');
        $totalDebitAdjustment = $invoices->sum('debit_adjustment');

        $location_report["target"] = ($totalAmount + $totalDebitAdjustment) - $totalCreditAdjustment;
        $location_report["total_collected"] = $invoices->sum("amount_paid");
        // dd($location_report["total_collected"]);
        $total_remaining = $location_report["target"] - $location_report["total_collected"];
        $location_report["total_remaining"] = $total_remaining;
        $location_report["customers"] = $customers;
        $location_report['totalSalesPersons'] = $totalSalespersons;







        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'salesPerson');
        })->where('location_id', $location->id)->get();


        $stackedData = [
            'labels' => [],
            'datasets' => []
        ];

        foreach ($users as $user) {
            $stackedData['labels'][] = $user->name;

            $collectedAmount = Invoice::where('user_id', $user->id)->sum('amount_paid');
            $balanceAmount = Invoice::where('user_id', $user->id)->sum('balance');

            $totalAmount = Invoice::where('user_id', $user->id)->sum('amount');

            $totalCreditAdjustment = Invoice::where('user_id', $user->id)->sum('credit_adjustment');
            $totalDebitAdjustment = Invoice::where('user_id', $user->id)->sum('debit_adjustment');

            $target = ($totalAmount + $totalDebitAdjustment) - $totalCreditAdjustment;

            $balanceAmount = $target - $collectedAmount;
            //$balanceAmount =

            $stackedData['datasets'][] = [
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)', // You can adjust colors as needed
                'data' => [$collectedAmount, $balanceAmount]
            ];
        }

        $stackedDataJson = json_encode($stackedData);



        return view('locations.report', compact('location_report', 'stackedDataJson'));
    }
    public function deleteLocation($id): RedirectResponse
    {
        try {
            //code...
            $location = Location::find($id)->deleteOrFail();
            if ($location) {
                return redirect()->route('locations')->with('feedback', 'Location deleted successfully!');
            }
            return back()->with('feedback_warning', 'failed to delete a location');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            return back()->with('feedback_warning', 'failed to delete a location due to internal server error');
        }
    }
}
