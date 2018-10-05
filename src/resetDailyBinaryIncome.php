<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/apihelper.php');


//$user_name = (isset($_GET['user_name']) && !empty($_GET['user_name'])) ? $_GET['user_name'] : '';

$responseAddBonus = ApiHelper::getApiResponse('POST', ['access_token' => ACCESS_TOKEN,
            'platform' => '3'
                ], 'resetDailyBinaryIncome');

$responseAddBonus = json_decode($responseAddBonus);
echo 'Cron started <br>';
if ($responseAddBonus->statusCode == 100) {
    echo "ResetDailyBinaryIncome cron processed successfully .<br>";
} else {
    echo "There is problem to excute the ResetDailyBinaryIncome .<br>";
} 
echo 'Cron finished. <br>';
exit;
?>
