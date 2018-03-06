<?php
    //session_start(); // Starting Session
    // Storing Session
    
    $login_session = false;
        
    if(isset($_SESSION['login_user_id'])){
       $login_user_id = $_SESSION['login_user_id'];
       $login_result = check_user_login($user_id, $user_pass);
            if($login_result[0] == false){
                echo "Session OUT !";
                $login_session = false;
                if (session_destroy()) { // Destroying All Sessions
                    header("Location: login.php"); // Redirecting To Home Page
                }
            }
            else {
                $login_session = true;
            }
        /*if(check_user_login_session($login_user_id)==false){
            echo "Session OUT !";
            $login_session = false;
        }
        else{
            $login_session = true;
        } */
    }else{
        header('Location: login.php');
        //exit;
    }    
    
    /*
    // SQL Query To Fetch Complete Information Of User
    $ses_sql = mysql_query("select username from login where username='$user_check'", $connection);
    $row = mysql_fetch_assoc($ses_sql);
    $login_session = $row['username'];
    if (!isset($login_session)) {
        mysql_close($connection); // Closing Connection
        header('Location: index.php'); // Redirecting To Home Page
    }
    */
?>