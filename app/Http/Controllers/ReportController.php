<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function index():View{
        return view('reports.reports');
    }
    public function Search(Request $request){

        $request->validate([
            "year"=>"required|integer",
            "month"=>"required|integer"

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
        return view('reports.reports',compact('stackedDataJson'));
    }
}
