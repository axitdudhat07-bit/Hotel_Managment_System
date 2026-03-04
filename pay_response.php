<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

require('inc/paytm/config_paytm.php');
require('inc/paytm/encdec_paytm.php');

date_default_timezone_set("Asia/Kolkata");

session_start();
unset($_SESSION['room']);

function regenrate_session($uid)
{
    $user_q = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$uid], 'i');
    $user_fetch = mysqli_fetch_assoc($user_q);
    $_SESSION['login'] = true;
    $_SESSION['uId'] = $user_fetch['id'];
    $_SESSION['uName'] = $user_fetch['name'];
    $_SESSION['uPic'] = $user_fetch['profile'];
    $_SESSION['uPhone'] = $user_fetch['phonenum'];
}

// header("Pragma: no-cache");
// header("Cache-Control: no-cache");
// header("Expires: 0");

// $paytmChecksum = "";
// $paramList = array();
// $isValidChecksum = "FALSE";

// $paramList = $_POST;
// $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

// //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
// $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

// if ($isValidChecksum == "TRUE") {

//     $slct_query = "SELECT `booking_id`,`user_id` FROM `booking_order` WHERE `order_id`='$_POST[ORDERID]'";

//     $slct_res = mysqli_query($con, $slct_query);

//     if (mysqli_num_rows($slct_res) == 0) {
//         redirect('index.php');
//     }

//     $slct_fetch = mysqli_fetch_assoc($slct_res);

//     if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
//         //regenerate session
//         regenrate_session($slct_fetch['user_id']);
//     }



//     $_POST["STATUS"] = "TXN_SUCCESS";

//     if ($_POST["STATUS"] == "TXN_SUCCESS") {
//         $upd_query = "UPDATE `booking_order` SET `booking_status`='booked',`trans_id`='$_POST[TXNID]',`trans_amt`='$_POST[TXNAMOUNT]',`trans_status`='$_POST[STATUS]',`trans_resp_msg`='$_POST[RESPMSG]' WHERE `booking_id`='$slct_fetch[booking_id]'";

//         mysqli_query($con, $upd_query);
//     } else {

//         $upd_query = "UPDATE `booking_order` SET `booking_status`='payment failed',`trans_id`='$_POST[TXNID]',`trans_amt`='$_POST[TXNAMOUNT]',`trans_status`='$_POST[STATUS]',`trans_resp_msg`='$_POST[RESPMSG]' WHERE `booking_id`='$slct_fetch[booking_id]'";

//         mysqli_query($con, $upd_query);
//     }
//     redirect('pay_status.php?order=' . $_POST['ORDERID']);

//     if (isset($_POST) && count($_POST) > 0) {
//         foreach ($_POST as $paramName => $paramValue) {
//             echo "<br/>" . $paramName . " = " . $paramValue;
//         }
//     }
// } else {
//     redirect('index.php');
// }

// $razorpay_payment_id = $_GET['payment_id'];
// $key_id = "rzp_test_ohX64rHrPjOalL";
// $key_secret = "F7hVOtNbOJhSSMfxb2h10HTv";

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/payments/$razorpay_payment_id");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_USERPWD, $key_id . ":" . $key_secret);

// $response = curl_exec($ch);
// curl_close($ch);

// $result = json_decode($response, true);

// if ($result && $result['status'] == "captured") {
//     // Payment successful, redirect to success page
//     header("Location: success.php?payment_id=" . $razorpay_payment_id);
//     exit();
// } else {
//     // Payment failed, redirect to failure page
//     header("Location:  success.php");
//     exit();
// }

require('inc/razorpay/Razorpay.php'); // Include Razorpay PHP SDK
use Razorpay\Api\Api;

$keyId = "rzp_test_ohX64rHrPjOalL";
$keySecret = "F7hVOtNbOJhSSMfxb2h10HTv";
$api = new Api($keyId, $keySecret);

if (isset($_GET['payment_id']) && isset($_GET['order_id'])) {
    $payment_id = $_GET['payment_id'];
    $order_id = $_GET['order_id'];

    $slct_query = "SELECT `booking_id`,`user_id` FROM `booking_order` WHERE `order_id`='$order_id'";

    $slct_res = mysqli_query($con, $slct_query);

    if (mysqli_num_rows($slct_res) == 0) {
        redirect('index.php');
    }

    $slct_fetch = mysqli_fetch_assoc($slct_res);

    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        //regenerate session
        regenrate_session($slct_fetch['user_id']);
    }

    $payment = $api->payment->fetch($payment_id);
    $amount = $payment['amount'] / 100;

    if ($payment['status'] == 'captured') {
        $upd_query = "UPDATE `booking_order` SET `booking_status`='booked',`trans_id`='$payment_id',`trans_amt`='$amount',`trans_status`='TXN_SUCCESS',`trans_resp_msg`='$payment[description]' WHERE `booking_id`='$slct_fetch[booking_id]'";

        mysqli_query($con, $upd_query);
        header("Location: pay_status.php?order=" . $order_id);
        exit();
    } else {
        print_r ($payment['description']);
        // header("Location:  pay_status.php?order=" . $order_id);
        // exit();
    }
} else {
    header("Location:  pay_status.php?order=" . $order_id);
    exit();
}
