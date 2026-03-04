<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

require('inc/paytm/config_paytm.php');
require('inc/paytm/encdec_paytm.php');

date_default_timezone_set("Asia/Kolkata");

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

// if (isset($_POST['pay_now'])) {
//     header("Pragma: no-cache");
//     header("Cache-Control: no-cache");
//     header("Expires: 0");


//     $checkSum = "";

//     $ORDER_ID = 'ORD_' . $_SESSION['uId'] . random_int(1111, 9999999);
//     $CUST_ID = $_SESSION['uId'];
//     $INDUSTRY_TYPE_ID = INDUSTRY_TYPE_ID;
//     $CHANNEL_ID = CHANNEL_ID;
//     $TXN_AMOUNT = $_SESSION['room']['payment'];


//     // Create an array having all required parameters for creating checksum.
//     $paramList = array();
//     $paramList["MID"] = PAYTM_MERCHANT_MID;
//     $paramList["ORDER_ID"] = $ORDER_ID;
//     $paramList["CUST_ID"] = $CUST_ID;
//     $paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
//     $paramList["CHANNEL_ID"] = $CHANNEL_ID;
//     $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
//     $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;

//     $paramList["CALLBACK_URL"] = CALLBACK_URL;

//     //Here checksum string will return by getChecksumFromArray() function.
//     $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

//     //Insert payment data into database

//     $frm_data = filteration($_POST);

//     $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, `order_id`) VALUES (?,?,?,?,?)";

//     insert($query1, [$CUST_ID, $_SESSION['room']['id'], $frm_data['checkin'], $frm_data['checkout'], $ORDER_ID], 'issss');

//     $booking_id = mysqli_insert_id($con);

//     $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`,`user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";

//     insert($query2, [$booking_id, $_SESSION['room']['name'], $_SESSION['room']['price'], $TXN_AMOUNT, $frm_data['name'], $frm_data['phonenum'], $frm_data['address']], 'issssss');
// }

require('inc/razorpay/Razorpay.php'); // Include Razorpay PHP SDK
use Razorpay\Api\Api;


// Razorpay API Key and Secret
$api_key = 'rzp_test_ohX64rHrPjOalL';
$api_secret = 'F7hVOtNbOJhSSMfxb2h10HTv';
$api = new Api($api_key, $api_secret);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $CUST_ID = $_SESSION['uId'];
    $amount = $_SESSION['room']['payment'] * 100; // Convert amount to paise
    $receipt = 'ORD_' . $_SESSION['uId'] . random_int(1111, 9999999);
    // $trans_id=$_POST['payment_id'];

    try {
        // Create Razorpay order
        $order = $api->order->create([
            'receipt' => $receipt,
            'amount' => $amount,
            'currency' => 'INR',
            'payment_capture' => 1 // Auto capture
        ]);

        // Insert order details into database
        $status = "booked";
        $frm_data = filteration($_POST);

        $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`, `order_id`) VALUES (?,?,?,?,?)";

        insert($query1, [$CUST_ID, $_SESSION['room']['id'], $frm_data['checkin'], $frm_data['checkout'], $order['id']], 'issss');

        $booking_id = mysqli_insert_id($con);

        $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`,`user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";

        insert($query2, [$booking_id, $_SESSION['room']['name'], $_SESSION['room']['price'], $amount / 100, $frm_data['name'], $frm_data['phonenum'], $frm_data['address']], 'issssss');


        $order_id = $order['id'];
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>
    <script>
        var options = {
            "key": "<?php echo $api_key; ?>",
            "amount": "<?php echo $amount; ?>",
            "currency": "INR",
            "name": "<?php echo $frm_data['name']; ?>",
            "description": "Booking Payment",
            "image": "https://yourlogo.com/logo.png",
            "order_id": "<?php echo $order_id; ?>",
            "handler": function(response) {
                window.location.href = "pay_response.php?payment_id=" + response.razorpay_payment_id + "&order_id=" + response.razorpay_order_id;
            },
            "prefill": {
                "name": "<?php echo $frm_data['name']; ?>",
                "phonenum": "<?php echo $frm_data['phonenum']; ?>"
            },
            "modal": {
                "ondismiss": function() {
                    alert("Payment Cancelled!");
                    window.location.href = "confirm_booking.php"; // पेमेंट कैंसिल होने पर index.php पर भेजें
                }
            },
            "theme": {
                "color": "#528FF0"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>
</body>

</html>
<!-- <html>

<head>
    <title>Processing</title>
</head>

<body>
    <h1>Please do not refresh this page...</h1>

    <form method="post" action="<?php // echo PAYTM_TXN_URL 
                                ?>" name="f1">
        <table border="1">
            <tbody>
                <?php
                // foreach ($paramList as $name => $value) {
                //   echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
                //}
                ?>
                <input type="hidden" name="CHECKSUMHASH" value="<?php //echo $checkSum 
                                                                ?>">
            </tbody>
        </table>

        <script type="text/javascript">
            document.f1.submit();
        </script>
    </form>
</body>

</html> -->