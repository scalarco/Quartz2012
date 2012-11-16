<?php

$ourFileName = "dbvars.php";
$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");

?>

<html>
<body>

<form action="" method="post">
MySQL Server Name: <input type="text" name="servername" /> <br />
MySQL Root Username: <input type="text" name="rootuser" /> <br />
MySQL Root Password: <input type="text" name="rootp" /> <br />
Quartz Admin Name: <input type="text" name="quartzadmin" /> <br />
Quartz Admin Password: <input type="text" name="quartzpassword" /> <br />
Quartz Database Name: <input type="text" name="databasename" /> <br />
URL of the webserver to run Quartz: <input type="text" name="webserver"/> <br />
Apache Port: <input type="text" name="apacheport" /> <br />
Subfolder where Quartz has been unzipped: <input type="text" name="subfolder" /> <br />
Ability to send email via PHP: <input type="radio" name="canmail" value="True" checked="checked" /> Yes
<input type="radio" name="canmail" value="False" /> No <br />
<input type="submit" name="submit1"/>
</form>

</body>
</html>

<?php
//include "md5it.php";

if(isset($_POST['submit1'])){
	$string = '<?php
			$serverurl = "'. $_POST["servername"]. '";
			$rootuser = "'. $_POST["rootuser"]. '";
			$rootp = "'. $_POST["rootp"]. '";
			$adminname = "'. $_POST["quartzadmin"]. '";
			$adminp = "'. $_POST["quartzpassword"].'";
			$dbname = "'. $_POST["databasename"]. '";
			$serverhost = "http://'. $_POST["webserver"]. '/";
			$serverhost = "http://'. $_POST["webserver"]. ':'. $_POST["apacheport"].'/";
			$rootpath = $serverhost."' .$_POST["subfolder"]. '/";
			$canmail = '. $_POST["canmail"]. ';
			$q = "\""; 
			?>';

	fwrite($ourFileHandle, $string);
	fclose($ourFileHandle);

	include "dbvars.php";
	$adminp=md5($adminp);
	$user = $_POST["rootuser"];
	$password = $_POST["rootp"];

	$conn = mysql_connect($serverurl, $user, $password) or die('Unable to connect to MySQL server. ' . mysql_error());
	print("PHP successfully connected to the server!");

	mysql_query("CREATE DATABASE $dbname") or die(' Unable to create database. ' . mysql_error());
	print(" PHP successfully created new database!");

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