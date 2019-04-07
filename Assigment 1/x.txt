<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  
ini_set( 'display_errors' , 1 );

include (  "myfns.php");
include (  "account.php"     ) ;
$db = mysqli_connect($hostname, $username, $password ,$project);
if (mysqli_connect_errno())
  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
    
print "<br>Successfully connected to MySQL.<br>";
mysqli_select_db( $db, $project );

$user    = getdata ("user");
$pass    = getdata ("pass");
$num     = getdata ("num");
$amnt    = getdata ("amnt");
$service = getdata ("service");

if ( !auth ($user, $pass)) { exit("Failed") ;}

if ($service == "1") {
    echo "Choose a Service";
} elseif ($service == "2") {
    show ($user, $output, $num);
    echo $output;
} elseif ($service == "3") {
    deposit ($user, $amnt, $output);
    echo $output;
} else {
    withdraw ($user, $amnt, $output);
    echo $output;
}
if(isset($_GET["EmailReceipt"])){
    mailer($user, $output);
}else{
    echo "no receipt";
}

print "<br><br>Bye" ;
//mysqli_free_result($t);
mysqli_close($db);
exit ( "<br>Interaction completed.<br><br>"  ) ;

?>