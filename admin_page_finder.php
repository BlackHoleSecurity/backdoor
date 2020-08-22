<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<title>Admin Finder</title>
<form action="" method="post">
<?php
set_time_limit(0);
error_reporting(0);
$list['front'] = "admin
adm
admincp
admcp
cp
modcp
moderatorcp
adminare
admins
cpanel
controlpanel";
$list['end'] = "
admin/admin-login.php
adminweb
adminpage
loginweb
administrator
adminarea
adminlogin
admin
adminlab
po-admin
wp-admin
webadmin
nasional
webmaster
ngadimin
operator
redaktur
adm
kcfinder/browse.php
kcfinder/upload.php
loginweb
useradmin
setingweb
directadmin
cpanel
user/login
register.html
login.html
home/administrator
bb-admin
public_html.zip
sika/
develop/
ketua/
redaktur/
author
user/
users/
dinkesadmin/
retel/
author/
panel/
paneladmin/
panellogin/
cp-admin/
master/
master/index.php
master/login.php
operator/index.php
sika/index.php
develop/index.php
ketua/index.php
redaktur/index.php
admin/index.php
user/index.php
users/index.php
dinkesadmin/index.php
retel/index.php
author/index.php
panel/index.php
paneladmin/index.php
panellogin/index.php
redaksi/index.php
cp-admin/index.php
operator/login.php
sika/login.php
develop/login.php
ketua/login.php
redaktur/login.php
admin/login.php
admin/admin-login.php
administrator/login.php
adminweb/login.php
user/login.php
users/login.php
dinkesadmin/login.php
retel/login.php
author/login.php
panel/login.php
paneladmin/login.php
panellogin/login.php
redaksi/login.php
cp-admin/login.php
terasadmin/
terasadmin/index.php
terasadmin/login.php
rahasia/
rahasia/index.php
rahasia/admin.php
rahasia/login.php
dinkesadmin/
dinkesadmin/login.php
adminpmb/
adminkpu
addmin/
adminarea/
redaktur/
webadmin/
systemadministrator/
adminpmb/index.php
adminpmb/login.php
system/
system/index.php
system/login.php
webadmin/
webadmin/index.php
webadmin/login.php
wpanel/
wpanel/index.php
wpanel/login.php
adminpanel/index.php
adminpanel/
adminpanel/login.php
adminkec/
adminkec/index.php
verivikator/index.php
administrasi/index.php
acces
access
aksesadmin/index,php
admin0101/
adminkec/login.php
admindesa/
admindesa/index.php
admindesa/login.php
adminkota/
adminkota/index.php
adminkota/login.php
admin123/
admin123/index.php
admin123/login.php
logout/
logout/index.php
logout/login.php
logout/admin.php
adminweb_setting";
function template()
{
    ?>
<script type="text/javascript">
<!--
function insertcode($text, $place, $replace)
{
    var $this = $text;
    var logbox = document.getElementById($place);
    if($replace == 0)
        document.getElementById($place).innerHTML = logbox.innerHTML+$this;
    else
        document.getElementById($place).innerHTML = $this;
//document.getElementById("helpbox").innerHTML = $this;
}
-->
</script>
<div class="container" style="padding:20px;width:60%;">
<table class="table table-bordered">
    <th colspan="2" class="p-3 mb-2 bg-primary text-white"><h5>ADMIN FINDER</h5></th>
<form action="" method="post" name="xploit_form">
<tr>
    <td colspan="2">
        <input class="form-control" type="text" name="xploit_url" placeholder="url" value="<?php print $_POST[
            'xploit_url'
        ]; ?>" />
        <input type="hidden" name="xploit_404string" value="<?php print $_POST[
            'xploit_404string'
        ]; ?>"/>
    </td>
</tr>
<tr>
    <td colspan="2">
        <input class="btn btn-outline-primary" style="width:100%;" type="submit" name="xploit_submit" value=" Find" align="center" />
    </td>
</tr>
</form>
<tr>
    <td>
        <div id="rightcol">
            <h5>Verificat: <span id="verified">0</span> / <span id="total">0</span></h5><br>
            <b>Found ones :</b> <br>
        </div>
    </td>
    <td>
        <div id="logbox" clear="all">
            <h5>Admin page Finder : </h5> <br>
            <b>Checking</b> <br>
        </div>
    </td>
</tr>
</table>
</div>
<?php
}
function show($msg, $br = 1, $stop = 0, $place = 'logbox', $replace = 0)
{
    if ($br == 1) {
        $msg .= "<br />";
    }
    echo "<script type=\"text/javascript\">insertcode('" .
        $msg .
        "', '" .
        $place .
        "', '" .
        $replace .
        "');</script>";
    if ($stop == 1) {
        exit();
    }
    @flush();
    @ob_flush();
}
function check($x, $front = 0)
{
    global $_POST, $site, $false;
    if ($front == 0) {
        $t = $site . $x;
    } else {
        $t = 'http://' . $x . '.' . $site . '/';
    }
    $headers = get_headers($t);
    if (!eregi('200', $headers[0])) {
        return 0;
    }
    $data = @file_get_contents($t);
    if ($_POST['xploit_404string'] == "") {
        if ($data == $false) {
            return 0;
        }
    }
    if ($_POST['xploit_404string'] != "") {
        if (strpos($data, $_POST['xploit_404string'])) {
            return 0;
        }
    }
    return 1;
}

// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
template();
if (!isset($_POST['xploit_url'])) {
    die();
}
if ($_POST['xploit_url'] == '') {
    die();
}
$site = $_POST['xploit_url'];
if ($site[strlen($site) - 1] != "/") {
    $site .= "/";
}
if ($_POST['xploit_404string'] == "") {
    $false = @file_get_contents(
        $site . "d65897f5380a21a42db94b3927b823d56ee1099a-this_can-t_exist.html"
    );
}
$list['end'] = str_replace("\r", "", $list['end']);
$list['front'] = str_replace("\r", "", $list['front']);
$pathes = explode("\n", $list['end']);
$frontpathes = explode("\n", $list['front']);
show(count($pathes) + count($frontpathes), 1, 0, 'total', 1);
$verificate = 0;
foreach ($pathes as $path) {
    show('' . $site . $path . '', 0, 0, 'logbox', 0);
    $verificate++;
    show($verificate, 0, 0, 'verified', 1);
    if (check($path) == 0) {
        show(
            '<span class="text-danger float-right">not found</span>',
            1,
            0,
            'logbox',
            0
        );
    } else {
        show(
            '<span class="text-success float-right">found</span>',
            1,
            0,
            'logbox',
            0
        );
        show(
            '<a href="http://' . $site . $path . '">' . $site . $path . '</a>',
            1,
            0,
            'rightcol',
            0
        );
    }
}
preg_match("/\/\/(.*?)\//i", $site, $xx);
$site = $xx[1];
if (substr($site, 0, 3) == "www") {
    $site = substr($site, 4);
}
foreach ($frontpathes as $frontpath) {
    show('http://' . $frontpath . '.' . $site . '/  ', 0, 0, 'logbox', 0);
    $verificate++;
    show($verificate, 0, 0, 'verified', 1);
    if (check($frontpath, 1) == 0) {
        show(
            '<span class="text-danger float-right">not found</span>',
            1,
            0,
            'logbox',
            0
        );
    } else {
        show(
            '<span class="text-success float-right">found</span>',
            1,
            0,
            'logbox',
            0
        );
        show(
            '<a href="http://' .
                $frontpath .
                '.' .
                $site .
                '/">' .
                $frontpath .
                '.' .
                $site .
                '</a></li></ul>',
            1,
            0,
            'rightcol',
            0
        );
    }
}

?>
