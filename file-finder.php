<title>FILE FINDER</title>
<style type="text/css">
  @import url('https://fonts.googleapis.com/css?family=Ubuntu+Mono&display=swap');
  body {
    font-family: 'Ubuntu Mono', monospace;
    color: #8a8a8a;
  }
  table {
    border-spacing:0;
    padding:10px;
    border-radius:7px;
    border:3px solid #d6d6d6;
  }
  tr, td {
    padding:7px;
  }
  th {
    color: #8a8a8a;
    padding:7px;
    font-size:25px;
  }
  input[type=submit]:focus {
    background: #ff9999;
    color:#fff;
    border: 3px solid #ff9999;
  }
  input[type=submit]:hover {
    border: 3px solid #ff9999;
    cursor: pointer;
  }
  input[type=text]:hover {
    border: 3px solid #ff9999;
  }
  input {
    font-family: 'Ubuntu Mono', monospace;
  }
  input[type=text] {
    border:3px solid #d6d6d6;
    outline:none;
    padding: 7px;
    color: #8a8a8a;
    width:100%;
    border-radius:7px;
  }
  input[type=submit] {
    color: #8a8a8a;
    border:3px solid #d6d6d6;
    outline:none;
    background:none;
    padding: 7px;
    width:100%;
    border-radius:7px;
  }
</style>
<table align="center" width="30%">
  <tr>
    <thead>
      <th>
        FILE FINDER
      </th>
    </thead>
  </tr>
  <form method="post">
    <tr>
      <td>
        <input type="text" name="string" id="string" placeholder="filename" value="<?php echo (isset($_POST['string'])) ? $_POST['string'] : "" ?>" />
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="dir" id="dir" placeholder="dir"  value="<?php echo (isset($_POST['dir'])) ? $_POST['dir'] : "" ?>"/>
      </td>
    </tr>
    <tr>
      <td>
        <input type="text" name="ext" id="ext" placeholder="extension" value="<?php echo (isset($_POST['ext'])) ? $_POST['ext'] : "" ?>"/>
      </td>
    </tr>
    <tr>
      <td>
        <input class="search-button" type="submit" title="Search" value="Search"/>
      </td>
    </tr>
  </form>
<?php

if ($_POST) {
  $string   = $_POST['string'];
  $dir      = $_POST['dir'];
  $extArray = [];
  if ($_POST['ext'] != "") {
    $extArray = explode(",", $_POST['ext']);
  }
  ?>
  <tr>
    <td>
      <span style="font-weight:bold;font-size:25px;">RESULT</span>
    </td>
  </tr>
  <?php
  finder($string, $dir, $extArray);
}

function finder($string, $dir = '', $extArray = []) {
  if (!$dir) {
    $dir = getcwd();
  }
  foreach (scandir($dir) as $file) {
    if ($file != '.' && $file != '..') {
      if (is_dir($dir.DIRECTORY_SEPARATOR.$file)) {
        finder($string, $dir.DIRECTORY_SEPARATOR.$file, $extArray);
      } else {
        $ext = strtolower(pathinfo($dir.DIRECTORY_SEPARATOR.$file, PATHINFO_EXTENSION));
        if (!empty($extArray)) {
          if (in_array($ext, $extArray)) {
            $content = file_get_contents($dir.DIRECTORY_SEPARATOR.$file);
            if (strpos($content, $string) !== false) {
              ?>
              <tr>
                <td>
                  <?= str_replace('\\\\', DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR."<b>".$file."</b>") ?>
                </td>
              </tr>
              <?php
            }
          }
        } else {
          $content = file_get_contents($dir.DIRECTORY_SEPARATOR.$file);
          if (strpos($content, $string) !== false) {
            ?>
            <tr>
              <td>
                <?= str_replace('\\\\', DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR."<b>".$file."</b>") ?>
              </td>
            </tr>
            <?php
          }
        }
      }
    }
  }
}
?>
</table>
