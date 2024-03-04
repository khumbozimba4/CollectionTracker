<?php

namespace App\Classes;

use App\Models\Application;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Download;
use App\Models\Faq;
use App\Models\Invoice;
use App\Models\Link;
use App\Models\News;
use App\Models\Press;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Notification
{
    public static function PrepareDashboard()
    {

        $data = [];

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        if (auth()->user()->hasRole("salesPerson")) {
            $customers  = Customer::where("user_id", auth()->user()->id)->count();
            $invoices = Invoice::where("user_id", auth()->user()->id)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->select("amount", "credit_adjustment", "debit_adjustment", "amount_paid", "balance")
                ->get();
        } else {
            $invoices = Invoice::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->select("amount", "credit_adjustment", "debit_adjustment", "amount_paid", "balance")
                ->get();
            $customers  = Customer::count();

            // dd($invoices);
        }

        $totalAmount = $invoices->sum('amount');
        $totalCreditAdjustment = $invoices->sum('credit_adjustment');
        $totalDebitAdjustment = $invoices->sum('debit_adjustment');

        $data["target"] = ($totalAmount + $totalDebitAdjustment) - $totalCreditAdjustment;
        $data["total_collected"] = $invoices->sum("amount_paid");
        // dd($data["total_collected"]);
        $data["total_remaining"] = $data["target"] - $data["total_collected"]; // $invoices->sum("balance");
        $data["customers"] = $customers;

        return $data;
    }
    public static function PrepareReportDashboard()
    {

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'salesPerson');
        })->get();
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
