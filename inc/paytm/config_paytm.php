<?php
/*
- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.
jUqgKT27404433977554,UvaLXt93516792108659
sZHcG1IKvCMeWGa6,UfnfTQdcfwHZiSma
*/

define('PAYTM_ENVIRONMENT', 'TEST'); // PROD TEST
define('PAYTM_MERCHANT_KEY', 'sZHcG1IKvCMeWGa6'); //Change this constant's value with Merchant key received from Paytm.
define('PAYTM_MERCHANT_MID', 'jUqgKT27404433977554'); //Change this constant's value with MID (Merchant ID) received from Paytm.
define('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING'); //Change this constant's value with Website name received from Paytm.
define('INDUSTRY_TYPE_ID', 'Retail'); //Change this constant's value with Website name received from Paytm.
define('CHANNEL_ID', 'WEB'); //Change this constant's value with Website name received from Paytm.
define('CALLBACK_URL', 'http://localhost/hdwebsite/pay_response.php'); //Change this constant's value with Website name received from Paytm.

$PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
$PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';
if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
	$PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
}

define('PAYTM_REFUND_URL', '');
define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
?>
