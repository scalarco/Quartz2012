<?php
//Created by Sean Calarco, scalarco, Peru, Team Quincy

//Creates dbvars using w flag to indicate to create a new file if it doesn't exist, or truncate existing file
$varsFile = "dbvars.php";
$varsFileHandle = fopen($varsFile, 'w') or die("can't open file");

?>
<! DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link rel="stylesheet" type="text/css" href="Style.css" />

    <title>Install QUARTZ2012</title>
</head>

<body bgcolor="#EEEEEE">
<center>
			<header>
			<div style="position: relative; background: url(<?php print("images/Header.jpg"); ?>); width: 945px; height: 120px;"></div>
			</header>
            
			<div style="position: relative; width: 945px; height: 400px; background: url('images/Body.jpg');">
			INSTALL QUARTZ2012
			<br></br>
			<form action="" method="post">
			MySQL Server Name: <input type="text" name="servername" /> <br />
			MySQL Root Username: <input type="text" name="rootuser" /> <br />
			MySQL Root Password: <input type="text" name="rootp" /> <br />
			Quartz Admin Name: <input type="text" name="adminname" /> <br />
			Quartz Admin Password: <input type="text" name="adminp" /> <br />
			Quartz Database Name: <input type="text" name="dbname" /> <br />
			URL of the webserver to run Quartz: <input type="text" name="serverhost"/> <br />
			Apache Port: <input type="text" name="port" /> <br />
			Subfolder where Quartz has been unzipped: <input type="text" name="subfolder" /> <br />
			Ability to send email via PHP: <input type="radio" name="canmail" value="True" checked="checked" /> Yes
			<input type="radio" name="canmail" value="False" /> No <br />
			<input type="submit" name="inst"/>
			</form>
			</div>
			
			<footer>
			<div style="position: relative; background: url(<?php print("images/Footer.jpg"); ?>); width: 945px; height: 120px;"></div>
			</footer>
</center>
</body>

<?php
//The name of the form is inst, if it has been submitted..
if(isset($_POST['inst'])){

	//Create string of the contents of dbvars.php 
	$str = '<?php
			$serverurl = "'. $_POST["servername"]. '";
			$rootuser = "'. $_POST["rootuser"]. '";
			$rootp = "'. $_POST["rootp"]. '";
			$adminname = "'. $_POST["adminname"]. '";
			$adminp = "'. $_POST["adminp"].'";
			$dbname = "'. $_POST["dbname"]. '";
			$serverhost = "http://'. $_POST["serverhost"]. '/";
			$serverhost = "http://'. $_POST["serverhost"]. ':'. $_POST["port"].'/";
			$rootpath = $serverhost."' .$_POST["subfolder"]. '/";
			$canmail = '. $_POST["canmail"]. ';
			$q = "\""; 
			?>';
	//Write string to file
	fwrite($varsFileHandle, $str);
	fclose($varsFileHandle);

	include "dbvars.php";
	$adminp=md5($adminp);
	$rUser = $_POST["rootuser"];
	$rootPass = $_POST["rootp"];

	//Connect to server and create database using input variables from the form
	$conn = mysql_connect($serverurl, $rUser, $rootPass) or die('Unable to connect to MySQL server. ' . mysql_error());
	print("Install has successfully connected to the server!");

	mysql_query("CREATE DATABASE $dbname") or die(' Unable to create database. ' . mysql_error());
	print(" Install has successfully created your database!");

	mysql_query("USE $dbname;");

	mysql_query("GRANT ALL ON $dbname.* TO $adminname@bu.edu;");

	mysql_query("SET PASSWORD FOR $adminname@bu.edu = PASSWORD($adminp);");

	mysql_query("SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO");

	mysql_query("CREATE TABLE `loginSet` ( `email` varchar(40) NOT NULL, `isApproved` int(5) NOT NULL  default '0', `hash` varchar(100) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

	mysql_query("INSERT INTO `loginSet` (`email`, `isApproved`, `hash`) VALUES('$adminname@bu.edu', 1, '');");

	mysql_query("CREATE TABLE `nLogin` (
	`email` varchar(40) collate ascii_bin NOT NULL,
	`password` varchar(100) collate ascii_bin NOT NULL,
	`name` varchar(40) collate ascii_bin NOT NULL,
	`buid` varchar(9) collate ascii_bin NOT NULL,
	`isactive` tinyint(1) NOT NULL,
	PRIMARY KEY  (`email`)
	) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin;");

	mysql_query("INSERT INTO `nLogin` (`email`, `password`, `name`, `buid`, `isactive`) VALUES('$adminname@bu.edu', '$adminp', 'admin', 'U00000000', 2);");

	mysql_query("CREATE TABLE `webData` (
	`email` varchar(40) NOT NULL,
	`name` varchar(40) NOT NULL default 'Title. Name M. Last',
	`bio` varchar(1500) NOT NULL default 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ...anim id est laborum.',
	`phone` varchar(15) NOT NULL default '(XXX) XXX-XXXX',
	`fax` varchar(40) NOT NULL default '(XXX) XXX-XXXX',
	`office` varchar(100) NOT NULL default '#XXX Street Name, BID-RMN <br> Boston, MA 02215, USA',
	`jobtitle` varchar(40) NOT NULL default 'Job Title Here',
	`ofhours` varchar(100) NOT NULL default 'Day TT:TT - TT:TT <br> Day TT:TT - TT:TT',
	`isonline` tinyint(1) NOT NULL default '0',
	`researchsum` varchar(2000) NOT NULL default 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ...anim id est laborum.',
	`teaching` varchar(1000) NOT NULL,
	`reslink` varchar(100) NOT NULL,
	`awards` varchar(5) NOT NULL,
	`projects` varchar(5) NOT NULL,
	`students` varchar(5) NOT NULL,
	`personal` varchar(5) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

	mysql_query("INSERT INTO `webData` (`email`, `name`, `bio`, `phone`, `fax`, `office`, `jobtitle`, `ofhours`, `isonline`, `researchsum`, `teaching`, `reslink`, `awards`, `projects`, `students`, `personal`) VALUES('$adminname@bu.edu', 'Title. Name M. Last', 'Lorem ipsum dolor ... est laborum.', '(XXX) XXX-XXXX', '(XXX) XXX-XXXX', '#XXX Street Name, BID-RMN <br> Boston, MA 02215, USA', 'Job Title Here', 'Day TT:TT - TT:TT <br> Day TT:TT - TT:TT', 1, 'Lorem ipsum dolor sit ...anim id est laborum.', 'CS XXX : Course Title;; - Lorem ipsum dolor ... pariatur.;CS XXX : Course Title;; - Lorem ipsum dolor ... pariatur.;', '', '', '', '', '');");
	
	mysql_close($conn);
}
?>
</html>