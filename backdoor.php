<?php
$dir=dirname("__DIR__");
$dir=scandir($dir);
$count=count($dir);
foreach($dir as $dir):
echo "
<center>
 <table border='1px' width='27%'>
  <tr>
    <td><a href='$dir'>$dir</a></td>
  </tr>
 </table>
</center>
";
endforeach;
