<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*   file                 :  _buy/paypal/paypal.php
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*   author               :  Muhamed Skoko - mskoko.me@gmail.com
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

include_once($_SERVER['DOCUMENT_ROOT'].'/includes.php');

//////////////////////////

// If do not login;
if (!($User->IsLoged()) == true) {
    header('Location: /login');
    die();
}

// Get Order ID
$orderID = @$Secure->SecureTxt($GET['oid']);
if(!isset($orderID) || empty($orderID) || !is_numeric($orderID)) {
    header('Location: /billing');
    die('Insert order ID');
}
// Is valid order?
if (empty($Billing->orderByID($orderID)['id'])) {
    header('Location: /billing');
    die('Insert order ID');
}
// Order info
$orderInfo = $Billing->orderByID($orderID);


require_once('paypal.class.php');  // include the class file
$p = new paypal_class();             // initiate an instance of the class

// $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

// if there is not action variable, set the default action of 'process'
if (empty($GET['action'])) $GET['action'] = 'process';
// $Price = $Secure->SecureTxt($orderInfo['Price']);
$Price = str_replace('&euro;', '', $orderInfo['Price']);

switch ($GET['action']) {
    case 'process':

        $p->add_field('business', 'your-email@email.com');
        
        $p->add_field('return', $this_script.'?action=success&oid='.$orderID);
        $p->add_field('cancel_return', $this_script.'?action=cancel&oid='.$orderID);
        $p->add_field('notify_url', $this_script.'?action=ipn&oid='.$orderID);

        $p->add_field('first_name', $Secure->SecureTxt($User->UserData()['Name']));
        $p->add_field('last_name', $Secure->SecureTxt($User->UserData()['Lastname']));

        $p->add_field('item_name', 'Game server / GameID#: '.$Secure->SecureTxt($orderInfo['gameID']));
        $p->add_field('item_number', $orderID);
        $p->add_field('amount', $Price);
        $p->add_field('currency_code', 'EUR');

        $p->submit_paypal_post(); // submit the fields to paypal
        // $p->dump_fields();      // for debugging, output a table of all the fields
    break;
    
    case 'success':
        // Add Cash to my profile;
        $nMRVcash = $User->getUserCash($User->UserData()['id'], 'db') + $Price; // MRV Cash + New Price order;
        if (!($User->updateUserCash($nMRVcash, $User->UserData()['id'])) == false) {
            // Update Order => Success
            $Billing->upStatusOrderID($orderID, $User->UserData()['id'], '1');
        }
        
        $Alert->SaveAlert('Thanks for the payment, you can now set up your server.', 'success');
        header('Location: /order?id='.$orderID);
        die();
    break;

    case 'cancel':
        if (!($Billing->upStatusOrderID($orderID, $User->UserData()['id'], '1')) == false) {
            $Alert->SaveAlert('Welcome back :)', 'info');
            header('Location: /order?id='.$orderID);
            die();
        }
        echo 'Jako nam je zao sto ste odustali od kupovine. - Vidimo se sledeceg puta kad budete bili sigurniji u sebe <3';
        die();
    break;

    case 'ipn':          // Paypal is calling page for IPN validation...
        // It's important to remember that paypal calling this script.  There
        // is no output here.  This is where you validate the IPN data and if it's
        // valid, update your database to signify that the user has payed.  If
        // you try and use an echo or printf function here it's not going to do you
        // a bit of good.  This is on the "backend".  That is why, by default, the
        // class logs all IPN data to a text file.

        if ($p->validate_ipn()) { 
            // Payment has been recieved and IPN is verified.  This is where you
            // update your database to activate or process the order, or setup
            // the database with the user's order details, email an administrator,
            // etc.  You can access a slew of information via the ipn_data() array.

            // Check the paypal documentation for specifics on what information
            // is available in the IPN POST variables.  Basically, all the POST vars
            // which paypal sends, which we send back for validation, are now stored
            // in the ipn_data() array.

            // For this example, we'll just email ourselves ALL the data.
            $dated = date("D, d M Y H:i:s", time()); 

            $subject    = 'Instant Payment Notification - Recieved Payment';
            $to         = 'your-email@email.com';    //  your email
            $body       =  "An instant payment notification was successfully recieved\n";
            $body      .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
            $body      .= " at ".date('g:i A')."\n\nDetails:\n";
            $headers    = "";
            $headers   .= "From: Test Paypal \r\n";
            $headers   .= "Date: $dated \r\n";

            $PaymentStatus  =  $p->ipn_data['payment_status']; 
            $Email          =  $p->ipn_data['payer_email'];
            $id             =  $p->ipn_data['item_number'];

            if($PaymentStatus == 'Completed' or $PaymentStatus == 'Pending'){
                $PaymentStatus = '2';
            } else {
                $PaymentStatus = '1';
            }
            /*                                                                           
            *
            * 
            *
            *      Here you write your quries to make payment received or pending etc. 
            * 
            *  
            * 
            */
            foreach ($p->ipn_data as $key => $value) { 
                $body .= "\n$key: $value"; 
            }
        }
        break;
    }