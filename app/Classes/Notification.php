<?php

namespace App\Classes;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Notification
{
    protected static function getCurrentMonth()
    {
        return Carbon::now()->month;
    }

    protected static function getCurrentYear()
    {
        return Carbon::now()->year;
    }
    public  static function PrepareDashboard()
    {

        $data = [];

        if (auth()->user()->hasRole("salesPerson")) {

            $customers  = Customer::where("user_id", auth()->user()->id)->count();
            $invoices = Invoice::where("user_id", auth()->user()->id)
                ->whereMonth('created_at', self::getCurrentMonth())
                ->whereYear('created_at', self::getCurrentYear())
                ->select("amount", "credit_adjustment", "debit_adjustment", "amount_paid", "balance")
                ->get();
        } else if (auth()->user()->hasRole("manager")) {

            $managerId = auth()->user()->id;
            // Assuming you want to retrieve the manager's location
            $managerLocation = Location::where('user_id', $managerId)->with('salesPersons')->first();

            // Assuming you want to get IDs of all salespersons associated with the manager's location
            $salesPersonIds = $managerLocation->salesPersons->pluck('id')->toArray();

            $invoices = Invoice::whereIn('user_id', $salesPersonIds)
                ->whereMonth('created_at', self::getCurrentMonth())
                ->whereYear('created_at', self::getCurrentYear())
                ->select("amount", "credit_adjustment", "debit_adjustment", "amount_paid", "balance")
                ->get();


            $customers = Customer::whereIn('user_id', $salesPersonIds)->count();


            $totalSalespersons = $managerLocation->salespersons()->count();
            //} else if (auth()->user()->hasRole('Head')) {
            //   $managers = Location::with('manager')->get();
        } else {
            $managers = Location::with('manager')->get();

            $invoices = Invoice::whereMonth('created_at', self::getCurrentMonth())
                ->whereYear('created_at', self::getCurrentYear())
                ->select("amount", "credit_adjustment", "debit_adjustment", "amount_paid", "balance")
                ->get();
            $customers  = Customer::count();
            $totalSalespersons =  User::whereHas('roles', function ($query) {
                $query->where('name', 'salesPerson');
            })->count();
            //dd($invoices);
        }
        $invoicesCount = Invoice::whereMonth('created_at', self::getCurrentMonth())
            ->whereYear('created_at', self::getCurrentYear())
            ->where('is_reviewed', 0)
            ->where('current_amount_collected', '>', '0')
            ->count();
        if (isset($invoices)) {


            $totalAmount = $invoices->sum('amount');
            $totalCreditAdjustment = $invoices->sum('credit_adjustment');
            $totalDebitAdjustment = $invoices->sum('debit_adjustment');

            $data["target"] =  ($totalAmount + $totalDebitAdjustment) - $totalCreditAdjustment;

            $data["total_collected"] = $invoices->sum("amount_paid");

            // dd($data["total_collected"]);
            $data["total_remaining"] = $data["target"] - $data["total_collected"]; // $invoices->;
            $data["customers"] = $customers;
            if (isset($totalSalespersons)) {

                $data['totalSalesPersons'] = $totalSalespersons;
            }
        }
        if (isset($managers)) {
            $data['managers'] = $managers;
        }
        $data["invoicesCount"] = $invoicesCount;
        //dd($data);
        return $data;
    }
    public static  function PrepareReportDashboard()
    {

        if (auth()->user()->hasRole('manager')) {
            $managerId = auth()->user()->id;

            $managerLocationId = Location::where('user_id', $managerId)->first()->id;

            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'salesPerson');
            })->where('location_id', $managerLocationId)->get();
        } else {

            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'salesPerson');
            })->get();
        }
        $stackedData = [
            'labels' => [],
            'datasets' => []
        ];

        foreach ($users as $user) {
            $stackedData['labels'][] = $user->name;

            $query = Invoice::where('user_id', $user->id)
                ->whereMonth('created_at', self::getCurrentMonth())
                ->whereYear('created_at', self::getCurrentYear());

            $collectedAmount = $query->sum('amount_paid');
            $balanceAmount = $query->sum('balance');
            $totalAmount = $query->sum('amount');
            $totalCreditAdjustment = $query->sum('credit_adjustment');
            $totalDebitAdjustment = $query->sum('debit_adjustment');

            $target = ($totalAmount + $totalDebitAdjustment) - $totalCreditAdjustment;
            $balanceAmount = $target - $collectedAmount;



            $stackedData['datasets'][] = [
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)', // You can adjust colors as needed
                'data' => [$collectedAmount, $balanceAmount]
            ];
        }
        $stackedDataJson = json_encode($stackedData);

        return $stackedDataJson;
    }
    // public static function composeEmail($name,$email,$password) {
    //     require base_path("vendor/autoload.php");
    //     $mail = new PHPMailer(true);     // Passing `true` enables exceptions

    //     try {

    //         $mail->SMTPDebug = 1;
    //         $mail->isSMTP();
    //         $mail->Host = 'mail.mra.mw';             //  smtp host
    //         $mail->SMTPAuth = false;
    //         $mail->Username = 'mra-eservices@mra.mw';   //  sender username
    //         $mail->Password = 'NewPassword123';       // sender password
    //         $mail->SMTPSecure = null;                  // encryption - ssl/tls
    //         $mail->Port = 25;                          // port - 587/465
    //         $mail->SMTPOptions = array(
    //           'ssl' => array(
    //               'verify_peer' => false,
    //               'verify_peer_name' => false,
    //               'allow_self_signed' => true
    //           )
    //       );
    //         $mail->setFrom('noreply@mra.mw', 'norepy@mra.mw');
    //         $mail->addAddress($email);
    //         $mail->isHTML(false);
    //         $mail->Subject ="MRA website authentication credentials";
    //         $mail->Body   = "Hello ".$name.", Your email is: ".$email. " and your passowrd is: ".$password." Use these credentials to log into your account.";
    //         if( !$mail->send() ) {
    //             return "Email not sent.".$mail->ErrorInfo;
    //         }

    //         else {
    //             return "Email has been sent.";
    //         }

    //     } catch (Exception $e) {
    //          return 'Message could not be sent.'.$e->errorMessage();
    //     }
    // }
}
