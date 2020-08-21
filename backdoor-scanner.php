<title>BACKDOOR SCANNER</title>
<style type="text/css">
  @import url('https://fonts.googleapis.com/css?family=Ubuntu+Mono&display=swap');
  body {
    font-family: 'Ubuntu Mono', monospace;
    color: #8a8a8a;
  }
  table {
    border-spacing:0;
    padding:10px;
    border-radius:7px;
    border:3px solid #d6d6d6;
  }
  tr, td {
    padding:7px;
  }
  th {
    color: #8a8a8a;
    padding:7px;
    font-size:25px;
  }
  input[type=submit]:focus {
    background: #ff9999;
    color:#fff;
    border: 3px solid #ff9999;
  }
  input[type=submit]:hover {
    border: 3px solid #ff9999;
    cursor: pointer;
  }
  input[type=text]:hover {
    border: 3px solid #ff9999;
  }
  input {
    font-family: 'Ubuntu Mono', monospace;
  }
  input[type=text] {
    border:3px solid #d6d6d6;
    outline:none;
    padding: 7px;
    color: #8a8a8a;
    width:100%;
    border-radius:7px;
  }
  input[type=submit] {
    color: #8a8a8a;
    border:3px solid #d6d6d6;
    outline:none;
    background:none;
    padding: 7px;
    width:100%;
    border-radius:7px;
  }
</style>
<?php
set_time_limit(0);
@ini_set('zlib.output_compression', 0);
header("Content-Encoding: none");
ob_start();
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

function reading($filename) {
	$token 	= token_get_all(file_get_contents($filename));
	$output = array();

	if (count($token) > 0) {
	 	for ($i=0; $i < count($token) ; $i++) { 
	 		if (isset($token[$i][1])) {
	 			$output[] .= $token[$i][1];
	 		}
	 	}
	 }
	 $output = array_values(array_unique(array_filter(array_map("trim", $output))));
	 return ($output);
}

function checking($string) {
	$find   = array(
        	'base64_encode',
        	'base64_decode',
        	'FATHURFREAKZ',
        	'eval',
		'system',
        	'gzinflate',
        	'str_rot13',
        	'convert_uu',
        	'shell_data',
        	'getimagesize',
        	'magicboom',
		'mysql_connect',
		'mysqli_connect',
		'basename',
		'getimagesize',
        	'exec',
        	'shell_exec',
        	'fwrite',
        	'str_replace',
        	'mail',
        	'file_get_contents',
        	'url_get_contents',
		'move_uploaded_file',
        	'symlink',
        	'substr',
		'pathinfo',
        	'__file__',
        	'__halt_compiler'
    	);
	$output = "";
	foreach ($find as $value) {
		if (in_array($value, $string)) {
			$output .= $value.", ";
		}
	} if ($output != "") {
		$output = substr($output, 0, -2);
	} return $output;
}

?>
<table align="center" width="30%">
	<tr>
		<th>
			BACKDOOR SCANNER
		</th>
	</tr>
	<form method="post">
		<tr>
			<td>
				<input type="text" name="dir" value="<?= getcwd() ?>">
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" name="submit" value="SEARCH">
			</td>
		</tr>
	</form>
<?php

if (isset($_POST['submit'])) {
	?>
	<tr>
		<td>
			<span style="font-weight:bold;font-size:25px;">RESULT</span>
		</td>
	</tr>
	<?php
	$list = listFile($_POST['dir']);

	foreach ($list as $value) {
		if (is_file($value)) {
			if (empty(checking(reading($value)))) {
				?>
				<tr>
					<td>
						<span style="color:green;">
							<?= $value ?> => SAFE
						</span>
					</td>
				</tr>
				<?php
			} elseif (preg_match("/, /", checking(reading($value)))) {
				?>
				<tr>
					<td>
						<span style="color:red;">
							<?= $value ?> => FOUND ( <?= checking(reading($value)) ?> )
						</span>
					</td>
				</tr>
				<?php
			}
			ob_flush();
			flush();
			sleep(1);
		}
	}
	ob_end_flush();
}
?>
</table>
