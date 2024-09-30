<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function index(): View
    {
        return view('reports.reports');
    }
    public function Search(Request $request)
    {

        $request->validate([
            "year" => "required|integer",
            "month" => "required|integer"

        ]);

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'salesPerson');
        })->get();
        $stackedData = [
            'labels' => [],
            'datasets' => []
        ];

        foreach ($users as $user) {
            $stackedData['labels'][] = $user->name;

            $collectedAmount = Invoice::where('user_id', $user->id)
                ->whereMonth('created_at', $request->month)
                ->whereYear('created_at', $request->year)
                ->sum('amount_paid');
            $balanceAmount = Invoice::where('user_id', $user->id)
                ->whereMonth('created_at', $request->month)
                ->whereYear('created_at', $request->year)
                ->sum('balance');

            $stackedData['datasets'][] = [
                //'label' => $user->name,
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)', // You can adjust colors as needed
                'data' => [$collectedAmount, $balanceAmount]
            ];
        }
        //   dd(count($stackedData["labels"]));
        $stackedDataJson = json_encode($stackedData);

        //  return $stackedDataJson;
        return view('reports.reports', compact('stackedDataJson'));
    }
    public function DynamicReports(Request $request)
    {
        // Validate that startDate and endDate are present and are valid dates
        $request->validate([
            "startDate" => "required|date|before_or_equal:endDate",
            "endDate" => "required|date|after_or_equal:startDate"
        ]);

        $startDate = $request->startDate;
        $endDate = $request->endDate;

        // Get users who have the role of 'salesPerson'
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'salesPerson');
        })->get();

        $stackedData = [
            'labels' => [],
            'datasets' => []
        ];

        // Iterate through the users and gather report data
        foreach ($users as $user) {
            $stackedData['labels'][] = $user->name;

            // Get total amount collected within the date range for each user
            $collectedAmount = Invoice::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount_paid');

            // Get total balance within the date range for each user
            $balanceAmount = Invoice::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('balance');

            // Add this user's data to the dataset
            $stackedData['datasets'][] = [
                //'label' => $user->name,
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)', // You can adjust colors as needed
                'data' => [$collectedAmount, $balanceAmount]
            ];
        }

        // Convert the stacked data array to JSON format for use in the frontend
        $stackedDataJson = json_encode($stackedData);

        // Return the view with the stacked data
        return view('reports.reports', compact('stackedDataJson'));
    }
}
