<?php
define("SLES", DIRECTORY_SEPARATOR);
class XN {
    public static $array;
    public static $owner;
    public static $group;
    public static $directory;
    public static function files($type) {
        self::$array = array();
        foreach (scandir(getcwd()) as $key => $value) {
            $filename['name'] = getcwd() . SLES . $value;
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
    public static function cd($directory) {
        return @chdir($directory);
    }
    public static function countDir($filename) {
        return @count(scandir($filename)) -2;
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
    if (XN::cd($_GET['x'])) {
    } else {
    ?> <script type="text/javascript">alert("permission danied")</script> <?
}
}
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"/>
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
    body {
        overflow: hidden;
        margin: 0;
        margin-top: 10px;
    }
    * {
        font-family: 'Open Sans', sans-serif;
    }
    .storage {
        padding-top:10px;
        padding-bottom:10px;
    }
    .storage span.title {
        font-size: 23px;
    }
    .storage span:nth-child(3) {
        float: right;
    }
    .back {
        font-size: 20px;
        padding-bottom: 10px;
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
        background: blue;
        border-radius:10px;
        padding: 5px;
        padding-left: 7px;
        padding-right: 10px;
    }
    .files {
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        overflow: hidden;
        text-align: left;
        padding: 20px;
        border-radius:10px;
        height:600px;
        background: #fff;
        width:50%;
    }
    .table {
        overflow: auto;
        height:485px;

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
    .block .date {
        margin-top: 4px;
        font-size: 70%;
        color: #666;
    }
    .block a {
        border-radius:5px;
        display: block;
        padding-top: 8px;
        padding-bottom: 13px;
        padding-left:5px;
        transition: all 0.35s;
    }
    .block a:hover {
        text-decoration: none;
    }
    a {
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
    ul.dropdown li a {
        color: #000;
        text-decoration: none;
        padding: .5em 1em;
        display: block;
    }
    ul.dropdown li a:hover {
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
</style>
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
<center>
<div class="files">
    <div class="back">
        <a href="?x=<?= dirname(getcwd()) ?>">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </a>
        <span>
            <?= SLES . basename(getcwd()) ?>
        </span>
        <button>
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
        </button>
    </div>
    <div class="storage">
        <span class="title">
            STORAGE
        </span>
        <br><span>Total : 500 GB</span>
        <span>Free : 100 GB</span>
    </div>
    <div class="table">
    <table>
        <?php
        foreach (XN::files('dir') as $key => $dir) { ?>
            <tr class="hover">
                <td>
                    <div class="block">
                        <a href="?x=<?= $dir['name'] ?>">
                            <div class="img">
                                <img src="https://image.flaticon.com/icons/svg/716/716784.svg">
                            </div>
                            <div class="name">
                                <?= $dir['names'] ?>
                                <div class="date">
                                    <?= $dir['size'] ?> &nbsp;&nbsp;&nbsp;
                                    // permission &nbsp;&nbsp;&nbsp;
                                    <?= $dir['ftime'] ?> &nbsp;&nbsp;&nbsp;
                                    <?= $dir['owner'] ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </td>
                <td>
                    <nav>
                        <a class="dropdown-toggle" title="Menu">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown">
                            <li><a href="?<?= $dir['name'] ?>">Edit</a></li>
                            <li><a href="#">Delete</a></li>
                            <li><a href="#">Rename</a></li>
                            <li><a href="#">Change name</a></li>
                        </ul>
                    </nav>
                </td>
            </tr>
        <?php }
        foreach (XN::files('file') as $key => $file) { ?>
            <tr class="hover">
                <td>
                    <div class="block">
                        <a>
                            <div class="img">
                                <img src="https://image.flaticon.com/icons/svg/833/833524.svg">
                            </div>
                            <div class="name">
                                <?= $file['names'] ?>
                                <div class="date">
                                    <?= $file['size'] ?> &nbsp;&nbsp;&nbsp;
                                    // permission &nbsp;&nbsp;&nbsp;
                                    <?= $file['ftime'] ?> &nbsp;&nbsp;&nbsp;
                                    <?= $file['owner'] ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </td>
                <td>
                    <nav>
                        <a class="dropdown-toggle" title="Menu">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown">
                            <li><a href="?<?= $file['name'] ?>">Edit</a></li>
                            <li><a href="#">Delete</a></li>
                            <li><a href="#">Rename</a></li>
                            <li><a href="#">Change name</a></li>
                        </ul>
                    </nav>
                </td>
            </tr>
        <?php }
        ?>
        <tr>
            <td>
                Total Files : <?= XN::countAllFiles(getcwd()) ?>
            </td>
        </tr>
    </table>
    </div>
</div>
</center>
