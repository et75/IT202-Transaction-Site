<?php 

//to call function (show ($user, $output);)
//mail ("mailaddress", $output)


function show ( $user  , &$output , $number ) {
  global $db; 
  $output = "";
  $s = "select * from A where user = '$user'"  ;
  $num=$_GET[ "num" ];
  $u = "select * from T where user = '$user' limit $num"  ;	
  $output .= "<br>SQL statement is: $s<br>";
  $output .= "<br>SQL statement is: $u<br>";
  
  ( $t = mysqli_query($db, $s) ) or  die ( mysqli_error( $db ) );
  ( $b = mysqli_query($db, $u) ) or  die ( mysqli_error( $db ) );
  $num = mysqli_num_rows ( $t ); echo "<br>num is $num <br>";
  $num = mysqli_num_rows ( $b ); echo "<br>num is $num <br>";
  
  $output .= "<br>There was $num row retrieved <br><br>";
  
  while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
    $pass 	= $r[ "pass" ];
  	$current 	= $r[ "current" ];
  	$initial 	= $r[ "initial" ];	
  	$recent_trans 	= $r[ "recent_trans" ];

    $output .= "Pass is           $pass <br>";
    $output .= "Balance is  $current <br>";
    $output .= "Initial Balance is  $initial <br>";
    $output .= "Recent Transaction of $recent_trans <br>";

    };
    echo $output ;
    
  while ( $r = mysqli_fetch_array ( $b, MYSQLI_ASSOC) ) {
    $type 	= $r[ "type" ];
    $amount 	= $r[ "amount" ];
    $date 	= $r[ "date" ];
    $mail_receipt 	= $r[ "mail_receipt" ];

    $output .= "Type $type ";
    $output .= "Amount $amount ";
    $output .= "Date $date ";
    $output .= "Mail Receipt $mail_receipt <br>";
    
    };
    echo $output ;
}

function auth ( $user, $pass ) {
  global $db;
  $pass = sha1($pass);  
  $s = "select * from A where user = '$user' and pass = '$pass' ";
  echo "<br>SQL statement is: $s<br>";
  ($t = mysqli_query( $db, $s )) or die(mysqli_error($db));
  $num = mysqli_num_rows($t);
  if($num == 0) {
    return false ;
    }
  return true;
  
}

function getdata( $arg ) {
  global $db;
  $temp = $_GET[$arg];
  $temp = mysqli_real_escape_string ($db , $temp);
  echo "<br>temp is: $temp";
  return $temp;

}

function deposit ( $user, $amnt, &$output){
  global $db;
  echo "********************************************<br>";
  //1. INSERT TO T
  $s = "insert into T values( '$user', 'D', '$amnt', NOW(), 'N' )";
  ( $t = mysqli_query($db, $s) ) or  die ( mysqli_error( $db ) );
  //2. UPDATE A
  $u = "update A set current = current +'$amnt', recent_trans = NOW( ) where user = '$user'";
  ( $v = mysqli_query($db, $u) ) or  die ( mysqli_error( $db ) );
  
  //3. ECHO 
    $s = "select * from A where user = '$user'"; 
    ( $t = mysqli_query($db, $s) ) or  die ( mysqli_error( $db ) );
    $num = mysqli_num_rows ( $t ); echo "<br>num is $num <br>";
    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
  	  $current 	= $r[ "current" ];	
  	  $recent_trans 	= $r[ "recent_trans" ];
      
      $output .= "Balance is  $current <br>";
      $output .= "Recent Transaction of $recent_trans <br>";
    };
    
    $u = "select * from T where user = '$user'"  ;
    ( $v = mysqli_query($db, $u) ) or  die ( mysqli_error( $db ) );
    $num = mysqli_num_rows ( $v ); echo "<br>num is $num <br>";
    
    while ( $r = mysqli_fetch_array ( $v, MYSQLI_ASSOC) ) {
      $type 	= $r[ "type" ];
      $mail_receipt 	= $r[ "mail_receipt" ];
      $amount 	= $r[ "amount" ];
      $date 	= $r[ "date" ];
      
      $output .= "Type $type ";
      $output .= "Amount $amount ";
      $output .= "Date $date ";
      $output .= "Receipt $mail_receipt <br>";
    };
}

function withdraw ($user, $amnt, &$output){
	global $db;
	echo "********************************************<br>";
//Insert T
	$s = "insert into T values( '$user', 'W', '$amnt', NOW(), 'N' )";
  ( $t = mysqli_query($db, $s) ) or  die ( mysqli_error( $db ) );

//Update A
	$u = "update A set current = current -'$amnt', recent_trans = NOW( ) where user = '$user'";
  ( $v = mysqli_query($db, $u) ) or  die ( mysqli_error( $db ) );

  //Fetch
	$s = "select * from A where user = '$user'"; 
    ( $t = mysqli_query($db, $s) ) or  die ( mysqli_error( $db ) );
	
	$num = mysqli_num_rows ( $t ); echo "<br>num is $num <br>";
	while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
  	  $current 	= $r[ "current" ];	
  	  $recent_trans 	= $r[ "recent_trans" ];
      
      $output .= "Balance is  $current <br>";
      $output .= "Recent Transaction of $recent_trans <br>";
    };
    
	$u = "select * from T where user = '$user'"  ;
    ( $v = mysqli_query($db, $u) ) or  die ( mysqli_error( $db ) );
    $num = mysqli_num_rows ( $v ); echo "<br>num is $num <br>";
	
	while ( $r = mysqli_fetch_array ( $v, MYSQLI_ASSOC) ) {
      $type 	= $r[ "type" ];
      $mail_receipt 	= $r[ "mail_receipt" ];
      $amount 	= $r[ "amount" ];
      $date 	= $r[ "date" ];
      
      $output .= "Type $type ";
      $output .= "Amount $amount ";
      $output .= "Date $date ";
      $output .= "Receipt $mail_receipt <br>";
    };
	
}


function mailer($user, $output)
{
	global $db;
	$s = "select * from A where user = '$user'";
    ( $t = mysqli_query($db, $s) ) or  die ( mysqli_error( $db ) );
	$num = mysqli_num_rows ( $t ); echo "<br>num is $num <br>";
	$r = mysqli_fetch_array ( $t, MYSQLI_ASSOC);
  	$mail 		= $r[ "mail" ];
	  $to			= $mail	;
	  $subj		= "..."		;
	  $message	= "$output";
    $message  = wordwrap($output);
    mail($to, $subj, $message);
}

?>