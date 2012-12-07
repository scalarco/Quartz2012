<?php
//Created by Sean Calarco, scalarco, Peru, Team Quincy
	if ( !isset($_SESSION['usertype']) )
	{
    	session_start();
    }
	$thisfilename = "emailApprove.php";
?>

<! DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="stylesheet" type="text/css" href="Style.css" />

        <title></title>

    </head>

<body bgcolor="#EEEEEE">
<?php 

	include 'dbvars.php';
	//Email address passed in through url, store in $to
	$to = $_GET['user'];
	
	mysql_connect($serverurl, $rootuser, $rootp) or die(mysql_error());
	mysql_select_db($dbname) or die(mysql_error());
	
	//Update loginSet so this user's hash is an md5 hash of their email address
	mysql_query("UPDATE loginSet SET hash = '".md5($to)."' WHERE email = '".$to."'");
	
	//Get mail passed in through url
	$canmail= $_GET['mail'];
 	
	//Get rootpath from dbvars to include in the emailed activation link
	$root=$rootpath;
	
	//The email message 	$subject = "CS Website Approval";
	//body0 is if they have a mail server, uses \n to return line
	$body0 = "Hi,\n\nClick the link below to approve your CS account.\n\n".$rootpath."approve.php?id=".md5($to)."\n\n";
	//body is if they do not have a mail server and uses the mailto equivalent of /n to return line    $body = "Hi, %0d%0a%0d%0aClick the link below to approve your CS account.%0d%0a%0d%0a".$rootpath."approve.php?id=".md5($to)."";
	$headers = "From: admin@cs.bu.edu\r\n"."X-Mailer: php";
	
	include 'header.php';
?>
	<center>

            <div style="position: relative; width: 945px; height: 400px; background: url('images/Body.jpg');">
			<div class="nlink">
			<?php
			//If the server cannot send mail
			if($canmail==1){
			
			//Create a mailto link
			echo "<a href='mailto:" . $to . "?subject=" . $subject . "&body=" . $body . "&headers=" . $headers. "'>";
			print("Send email");

			echo "<a href=".$rootpath."index.php><br />";
			print("Return to admin panel");
			}

			else{
			//Otherwise just send the email
			mail($to, $subject, $body0, $headers);
			print("Email sent");

			echo "<a href=".$rootpath."index.php><br />";
			print("Return to admin Panel");
			}
			?>
			</div>
			</div>
    </center>
<?php
	
	include 'footer.php';
?>
</body>
</html>
