<?php
ob_start();
if (!isset($_SESSION))
    session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/constant.php');
include('includes/apihelper.php');
include('includes/dbconnect.php');
//use Helper;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bitmine Pool</title>

        <!-- Bootstrap -->
        <link href="../vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="../vendor/nprogress/nprogress.css" rel="stylesheet">
        <!-- Animate.css -->
        <link href="../vendor/animate.css/animate.min.css" rel="stylesheet">
        <link rel="icon" href="../images/favicon.ico" type="image/ico" sizes="32x32">
        <!-- Custom Theme Style -->
        <link href="../vendor/build/css/custom.css" rel="stylesheet">
        <link rel="stylesheet" href="../vendor/build/css/style.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>

        
    </head>
    <?php
    if (isset($_SESSION['Username'])) {
        $userName = $_SESSION['Username'];

        $response = ApiHelper::getApiResponse('POST', ['access_token' => ACCESS_TOKEN,
                'user_name' => $userName,
                'platform' => '3'
                ], 'getAllRankData');

        $response = json_decode($response);

        if ($response->statusCode != 100) {
           $_SESSION['error'] = 1;
           $_SESSION['message'] = $response->statusDescription;
        } 
    }
    ?>
