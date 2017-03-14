<?php
//add database and validate funcitons
require_once('../Model/database.php');
require_once('validate.php');
//set session length and start session
$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
session_set_cookie_params($lifetime, '/');
session_start();

//if session has alias, someone is logged in otherwise they are not
if (!isset($_SESSION['alias'])) {
    $logged_in = false;
    $commentview = 'hidden';
    $controls = 'hidden';
} else {
    $logged_in = true;
    $commentview = '';
    $controls = '';
}

//get action from post
$action = filter_input(INPUT_POST, 'action');

//set up default actions
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'default';
    }
}
//echo $action

// users needs to be accesed by most pages and functions
$bidders = get_bidders();

switch ($action) {
    //default action
    case 'default':
        $bidders = get_bidders();
        
        $error_message = filter_input(INPUT_GET, 'error_message');
        include('../View/login.php');  //header to log in page
        
        break; //end for default action

    //register logic
    case 'register':
       
        //if variables are not set, set them as blank
        if (!isset($firstName)) {
            $firstName = '';
        }
        if (!isset($lastName)) {
            $lastName = '';
        }
        if (!isset($alias)) {
            $alias = '';
        }
        if (!isset($password)) {
            $password = '';
        }
        if (!isset($email)) {
            $email = '';
        }
        include('../View/Register.php');
        break; //end for register

    //login logic
    case 'login':
        
        date_default_timezone_set('America/Chicago');
        $bidDate = date('Y-m-d H:i');
        
        //pull alias and pw from post
        $alias = filter_input(INPUT_POST, 'alias', FILTER_DEFAULT);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

        if (login_check($alias, $password)) { //check alias and password for match

            //populate variables to show on page
            $_SESSION['alias'] = $alias;
            $profile = get_bidders_info($alias);
            $firstName = $profile[0]['firstName'];
            $lastName = $profile[0]['lastName'];
            $email = $profile[0]['email'];
            

            //test if image is set or empty, use default if it is

            $password = ''; //hide password
            //take to pasture rental page if login success
            $bidders = get_bidders();
            $logged_in = true;
            $commentview = '';
            $controls = '';
            $bid=0;
            $total_bid = 0;
            include('../View/pastureRental.php');
            break; //break for login success
        }
        
        //clear password and return to log in page if log in fails
        if (!isset($alias)) {
            $alias = '';
        }
        $password = '';
        header('Location: ../controller/index.php?error_message=Log-in failed');

        break; //end for login(fail)
        
    case 'log_out':
        //destroy session and header to controller to log back in
        session_destroy();
        header('Location: ../controller/index.php');
        break; //end for log out    
        
    case 'submit_bid'://submit bids 
    
        if(!isset($bid)){
            $bid = 0;
        }
        if(!isset($alias)){
            $alias = null;
        }
        
        if(!isset($Date)){
            $Date = null;
        }
        
        $alias=$_SESSION['alias'];
        
        $bid = filter_input(INPUT_POST, 'bid');
        
        $total_bid = 42.5*$bid;
        
        $bid = $total_bid;
        
        date_default_timezone_set('America/Chicago');
        
        $bidDate = date('Y-m-d H:i');
        
        insert_bid($alias, $bid, $bidDate);
        
        $bid_Starts=new DateTime();
        
        $bid_Ends=new DateTime('2017-3-15 12:00:00');
        
        $end_Time = $bid_Starts->diff($bid_Ends);
        
        
        
//        $top_bid = 0;
//        $top_bidder = '';
//        $top_Date = date('Y-m-d H:i');
//        
//        $results=retrieve_hBid();
//        $top_bidder= $results[0][0];
//        $top_bid=$results[0][1];
//        $top_Date=$results[0][2];
        
        include '../View/pastureRental.php';
        break;   
    //log out
    case 'log_out':
        //destroy session and header to controller to log back in
        session_destroy();
        header('Location: ../controller/index.php');
        break; //end for log out

    //attempt to register
    case 'try_register':

        //pull information from post
        $firstName = filter_input(INPUT_POST, 'first_name', FILTER_DEFAULT);
        $lastName = filter_input(INPUT_POST, 'last_name', FILTER_DEFAULT);
        $alias = filter_input(INPUT_POST, 'alias', FILTER_DEFAULT);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        //validate registration information(false at end indicates it's not a profile update)
        $error_message = validate_reg_update($firstName, $lastName, $alias, $password, $email, false);

        // if an error message exists, go to the register page
        if ($error_message != '') {
            include('../View/register.php');
            exit();
        }

        $options = ['cost' => 12,];   //options for hashing password  
        $hash = password_hash($password, PASSWORD_DEFAULT, $options); //hash password
        //
        //otherwise insert into the database and display results
        insert_bidder($firstName, $lastName, $alias, $hash, $email, '../Model/images/profile_default.png');
        include('../View/Reg_Success.php');
        break; //end for try register
}
?> 

