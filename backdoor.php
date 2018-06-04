<?php
$dir=dirname("__DIR__");
$dir=scandir($dir);
$count=count($dir);
for($x=2;$x<$count;$x++) {
echo "
<center>
 <table border='1px' width='27%'>
  <tr>
    <td><a href='$dir[$x]'>$dir[$x]</a></td>
  </tr>
 </table>
</center>
";
}