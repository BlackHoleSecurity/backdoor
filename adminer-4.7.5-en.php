<?php
/** Adminer - Compact database management
 * @link https://www.adminer.org/
 * @author Jakub Vrana, https://www.vrana.cz/
 * @copyright 2007 Jakub Vrana
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 * @version 4.7.5
 */ error_reporting(6135);
$Vc = !preg_match('~^(unsafe_raw)?$~', ini_get("filter.default"));
if ($Vc || ini_get("filter.default_flags")) {
    foreach (['_GET', '_POST', '_COOKIE', '_SERVER'] as $X) {
        $Gi = filter_input_array(constant("INPUT$X"), FILTER_UNSAFE_RAW);
        if ($Gi) {
            $$X = $Gi;
        }
    }
}
if (function_exists("mb_internal_encoding")) {
    mb_internal_encoding("8bit");
}
function connection()
{
    global $g;
    return $g;
}
function adminer()
{
    global $b;
    return $b;
}
function version()
{
    global $ia;
    return $ia;
}
function idf_unescape($u)
{
    $ne = substr($u, -1);
    return str_replace($ne . $ne, $ne, substr($u, 1, -1));
}
function escape_string($X)
{
    return substr(q($X), 1, -1);
}
function number($X)
{
    return preg_replace('~[^0-9]+~', '', $X);
}
function number_type()
{
    return '((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';
}
function remove_slashes($qg, $Vc = false)
{
    if (get_magic_quotes_gpc()) {
        while (list($y, $X) = each($qg)) {
            foreach ($X as $de => $W) {
                unset($qg[$y][$de]);
                if (is_array($W)) {
                    $qg[$y][stripslashes($de)] = $W;
                    $qg[] = &$qg[$y][stripslashes($de)];
                } else {
                    $qg[$y][stripslashes($de)] = $Vc ? $W : stripslashes($W);
                }
            }
        }
    }
}
function bracket_escape($u, $Oa = false)
{
    static $si = [':' => ':1', ']' => ':2', '[' => ':3', '"' => ':4'];
    return strtr($u, $Oa ? array_flip($si) : $si);
}
function min_version($Yi, $Be = "", $h = null)
{
    global $g;
    if (!$h) {
        $h = $g;
    }
    $lh = $h->server_info;
    if ($Be && preg_match('~([\d.]+)-MariaDB~', $lh, $A)) {
        $lh = $A[1];
        $Yi = $Be;
    }
    return version_compare($lh, $Yi) >= 0;
}
function charset($g)
{
    return min_version("5.5.3", 0, $g) ? "utf8mb4" : "utf8";
}
function script($wh, $ri = "\n")
{
    return "<script" . nonce() . ">$wh</script>$ri";
}
function script_src($Li)
{
    return "<script src='" . h($Li) . "'" . nonce() . "></script>\n";
}
function nonce()
{
    return ' nonce="' . get_nonce() . '"';
}
function target_blank()
{
    return ' target="_blank" rel="noreferrer noopener"';
}
function h($P)
{
    return str_replace("\0", "&#0;", htmlspecialchars($P, ENT_QUOTES, 'utf-8'));
}
function nl_br($P)
{
    return str_replace("\n", "<br>", $P);
}
function checkbox($B, $Y, $fb, $ke = "", $sf = "", $kb = "", $le = "")
{
    $H =
        "<input type='checkbox' name='$B' value='" .
        h($Y) .
        "'" .
        ($fb ? " checked" : "") .
        ($le ? " aria-labelledby='$le'" : "") .
        ">" .
        ($sf ? script("qsl('input').onclick = function () { $sf };", "") : "");
    return $ke != "" || $kb
        ? "<label" . ($kb ? " class='$kb'" : "") . ">$H" . h($ke) . "</label>"
        : $H;
}
function optionlist($yf, $fh = null, $Qi = false)
{
    $H = "";
    foreach ($yf as $de => $W) {
        $zf = [$de => $W];
        if (is_array($W)) {
            $H .= '<optgroup label="' . h($de) . '">';
            $zf = $W;
        }
        foreach ($zf as $y => $X) {
            $H .=
                '<option' .
                ($Qi || is_string($y) ? ' value="' . h($y) . '"' : '') .
                (($Qi || is_string($y) ? (string) $y : $X) === $fh
                    ? ' selected'
                    : '') .
                '>' .
                h($X);
        }
        if (is_array($W)) {
            $H .= '</optgroup>';
        }
    }
    return $H;
}
function html_select($B, $yf, $Y = "", $rf = true, $le = "")
{
    if ($rf) {
        return "<select name='" .
            h($B) .
            "'" .
            ($le ? " aria-labelledby='$le'" : "") .
            ">" .
            optionlist($yf, $Y) .
            "</select>" .
            (is_string($rf)
                ? script("qsl('select').onchange = function () { $rf };", "")
                : "");
    }
    $H = "";
    foreach ($yf as $y => $X) {
        $H .=
            "<label><input type='radio' name='" .
            h($B) .
            "' value='" .
            h($y) .
            "'" .
            ($y == $Y ? " checked" : "") .
            ">" .
            h($X) .
            "</label>";
    }
    return $H;
}
function select_input($Ja, $yf, $Y = "", $rf = "", $cg = "")
{
    $Wh = $yf ? "select" : "input";
    return "<$Wh$Ja" .
        ($yf
            ? "><option value=''>$cg" . optionlist($yf, $Y, true) . "</select>"
            : " size='10' value='" . h($Y) . "' placeholder='$cg'>") .
        ($rf ? script("qsl('$Wh').onchange = $rf;", "") : "");
}
function confirm($Le = "", $gh = "qsl('input')")
{
    return script(
        "$gh.onclick = function () { return confirm('" .
            ($Le ? js_escape($Le) : 'Are you sure?') .
            "'); };",
        ""
    );
}
function print_fieldset($t, $se, $bj = false)
{
    echo "<fieldset><legend>",
        "<a href='#fieldset-$t'>$se</a>",
        script("qsl('a').onclick = partial(toggle, 'fieldset-$t');", ""),
        "</legend>",
        "<div id='fieldset-$t'" . ($bj ? "" : " class='hidden'") . ">\n";
}
function bold($Wa, $kb = "")
{
    return $Wa ? " class='active $kb'" : ($kb ? " class='$kb'" : "");
}
function odd($H = ' class="odd"')
{
    static $s = 0;
    if (!$H) {
        $s = -1;
    }
    return $s++ % 2 ? $H : '';
}
function js_escape($P)
{
    return addcslashes($P, "\r\n'\\/");
}
function json_row($y, $X = null)
{
    static $Wc = true;
    if ($Wc) {
        echo "{";
    }
    if ($y != "") {
        echo ($Wc ? "" : ",") .
            "\n\t\"" .
            addcslashes($y, "\r\n\t\"\\/") .
            '": ' .
            ($X !== null ? '"' . addcslashes($X, "\r\n\"\\/") . '"' : 'null');
        $Wc = false;
    } else {
        echo "\n}\n";
        $Wc = true;
    }
}
function ini_bool($Qd)
{
    $X = ini_get($Qd);
    return preg_match('~^(on|true|yes)$~i', $X) || (int) $X;
}
function sid()
{
    static $H;
    if ($H === null) {
        $H = SID && !($_COOKIE && ini_bool("session.use_cookies"));
    }
    return $H;
}
function set_password($Xi, $M, $V, $E)
{
    $_SESSION["pwds"][$Xi][$M][$V] =
        $_COOKIE["adminer_key"] && is_string($E)
            ? [encrypt_string($E, $_COOKIE["adminer_key"])]
            : $E;
}
function get_password()
{
    $H = get_session("pwds");
    if (is_array($H)) {
        $H = $_COOKIE["adminer_key"]
            ? decrypt_string($H[0], $_COOKIE["adminer_key"])
            : false;
    }
    return $H;
}
function q($P)
{
    global $g;
    return $g->quote($P);
}
function get_vals($F, $e = 0)
{
    global $g;
    $H = [];
    $G = $g->query($F);
    if (is_object($G)) {
        while ($I = $G->fetch_row()) {
            $H[] = $I[$e];
        }
    }
    return $H;
}
function get_key_vals($F, $h = null, $oh = true)
{
    global $g;
    if (!is_object($h)) {
        $h = $g;
    }
    $H = [];
    $G = $h->query($F);
    if (is_object($G)) {
        while ($I = $G->fetch_row()) {
            if ($oh) {
                $H[$I[0]] = $I[1];
            } else {
                $H[] = $I[0];
            }
        }
    }
    return $H;
}
function get_rows($F, $h = null, $n = "<p class='error'>")
{
    global $g;
    $xb = is_object($h) ? $h : $g;
    $H = [];
    $G = $xb->query($F);
    if (is_object($G)) {
        while ($I = $G->fetch_assoc()) {
            $H[] = $I;
        }
    } elseif (!$G && !is_object($h) && $n && defined("PAGE_HEADER")) {
        echo $n . error() . "\n";
    }
    return $H;
}
function unique_array($I, $w)
{
    foreach ($w as $v) {
        if (preg_match("~PRIMARY|UNIQUE~", $v["type"])) {
            $H = [];
            foreach ($v["columns"] as $y) {
                if (!isset($I[$y])) {
                    continue 2;
                }
                $H[$y] = $I[$y];
            }
            return $H;
        }
    }
}
function escape_key($y)
{
    if (
        preg_match(
            '(^([\w(]+)(' .
                str_replace("_", ".*", preg_quote(idf_escape("_"))) .
                ')([ \w)]+)$)',
            $y,
            $A
        )
    ) {
        return $A[1] . idf_escape(idf_unescape($A[2])) . $A[3];
    }
    return idf_escape($y);
}
function where($Z, $p = [])
{
    global $g, $x;
    $H = [];
    foreach ((array) $Z["where"] as $y => $X) {
        $y = bracket_escape($y, 1);
        $e = escape_key($y);
        $H[] =
            $e .
            ($x == "sql" && is_numeric($X) && preg_match('~\.~', $X)
                ? " LIKE " . q($X)
                : ($x == "mssql"
                    ? " LIKE " . q(preg_replace('~[_%[]~', '[\0]', $X))
                    : " = " . unconvert_field($p[$y], q($X))));
        if (
            $x == "sql" &&
            preg_match('~char|text~', $p[$y]["type"]) &&
            preg_match("~[^ -@]~", $X)
        ) {
            $H[] = "$e = " . q($X) . " COLLATE " . charset($g) . "_bin";
        }
    }
    foreach ((array) $Z["null"] as $y) {
        $H[] = escape_key($y) . " IS NULL";
    }
    return implode(" AND ", $H);
}
function where_check($X, $p = [])
{
    parse_str($X, $db);
    remove_slashes([&$db]);
    return where($db, $p);
}
function where_link($s, $e, $Y, $uf = "=")
{
    return "&where%5B$s%5D%5Bcol%5D=" .
        urlencode($e) .
        "&where%5B$s%5D%5Bop%5D=" .
        urlencode($Y !== null ? $uf : "IS NULL") .
        "&where%5B$s%5D%5Bval%5D=" .
        urlencode($Y);
}
function convert_fields($f, $p, $K = [])
{
    $H = "";
    foreach ($f as $y => $X) {
        if ($K && !in_array(idf_escape($y), $K)) {
            continue;
        }
        $Ga = convert_field($p[$y]);
        if ($Ga) {
            $H .= ", $Ga AS " . idf_escape($y);
        }
    }
    return $H;
}
function cookie($B, $Y, $ve = 2592000)
{
    global $ba;
    return header(
        "Set-Cookie: $B=" .
            urlencode($Y) .
            ($ve
                ? "; expires=" . gmdate("D, d M Y H:i:s", time() + $ve) . " GMT"
                : "") .
            "; path=" .
            preg_replace('~\?.*~', '', $_SERVER["REQUEST_URI"]) .
            ($ba ? "; secure" : "") .
            "; HttpOnly; SameSite=lax",
        false
    );
}
function restart_session()
{
    if (!ini_bool("session.use_cookies")) {
        session_start();
    }
}
function stop_session($bd = false)
{
    $Pi = ini_bool("session.use_cookies");
    if (!$Pi || $bd) {
        session_write_close();
        if ($Pi && @ini_set("session.use_cookies", false) === false) {
            session_start();
        }
    }
}
function &get_session($y)
{
    return $_SESSION[$y][DRIVER][SERVER][$_GET["username"]];
}
function set_session($y, $X)
{
    $_SESSION[$y][DRIVER][SERVER][$_GET["username"]] = $X;
}
function auth_url($Xi, $M, $V, $l = null)
{
    global $ec;
    preg_match(
        '~([^?]*)\??(.*)~',
        remove_from_uri(
            implode("|", array_keys($ec)) .
                "|username|" .
                ($l !== null ? "db|" : "") .
                session_name()
        ),
        $A
    );
    return "$A[1]?" .
        (sid() ? SID . "&" : "") .
        ($Xi != "server" || $M != ""
            ? urlencode($Xi) . "=" . urlencode($M) . "&"
            : "") .
        "username=" .
        urlencode($V) .
        ($l != "" ? "&db=" . urlencode($l) : "") .
        ($A[2] ? "&$A[2]" : "");
}
function is_ajax()
{
    return $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest";
}
function redirect($xe, $Le = null)
{
    if ($Le !== null) {
        restart_session();
        $_SESSION["messages"][
            preg_replace(
                '~^[^?]*~',
                '',
                $xe !== null ? $xe : $_SERVER["REQUEST_URI"]
            )
        ][] = $Le;
    }
    if ($xe !== null) {
        if ($xe == "") {
            $xe = ".";
        }
        header("Location: $xe");
        exit();
    }
}
function query_redirect(
    $F,
    $xe,
    $Le,
    $Bg = true,
    $Cc = true,
    $Nc = false,
    $ei = ""
) {
    global $g, $n, $b;
    if ($Cc) {
        $Dh = microtime(true);
        $Nc = !$g->query($F);
        $ei = format_time($Dh);
    }
    $zh = "";
    if ($F) {
        $zh = $b->messageQuery($F, $ei, $Nc);
    }
    if ($Nc) {
        $n = error() . $zh . script("messagesPrint();");
        return false;
    }
    if ($Bg) {
        redirect($xe, $Le . $zh);
    }
    return true;
}
function queries($F)
{
    global $g;
    static $vg = [];
    static $Dh;
    if (!$Dh) {
        $Dh = microtime(true);
    }
    if ($F === null) {
        return [implode("\n", $vg), format_time($Dh)];
    }
    $vg[] =
        (preg_match('~;$~', $F) ? "DELIMITER ;;\n$F;\nDELIMITER " : $F) . ";";
    return $g->query($F);
}
function apply_queries($F, $S, $zc = 'table')
{
    foreach ($S as $Q) {
        if (!queries("$F " . $zc($Q))) {
            return false;
        }
    }
    return true;
}
function queries_redirect($xe, $Le, $Bg)
{
    list($vg, $ei) = queries(null);
    return query_redirect($vg, $xe, $Le, $Bg, false, !$Bg, $ei);
}
function format_time($Dh)
{
    return sprintf('%.3f s', max(0, microtime(true) - $Dh));
}
function remove_from_uri($Nf = "")
{
    return substr(
        preg_replace(
            "~(?<=[?&])($Nf" . (SID ? "" : "|" . session_name()) . ")=[^&]*&~",
            '',
            "$_SERVER[REQUEST_URI]&"
        ),
        0,
        -1
    );
}
function pagination($D, $Kb)
{
    return " " .
        ($D == $Kb
            ? $D + 1
            : '<a href="' .
                h(
                    remove_from_uri("page") .
                        ($D
                            ? "&page=$D" .
                                ($_GET["next"]
                                    ? "&next=" . urlencode($_GET["next"])
                                    : "")
                            : "")
                ) .
                '">' .
                ($D + 1) .
                "</a>");
}
function get_file($y, $Sb = false)
{
    $Tc = $_FILES[$y];
    if (!$Tc) {
        return null;
    }
    foreach ($Tc as $y => $X) {
        $Tc[$y] = (array) $X;
    }
    $H = '';
    foreach ($Tc["error"] as $y => $n) {
        if ($n) {
            return $n;
        }
        $B = $Tc["name"][$y];
        $mi = $Tc["tmp_name"][$y];
        $_b = file_get_contents(
            $Sb && preg_match('~\.gz$~', $B) ? "compress.zlib://$mi" : $mi
        );
        if ($Sb) {
            $Dh = substr($_b, 0, 3);
            if (
                function_exists("iconv") &&
                preg_match("~^\xFE\xFF|^\xFF\xFE~", $Dh, $Hg)
            ) {
                $_b = iconv("utf-16", "utf-8", $_b);
            } elseif ($Dh == "\xEF\xBB\xBF") {
                $_b = substr($_b, 3);
            }
            $H .= $_b . "\n\n";
        } else {
            $H .= $_b;
        }
    }
    return $H;
}
function upload_error($n)
{
    $Ie = $n == UPLOAD_ERR_INI_SIZE ? ini_get("upload_max_filesize") : 0;
    return $n
        ? 'Unable to upload a file.' .
                ($Ie
                    ? " " . sprintf('Maximum allowed file size is %sB.', $Ie)
                    : "")
        : 'File does not exist.';
}
function repeat_pattern($ag, $te)
{
    return str_repeat("$ag{0,65535}", $te / 65535) .
        "$ag{0," .
        $te % 65535 .
        "}";
}
function is_utf8($X)
{
    return preg_match('~~u', $X) && !preg_match('~[\0-\x8\xB\xC\xE-\x1F]~', $X);
}
function shorten_utf8($P, $te = 80, $Kh = "")
{
    if (
        !preg_match(
            "(^(" . repeat_pattern("[\t\r\n -\x{10FFFF}]", $te) . ")($)?)u",
            $P,
            $A
        )
    ) {
        preg_match(
            "(^(" . repeat_pattern("[\t\r\n -~]", $te) . ")($)?)",
            $P,
            $A
        );
    }
    return h($A[1]) . $Kh . (isset($A[2]) ? "" : "<i>…</i>");
}
function format_number($X)
{
    return strtr(
        number_format($X, 0, ".", ','),
        preg_split('~~u', '0123456789', -1, PREG_SPLIT_NO_EMPTY)
    );
}
function friendly_url($X)
{
    return preg_replace('~[^a-z0-9_]~i', '-', $X);
}
function hidden_fields($qg, $Fd = [])
{
    $H = false;
    while (list($y, $X) = each($qg)) {
        if (!in_array($y, $Fd)) {
            if (is_array($X)) {
                foreach ($X as $de => $W) {
                    $qg[$y . "[$de]"] = $W;
                }
            } else {
                $H = true;
                echo '<input type="hidden" name="' .
                    h($y) .
                    '" value="' .
                    h($X) .
                    '">';
            }
        }
    }
    return $H;
}
function hidden_fields_get()
{
    echo sid()
    ? '<input type="hidden" name="' .
        session_name() .
        '" value="' .
        h(session_id()) .
        '">'
    : '',
        SERVER !== null
            ? '<input type="hidden" name="' .
                DRIVER .
                '" value="' .
                h(SERVER) .
                '">'
            : "",
        '<input type="hidden" name="username" value="' .
            h($_GET["username"]) .
            '">';
}
function table_status1($Q, $Oc = false)
{
    $H = table_status($Q, $Oc);
    return $H ? $H : ["Name" => $Q];
}
function column_foreign_keys($Q)
{
    global $b;
    $H = [];
    foreach ($b->foreignKeys($Q) as $q) {
        foreach ($q["source"] as $X) {
            $H[$X][] = $q;
        }
    }
    return $H;
}
function enum_input($T, $Ja, $o, $Y, $tc = null)
{
    global $b;
    preg_match_all("~'((?:[^']|'')*)'~", $o["length"], $De);
    $H =
        $tc !== null
            ? "<label><input type='$T'$Ja value='$tc'" .
                ((is_array($Y)
                        ? in_array($tc, $Y)
                        : $Y === 0)
                    ? " checked"
                    : "") .
                "><i>" .
                'empty' .
                "</i></label>"
            : "";
    foreach ($De[1] as $s => $X) {
        $X = stripcslashes(str_replace("''", "'", $X));
        $fb = is_int($Y)
            ? $Y == $s + 1
            : (is_array($Y)
                ? in_array($s + 1, $Y)
                : $Y === $X);
        $H .=
            " <label><input type='$T'$Ja value='" .
            ($s + 1) .
            "'" .
            ($fb ? ' checked' : '') .
            '>' .
            h($b->editVal($X, $o)) .
            '</label>';
    }
    return $H;
}
function input($o, $Y, $r)
{
    global $U, $b, $x;
    $B = h(bracket_escape($o["field"]));
    echo "<td class='function'>";
    if (is_array($Y) && !$r) {
        $Ea = [$Y];
        if (version_compare(PHP_VERSION, 5.4) >= 0) {
            $Ea[] = JSON_PRETTY_PRINT;
        }
        $Y = call_user_func_array('json_encode', $Ea);
        $r = "json";
    }
    $Lg = $x == "mssql" && $o["auto_increment"];
    if ($Lg && !$_POST["save"]) {
        $r = null;
    }
    $kd =
        (isset($_GET["select"]) || $Lg ? ["orig" => 'original'] : []) +
        $b->editFunctions($o);
    $Ja = " name='fields[$B]'";
    if ($o["type"] == "enum") {
        echo h($kd[""]) . "<td>" . $b->editInput($_GET["edit"], $o, $Ja, $Y);
    } else {
        $ud = in_array($r, $kd) || isset($kd[$r]);
        echo (count($kd) > 1
            ? "<select name='function[$B]'>" .
                optionlist($kd, $r === null || $ud ? $r : "") .
                "</select>" .
                on_help("getTarget(event).value.replace(/^SQL\$/, '')", 1) .
                script("qsl('select').onchange = functionChange;", "")
            : h(reset($kd))) . '<td>';
        $Sd = $b->editInput($_GET["edit"], $o, $Ja, $Y);
        if ($Sd != "") {
            echo $Sd;
        } elseif (preg_match('~bool~', $o["type"])) {
            echo "<input type='hidden'$Ja value='0'>" .
                "<input type='checkbox'" .
                (preg_match('~^(1|t|true|y|yes|on)$~i', $Y)
                    ? " checked='checked'"
                    : "") .
                "$Ja value='1'>";
        } elseif ($o["type"] == "set") {
            preg_match_all("~'((?:[^']|'')*)'~", $o["length"], $De);
            foreach ($De[1] as $s => $X) {
                $X = stripcslashes(str_replace("''", "'", $X));
                $fb = is_int($Y)
                    ? ($Y >> $s) & 1
                    : in_array($X, explode(",", $Y), true);
                echo " <label><input type='checkbox' name='fields[$B][$s]' value='" .
                    (1 << $s) .
                    "'" .
                    ($fb ? ' checked' : '') .
                    ">" .
                    h($b->editVal($X, $o)) .
                    '</label>';
            }
        } elseif (
            preg_match('~blob|bytea|raw|file~', $o["type"]) &&
            ini_bool("file_uploads")
        ) {
            echo "<input type='file' name='fields-$B'>";
        } elseif (
            ($ci = preg_match('~text|lob|memo~i', $o["type"])) ||
            preg_match("~\n~", $Y)
        ) {
            if ($ci && $x != "sqlite") {
                $Ja .= " cols='50' rows='12'";
            } else {
                $J = min(12, substr_count($Y, "\n") + 1);
                $Ja .=
                    " cols='30' rows='$J'" .
                    ($J == 1 ? " style='height: 1.2em;'" : "");
            }
            echo "<textarea$Ja>" . h($Y) . '</textarea>';
        } elseif ($r == "json" || preg_match('~^jsonb?$~', $o["type"])) {
            echo "<textarea$Ja cols='50' rows='12' class='jush-js'>" .
                h($Y) .
                '</textarea>';
        } else {
            $Ke =
                !preg_match('~int~', $o["type"]) &&
                preg_match('~^(\d+)(,(\d+))?$~', $o["length"], $A)
                    ? (preg_match("~binary~", $o["type"]) ? 2 : 1) * $A[1] +
                        ($A[3] ? 1 : 0) +
                        ($A[2] && !$o["unsigned"] ? 1 : 0)
                    : ($U[$o["type"]]
                        ? $U[$o["type"]] + ($o["unsigned"] ? 0 : 1)
                        : 0);
            if (
                $x == 'sql' &&
                min_version(5.6) &&
                preg_match('~time~', $o["type"])
            ) {
                $Ke += 7;
            }
            echo "<input" .
                ((!$ud || $r === "") &&
                preg_match('~(?<!o)int(?!er)~', $o["type"]) &&
                !preg_match('~\[\]~', $o["full_type"])
                    ? " type='number'"
                    : "") .
                " value='" .
                h($Y) .
                "'" .
                ($Ke ? " data-maxlength='$Ke'" : "") .
                (preg_match('~char|binary~', $o["type"]) && $Ke > 20
                    ? " size='40'"
                    : "") .
                "$Ja>";
        }
        echo $b->editHint($_GET["edit"], $o, $Y);
        $Wc = 0;
        foreach ($kd as $y => $X) {
            if ($y === "" || !$X) {
                break;
            }
            $Wc++;
        }
        if ($Wc) {
            echo script(
                "mixin(qsl('td'), {onchange: partial(skipOriginal, $Wc), oninput: function () { this.onchange(); }});"
            );
        }
    }
}
function process_input($o)
{
    global $b, $m;
    $u = bracket_escape($o["field"]);
    $r = $_POST["function"][$u];
    $Y = $_POST["fields"][$u];
    if ($o["type"] == "enum") {
        if ($Y == -1) {
            return false;
        }
        if ($Y == "") {
            return "NULL";
        }
        return +$Y;
    }
    if ($o["auto_increment"] && $Y == "") {
        return null;
    }
    if ($r == "orig") {
        return preg_match('~^CURRENT_TIMESTAMP~i', $o["on_update"])
            ? idf_escape($o["field"])
            : false;
    }
    if ($r == "NULL") {
        return "NULL";
    }
    if ($o["type"] == "set") {
        return array_sum((array) $Y);
    }
    if ($r == "json") {
        $r = "";
        $Y = json_decode($Y, true);
        if (!is_array($Y)) {
            return false;
        }
        return $Y;
    }
    if (
        preg_match('~blob|bytea|raw|file~', $o["type"]) &&
        ini_bool("file_uploads")
    ) {
        $Tc = get_file("fields-$u");
        if (!is_string($Tc)) {
            return false;
        }
        return $m->quoteBinary($Tc);
    }
    return $b->processInput($o, $Y, $r);
}
function fields_from_edit()
{
    global $m;
    $H = [];
    foreach ((array) $_POST["field_keys"] as $y => $X) {
        if ($X != "") {
            $X = bracket_escape($X);
            $_POST["function"][$X] = $_POST["field_funs"][$y];
            $_POST["fields"][$X] = $_POST["field_vals"][$y];
        }
    }
    foreach ((array) $_POST["fields"] as $y => $X) {
        $B = bracket_escape($y, 1);
        $H[$B] = [
            "field" => $B,
            "privileges" => ["insert" => 1, "update" => 1],
            "null" => 1,
            "auto_increment" => $y == $m->primary,
        ];
    }
    return $H;
}
function search_tables()
{
    global $b, $g;
    $_GET["where"][0]["val"] = $_POST["query"];
    $ih = "<ul>\n";
    foreach (table_status('', true) as $Q => $R) {
        $B = $b->tableName($R);
        if (
            isset($R["Engine"]) &&
            $B != "" &&
            (!$_POST["tables"] || in_array($Q, $_POST["tables"]))
        ) {
            $G = $g->query(
                "SELECT" .
                    limit(
                        "1 FROM " . table($Q),
                        " WHERE " .
                            implode(
                                " AND ",
                                $b->selectSearchProcess(fields($Q), [])
                            ),
                        1
                    )
            );
            if (!$G || $G->fetch_row()) {
                $mg =
                    "<a href='" .
                    h(
                        ME .
                            "select=" .
                            urlencode($Q) .
                            "&where[0][op]=" .
                            urlencode($_GET["where"][0]["op"]) .
                            "&where[0][val]=" .
                            urlencode($_GET["where"][0]["val"])
                    ) .
                    "'>$B</a>";
                echo "$ih<li>" .
                    ($G ? $mg : "<p class='error'>$mg: " . error()) .
                    "\n";
                $ih = "";
            }
        }
    }
    echo ($ih ? "<p class='message'>" . 'No tables.' : "</ul>") . "\n";
}
function dump_headers($Cd, $Ue = false)
{
    global $b;
    $H = $b->dumpHeaders($Cd, $Ue);
    $Kf = $_POST["output"];
    if ($Kf != "text") {
        header(
            "Content-Disposition: attachment; filename=" .
                $b->dumpFilename($Cd) .
                ".$H" .
                ($Kf != "file" && !preg_match('~[^0-9a-z]~', $Kf) ? ".$Kf" : "")
        );
    }
    session_write_close();
    ob_flush();
    flush();
    return $H;
}
function dump_csv($I)
{
    foreach ($I as $y => $X) {
        if (preg_match("~[\"\n,;\t]~", $X) || $X === "") {
            $I[$y] = '"' . str_replace('"', '""', $X) . '"';
        }
    }
    echo implode(
        $_POST["format"] == "csv"
            ? ","
            : ($_POST["format"] == "tsv"
                ? "\t"
                : ";"),
        $I
    ) . "\r\n";
}
function apply_sql_function($r, $e)
{
    return $r
        ? ($r == "unixepoch"
            ? "DATETIME($e, '$r')"
            : ($r == "count distinct" ? "COUNT(DISTINCT " : strtoupper("$r(")) .
                "$e)")
        : $e;
}
function get_temp_dir()
{
    $H = ini_get("upload_tmp_dir");
    if (!$H) {
        if (function_exists('sys_get_temp_dir')) {
            $H = sys_get_temp_dir();
        } else {
            $Uc = @tempnam("", "");
            if (!$Uc) {
                return false;
            }
            $H = dirname($Uc);
            unlink($Uc);
        }
    }
    return $H;
}
function file_open_lock($Uc)
{
    $id = @fopen($Uc, "r+");
    if (!$id) {
        $id = @fopen($Uc, "w");
        if (!$id) {
            return;
        }
        chmod($Uc, 0660);
    }
    flock($id, LOCK_EX);
    return $id;
}
function file_write_unlock($id, $Mb)
{
    rewind($id);
    fwrite($id, $Mb);
    ftruncate($id, strlen($Mb));
    flock($id, LOCK_UN);
    fclose($id);
}
function password_file($i)
{
    $Uc = get_temp_dir() . "/adminer.key";
    $H = @file_get_contents($Uc);
    if ($H || !$i) {
        return $H;
    }
    $id = @fopen($Uc, "w");
    if ($id) {
        chmod($Uc, 0660);
        $H = rand_string();
        fwrite($id, $H);
        fclose($id);
    }
    return $H;
}
function rand_string()
{
    return md5(uniqid(mt_rand(), true));
}
function select_value($X, $_, $o, $di)
{
    global $b;
    if (is_array($X)) {
        $H = "";
        foreach ($X as $de => $W) {
            $H .=
                "<tr>" .
                ($X != array_values($X) ? "<th>" . h($de) : "") .
                "<td>" .
                select_value($W, $_, $o, $di);
        }
        return "<table cellspacing='0'>$H</table>";
    }
    if (!$_) {
        $_ = $b->selectLink($X, $o);
    }
    if ($_ === null) {
        if (is_mail($X)) {
            $_ = "mailto:$X";
        }
        if (is_url($X)) {
            $_ = $X;
        }
    }
    $H = $b->editVal($X, $o);
    if ($H !== null) {
        if (!is_utf8($H)) {
            $H = "\0";
        } elseif ($di != "" && is_shortable($o)) {
            $H = shorten_utf8($H, max(0, +$di));
        } else {
            $H = h($H);
        }
    }
    return $b->selectVal($H, $_, $o, $X);
}
function is_mail($qc)
{
    $Ha = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
    $dc = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
    $ag = "$Ha+(\\.$Ha+)*@($dc?\\.)+$dc";
    return is_string($qc) && preg_match("(^$ag(,\\s*$ag)*\$)i", $qc);
}
function is_url($P)
{
    $dc = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
    return preg_match(
        "~^(https?)://($dc?\\.)+$dc(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",
        $P
    );
}
function is_shortable($o)
{
    return preg_match(
        '~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~',
        $o["type"]
    );
}
function count_rows($Q, $Z, $Yd, $nd)
{
    global $x;
    $F = " FROM " . table($Q) . ($Z ? " WHERE " . implode(" AND ", $Z) : "");
    return $Yd && ($x == "sql" || count($nd) == 1)
        ? "SELECT COUNT(DISTINCT " . implode(", ", $nd) . ")$F"
        : "SELECT COUNT(*)" .
                ($Yd
                    ? " FROM (SELECT 1$F GROUP BY " . implode(", ", $nd) . ") x"
                    : $F);
}
function slow_query($F)
{
    global $b, $oi, $m;
    $l = $b->database();
    $fi = $b->queryTimeout();
    $th = $m->slowQuery($F, $fi);
    if (
        !$th &&
        support("kill") &&
        is_object($h = connect()) &&
        ($l == "" || $h->select_db($l))
    ) {
        $ie = $h->result(connection_id());
        echo '<script',
            nonce(),
            '>
var timeout = setTimeout(function () {
	ajax(\'',
            js_escape(ME),
            'script=kill\', function () {
	}, \'kill=',
            $ie,
            '&token=',
            $oi,
            '\');
}, ',
            1000 * $fi,
            ');
</script>
';
    } else {
        $h = null;
    }
    ob_flush();
    flush();
    $H = @get_key_vals($th ? $th : $F, $h, false);
    if ($h) {
        echo script("clearTimeout(timeout);");
        ob_flush();
        flush();
    }
    return $H;
}
function get_token()
{
    $yg = rand(1, 1e6);
    return ($yg ^ $_SESSION["token"]) . ":$yg";
}
function verify_token()
{
    list($oi, $yg) = explode(":", $_POST["token"]);
    return ($yg ^ $_SESSION["token"]) == $oi;
}
function lzw_decompress($Sa)
{
    $ac = 256;
    $Ta = 8;
    $mb = [];
    $Ng = 0;
    $Og = 0;
    for ($s = 0; $s < strlen($Sa); $s++) {
        $Ng = ($Ng << 8) + ord($Sa[$s]);
        $Og += 8;
        if ($Og >= $Ta) {
            $Og -= $Ta;
            $mb[] = $Ng >> $Og;
            $Ng &= (1 << $Og) - 1;
            $ac++;
            if ($ac >> $Ta) {
                $Ta++;
            }
        }
    }
    $Zb = range("\0", "\xFF");
    $H = "";
    foreach ($mb as $s => $lb) {
        $pc = $Zb[$lb];
        if (!isset($pc)) {
            $pc = $mj . $mj[0];
        }
        $H .= $pc;
        if ($s) {
            $Zb[] = $mj . $pc[0];
        }
        $mj = $pc;
    }
    return $H;
}
function on_help($sb, $qh = 0)
{
    return script(
        "mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $sb, $qh) }, onmouseout: helpMouseout});",
        ""
    );
}
function edit_form($a, $p, $I, $Ji)
{
    global $b, $x, $oi, $n;
    $Ph = $b->tableName(table_status1($a, true));
    page_header($Ji ? 'Edit' : 'Insert', $n, ["select" => [$a, $Ph]], $Ph);
    if ($I === false) {
        echo "<p class='error'>" . 'No rows.' . "\n";
    }
    echo '<form action="" method="post" enctype="multipart/form-data" id="form">
';
    if (!$p) {
        echo "<p class='error'>" .
            'You have no privileges to update this table.' .
            "\n";
    } else {
        echo "<table cellspacing='0' class='layout'>" .
            script("qsl('table').onkeydown = editingKeydown;");
        foreach ($p as $B => $o) {
            echo "<tr><th>" . $b->fieldName($o);
            $Tb = $_GET["set"][bracket_escape($B)];
            if ($Tb === null) {
                $Tb = $o["default"];
                if (
                    $o["type"] == "bit" &&
                    preg_match("~^b'([01]*)'\$~", $Tb, $Hg)
                ) {
                    $Tb = $Hg[1];
                }
            }
            $Y =
                $I !== null
                    ? ($I[$B] != "" &&
                    $x == "sql" &&
                    preg_match("~enum|set~", $o["type"])
                        ? (is_array($I[$B])
                            ? array_sum($I[$B])
                            : +$I[$B])
                        : $I[$B])
                    : (!$Ji && $o["auto_increment"]
                        ? ""
                        : (isset($_GET["select"])
                            ? false
                            : $Tb));
            if (!$_POST["save"] && is_string($Y)) {
                $Y = $b->editVal($Y, $o);
            }
            $r = $_POST["save"]
                ? (string) $_POST["function"][$B]
                : ($Ji && preg_match('~^CURRENT_TIMESTAMP~i', $o["on_update"])
                    ? "now"
                    : ($Y === false
                        ? null
                        : ($Y !== null
                            ? ''
                            : 'NULL')));
            if (
                preg_match("~time~", $o["type"]) &&
                preg_match('~^CURRENT_TIMESTAMP~i', $Y)
            ) {
                $Y = "";
                $r = "now";
            }
            input($o, $Y, $r);
            echo "\n";
        }
        if (!support("table")) {
            echo "<tr>" .
                "<th><input name='field_keys[]'>" .
                script("qsl('input').oninput = fieldChange;") .
                "<td class='function'>" .
                html_select(
                    "field_funs[]",
                    $b->editFunctions(["null" => isset($_GET["select"])])
                ) .
                "<td><input name='field_vals[]'>" .
                "\n";
        }
        echo "</table>\n";
    }
    echo "<p>\n";
    if ($p) {
        echo "<input type='submit' value='" . 'Save' . "'>\n";
        if (!isset($_GET["select"])) {
            echo "<input type='submit' name='insert' value='" .
            ($Ji ? 'Save and continue edit' : 'Save and insert next') .
            "' title='Ctrl+Shift+Enter'>\n",
                $Ji
                    ? script(
                        "qsl('input').onclick = function () { return !ajaxForm(this.form, '" .
                            'Saving' .
                            "…', this); };"
                    )
                    : "";
        }
    }
    echo $Ji
        ? "<input type='submit' name='delete' value='" .
            'Delete' .
            "'>" .
            confirm() .
            "\n"
        : ($_POST || !$p
            ? ""
            : script("focus(qsa('td', qs('#form'))[1].firstChild);"));
    if (isset($_GET["select"])) {
        hidden_fields([
            "check" => (array) $_POST["check"],
            "clone" => $_POST["clone"],
            "all" => $_POST["all"],
        ]);
    }
    echo '<input type="hidden" name="referer" value="',
        h(
            isset($_POST["referer"])
                ? $_POST["referer"]
                : $_SERVER["HTTP_REFERER"]
        ),
        '">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
}
if (isset($_GET["file"])) {
    if ($_SERVER["HTTP_IF_MODIFIED_SINCE"]) {
        header("HTTP/1.1 304 Not Modified");
        exit();
    }
    header(
        "Expires: " .
            gmdate("D, d M Y H:i:s", time() + 365 * 24 * 60 * 60) .
            " GMT"
    );
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: immutable");
    if ($_GET["file"] == "favicon.ico") {
        header("Content-Type: image/x-icon");
        echo lzw_decompress(
            "\0\0\0` \0�\0\n @\0�C��\"\0`E�Q����?�tvM'�Jd�d\\�b0\0�\"��fӈ��s5����A�XPaJ�0���8�#R�T��z`�#.��c�X��Ȁ?�-\0�Im?�.�M��\0ȯ(̉��/(%�\0"
        );
    } elseif ($_GET["file"] == "default.css") {
        header("Content-Type: text/css; charset=utf-8");
        echo lzw_decompress(
            "\n1̇�ٌ�l7��B1�4vb0��fs���n2B�ѱ٘�n:�#(�b.\rDc)��a7E����l�ñ��i1̎s���-4��f�	��i7�����t4���y�Zf4��i�AT�VV��f:Ϧ,:1�Qݼ�b2`�#�>:7G�1���s��L�XD*bv<܌#�e@�:4�!fo���t:<��咾�o��\ni���',�a_�:�i�Bv�|N�4.5Nf�i�vp�h��l��֚�O����= �OFQ��k\$��i����d2T�p��6�����-�Z�����6����h:�a�,����2�#8А�#��6n����J��h�t�����4O42��ok��*r���@p@�!������?�6��r[��L���:2B�j�!Hb��P�=!1V�\"��0��\nS���D7��Dڛ�C!�!��Gʌ� �+�=tC�.C��:+��=�������%�c�1MR/�EȒ4���2�䱠�`�8(�ӹ[W��=�yS�b�=�-ܹBS+ɯ�����@pL4Yd��q�����6�3Ĭ��Ac܌�Ψ�k�[&>���Z�pkm]�u-c:���Nt�δpҝ��8�=�#��[.��ޯ�~���m�y�PP�|I֛���Q�9v[�Q��\n��r�'g�+��T�2��V��z�4��8��(	�Ey*#j�2]��R����)��[N�R\$�<>:�>\$;�>��\r���H��T�\nw�N �wأ��<��Gw����\\Y�_�Rt^�>�\r}��S\rz�4=�\nL�%J��\",Z�8����i�0u�?�����s3#�ى�:���㽖��E]x���s^8��K^��*0��w����~���:��i���v2w����^7���7�c��u+U%�{P�*4̼�LX./!��1C��qx!H��Fd��L���Ġ�`6��5��f��Ć�=H�l �V1��\0a2�;��6����_ه�\0&�Z�S�d)KE'��n��[X��\0ZɊ�F[P�ޘ@��!��Y�,`�\"ڷ��0Ee9yF>��9b����F5:���\0}Ĵ��(\$����37H��� M�A��6R��{Mq�7G��C�C�m2�(�Ct>[�-t�/&C�]�etG�̬4@r>���<�Sq�/���Q�hm���������L��#��K�|���6fKP�\r%t��V=\"�SH\$�} ��)w�,W\0F��u@�b�9�\rr�2�#�D��X���yOI�>��n��Ǣ%���'��_��t\rτz�\\1�hl�]Q5Mp6k���qh�\$�H~�|��!*4����`S���S t�PP\\g��7�\n-�:袪p����l�B���7Өc�(wO0\\:��w���p4���{T��jO�6HÊ�r���q\n��%%�y']\$��a�Z�.fc�q*-�FW��k��z���j���lg�:�\$\"�N�\r#�d�Â���sc�̠��\"j�\r�����Ւ�Ph�1/��DA)���[�kn�p76�Y��R{�M�P���@\n-�a�6��[�zJH,�dl�B�h�o�����+�#Dr^�^��e��E��� ĜaP���JG�z��t�2�X�����V�����ȳ��B_%K=E��b弾�§kU(.!ܮ8����I.@�K�xn���:�P�32��m�H		C*�:v�T�\nR�����0u�����ҧ]�����P/�JQd�{L�޳:Y��2b��T ��3�4���c�V=���L4��r�!�B�Y�6��MeL������i�o�9< G��ƕЙMhm^�U�N����Tr5HiM�/�n�흳T��[-<__�3/Xr(<���������uҖGNX20�\r\$^��:'9�O��;�k����f��N'a����b�,�V��1��HI!%6@��\$�EGڜ�1�(mU��rս���`��iN+Ü�)���0l��f0��[U��V��-:I^��\$�s�b\re��ug�h�~9�߈�b�����f�+0�� hXrݬ�!\$�e,�w+����3��_�A�k��\nk�r�ʛcuWdY�\\�={.�č���g��p8�t\rRZ�v�J:�>��Y|+�@����C�t\r��jt��6��%�?��ǎ�>�/�����9F`ו��v~K�����R�W��z��lm�wL�9Y�*q�x�z��Se�ݛ����~�D�����x���ɟi7�2���Oݻ��_{��53��t���_��z�3�d)�C��\$?KӪP�%��T&��&\0P�NA�^�~���p� �Ϝ���\r\$�����b*+D6궦ψ��J\$(�ol��h&��KBS>���;z��x�oz>��o�Z�\nʋ[�v���Ȝ��2�OxِV�0f�����2Bl�bk�6Zk�hXcd�0*�KT�H=��π�p0�lV����\r���n�m��)(�(�:#����E��:C�C���\r�G\ré0��i����:`Z1Q\n:��\r\0���q���:`�-�M#}1;����q�#|�S���hl�D�\0fiDp�L��``����0y��1���\r�=�MQ\\��%oq��\0��1�21�1�� ���ќbi:��\r�/Ѣ� `)��0��@���I1�N�C�����O��Z��1���q1 ����,�\rdI�Ǧv�j�1 t�B���⁒0:�0��1�A2V���0���%�fi3!&Q�Rc%�q&w%��\r��V�#���Qw`�% ���m*r��y&i�+r{*��(rg(�#(2�(��)R@i�-�� ���1\"\0��R���.e.r��,�ry(2�C��b�!Bޏ3%ҵ,R�1��&��t��b�a\rL��-3�����\0��Bp�1�94�O'R�3*��=\$�[�^iI;/3i�5�&�}17�# ѹ8��\"�7��8�9*�23�!�!1\\\0�8��rk9�;S�23��ړ*�:q]5S<��#3�83�#e�=�>~9S螳�r�)��T*a�@і�bes���:-���*;,�ؙ3!i���LҲ�#1 �+n� �*��@�3i7�1���_�F�S;3�F�\rA��3�>�x:� \r�0��@�-�/��w��7��S�J3� �.F�\$O�B���%4�+t�'g�Lq\rJt�J��M2\r��7��T@���)ⓣd��2�P>ΰ��Fi಴�\nr\0��b�k(�D���KQ����1�\"2t����P�\r��,\$KCt�5��#��)��P#Pi.�U2�C�~�\"�"
        );
    } elseif ($_GET["file"] == "functions.js") {
        header("Content-Type: text/javascript; charset=utf-8");
        echo lzw_decompress(
            "f:��gCI��\n8��3)��7���81��x:\nOg#)��r7\n\"��`�|2�gSi�H)N�S��\r��\"0��@�)�`(\$s6O!��V/=��' T4�=��iS��6IO�G#�X�VC��s��Z1.�hp8,�[�H�~Cz���2�l�c3���s���I�b�4\n�F8T��I���U*fz��r0�E����y���f�Y.:��I��(�c��΋!�_l��^�^(��N{S��)r�q�Y��l٦3�3�\n�+G���y���i���xV3w�uh�^r����a۔���c��\r���(.��Ch�<\r)�ѣ�`�7���43'm5���\n�P�:2�P����q ���C�}ī�����38�B�0�hR��r(�0��b\\0�Hr44��B�!�p�\$�rZZ�2܉.Ƀ(\\�5�|\nC(�\"��P���.��N�RT�Γ��>�HN��8HP�\\�7Jp~���2%��OC�1�.��C8·H��*�j����S(�/��6KU����<2�pOI���`���ⳈdO�H��5�-��4��pX25-Ң�ۈ�z7��\"(�P�\\32:]U����߅!]�<�A�ۤ���iڰ�l\r�\0v��#J8��wm��ɤ�<�ɠ��%m;p#�`X�D���iZ��N0����9��占��`��wJ�D��2�9t��*��y��NiIh\\9����:����xﭵyl*�Ȉ��Y�����8�W��?���ޛ3���!\"6�n[��\r�*\$�Ƨ�nzx�9\r�|*3ףp�ﻶ�:(p\\;��mz���9����8N���j2����\r�H�H&��(�z��7i�k� ����c��e���t���2:SH�Ƞ�/)�x�@��t�ri9����8����yҷ���V�+^Wڦ��kZ�Y�l�ʣ���4��Ƌ������\\E�{�7\0�p���D��i�-T����0l�%=���˃9(�5�\n\n�n,4�\0�a}܃.��Rs\02B\\�b1�S�\0003,�XPHJsp�d�K� CA!�2*W����2\$�+�f^\n�1����zE� Iv�\\�2��.*A���E(d���b��܄��9����Dh�&��?�H�s�Q�2�x~nÁJ�T2�&��eR���G�Q��Tw�ݑ��P���\\�)6�����sh\\3�\0R	�'\r+*;R�H�.�!�[�'~�%t< �p�K#�!�l���Le����,���&�\$	��`��CX��ӆ0֭����:M�h	�ڜG��!&3�D�<!�23��?h�J�e ��h�\r�m���Ni�������N�Hl7��v��WI�.��-�5֧ey�\rEJ\ni*�\$@�RU0,\$U�E����ªu)@(t�SJk�p!�~���d`�>��\n�;#\rp9�jɹ�]&Nc(r���TQU��S��\08n`��y�b���L�O5��,��>���x���f䴒���+��\"�I�{kM�[\r%�[	�e�a�1! ����Ԯ�F@�b)R��72��0�\nW���L�ܜҮtd�+���0wgl�0n@��ɢ�i�M��\nA�M5n�\$E�ױN��l�����%�1 A������k�r�iFB���ol,muNx-�_�֤C( ��f�l\r1p[9x(i�BҖ��zQl��8C�	��XU Tb��I�`�p+V\0��;�Cb��X�+ϒ�s��]H��[�k�x�G*�]�awn�!�6�����mS���I��K�~/�ӥ7��eeN��S�/;d�A�>}l~��� �%^�f�آpڜDE��a��t\nx=�kЎ�*d���T����j2��j��\n��� ,�e=��M84���a�j@�T�s���nf��\n�6�\rd��0���Y�'%ԓ��~	�Ҩ�<���AH�G��8���΃\$z��{���u2*��a��>�(w�K.bP�{��o��´�z�#�2�8=�8>���A,�e���+�C�x�*���-b=m���,�a��lzk���\$W�,�m�Ji�ʧ���+���0�[��.R�sK���X��ZL��2�`�(�C�vZ������\$�׹,�D?H��NxX��)��M��\$�,��*\nѣ\$<q�şh!��S����xsA!�:�K��}�������R��A2k�X�p\n<�����l���3�����VV�}�g&Yݍ!�+�;<�Y��YE3r�َ��C�o5����ճ�kk�����ۣ��t��U���)�[����}��u��l�:D��+Ϗ _o��h140���0��b�K�㬒�����lG��#��������|Ud�IK���7�^��@��O\0H��Hi�6\r����\\cg\0���2�B�*e��\n��	�zr�!�nWz&� {H��'\$X �w@�8�DGr*���H�'p#�Į���\nd���,���,�;g~�\0�#����E��\r�I`��'��%E�.�]`�Л��%&��m��\r��%4S�v�#\n��fH\$%�-�#���qB�����Q-�c2���&���]�� �qh\r�l]�s���h�7�n#����-�jE�Fr�l&d����z�F6����\"���|���s@����z)0rpڏ\0�X\0���|DL<!��o�*�D�{.B<E���0nB(� �|\r\n�^���� h�!���r\$��(^�~����/p�q��B��O����,\\��#RR��%���d�Hj�`����̭ V� bS�d�i�E���oh�r<i/k\$-�\$o��+�ŋ��l��O�&evƒ�i�jMPA'u'���( M(h/+��WD�So�.n�.�n���(�(\"���h�&p��/�/1D̊�j娸E��&⦀�,'l\$/.,�d���W�bbO3�B�sH�:J`!�.���������,F��7(��Կ��1�l�s �Ҏ���Ţq�X\r����~R鰱`�Ҟ�Y*�:R��rJ��%L�+n�\"��\r��͇H!qb�2�Li�%����Wj#9��ObE.I:�6�7\0�6+�%�.����a7E8VS�?(DG�ӳB�%;���/<�����\r ��>�QV�t/8�c8�\$\0����RV�I8�RW���\n��v��yC��-�5F��iQ0��_�IE�sIR!���Xk��z@��`���D�`DV!C�8��\r���b�3�!3�@�33N}�ZB�3F.H}�30��M(�>��}�\\�t�f�f���I\r���337 X�\"td�,\nbtNO`P�;�ܕҭ���\$\n����Zѭ5U5WU�^ho���t�PM/5K4Ej�KQ&53GX�Xx)�<5D�^���V�\n�r�5b܀\\J\">��1S\r[-��Du�\r���)00�Y��ˢ�k{\n��#��\r�^��|�uܻU�_n�U4�U�~Yt�\rI��@䏳�R �3:�uePMS�0T�wW�X���D��KF5����;U�\n�OY��Y�Q,M[\0�_�D���W��J*�\rg(]�\r\"ZC��6u�+�Y��Y6ô�0�q�(��8}��3AX3T�h9j�j�fcMt�PJbqMP5>������Y�k%&\\�1d��E4� �Yn���\$<�U]Ӊ1�mbֶ�^�����\"NV��p��p��eM���W�ܢ�\\�)\n �\nf7\n�2�cr8��=K7tV����7P��L��a6��v@'�6i��j&>��;��`��a	\0pڨ(�J��)�\\��n��Ĭm\0��2��eqJ��P��h��fj��\"[\0����X,<\\������+md��~�����s%o��mn�),ׄ�ԇ�\r4��8\r����mE�H]�����HW�M0D�߀��~�ˁ�K��E}����|f�^���\r>�-z]2s�xD�d[s�t�S��\0Qf-K`���t���wT�9��Z��	�\nB�9 Nb��<�B�I5o�oJ�p��Jd��\r�hލ��2�\"�yG��C��s�ӕ�V���%zr+z���\\�����m ��T ���@Y2lQ<2O+�%��.Ӄh�,A���Z��2R��1��/�hH\r�X��aNB&� �M@�[x��ʮ���8&L�V͜v�*�j�ۚH��\\٪	���&s�\0Q�`\\\"�b��	��\rBs��w�B	��ݞN`�7�Co(ٿ�\nè��h1���*E���S��U�0U�t�#|�4�'{���� #�5	 �	p��yB�@R���p�@|��7\r�\0�_B�^z<B�@W4&K�s��xO׷�P�@X�]�����w>�Ze{��LY��Lڐ�\\�(*R`�	�\n������QC�(*���c�;�l�p�X|`N���\$�[���@�U������Z�`Zd\"\\\"����)�I�:�t��oD�\0[�(���-���'��	���`hu%��,����I�7ī���m�V�}��N�ͳ\$�E��Yf&1����]]pz�U�x\r�}��;w�UX�\\��a^ �U�0SZOD�RK��&�Z\\Oq}ƾw���g��I��V���	5�k���?�={������*�k�@[u�h�v�m��a;]���&��\"��/\$\0C�قdSg�k��{�\0�\n`�	���C ���a�r\r��2G׌��O{��[����C��FKZ�j��FY�B�pFk��0<���D<JE�Zb^�.�2��8�U@*�5fk��FD���4��DU76�4Q�@��K+���J�����@�=��WIF\$�85M��N�\$R�\0�5�\r��_���E���I�ϳN�l���y\\��qU��Q���\n@�����cp���P۱+7ԽN\r�R{*�qm�F	M}I8�`W\0�8��T\r�*NpT�b�d<�ˤ�8�F��_�+ܻT�eN#]�d;�,���~�U|0VRe�����֎Y|,d Y�<Ͳ]���ᷗɔ=��mś�,\r�j\r5�p�du ���fp�+�J����X^��\n��)�>-�h�����<�6��b�dmh��@q���Ah�),J��W��cm�em]��\\�)1Zb0�����Y�]ym��f�e��;���O��W�apDW�����zE���\"�\$��=k���!8�怂g@�-Q��/e&�Ƈ�v_�xn\r�e3{U�4���n{�:B����sm��Y d���7}3?*�t����lT�}�~�����=c�����ǹ�{�8S�A\$�}�Q\"���;TW�98��{IDq������ǘ�O�[�&�]�؁��s����-��\r6��qq� h�e5�\0Ң���*�b�IS����ή9y�p�-��`{��ɖkP�0T<��Z9�0<ՙͩ�;[��g��\nK�\n�\0��*�\nb7(�_�@,�E2\r�]�K�*\0��p C\\Ѣ,0�^�MЧ����@�;X\r��?\$\r�j�*�O��B��P��1�hLK����3�/��a@|���w�(p��0����uo	T/b���B��dk�L8�Db�D��`����*3؅N����M	w�k�z����̫q�!�n�����~����ʴ��Eͦ�}Q�m\0��4@;��&�@�\"B���	P� m5p����)Ƅ�@2�M��;�\r��b��05	��\0[�N9�hY�່�t1e�A�o`�X���g�Ub5�X�6����hUp��0&*��E�:�qt%>���Ya�����hb�b ���L��8U�rC��[V�I�9Dд{����]�!�a��=T��&B5��\0~y��U�+��\"��h�H�Tb\".\r�̠<)�o��F�m��jb!ڇDE�%� I�ڢ�DAm2ki�!���\"��N�w�T�ǀ�u��*h�1UdV��D#)����`�x\\CM=r)�� ��80���cSD��ޕW���)\\-�b!�7����G_��Z��2yȅq�)�}(\$��Ët\0�'�ȴpZ,a�˘�8�E��ї���4�#���~Rϐ��t��=�ap~ŀ<wU��Q+��l��R��{ќV�	ոo%��a.Y�c}\n�3'Z|`��6�4HUep�H1���d��\\\\����do\\�i��a���5���u��8�A�;����P�\"ǖ.玼~4�����>��۞��%����VG'z��A!%\\=AGM�p}C��?/X���J���TR(ƹ����`��#Z6�t�iua��u��t���p�������O1��#pTa#�<.�+�� �\\I{��`M\nk% �IP|GʒPA��;W��Š�5B9%.@I#�P�:E��\$�+E���,:�|U���k���e0��2L�9)�`T+\$�l��U\"+��\0�\$\n�_�ђ�(��4DR���'�1\"h6�%<*/�\\�\"��=y��F}l���#70��E�m����A(�T�G]@�Ѯ.IK�W���ѥxD�.�V.�D\\��*{��AAeԌf��3��U؜@Uw.�5�ZĆS�*<BA�#�\0O.����]����Npi��U)�s(�쒰�a��ag�%���Ă�yx#��[��eX�4� ,�Ho�8N�I��	�%y-�p��T����dw��[�^gxfb�(U��~��\0P��+Ã'h�Ak�π��ٟ��.\"2@�f�����O�>tѣ\"����i\0j3�X��w!/��^��bq��� (5*�\0Z��9�\\�\rJ@ZAQE͑{��x�L/��| # 	�D���*kr���QE�`.\0_�qd�B(�.4�%S�l��*�Ne(\n��'4��`@mx��:�����S���4���N4�s��'=6 �����8��Y;�̆s�Pn'��9͌s,�&y!�>\0[�S(N��11\n�VfΠ���B���ƕ�%�~E�3���H4��(B�\"����� s3m�'p�<��� ����LԱp���E�B��5 ����2Yѧ&�������\"(�r�G�Xxɩ��R�O0�Jn�a�1`�呜g�n�@(	��y%��K�c<ɕ��6������dH�;�c.�ޡ�Kx��^=�+�\0�3�&��D�\rʉC��;)�\\b���E���*Q��D�����ݖ�t��{\\��p3�T��E\0)	%b��*쭤2�h{�X�����P�K�H(��Q\n�e�!��F�ɓe�aC�B�.�%�	ܡ�C�Jp���\$���M�Z2|� )�N�Z\\Z_��)�T �y\"���q+�Yzxb�EU�e\"�LZc��c/=aa��L�0��k�(��G5���t�[�])ƍ��8���62/�<�aM��.��֌y�,���Y�k\nPC.��vJ6�2��N�fS���]82��5�;��\0��	\"*&/�eS��T�(�-N�aCL1t#\"�#�4Ƣ�1�^�6D��`��ȑ�+����YFh�0�FI�\$��\\�P��u0nmY�4b�#��\"�p�#�&R8�줁�2(U\0��%�Si�qe3�kB����j�gI�U��U���3u� NBb�a41�v�@dh�aa�LKx�ռ���)�	�P(��-u��JGX�\nK�/������\\�i���\0^�\$�,�|�Z��(Rv*��EbE{Z��H�e�\n���P�ɠ���uNXb`XTU06��a�XP=Q*ΐ�dt*z+H@����Iv�Z���g�q�I^R�\0��A\n *�!�8|\$pr���!WF�����OB�+�Vi��u�'�KYz(��)�ed�3\\��Ր�	�\nz&�^bߋJ^V%t+�Ti[Q4&����t�\\��6�i�\r�s*����H��&[W'�ZŖ'���+Bx[	,¹�زŦ��q��8�~3�ځ��@'	�i�f���.J�ʈT���X1-����&3��6������f@|O`b�UeD\0�:���p�SjMD�Qt\n����g����a�y\$s��`\"��5����56V���| `&�����又7��:�r5:���/'m�Piw	A\rP��G�X#H���Y\n����&R�t{�f���m@8�x��c�m��FD3�\"����]�u�)la�Z�:#�Y�KKhW�^Lݵ��m�����p�6}���i[���W���m�ۋtZ�M���e�(oe�rp�[PY������_����oR�1�\"R)���\$H�;�\0�����%Y#��-Ihx�*ɔQR�^Z��.Y�W��*��LZ�]jU��V��\\;4z#�v��:R��)�*:��ǟ���iXbs.hqZT��\"��I�h��\0�;���@Zx���I���N'�Ӎ~���\r���BB���Òh���YG��F4)��i%P����xx\n+��2�5ݬ�h���'�݂,��^^9̠-��l�۷n��mQ�i�\0��B�8�n�:T1��1RĢ�Y����9�=�p�s-�^�f%�q't8�(����@�o��Z1�h��P�?���+g_U�q	��^~�@n��ξ ��P&�g��C9|�9_���c�U����5_���?�E�!�'�T]�����Y��\rE�pNJROӀ���\nS�ܜ��l�e�B8�� \n}6���|�� �9 N����Q׽�ǸI5yQ�D��ʉ���uj*?m\\M�޲`��d��U(\$��N~UY#}�n�@h:�H��\rZ'�@j���4�2I�����֡�� 0h@\\Ե�\0�8P3�B.�0�a���JLh\r?K\\�NxQ0��#ՅH��t���c��?�,���t0�;up��0d7� ��ʰ<a�i�2�s�9�b��Ox���\0P�2��@,�U�\0�[V���h|BQ X�5Ҙ_���1Ar8����r �}��N���Db�&���\"a|?�0?���Oq[�8�^K���Q�6�[�v��ѕ۾���ư�n�	�4S-R8��e��y�1���Go�\r�d������IP�6�mͳ���͆������)G�AK*�x��U���Rma�%ƣHsE����9L}�s��`6@Q��g#a����F@B'<r��˓[��E\$i#�\"Ś,�7i����� �t��R 9���k�P�s���)÷ʺ���t����*`gʮ6�L�w���L��^i��PY%�%v�a�ԙ � 2�^����ch��,�!w^��M3WE�����=����Zb\$���~V�Xk����\0[`���I����bc0Mk��C���F9��h�J�ӗ�����(K�X�Ў�ŷ�auQ��qw��=��Y���8�s����|\r�ވ1�ļ\"N�uL�s2��ͤ0x����T`��B�v��2��9D����1�U�`ɕ/�1:,&�Ǚ��	8���\$��ojU��9�\n�џ��`6��#7A͐X-w�|��F�!ضI��u����f�����7���\0?9 O�� �ͥ�*�J5�������!طk����rN�z|~�3�v��~ץ�c�n�h<&m`P4M%�'G���f�f0�ӗH��>��,-���Z;��\0�Ŧ.#]����志h���]�BhPÉ�*��̵F\r���AHf�A���B���<�e��G3Vƛ\"����~7�y�p��OS�f�A9��{u\n�M��Z��I5X�P4Lzm�#m�`h\"��\n����4ǜ�J��\n9J=1�z�M��-A�-`\"�XR�rG�dMXc��(՘Bٜ+[��)�\n��|�p��w����Ckt�\n�|~\0z姯>���X)	�v������5�ְ�[�.���)I?���r[��|�X3!>\r�P�5�	���\ro���ɽ�u�X))܋n^\n���W��n��c��Wc���M�ӵ yo��.���q5JsKVWV�H#�λv��+�P�&�r�~G�\r�px(��9<���<&A2Y�9���s-���&��G��T� \"���yd�Ye��p�5|�=��\$��Ne���W0;���MOHɍ&39�\$�@�an.|+bfx��1C�i����H������ڏ���R��Km8P.���%�Z\0^  �9��|�CXlH��Ğ��z\\�24n+��ظ����ܹ��F]�����F��ո�\0�w�5)��f��cy{�0�P4���5��zaƼ��)_�QY3�&��nݛ�,���K��_�Y�W0Y��.s�-i=��e�,u@|Uvt!#��δ����^���&���dSր�0�8ݤ�g.�oG@\\(�c�t\r�XG�֕̃��TڍF�em��:�D��֍9)`EYk�Mk��\$ȊONӂJ��e�7�8y�M�n�Z*|�r�	D�ZB[ҡ@T!�\0�00�L���|,���w߾f\\&��e�mj��&/	ً����B�ե|rI��bx�QD��wJ��|�����M�`ߋ-5t�4�X�w�W��O�Ž��u��_>	x�+^2�5#��-�����'����f�ȩ奥-b�KjQ;�&>�3�ⲻ'jtYq�ާ�+Jv\"j�t~_◎�E�BORԾ�0�)�p�29IB����e�\"I;۩X�\$,p0��_K���\$ċ�v��,?1��Ջ<LD;rJ;��lg.��~;�UW���v��ό0P+g0��r+IAA*�\0|��S�o �\\�S�5�u��'(����|������W��5;\$5\0��{��;d�i�t�đ� ��:�)�Ⱥ)�.�;��j%\r���F�=��D��]H���\0�	 N @�!��+|�d!.�H|�M���COU�wI�R�|��H�R�T�@�%<��n���n7r���]�c#;��\"f�A�9�ʾd��',��'U��K�r^���_:Ry�O~m!ۥ�j>�S��\"[�q��ܽ뜋�\\�8Ms\0��7��_�U̎V�f6�K�D���s4S��P_=\"A��,&G�=���X�9I�`o#IF��SA���A�;4kY�N@��<�@gu|It\r��.�R9�:���y�K� �����y�*E�`r�Y����	�\${�6�\0����hL�3�����\" _\$U��_�(�G�C0�(Օ��1F��Mz����{�Q!\r��N�xCsa�5Ш�Oz	M���G�`Q�4�����II�Ja�6盀�T`(�M��J\\Wǂ���Eju�8��B���Q[�?��_%+�O�+|�����w(e������\\U���ރU�Z�4�\n�P @�P<Ț4�C����.K!���M#oSY3�L���B�\$��0{�H�t��)Jp�\$\rJ˝y\"��;���@,�_�Z��\$�������`�T���c�S�%�C�(+oO��@�\0^kX��@|��͇�U@���(h�B�>��Vn�\$�H���2�(�A�L�ma�hÆ�I����Ki�:�'��E��V�C�EE5�aF���b��H�dA|���\"�ǊB�,��X�JvN��yJ����@���ld��W���+&w�]\0��od� ��K�y�.ȈH̉��UCpLa��/�\rK����t�����8c�i��o��S�τ���`=��E\0;�|'�llcTHU�?Ps�=����b������8	\r־fߝ������~�K���[�>�8MlF𝚏�����х񿴀���<�����^��k�@׸���/u�� .�g�+��`�%�l�2\n�[v��iS���]}I�A�z�*���~%�_c|���-Q7�:ҳ��ɪ_;�b��g}�1?p>W܀�����`=�ؔ5i���~��?{��~���[|�E�_����UN�]?7pt?22?��Tr������T���]?f������,w�����Ѻ�2�y�:P.T�1G����*��hb����?��Q ���� �?�W�r�\0�b�`*��:=v hv\0��������%L:V(�P�8wD�1賐a\0�o���p4�D&���@�a�5��m�P��Z�ڒE���]wI^)Q��w�(#-��u#��Z��*0�L���d��G�5T�@p/����b�:�\"|01\0��`���ڐb�:P�����'!��Ą\r�\0fx���4\0�ߑ����H[,p<��MU��T�/a\rLC�bE��\\�A�BV��޻MF/����v�\n<�MB&DO���f���,:M\rU4��MxF}`҉�#0�}�����B�o0��&� N፩p�:�~�Ǎ\r�M�|�N�R�\n\"	#'@�b��� Pq�ǽJ\\�<�:h!pG���dd\n�@jm整��p�1��PX��`#/|���ﺾ��\"�nc�D]���8�r6�{5�~�\r\0A��De�q\\o�B!�[���0BD����3�T��/0B�r����I��P��;��e��P�M��á���#��p�Z?��`pW����\0`�\0"
        );
    } elseif ($_GET["file"] == "jush.js") {
        header("Content-Type: text/javascript; charset=utf-8");
        echo lzw_decompress(
            "v0��F����==��FS	��_6MƳ���r:�E�CI��o:�C��Xc��\r�؄J(:=�E���a28�x�?�'�i�SANN���xs�NB��Vl0���S	��Ul�(D|҄��P��>�E�㩶yHch��-3Eb�� �b��pE�p�9.����~\n�?Kb�iw|�`��d.�x8EN��!��2��3���\r���Y���y6GFmY�8o7\n\r�0��\0�Dbc�!�Q7Шd8���~��N)�Eг`�Ns��`�S)�O���/�<�x�9�o�����3n��2�!r�:;�+�9�CȨ���\n<�`��b�\\�?�`�4\r#`�<�Be�B#�N ��\r.D`��j�4���p�ar��㢺�>�8�\$�c��1�c���c����{n7����A�N�RLi\r1���!�(�j´�+��62�X�8+����.\r����!x���h�'��6S�\0R����O�\n��1(W0���7q��:N�E:68n+��մ5_(�s�\r��/m�6P�@�EQ���9\n�V-���\"�.:�J��8we�q�|؇�X�]��Y X�e�zW�� �7��Z1��hQf��u�j�4Z{p\\AU�J<��k��@�ɍ��@�}&���L7U�wuYh��2��@�u� P�7�A�h����3Û��XEͅZ�]�l�@Mplv�)� ��HW���y>�Y�-�Y��/�������hC�[*��F�#~�!�`�\r#0P�C˝�f������\\���^�%B<�\\�f�ޱ�����&/�O��L\\jF��jZ�1�\\:ƴ>�N��XaF�A�������f�h{\"s\n�64������?�8�^p�\"띰�ȸ\\�e(�P�N��q[g��r�&�}Ph���W��*��r_s�P�h���\n���om������#���.�\0@�pdW �\$Һ�Q۽Tl0� ��HdH�)��ۏ��)P���H�g��U����B�e\r�t:��\0)\"�t�,�����[�(D�O\nR8!�Ƭ֚��lA�V��4�h��Sq<��@}���gK�]���]�=90��'����wA<����a�~��W��D|A���2�X�U2��yŊ��=�p)�\0P	�s��n�3�r�f\0�F���v��G��I@�%���+��_I`����\r.��N���KI�[�ʖSJ���aUf�Sz���M��%��\"Q|9��Bc�a�q\0�8�#�<a��:z1Uf��>�Z�l������e5#U@iUG��n�%Ұs���;gxL�pP�?B��Q�\\�b��龒Q�=7�:��ݡQ�\r:�t�:y(� �\n�d)���\n�X;����CaA�\r���P�GH�!���@�9\n\nAl~H���V\ns��ի�Ư�bBr���������3�\r�P�%�ф\r}b/�Α\$�5�P�C�\"w�B_��U�gAt��夅�^Q��U���j����Bvh졄4�)��+�)<�j^�<L��4U*���Bg�����*n�ʖ�-����	9O\$��طzyM�3�\\9���.o�����E(i������7	tߚ�-&�\nj!\r��y�y�D1g���]��yR�7\"������~����)TZ0E9M�YZtXe!�f�@�{Ȭyl	8�;���R{��8�Į�e�+UL�'�F�1���8PE5-	�_!�7��[2�J��;�HR��ǹ�8p痲݇@��0,ծpsK0\r�4��\$sJ���4�DZ��I��'\$cL�R��MpY&����i�z3G�zҚJ%��P�-��[�/x�T�{p��z�C�v���:�V'�\\��KJa��M�&���Ӿ\"�e�o^Q+h^��iT��1�OR�l�,5[ݘ\$��)��jLƁU`�S�`Z^�|��r�=��n登��TU	1Hyk��t+\0v�D�\r	<��ƙ��jG���t�*3%k�YܲT*�|\"C��lhE�(�\r�8r��{��0����D�_��.6и�;����rBj�O'ۜ���>\$��`^6��9�#����4X��mh8:��c��0��;�/ԉ����;�\\'(��t�'+�����̷�^�]��N�v��#�,�v���O�i�ϖ�>��<S�A\\�\\��!�3*tl`�u�\0p'�7�P�9�bs�{�v�{��7�\"{��r�a�(�^��E����g��/���U�9g���/��`�\nL\n�)���(A�a�\" ���	�&�P��@O\n師0�(M&�FJ'�! �0�<�H�������*�|��*�OZ�m*n/b�/�������.��o\0��dn�)����i�:R���P2�m�\0/v�OX���Fʳψ���\"�����0�0�����0b��gj��\$�n�0}�	�@�=MƂ0n�P�/p�ot������.�̽�g\0�)o�\n0���\rF����b�i��o}\n�̯�	NQ�'�x�Fa�J���L������\r��\r����0��'��d	oep��4D��ʐ�q(~�� �\r�E��pr�QVFH�l��Kj���N&�j!�H`�_bh\r1���n!�Ɏ�z�����\\��\r���`V_k��\"\\ׂ'V��\0ʾ`AC������V�`\r%�����\r����k@N����B�횙� �!�\n�\0Z�6�\$d��,%�%la�H�\n�#�S\$!\$@��2���I\$r�{!��J�2H�ZM\\��hb,�'||cj~g�r�`�ļ�\$���+�A1�E���� <�L��\$�Y%-FD��d�L焳��\n@�bVf�;2_(��L�п��<%@ڜ,\"�d��N�er�\0�`��Z��4�'ld9-�#`��Ŗ����j6�ƣ�v���N�͐f��@܆�&�B\$�(�Z&���278I ��P\rk\\���2`�\rdLb@E��2`P( B'�����0�&��{���:��dB�1�^؉*\r\0c<K�|�5sZ�`���O3�5=@�5�C>@�W*	=\0N<g�6s67Sm7u?	{<&L�.3~D��\rŚ�x��),r�in�/��O\0o{0k�]3>m��1\0�I@�9T34+ԙ@e�GFMC�\rE3�Etm!�#1�D @�H(��n ��<g,V`R]@����3Cr7s~�GI�i@\0v��5\rV�'������P��\r�\$<b�%(�Dd��PW����b�fO �x\0�} ��lb�&�vj4�LS��ִԶ5&dsF M�4��\".H�M0�1uL�\"��/J`�{�����xǐYu*\"U.I53Q�3Q��J��g��5�s���&jь��u�٭ЪGQMTmGB�tl-c�*��\r��Z7���*hs/RUV����B�Nˈ�����Ԋ�i�Lk�.���t�龩�rYi���-S��3�\\�T�OM^�G>�ZQj���\"���i��MsS�S\$Ib	f���u����:�SB|i��Y¦��8	v�#�D�4`��.��^�H�M�_ռ�u��U�z`Z�J	e��@Ce��a�\"m�b�6ԯJR���T�?ԣXMZ��І��p����Qv�j�jV�{���C�\r��7�Tʞ� ��5{P��]�\r�?Q�AA������2񾠓V)Ji��-N99f�l Jm��;u�@�<F�Ѡ�e�j��Ħ�I�<+CW@�����Z�l�1�<2�iF�7`KG�~L&+N��YtWH飑w	����l��s'g��q+L�zbiz���Ţ�.Њ�zW�� �zd�W����(�y)v�E4,\0�\"d��\$B�{��!)1U�5bp#�}m=��@�w�	P\0�\r�����`O|���	�ɍ����Y��JՂ�E��Ou�_�\n`F`�}M�.#1��f�*�ա��  �z�uc���� xf�8kZR�s2ʂ-���Z2�+�ʷ�(�sU�cD�ѷ���X!��u�&-vP�ر\0'L�X �L����o	��>�Վ�\r@�P�\rxF��E��ȭ�%����=5N֜��?�7�N�Å�w�`�hX�98 �����q��z��d%6̂t�/������L��l��,�Ka�N~�����,�'�ǀM\rf9�w��!x��x[�ϑ�G�8;�xA��-I�&5\$�D\$���%��xѬ���´���]����&o�-3�9�L��z���y6�;u�zZ ��8�_�ɐx\0D?�X7����y�OY.#3�8��ǀ�e�Q�=؀*��G�wm ���Y�����]YOY�F���)�z#\$e��)�/�z?�z;����^��F�Zg�����������`^�e����#�������?��e��M��3u�偃0�>�\"?��@חXv�\"������*Ԣ\r6v~��OV~�&ר�^g���đٞ�'��f6:-Z~��O6;zx��;&!�+{9M�ٳd� \r,9����W��ݭ:�\r�ٜ��@睂+��]��-�[g��ۇ[s�[i��i�q��y��x�+�|7�{7�|w�}����E��W��Wk�|J؁��xm��q xwyj���#��e��(�������ߞþ��� {��ڏ�y���M���@��ɂ��Y�(g͚-����������J(���@�;�y�#S���Y��p@�%�s��o�9;�������+��	�;����ZNٯº��� k�V��u�[�x��|q��ON?���	�`u��6�|�|X����س|O�x!�:���ϗY]�����c���\r�h�9n�������8'������\rS.1��USȸ��X��+��z]ɵ��?����C�\r��\\����\$�`��)U�|ˤ|Ѩx'՜����<�̙e�|�ͳ����L���M�y�(ۧ�l�к�O]{Ѿ�FD���}�yu��Ē�,XL\\�x��;U��Wt�v��\\OxWJ9Ȓ�R5�WiMi[�K��f(\0�dĚ�迩�\r�M����7�;��������6�KʦI�\r���xv\r�V3���ɱ.��R������|��^2�^0߾\$�Q��[�D��ܣ�>1'^X~t�1\"6L���+��A��e�����I��~����@����pM>�m<��SK��-H���T76�SMfg�=��GPʰ�P�\r��>�����2Sb\$�C[���(�)��%Q#G`u��Gwp\rk�Ke�zhj��zi(��rO�������T=�7���~�4\"ef�~�d���V�Z���U�-�b'V�J�Z7���)T��8.<�RM�\$�����'�by�\n5����_��w����U�`ei޿J�b�g�u�S��?��`���+��� M�g�7`���\0�_�-���_��?�F�\0����X���[��J�8&~D#��{P���4ܗ��\"�\0��������@ғ��\0F ?*��^��w�О:���u��3xK�^�w���߯�y[Ԟ(���#�/zr_�g��?�\0?�1wMR&M���?�St�T]ݴG�:I����)��B�� v����1�<�t��6�:�W{���x:=��ޚ��:�!!\0x�����q&��0}z\"]��o�z���j�w�����6��J�P۞[\\ }��`S�\0�qHM�/7B��P���]FT��8S5�/I�\r�\n ��O�0aQ\n�>�2�j�;=ڬ�dA=�p�VL)X�\n¦`e\$�TƦQJ����lJ����y�I�	�:����B�bP���Z��n����U;>_�\n	�����`��uM򌂂�֍m����Lw�B\0\\b8�M��[z��&�1�\0�	�\r�T������+\\�3�Plb4-)%Wd#\n��r��MX\"ϡ�(Ei11(b`@f����S���j�D��bf�}�r����D�R1���b��A��Iy\"�Wv��gC�I�J8z\"P\\i�\\m~ZR��v�1ZB5I��i@x����-�uM\njK�U�h\$o��JϤ!�L\"#p7\0� P�\0�D�\$	�GK4e��\$�\nG�?�3�EAJF4�Ip\0��F�4��<f@� %q�<k�w��	�LOp\0�x��(	�G>�@�����9\0T����GB7�-�����G:<Q��#���Ǵ�1�&tz��0*J=�'�J>���8q��Х���	�O��X�F��Q�,����\"9��p�*�66A'�,y��IF�R��T���\"��H�R�!�j#kyF���e��z�����G\0�p��aJ`C�i�@�T�|\n�Ix�K\"��*��Tk\$c��ƔaAh��!�\"�E\0O�d�Sx�\0T	�\0���!F�\n�U�|�#S&		IvL\"����\$h���EA�N\$�%%�/\nP�1���{��) <���L���-R1��6���<�@O*\0J@q��Ԫ#�@ǵ0\$t�|�]�`��ĊA]���Pᑀ�C�p\\pҤ\0���7���@9�b�m�r�o�C+�]�Jr�f��\r�)d�����^h�I\\�. g��>���8���'�H�f�rJ�[r�o���.�v���#�#yR�+�y��^����F\0᱁�]!ɕ�ޔ++�_�,�\0<@�M-�2W���R,c���e2�*@\0�P ��c�a0�\\P���O���`I_2Qs\$�w��=:�z\0)�`�h�������\nJ@@ʫ�\0�� 6qT��4J%�N-�m����.ɋ%*cn��N�6\"\r͑�����f�A���p�MۀI7\0�M�>lO�4�S	7�c���\"�ߧ\0�6�ps�����y.��	���RK��PAo1F�tI�b*��<���@�7�˂p,�0N��:��N�m�,�xO%�!��v����gz(�M���I��	��~y���h\0U:��OZyA8�<2����us�~l���E�O�0��0]'�>��ɍ�:���;�/��w�����'~3GΖ~ӭ����c.	���vT\0c�t'�;P�\$�\$����-�s��e|�!�@d�Obw��c��'�@`P\"x����0O�5�/|�U{:b�R\"�0�шk���`BD�\nk�P��c��4�^ p6S`��\$�f;�7�?ls��߆gD�'4Xja	A��E%�	86b�:qr\r�]C8�c�F\n'ьf_9�%(��*�~��iS����@(85�T��[��Jڍ4�I�l=��Q�\$d��h�@D	-��!�_]��H�Ɗ�k6:���\\M-����\r�FJ>\n.��q�eG�5QZ����' ɢ���ہ0��zP��#������r���t����ˎ��<Q��T��3�D\\����pOE�%)77�Wt�[��@����\$F)�5qG0�-�W�v�`�*)Rr��=9qE*K\$g	��A!�PjBT:�K���!��H� R0?�6�yA)B@:Q�8B+J�5U]`�Ҭ��:���*%Ip9�̀�`KcQ�Q.B��Ltb��yJ�E�T��7���Am�䢕Ku:��Sji� 5.q%LiF��Tr��i��K�Ҩz�55T%U��U�IՂ���Y\"\nS�m���x��Ch�NZ�UZ���( B��\$Y�V��u@蔻����|	�\$\0�\0�oZw2Ҁx2���k\$�*I6I�n�����I,��QU4�\n��).�Q���aI�]����L�h\"�f���>�:Z�>L�`n�ض��7�VLZu��e��X����B���B�����Z`;���J�]�����S8��f \nڶ�#\$�jM(��ޡ����a�G���+A�!�xL/\0)	C�\n�W@�4�����۩� ��RZ����=���8�`�8~�h��P ��\r�	���D-FyX�+�f�QSj+X�|��9-��s�x�����+�V�cbp쿔o6H�q�����@.��l�8g�YM��WMP��U��YL�3Pa�H2�9��:�a�`��d\0�&�Y��Y0٘��S�-��%;/�T�BS�P�%f������@�F��(�֍*�q +[�Z:�QY\0޴�JUY֓/���pkzȈ�,�𪇃j�ꀥW�״e�J�F��VBI�\r��pF�Nقֶ�*ը�3k�0�D�{����`q��ҲBq�e�D�c���V�E���n����FG�E�>j�����0g�a|�Sh�7u�݄�\$���;a��7&��R[WX���(q�#���P���ז�c8!�H���VX�Ď�j��Z������Q,DUaQ�X0��ը���Gb��l�B�t9-oZ���L���­�pˇ�x6&��My��sҐ����\"�̀�R�IWU`c���}l<|�~�w\"��vI%r+��R�\n\\����][��6�&���ȭ�a�Ӻ��j�(ړ�Tѓ��C'��� '%de,�\n�FC�эe9C�N�Ѝ�-6�Ueȵ��CX��V������+�R+�����3B��ڌJ�虜��T2�]�\0P�a�t29��(i�#�aƮ1\"S�:�����oF)k�f���Ъ\0�ӿ��,��w�J@��V򄎵�q.e}KmZ����XnZ{G-���ZQ���}��׶�6ɸ���_�؁Չ�\n�@7�` �C\0]_ ��ʵ����}�G�WW: fCYk+��b۶���2S,	ڋ�9�\0﯁+�W�Z!�e��2�������k.Oc��(v̮8�DeG`ۇ�L���,�d�\"C���B-�İ(����p���p�=����!�k������}(���B�kr�_R�ܼ0�8a%ۘL	\0���b������@�\"��r,�0T�rV>����Q��\"�r��P�&3b�P��-�x���uW~�\"�*舞�N�h�%7���K�Y��^A����C����p����\0�..`c��+ϊ�GJ���H���E����l@|I#Ac��D��|+<[c2�+*WS<�r��g���}��>i�݀�!`f8�(c����Q�=f�\n�2�c�h4�+q���8\na�R�B�|�R����m��\\q��gX����ώ0�X�`n�F���O p��H�C��jd�f��EuDV��bJɦ��:��\\�!mɱ?,TIa���aT.L�]�,J��?�?��FMct!a٧R�F�G�!�A���rr�-p�X��\r��C^�7���&�R�\0��f�*�A\n�՛H��y�Y=���l�<��A�_��	+��tA�\0B�<Ay�(fy�1�c�O;p���ᦝ`�4СM��*��f�� 5fvy {?���:y��^c��u�'���8\0��ӱ?��g��� 8B��&p9�O\"z���rs�0��B�!u�3�f{�\0�:�\n@\0����p���6�v.;�����b�ƫ:J>˂��-�B�hkR`-����aw�xEj����r�8�\0\\����\\�Uhm� �(m�H3̴��S����q\0��NVh�Hy�	��5�M͎e\\g�\n�IP:Sj�ۡٶ�<���x�&�L��;nfͶc�q��\$f�&l���i�����0%yΞ�t�/��gU̳�d�\0e:��h�Z	�^�@��1��m#�N��w@��O��zG�\$�m6�6}��ҋ�X'�I�i\\Q�Y���4k-.�:yz���H��]��x�G��3��M\0��@z7���6�-DO34�ދ\0Κ��ΰt\"�\"vC\"Jf�Rʞ��ku3�M��~����5V ��j/3���@gG�}D���B�Nq��=]\$�I��Ӟ�3�x=_j�X٨�fk(C]^j�M��F��ա��ϣCz��V��=]&�\r�A<	������6�Ԯ�״�`jk7:g��4ծ��YZq�ftu�|�h�Z��6��i〰0�?��骭{-7_:��ސtѯ�ck�`Y��&���I�lP`:�� j�{h�=�f	��[by��ʀoЋB�RS���B6��^@'�4��1U�Dq}��N�(X�6j}�c�{@8���,�	�PFC���B�\$mv���P�\"��L��CS�]����E���lU��f�wh{o�(��)�\0@*a1G� (��D4-c��P8��N|R���VM���n8G`e}�!}���p�����@_���nCt�9��\0]�u��s���~�r��#Cn�p;�%�>wu���n�w��ݞ�.���[��hT�{��值	�ˁ��J���ƗiJ�6�O�=������E��ٴ��Im���V'��@�&�{��������;�op;^��6Ŷ@2�l���N��M��r�_ܰ�Í�` �( y�6�7�����ǂ��7/�p�e>|��	�=�]�oc����&�xNm���烻��o�G�N	p����x��ý���y\\3����'�I`r�G�]ľ�7�\\7�49�]�^p�{<Z��q4�u�|��Qۙ��p���i\$�@ox�_<���9pBU\"\0005�� i�ׂ��C�p�\n�i@�[��4�jЁ�6b�P�\0�&F2~������U&�}����ɘ	��Da<��zx�k���=���r3��(l_���FeF���4�1�K	\\ӎld�	�1�H\r���p!�%bG�Xf��'\0���	'6��ps_��\$?0\0�~p(�H\n�1�W:9�͢��`��:h�B��g�B�k��p�Ɓ�t��EBI@<�%����` �y�d\\Y@D�P?�|+!��W��.:�Le�v,�>q�A���:���bY�@8�d>r/)�B�4���(���`|�:t�!����?<�@���/��S��P\0��>\\�� |�3�:V�uw���x�(����4��ZjD^���L�'���C[�'�����jº[�E�� u�{KZ[s���6��S1��z%1�c��B4�B\n3M`0�;����3�.�&?��!YA�I,)��l�W['��ITj���>F���S���BбP�ca�ǌu�N����H�	LS��0��Y`���\"il�\r�B���/����%P���N�G��0J�X\n?a�!�3@M�F&ó����,�\"���lb�:KJ\r�`k_�b��A��į��1�I,�����;B,�:���Y%�J���#v��'�{������	wx:\ni����}c��eN���`!w��\0�BRU#�S�!�<`��&v�<�&�qO�+Σ�sfL9�Q�Bʇ����b��_+�*�Su>%0�����8@l�?�L1po.�C&��ɠB��qh�����z\0�`1�_9�\"���!�\$���~~-�.�*3r?�ò�d�s\0����>z\n�\0�0�1�~���J����|Sޜ��k7g�\0��KԠd��a��Pg�%�w�D��zm�����)����j�����`k���Q�^��1���+��>/wb�GwOk���_�'��-CJ��7&����E�\0L\r>�!�q́���7����o��`9O`�����+!}�P~E�N�c��Q�)��#��#�����������J��z_u{��K%�\0=��O�X�߶C�>\n���|w�?�F�����a�ϩU����b	N�Y��h����/��)�G��2���K|�y/�\0��Z�{��P�YG�;�?Z}T!�0��=mN����f�\"%4�a�\"!�ޟ����\0���}��[��ܾ��bU}�ڕm��2�����/t���%#�.�ؖ��se�B�p&}[˟��7�<a�K���8��P\0��g��?��,�\0�߈r,�>���W����/��[�q��k~�CӋ4��G��:��X��G�r\0������L%VFLUc��䑢��H�ybP��'#��	\0п���`9�9�~���_��0q�5K-�E0�b�ϭ�����t`lm����b��Ƙ; ,=��'S�.b��S���Cc����ʍAR,����X�@�'��8Z0�&�Xnc<<ȣ�3\0(�+*�3��@&\r�+�@h, ��\$O���\0Œ��t+>����b��ʰ�\r�><]#�%�;N�s�Ŏ����*��c�0-@��L� >�Y�p#�-�f0��ʱa�,>��`����P�:9��o���ov�R)e\0ڢ\\����\nr{îX����:A*��.�D��7�����#,�N�\r�E���hQK2�ݩ��z�>P@���	T<��=�:���X�GJ<�GAf�&�A^p�`���{��0`�:���);U !�e\0����c�p\r�����:(��@�%2	S�\$Y��3�hC��:O�#��L��/����k,��K�oo7�BD0{���j��j&X2��{�}�R�x��v���أ�9A����0�;0�����-�5��/�<�� �N�8E����	+�Ѕ�Pd��;���*n��&�8/jX�\r��>	PϐW>K��O��V�/��U\n<��\0�\nI�k@��㦃[��Ϧ²�#�?���%���.\0001\0��k�`1T� ����ɐl�������p���������< .�>��5��\0��	O�>k@Bn��<\"i%�>��z��������3�P�!�\r�\"��\r �>�ad���U?�ǔ3P��j3�䰑>;���>�t6�2�[��޾M\r�>��\0��P���B�Oe*R�n���y;� 8\0���o�0���i���3ʀ2@����?x�[����L�a����w\ns����A��x\r[�a�6�clc=�ʼX0�z/>+����W[�o2���)e�2�HQP�DY�zG4#YD����p)	�H�p���&�4*@�/:�	�T�	���aH5���h.�A>��`;.���Y��a	���t/ =3��BnhD?(\n�!�B�s�\0��D�&D�J��)\0�j�Q�y��hDh(�K�/!�>�h,=�����tJ�+�S��,\"M�Ŀ�N�1�[;�Т��+��#<��I�Zğ�P�)��LJ�D��P1\$����Q�>dO��v�#�/mh8881N:��Z0Z���T �B�C�q3%��@�\0��\"�XD	�3\0�!\\�8#�h�v�ib��T�!d�����V\\2��S��Œ\nA+ͽp�x�iD(�(�<*��+��E��T���B�S�CȿT���� e�A�\"�|�u�v8�T\0002�@8D^oo�����|�N������J8[��3����J�z׳WL\0�\0��Ȇ8�:y,�6&@�� �E�ʯݑh;�!f��.B�;:���[Z3������n���ȑ��A���qP4,��Xc8^��`׃��l.����S�hޔ���O+�%P#Ρ\n?��IB��eˑ�O\\]��6�#��۽؁(!c)�N����?E��B##D �Ddo��P�A�\0�:�n�Ɵ�`  ��Q��>!\r6�\0��V%cb�HF�)�m&\0B�2I�5��#]���D>��3<\n:ML��9C���0��\0���(ᏩH\n����M�\"GR\n@���`[���\ni*\0��)������u�)��Hp\0�N�	�\"��N:9q�.\r!���J��{,�'����4�B���lq���Xc��4��N1ɨ5�Wm��3\n��F��`�'��Ҋx��&>z>N�\$4?����(\n쀨>�	�ϵP�!Cq͌��p�qGLqq�G�y�H.�^��\0z�\$�AT9Fs�Ѕ�D{�a��cc_�G�z�)� �}Q��h��HBָ�<�y!L����!\\�����'�H(��-�\"�in]Ğ���\\�!�`M�H,gȎ��*�Kf�*\0�>6���6��2�hJ�7�{nq�8����H�#c�H�#�\r�:��7�8�܀Z��ZrD��߲`rG\0�l\n�I��i\0<����\0Lg�~���E��\$��P�\$�@�PƼT03�HGH�l�Q%*\"N?�%��	��\n�CrW�C\$��p�%�uR`��%��R\$�<�`�Ifx���\$/\$�����\$���O�(���\0��\0�RY�*�/	�\rܜC9��&hh�=I�'\$�RRI�'\\�a=E����u·'̙wI�'T���������K9%�d����!��������j�����&���v̟�\\=<,�E��`�Y��\\����*b0>�r��,d�pd���0DD ̖`�,T �1�% P���/�\r�b�(���J����T0�``ƾ����J�t���ʟ((d�ʪ�h+ <Ɉ+H%i�����#�`� ���'��B>t��J�Z\\�`<J�+hR���8�hR�,J]g�I��0\n%J�*�Y���JwD��&ʖD�������R�K\"�1Q�� ��AJKC,�mV�������-���KI*�r��\0�L�\"�Kb(����J:qKr�d�ʟ-)��ˆ#Ը�޸[�A�@�.[�Ҩʼ�4���.�1�J�.̮�u#J���g\0��򑧣<�&���K�+�	M?�/d��%'/��2Y��>�\$��l�\0��+����}-t��ͅ*�R�\$ߔ��K�.����JH�ʉ�2\r��B���(P���6\"��nf�\0#Ї ��%\$��[�\n�no�LJ�����e'<����1K��y�Y1��s�0�&zLf#�Ƴ/%y-�ˣ3-��K��L�΁��0����[,��̵,������0���(�.D��@��2�L+.|�����2�(�L�*��S:\0�3����G3l��aːl�@L�3z4�ǽ%̒�L�3����!0�33=L�4|ȗ��+\"���4���7�,\$�SPM�\\��?J�Y�̡��+(�a=K��4���C̤<Ё�=\$�,��UJ]5h�W�&t�I%��5�ҳ\\M38g�́5H�N?W1H��^��Ը�Y͗ؠ�͏.�N3M�4Å�`��i/P�7�dM>�d�/�LR���=K�60>�I\0[��\0��\r2���Z@�1��2��7�9�FG+�Ҝ�\r)�hQtL}8\$�BeC#��r*H�۫�-�H�/���6��\$�RC9�ب!���7�k/P�0Xr5��3D���<T�Ԓq�K���n�H�<�F�:1SL�r�%(��u)�Xr�1��nJ�I��S�\$\$�.·9��IΟ�3 �L�l���Ι9��C�N�#ԡ�\$�/��s��9�@6�t���N�9���N�:����7�Ӭ�:D���M)<#���M}+�2�N��O&��JNy*���ٸ[;���O\"m����M�<c�´���8�K�,���N�=07s�JE=T��O<����J�=D��:�C<���ˉ=���K�ʻ̳�L3�����LTЀ3�S,�.���q-��s�7�>�?�7O;ܠ`�OA9���ϻ\$���O�;��`9�n�I�A�xp��E=O�<��5����2�O�?d�����`N�iO�>��3�P	?���O�m��S�M�ˬ��=�(�d�Aȭ9���\0�#��@��9D����&���?����i9�\n�/��A���ȭA��S�Po?kuN5�~4���6���=򖌓*@(�N\0\\۔dG��p#��>�0��\$2�4z )�`�W���+\0��80�菦������z\"T��0�:\0�\ne \$��rM�=�r\n�N�P�Cmt80�� #��J=�&��3\0*��B�6�\"������#��>�	�(Q\n���8�1C\rt2�EC�\n`(�x?j8N�\0��[��QN>���'\0�x	c���\n�3��Ch�`&\0���8�\0�\n���O`/����A`#��Xc���D �tR\n>���d�B�D�L��������Dt4���j�p�GAoQoG8,-s����K#�);�E5�TQ�G�4Ao\0�>�tM�D8yRG@'P�C�	�<P�C�\"�K\0��x��~\0�ei9���v))ѵGb6���H\r48�@�M�:��F�tQ�!H��{R} �URp���O\0�I�t8������[D4F�D�#��+D�'�M����>RgI���Q�J���U�)Em���TZ�E�'��iE����qFzA��>�)T�Q3H�#TL�qIjNT���&C��h�X\nT���K\0000�5���JH�\0�FE@'љFp�hS5F�\"�oѮ�e%aoS E)� ��DU��Q�Fm�ѣM��Ѳe(tn� �U1ܣ~>�\$��ǂ��(h�ǑG�y`�\0��	��G��3�5Sp(��P�G�\$��#��	���N�\n�V\$��]ԜP�=\"RӨ?Lzt��1L\$\0��G~��,�KN�=���GM����NS�)��O]:ԊS}�81�RGe@C�\0�OP�S�N�1��T!P�@��S����S�G`\n�:��P�j�7R� @3��\n� �������DӠ��L�����	��\0�Q5���CP��SMP�v4��?h	h�T�D0��֏��>&�ITx�O�?�@U��R8@%Ԗ��K���N�K��RyE�E#�� @����%L�Q�Q����?N5\0�R\0�ԁT�F�ԔR�S�!oTE�C(�����ĵ\0�?3i�SS@U�QeM��	K�\n4P�CeS��\0�NC�P��O�!�\"RT�����S�N���U5OU>UiI�PU#UnKP��UYT�*�C��U�/\0+���)��:ReA�\$\0���x��WD�3���`����U5�IHUY��:�P	�e\0�MJi�����Q�>�@�T�C{��u��?�^�v\0WR�]U}C��1-5+U�?�\r�W<�?5�JU-SX��L�� \\t�?�sM�b�ՃV܁t�T�>�MU+�	E�c���9Nm\rRǃC�8�S�X�'R��XjCI#G|�!Q�Gh�t�Q��� )<�Y�*��RmX0����M���OQ�Y�h���du���Z(�Ao#�NlyN�V�Z9I���M��V�ZuOՅT�T�EՇַS�e����\n�X��S�QER����[MF�V�O=/����>�gչT�V�oU�T�Z�N�*T\\*����S-p�S��V�q��M(�Q=\\�-UUUV�C���Z�\nu�V\$?M@U�WJ\r\rU��\\�'U�W]�W��W8�N�'#h=oC���F(��:9�Yu����V-U�9�]�C�:U�\\�\n�qW���(TT?5P�\$ R3�⺟C}`>\0�E]�#R��	��#R�)�W���:`#�G�)4�R��;��ViD%8�)Ǔ^�Q��#�h	�HX	��\$N�x��#i x�ԒXR��'�9`m\\���\nE��Q�`�bu@��N�dT�#YY����GV�]j5#?L�xt/#���#酽O�P��Q��6����^� �������M\\R5t�Ӛp�*��X�V\"W�D�	oRALm\rdG�N	����6�p\$�P废E5����Tx\n�+��C[��V�����8U�Du}ػF\$.��Q-;4Ȁ�NX\n�.X�b͐�\0�b�)�#�N�G4K��ZS�^״M�8��d�\"C��>��dHe\n�Y8���.� ���ҏF�D��W1cZ6��Q�KH�@*\0�^���\\Q�F�4U3Y|�=�Ӥ�E��ۤ�?-�47Y�Pm�hYw_\r�VeױM���ُe(0��F�\r�!�PUI�u�7Q�C�ю?0����gu\rqधY-Q�����=g\0�\0M#�U�S5Zt�֟ae^�\$>�ArV�_\r;t���HW�Z�@H��hzD��\0�S2J� HI�O�'ǁe�g�6�[�R�<�?� /��KM����\n>��H�Z!i����TX6���i�C !ӛg�� �G }Q6��4>�w�!ڙC}�VB�>�UQڑj�8c�U�T���'<�>����HC]�V��7jj3v���`0���23����x�@U�k�\n�:Si5��#Y�-w����M?c��MQ�GQ�уb`��\0�@��ҧ\0M��)ZrKX�֟�Wl������l�TM�D\r4�QsS�40�sQ́�mY�h�d��C`{�V�gE�\n��XkՁ�'��,4���^��6�#<4��NXnM):��OM_6d�������[\"KU�n��?l�x\0&\0�R56�T~>��ո?�Jn��� ��Z/i�6���glͦ�U��F}�.����JL�CTbM�4��cL�TjSD�}Jt���Z����:�L���d:�Ez�ʤ�>��V\$2>����[�p�6��R�9u�W.?�1��RHu���R�?58Ԯ��D��u���p�c�Z�?�r׻ Eaf��}5wY���ϒ���W�wT[Sp7'�_aEk�\"[/i��#�\$;m�fأWO����F�\r%\$�ju-t#<�!�\n:�KEA����]�\nU�Q�KE��#��X��5[�>�`/��D��֭VEp�)��I%�q���n�x):��le���[e�\\�eV[j�����7 -+��G�WEwt�WkE�~u�Q/m�#ԐW�`�yu�ǣD�A�'ױ\r��ՙO�D )ZM^��u-|v8]�g��h���L��W\0���6�X��=Y�d�Q�7ϓ��9����r <�֏�D��B`c�9���`�D�=wx�I%�,ᄬ�����j[њ����O��� ``��|�����������.�	AO���	��@�@ 0h2�\\�ЀM{e�9^>���@7\0��˂W���\$,��Ś�@؀����w^fm�,\0�yD,ם^X�.�ֆ�7����2��f;��6�\n����^�zC�קmz��n�^���&LFF�,��[��e��aXy9h�!:z�9c�Q9b� !���Gw_W�g�9���S+t���p�tɃ\nm+����_�	��\\���k5���]�4�_h�9 ��N����]%|��7�֜�];��|���X��9�|����G���[��\0�}U���MC�I:�qO�Vԃa\0\r�R�6π�\0�@H��P+r�S�W���p7�I~�p/��H�^������E�-%��̻�&.��+�Jђ;:���!���N�	�~����/�W��!�B�L+�\$��q�=��+�`/Ƅe�\\���x�pE�lpS�JS�ݢ��6��_�(ů���b\\O��&�\\�59�\0�9n���D�{�\$���K��v2	d]�v�C�����?�tf|W�:���p&��Ln��賞�{;���G�R9��T.y���I8���\rl� �	T��n�3���T.�9��3����Z�s����G����:	0���z��.�]��ģQ�?�gT�%��x�Ռ.����n<�-�8B˳,B��rgQ�����Ɏ`��2�:{�g��s��g�Z��� ׌<��w{���bU9�	`5`4�\0BxMp�8qnah�@ؼ�-�(�>S|0�����3�8h\0���C�zLQ�@�\n?��`A��>2��,���N�&��x�l8sah1�|�B�ɇD�xB�#V��V�׊`W�a'@���	X_?\n�  �_�. �P�r2�bUar�I�~��S���\0ׅ\"�2����>b;�vPh{[�7a`�\0�˲j�o�~���v��|fv�4[�\$��{�P\rv�BKGbp������O�5ݠ2\0j�لL���)�m��V�ejBB.'R{C��V'`؂ ��%�ǀ�\$�O��\0�`����4 �N�>;4���/�π��*��\\5���!��`X*�%��N�3S�AM���Ɣ,�1����\\��caϧ ��@��˃�B/����0`�v2��`hD�JO\$�@p!9�!�\n1�7pB,>8F4��f�π:��7���3��3����T8�=+~�n���\\�e�<br����Fز� ��C�N�:c�:�l�<\r��\\3�>���6�ONn��!;��@�tw�^F�L�;���,^a��\ra\"��ڮ'�:�v�Je4�א;��_d\r4\r�:����S�����2��[c��X�ʦPl�\$�ޣ�i�w�d#�B��b��������`:���~ <\0�2����R���P�\r�J8D�t@�E��\0\r͜6����7����Y���\"����\r�����3��.�+�z3�;_ʟvL����wJ�94�I�Ja,A����;�s?�N\nR��!��ݐ�Om�s�_��-zۭw���zܭ7���z���M����o����\0��a��ݹ4�8�Pf�Y�?��i��eB�S�1\0�jDTeK��UYS�?66R	�c�6Ry[c���5�]B͔�R�_eA)&�[凕XYRW�6VYaeU�fYe�w��U�b�w�E�ʆ;z�^W�9��ק�ݖ��\0<ޘ�e�9S���da�	�_-��L�8ǅ�Q��TH[!<p\0��Py5�|�#��P�	�9v��2�|Ǹ��fao��,j8�\$A@k����a���b�c��f4!4���cr,;�����b�=��;\0��ź���cd��X�b�x�a�Rx0A�h�+w�xN[��B��p���w�T�8T%��M�l2�������}��s.kY��0\$/�fU�=��s�gK���M� �?���`4c.��!�&�分g��f�/�f1�=��V AE<#̹�f\n�)���Np��`.\"\"�A�����q��X��٬:a�8��f��Vs�G��r�:�V��c�g�Vl��g=��`��W���y�gU��˙�Ẽ�eT=�����x 0� M�@����%κb���w��f��O�筘�*0���|t�%��P��p��gK���?p�@J�<Bٟ#�`1��9�2�g�!3~����nl��f��Vh���.����aC���?���-�1�68>A��a�\r��y�0��i�J�}�������z:\r�)�S���@��h@���Y���mCEg�cyφ��<���h@�@�zh<W��`��:zO���\r��W���V08�f7�(Gy���`St#��f�#����C(9���؀d���8T:���0�� q���79��phAg�6�.��7Fr�b� �j��A5��a1��h�ZCh:�%��gU��D9��Ɉ�׹��0~vTi;�VvS��w��\r΃?��f�����n�ϛiY��a��3�·9�,\n��r��,/,@.:�Y>&��F�)�����}�b���iO�i��:d�A�n��c=�L9O�h{�� 8hY.������������\r��և�����1Q�U	�C�h��e�O���+2o����N�����zp�(�]�h��Z|�O�c�zD���;�T\0j�\0�8#�>Ύ�=bZ8Fj���;�޺T酡w��)���N`���ÅB{��z\r�c���|dTG�i�/��!i��0���'`Z:�CH�(8�`V������\0�ꧩ��W��Ǫ��zgG������-[��	i��N\rq��n���o	ƥfEJ��apb��}6���=o���,t�Y+��EC\r�Px4=����@���.��F��[�zq���X6:FG��#��\$@&�ab��hE:����`�S�1�1g1���2uhY��_:Bߡdc�*���\0�ƗFYF�:���n���=ۨH*Z�Mhk�/�냡�zٹ]��h@����1\0��ZK�������^+�,vf�s��>���O�|���s�\0֜5�X��ѯF��n�A�r]|�Ii4�� ��C� h@ع����cߥ�6smO������gX�V2�6g?~��Y�Ѱ�s�cl \\R�\0��c��A+�1������\n(����^368cz:=z��(�� ;裨�s�F�@`;�,>yT��&��d�Lן��%��-�CHL8\r��b�����Mj]4�Ym9����Z�B��P}<���X���̥�+g�^�M� + B_Fd�X���l�w�~�\r⽋�\":��qA1X������3�ΓE�h�4�ZZ��&����1~!N�f��o���\nMe�଄��XI΄�G@V*X��;�Y5{V�\n���T�z\rF�3}m��p1�[�>�t�e�w����@V�z#��2��	i���{�9��p̝�gh���+[elU���A�ٶӼi1�!��omm�*K���}��!�Ƴ����{me�f`��m��C�z=�n�:}g� T�mLu1F��}=8�Z���O��mFFMf��OO����������/����ޓ���V�oqj���n!+����Z��I�.�9!nG�\\��3a�~�O+��::�K@�\n�@���Hph��\\B��dm�fvC���P�\" ��.nW&��n��HY�+\r���z�i>Mfqۤ��Qc�[�H+��o��*�1'��#āEw�D_X�)>�s��-~\rT=�������- �y�m����{�h��j�M�)�^����'@V�+i�������;F��D[�b!����B	��:MP���ۭoC�vAE?�C�IiY��#�p�P\$k�J�q�.�07���x�l�sC|���bo�2�X�>M�\rl&��:2�~��cQ����o��d�-��U�Ro�Y�nM;�n�#��\0�P�f��Po׿(C�v<���[�o۸����fѿ���;�ẖ�[�Y�.o�Up���pU���.���B!'\0���<T�:1�������<���n��F���I�ǔ��V0�ǁRO8�w��,aF��ɥ�[�Ο��YO����/\0��ox���Q�?��:ً���`h@:�����/M�m�x:۰c1������v�;���^���@��@�����\n{�����;���B���8�� g坒�\\*g�yC)��E�^�O�h	���A�u>���@�D��Y�����`o�<>��p���ķ�q,Y1Q��߸��/qg�\0+\0���D���?�� ����k:�\$����ץ6~I��=@���!��v�zO񁚲�+���9�i����a������g������?��0Gn�q�]{Ҹ,F���O���� <_>f+��,��	���&�����·�y�ǩO�:�U¯�L�\n�úI:2��-;_Ģ�|%�崿!��f�\$���Xr\"Kni����\$8#�g�t-��r@L�圏�@S�<�rN\n�D/rLdQk࣓�����e����Э��\n=4)�B���ך��Z-|Hb����Hk�*	�Q!�'��G ��Ybt!��(n,�P�Ofq�+X�Y����\"b F6��r f�\"�ܳ!N��^��r�B_(�\"�K�_-<��*Q���/,)�H\0����r�\"z2(�tه.F>��#3���268sh٠��ƑI1Sn20���-��4���2A�s(�4�˶��\0��#��r�K'�ͷG'�7&\n>x���J�GO8,�0���8���\0�W9��I�?:3n�\r-w:�����;3ȉ�!�;��ꃘ�Z�RM�+>�����0/=R�'1�4�8����m�%ȥ}χ9�;�=�nQ��=�hhL��G�kW�\r�	%�4Ҝs�ΖJ�3s�4�@�U�%\$���N;�?4���N��2|��Z�3�h\0�3�5�^�xi2d\r|�M�ʣbh|�#v�` \0�ꐮ���\$\r2h#���?���I\n���+o-��?6`ṽ�.\$���KY%�J?�c�R�N#K:�K�EL�>:��@��jP��n_t&slm�'�ЩɸӜ�����;6ۗHU5#�Q7U��WY�U bN��W�_���;TC�[�<ږ>����W�CU��6X#`MI:t�ӵ��	u#`�fu�\$�t���X�`�f<�;b�gh���9�7�S58���#^�-�\0����չR*�'��(���qZ壣�X�Q�FUv�W GW���T��W�~ڭ^�W�����J=_ؗbm��bV\\l��/�M��TmTOXu�=_��ITvvu�a\rL_�qR/]]m�su=H=u�g o\\UՅgM�	XVU��%�h��53U�\\=��Q��M�v���g�m��ue�����h�b�M�GCeO5�ԁ�O5��Y�i=e�	G�TURvOa�*�ivWX�J5<��bu�]������<����\$u3v#�'e�u�R5m��v�D5�.v���W=�U_�(�\\V��_<��S�n)�1M%Qh�Z�T�f5E�'��W��v�UmiՂU��]aW�U�dRv��-YUZu��UV��UiR�V������[��ZMU�\\=�v{�X���wQ�huHv��gqݴw!�oqt�U{TGq�{�#^G_ubQ���i9Qb>�NUd��k��5hP�mu[�\0����_��[�Y-����r���(�CrMe�J�!h?QrX3 x���#��x�<�{u5~���-�u��YyQ\r-��\0�uգuuٿpUڅ�)�P��\r<u�S�0��w��-i���!�֊�B���d]��Ň��E��vlmQݏ6k��J��w��Ğ����ED�U�R�e�v:X�c�NW}`-�t�H#e��b��u���	~B7� ?�	OP�CW���SE͕V>���U�7�����m�ӂ�z�=����1���+��m�I,>�X7��]�.��*	^��N��.��/\"���)�	���s��|��ӟ�l�}�����!�5n�p�j��h�}���m�E�zH�aO0d=A|w�߳������u���v���G�x#��b�cS�o-��tOm`C��^M��@�h�n\$k�`�`HD^�PE�[�]��rR�m�=�.�ه>Ayi� \"���	��o�-,.�\nq+���fXd����*߽�K�؃'�� �%a����9p���KLM��!�,������zX#�V�uH%!��63�J�ryՁ��q_�u	�W����|@3b1��7|~wﱳ��A7���	��9cS&{���%Vx��kZO��w�Ur?����N �|�C�#Ű��կ �/��9�ft�Ew�C��a�^\0�O<�W�{Y�=�e��n���gyf0h@�S�\0:C���^��VgpE9:85�3�ާ���@��j_�[�+��ǩx�^�ꮆ~@чW���㓜�9x�FC���.�����k^I���pU9��S������\$���\r4���\0��O���)L[�p?�.PECS�I1nm{�?�P�WA߲�;���D�;S�a�Kf��%�?�X��+��B>��9���Gj�c�z�A͎�:�a�n0bJ{o��!3��!'��K�����}�\\��3W��5�x���L;�2ζn�a;���׺Xӛ]�o��x�{�5ޙjX���vӚ��q��EE{р4����{���	�\n��>��aﯷ�����L����������'����{�\n��>J�ߌ��ӗ��Y�\rOʽ�t����-O���4��9F�;�����G��I�F��1�o����O���a{w�0����Ư;񔄑l�o��J�Tb\rw�2�J��=D#�n�:�y��S�^�,.�?(�I\$���Ư���3��s�4M�aCR���G̑��I߰n<�zy�XN��?��.��=���DǼ�\r����\n��\ro��\nПCl%��Y���߰��G���}#�VН%�(����3�ɍ�r��};��׿G��n�[�{����_<m4[	I����q��?�0cV�nms��nM���\"Nj1�w?@�\$1��>��^�����\\�{n�\\���7���ٟic1���hoo�?j<G�x�l���S�r}���|\"}��/�?s��tI���&^�1e��t��,�*'F��=�/F�k�,95rV������쑈��o9��/F��_�~*^��{�I����_�����^n���N��~���A���d����U�w�qY���T�2��G�?�&����:y��%��X�J�C�d	W�ߎ~�G!��J}��������B-��;���h�*�R���E��~���.�~���SAqDVx���='��E�(^���~����������o7~�M[��Q��(��y��nP�>[WX{q�aϤ���.&N�3]��HY������[���&�8?�3������݆����#���B�e�6��@��[������G\r�+��}������_��7�|N����4~(z�~����%��?����[��1�S�]x�k��KxO^�A���rZ+����*�W��k�wD(���R:��\0����'����m!O�\n��u���.�[ �P�!��}��m ��1p�u��,T��L 	0}��&P٥\n�=D�=���\rA/�o@��2�t�6�DK��\0���q�7�l���B���(�;[��kr\r�;#���lŔ\r�<}zb+��O�[�WrX�`�Z ţ�Pm'Fn����Sp�-�\0005�`d���P���Ǿ��;��n\0�5f�P���EJ�w�� �.?�;��N�ޥ,;Ʀ�-[7��e��i��-���dَ<[~�6k:&�.7�]�\0������/�59 ��@eT:煘�3�d�sݝ�5䏜5f\0�P��HB������8J�LS\0vI\0���7Dm��a�3e��?B��\$�.E���f���@�n���b�Gb��q3�|��Paˈ�ϯX7Tg>�.�p�5��AHŵ��3S�,��@�#&w��3��m[���I�ѥ�^�̤J1?�gTၽ#�S�=_��_��	���Vq/C۾�݀�|�����D �g>܄��� 6\r�7}q��Ť�JG�B^�\\g������&%��[�2Ixì��6\03]�3�{�@RU��M��v<�1����sz�uP�5��F:�i�|�`�q���V| ��\nk��}�'|�gd�!�8� <,�P7�m��||���I�A��]BB �F�0X���	�D��`W���qm�OL�	�.�(�p��ҁ��\"!����\0��A����V��7k��M�\$�N0\\���\"�f������\0uq��,��5��A6�p���\n�ΐjY�7[pK��4;�l�5n��@�\\f��l	��M���P��3��C�HbЌ��cEpP���4eooe�{\r-��2.�֥��P50u���G}��\0����<\r��!��~�������\n7F��d�����>��a��%�c6Ԟ��M��|��d����O�_�?J��C0�>Ё�&7kM4�`%f�l�ΘB~�wx��ZG�P�2��0�=�*p��@�BeȔ��|2�\r�?q��8�����Њ(�yr���0��>�>�E?w�|r]�%Av�����@�+�X��Ag����s��C��AXmNҝ�4\0\r���8J�J�ǸD�Қ�:=	������S�4��F;	�\\&��P!6%\$i�xi4c�0B�;62=��1��̈PC��m���dpc+�5��\$/rCR�`�MQ�6(\\��2A���\\��lG�l�\0Bq��P�r���B����т�_6Ll�!BQ��IG�����XRbs�]B�Hr���`�X��\$p�8���	nbR,±�L��\"�E%\0�aYB�s���D,�!��ϛpN9RbG�4��M��t����jU�����y\0��%\$.�iL!x��ғ�(�.�)6T(�I��a%�K�]m�t���&��G7�ITM�B�\rza��])va�%���41T�j͹(!�����\\�\\�W��\\t\$�0��%�\0aK\$�T�F(Y�C@��H���H�nD�d��Wp��hZ�'�ZC,/���\$����J�FB�uܬQ:Υ�A��:-a#��=jb��l�Ug;{R��U��EWn�Ua��V��Nj��u�G�*�yֹ%��@��*���Yx�_�z�]�)v\"��R��L�VIv�=`��'��U�) S\r~R���\ni��)5S��D49~�b�;)3�,�9M3�HsJkT�Ü�(����uJ�][\$uf��ob���\n.,�Yܵ9j1'��!�1�\$J��gڤ՟ĆU0��Zuah���cH��,�Yt��Kb�5��5��/dY��AU�҅��[W>�_V�\r��*���j��-T�� z�Y�d�c�m�ҹ��:����[Ut-{���l	�i+a)�.[��_:�5��h��W§�m��%JI��[T�h>�������;�X̺d�S�d�V�;\rƱ!N��K&�A�Ju4B��dg΢.Vp��mb��)�V!U\0G丨��`���\\��q�7Q�b�VL��:�Ղ���Z.�N��*�ԏU]Z�l�z������R D1I��£�r:\0<1~;#�Jb���M�y�+�۔/�\"ϛj<3�#��̌��:P.}�e����D\"q�yJ�G���sop�����X�\r��d��\rxJ%���ƼO:%yy��,��%{�3<�Xø����z�E�z(\0 �D_���.2+�g�b�c�x�pgި��|9CP����48U	Q�/Aq��Q�(4 7e\$D��v:�V�b��N4[��iv���2�\r�X1��AJ(<PlF�\0���\\z�)���W�(�4����� p�����`��\r�da6����O��m�a�}q�`��6P�'h��3�|����f� j��A�z���+�D�UW�D���5��%#�x�3{��L\r-͙]:jd�P	j�f�q:Z�\"sad�)�G�3	��+��r�NK��1Q���x=>�\"��-�:�F���Iك*�@ԟ�y�T�\\U��Y~������3D������f,s�8HV�'�t9v(:��B9�\\Z����(�&�E8���W\$X\0�\n��9�WB��b��66j9� �ʈ��?,��| �a��g1�\nPs�\0@�%#K����\r\0ŧ\0���0�?�š,�\0��h��h�\08\0l\0�-�Z��jb�Ŭ\0p\0�-�f`ql��0\0i-�\\ps��7�e\"-Z�lb�E�,�\0��]P ��E��b\0�/,Z��\r�\0000�[f-@\rӯEڋ�/�Z8��~\"��ڋ��.^��Qw��ϋ�\0�/t_ȼ���E���\0�0d]��b�Ť�|\0��\\ؼ���E�\0af0tZ��n�J�\0l\0�0L^��Qj@��J��^��q#F(�1�/�[�1�����I�.�^8��\0[�q��[Ñl\"�� ��\0�0,d����\r����c��{cE�\0o�0�]�\0\rc%�ۋ���8�w���Z��-�\\��{��֋G�/\\bp��@1�\0a�1�����s�!Ũ�/�/�]8��~c\"�ۋ��2�cΑm�\"�9�q�/\\^fQ~c�_���-\$i�\"�\0003����fX�qx#\09��Z.�i���@F���3tZH� \rcK�b\0j�/Dj��1����I�h�a��v�Ʃ�OZ4�Z��т#YE�\0i�.hH��sX/F<���.�j���b���\0mV/d\\���b�E����3T^(�шcKFR�����]X�q��������6�]h��c6Eċ�66�h����n\0005�sn/dn��`\r\"�F���-D`�Ց��N�2�Y��bx��#\\�닇V3x�1x�Fx��\0�6�b�q����!��8|^���ub�����-�r��q��:��%�0�pp�#����\0�6�f��Ǣ�Ŭ�d�0�qH����\$�@�q�-�^B4��\"�\08�1�/lnxϑ���G�3:0tjh�~@Ƽ���3�vH��b�G(�e��4gغq��2�1��-�nX��\"�F<�Q�1\\j��1���Eǋ��4m����[�n�z7�yh�1�#�ގ/�3\\x�q�KG����6�o��1{��FJ���6�lX�q⣄�u���9�r(�1��Gc\0�f:�rX��#�Ž\0i�<\\}���b�F�\0s�7�y2���#uFe��\">4i��������\n<{�㑍��Ɖ�J;�]��1�#��0��J;4^��D���Ǯ����4i��(H#��E�x�/�n��1��/ǡ��j6,l��1t�/\0005%�0�]x����GG5�!�0��������r�q�2��ޑ��NFP�o\"4�_��1�d�%�e �3�s8���G5�� �6�[H��c�H�jY�;�[辑�b�! �y�@�\\��q�#WHN���;�c�Q��:�-�%�.�kXƑ���G͌��1Df�ߑ�cWFl��!�0����c Eܐ��;l��q�\"�F����7\\\\������O�q�.T|\"?����E��f9TyYѩ�SG1���A\$f9R\n\"��x��>B��H��ߤ\0���:\$e�1���F?�=�3Tu)\nq�b��~���<T��α�c�H.�m~C�wHʱ�#/�I�]~3�^��ф#��>�Y�4�^��Qjc��K�1\"�8�|6��c\"�B��\"b4���%����G\0e\"�/t���1r�1��e!v2�y����<Ǡ���8\\o��ђ#t�ѐ\rz@�}H�b���y �1�\\���deG��Z3�~�r)�1ȿ���Bl~H��:�dF��-�?�k8�q�c(F͋�K�5|my�c1�<�*@�j���1��ž��>I�Z��Qj��2��\$0��h�Q��VFT�	\$�Al~�qڣȱ�\$�>\\p�\rq�\$/�u%�!�Jq \$��tE��GN-Tq)�\"��Hʌ��=�X�2-�H���8\\n��RW\$H��\"�C\\_�\0�d\$�f��\".D�u	'Q�zE��&0to��qj��ƿ��R@d������u�##�LLk�*q�\$*Gđi�@T�i�l��E����5���r\\d�I���\"/�Z�0�j\$T���z5Ld3�����o�.Tq�!1{�����9�Z��Q�b�F�wJ94n�����{�(�-�8�2h�u��;\$�-Dk��rs��H���#���Y7�\"�/E����	\$j�^�-�]�7�[\"N\$����W����/]�\$�+�1Ga�/&IDn�@\$��!��\$�-�k!�Q����)(N/\$t������O�KzP�tX��[\0�G��w(*K\$v��1�c�'��G̞I�xd��\n�A�8\\rX��a��I�iN�I%\$���_���6�f�Q�#��I�5#�F��غ��#�E⒕\"�3\$�I�c�H���vR|�Q��cE���:R�e��h�EΏfK`8�r.#�E��s�0L���R��F���!\nC\$`���\$�H?��nP�e�!�@F'���/�����������%�N,h��rF\$�����3�t��Ҁ���!1<��CQ�%�Ò��J�Z�f.�6ō����C���Ԝ.�[��Bҿx����\0NRn`���Y\n�%+N�IMs:ùYd�ef�B[���nƹY��m��R�ג��Y��C�X���j��U+Vk,�\0P��b@e���x��V��yT�7�u�[J�ȱ\nD��eR��mx&�l�\0)�}�J�,\0�I�ZƵ\$k!���Yb�����Re/Q���k�5.�e��5����W�`��\0)�Yv\"V�\0��\n�%��`Yn�աa��xÆQ!,�`\"�	_.�偩Ɩtm\$�\"��J��֍���v�%�M9j��	斧�*�Kp֔�;\\R ��3(���^��:}���|>µa-'U%w*�#>�@�̬e�J���;Pw/+��5E\rjn���d���^[���cΰ�u�z\\ؐ1mi\"x��p��;����P)����#��ؒ���!A�;��	4�a{`aV{K�U��8㨟0''o�2���yc̸9]K�@�җ^�lB��Or���,du��8�?����%�gB����Yn+�%c�e\0���ऱYr@f�(]ּ�\nbiz��n�SS2��GdBPj���@�(�ȥ�!�-�v��e�*c\0��4J�炒���,�U�	d��e�j'T�H]Ԋ�G!�)u��֯��ү�Z�B5�̓W��0\n���R���W��\\�Q j�^r�%l��3,�Yy��f3&��܎�Q:ϵ2�m�R)�T��(KR��0�ʔ@��Y��Y:��e3\r%���T�%�X����ST�.J\\�0�h�ą�D!�:�u���U\"�Ł�o+7�\"����f'��R\0���J��2S�2�#nm ��I劜�\"X���[�ր��} J��c�9p0���Q�(U\0�xDEW��.L��=<B�0+�)ZS V;�\\�I{�5I�A���,dW�u�5Ew\n\$%ҁ���2i_\$��+��O,����X��ՑJg&J��G��%\\J��b.��^L�T�Fl�薹]k#f@L�G�ĐT�ٗ��H��\"�q1S̰��j�V�(Ι��ZVz�ņ�,����G�.1F��gN�;�1ÊV��5E��5`�\0Ct�=F\nṛα�K����\0�ۊ�%��D]Q\$\r\0�3J\\,͙��<T4*���.�YK�D�Q��L�S%,�g������<��u0���Uĉ�*x(��NYv!��y�	w�4fd��rG��M \$��^;�����)<P�]D�%%�;�j��I0�a�u^Jp�[)�v�3RhR�E��\n�L_�#5|ܾ�m3P�*�\\Y51X��	i�N���\$\"��a���h*KU���V8��u�%&�r�˚��5o���g�;�rMl[ƨ�g������U�q�깚h|�eO2�f MlW2AP�׹�����v~eD�e�3Uӫl�E62i�����Ub���U���������V��iI!\$i�ʭ&Z:��xm!ņ�.�O�fwү!���kݤ̓��6b\"�I�J]]:T��6�Vr��}��ǫ]����U��	ys7f�Mř�3����Y��:T_M�w%3�n��\n��z*��3�h��	�`U��L���,�ۄ�5��vf��Û�42_Q��h���uD�\no��)�ĜիM9�7foۼ��r����WB~iT�eyQT�N\n�d�pr�#��M�;���4�p���t���(;���5	|��ǂ��',AV7ܔ��UA�&��R�P�\"��y�ҷ��)�[�n���-3V��,?�s6�p���3�f��A��9k|�ɮS�f�*@��5�g��ɿ2��}����U�ݙ����H�F�l%�p«Ie�be�M�SO\r�[��i�3�f��LV��r�u�����NA�:�%r��y3Q�_̸�W.���^Sl@&���5�Yl��1���}Vx�gʅ�^Sn���Q!:5�Z�iZCԈ:���3qg�%D��ݪ{U�3�tZ�`��u%w:�ZQ:Q���W f�훿9Jpl�)�3x�v���K7�b#�����X+J�(��h��P*Ӂ���Λ��!ה�ŏSL�h*'���\npB��ڪ�gNʝ�8BuҪ���Ό��8ni�I�s�US�I��;vvڳU�sR�7N�u�8�H|���ӷ�̎��8�q����+'���`�x�9R�	ծ��MaR8�x�)��'!���;�U��Y֓��sNI�g:�KT�y�3�g��Y����k���ܳn'LO(��3�w4�4������l���J����w��9�\\����hf(�_~���}9N���\0���b\"�Y餃Th,ڞ�@��D���\$�I��;�e��U��n����,�O��	X��g�-���+>ti'G����l�%\0�8�VB�U1�ye�\0KT�4���m��V2)\r]I/\rF���X���ߨ�a��G�¹�*�����>ER������Z�-)I\$����:�a�\0�Fyba�g�w��(�_@�v}�i�ʳ�S^�25DԳ�	��URO��JH��\\�is�f��K�N��qi�Sg�O\n�F~|���*@gR�_Q<9sܬ3i+ؗ�.Cw���|���y�6a�O�Y9���ɖ\n�Խ-([���_�}�S�]c�S=��������Y��U->�<���\n<�sO�Q4F�^}\0007u�k(/���/5{L�9�\0����&��[<���s�\0&��#�@h��3�V}��H���*�w+]'D�&�@�ց])��;TGe3��\\��n����d\$:�uN4�ykt�-dR!7����e4(P!��-��9�4�_PMGb��ıw����6O�S�F���)��yh0+����qT|��+u���+��A�?��	�T�3.q��41T��e��\n:P����{T�\n��h?��T�A�S��*���+�u�>�\\�Z����Y췢wEJ��%��s�L��d��y�+\rC�ߡ'A�l,�y�3���͗`�	_*�P� ThKDV���~5	�0�+�,�-?�]���3�֍K�`�^���I42(]�w�.�r����]�\nYƨB����	��}ЋR ��g�}:H��J�WP��\"޵���V\\�<��? >�����ܬ݆�=��:�\n0��\\+�S���f�U���U,�WCֈ�On��΅��.�e9|R�I'�[�/������2���Q��Bn:�I�\n��g�9�\r�,�R6����Q\$X�+�>����`\n�)/_8Qi�����=��v?5v�\0 \n���LG�Dm�w\\�F֌�Ѣ���dꟵ}s�\"��Yv�|�J*�9h���@XEU�*�(oQ]\$�B��,�����KT�v�AptCɃ\n�C,/�<��ڙEW�-V�P��=W�*%K�-Q`9	(��59Ӏ�m)�X��@�2���T@��\nS���bd�Eδa�+�DX��|U�	�	��F� 2�%5\nj�m��W�+�x�K��V�3#��CT�ek���&�,�l�jbd7)ӓ\"\n+�P��b��I�@�3��ܵjU��Es��)D�f뒃������P�Z3AΌ�\nwTh𗲪ۘ�4Z��<�uߩ�dq�ˊu(���bKG����n�Tﮈ]z��f%#�3I�fS��&}�@D�@++��A�h���\n��U�ޥ|B�;��Um��U�E�N�!�x2�1�\0�GmvH~��H�T�)�W��YN�\"�k5��vT#=�ڥ�<\n}�#R3Y�H�R�Iͳܦ;��Rl�1l�uB%TQJ�*���'�E�0i�dw,�z�ͥ:\$��;�?���j��)��)ԏ�\$32J}�&�[�\$��́�;Dn��E״�+0�aZ{���C ���(��:����O@h��D��\0��`PTou����F�\rQv����o�ܡ\$S��+��#7��Izr�pk�DW��Fs�9��Q� ���1�g��#�\0\\L�\$��3�g�X�y�y �-3h����!�nX��]+��	ɝ�c\0�\0�b��\0\r���-{�\0�Q(�Q�\$s�0���m(�[Ru�V����>��+�J[�6����J\0֗�\\���,��K�3�.�]a_\0R�J Ɨ`�^ԶClR�IK��\n�\$�nŏ���Kj��\n����~/��mn�].�`��ij��#K��f:`\0�錀6�7K▨zc��\0����/K���/�d���FE\0aL���dZ`�J�S��ʙ�2��4�@/�(��L��0�`�ĩ��_�L��]4Zh�Щ�SD�M��4:c��SR��M�E4�i��SG�EMj��4zd�թ�SFKL��%4�e��%\$�lKM2��1�ڔ�i����MV��.�ڔ�i����Lz�/���ۣӄ��M�,`�_��imS��gMƜ�jg�����5�9.��9j_��S���.��9�_���S���.�7�r�)��%�[2�m8�uT��S��3M:�]3�q���nӱ�KN�1|^�kt�\"��H�gKj�-;zc�i�Ӛ����\r<�_�-i�Ӹ��\"֞U.���i�RڑkOF��=:\\��\$Zө�MLE�5�x����ӻ_\"֜=<\0�t��S�9OҞ�1�~��i�����O��>�~q�)�F����=6:~���J���P:��=��T�)�ƫ��PJ8�@�w�����*��O�5]>��t���T\n��!\"��6Y	)��H�/P���3�	���/��P~���	�Ӯ�!\"��C����j� �eNJ������*%�4�1Q��CZ�Q�jTB�Q.�\rE)\0004��\$�2�SM+�<j�t�j0�,�9Q��}F\0\$�s��Ta��KΣ]Ecj*�'K�M��MGx��R�T1�#QꡥG��5�:�z�L��4u6z��\"j\"T�KuN֣�G�g\$jFSܨ�Q2��H��\"�MT��%R��Hz��\$�,�w�Re.\$r�z�)��Ԧ�-Q���J���ʪ@԰�=R&/�Iʕ1�*]T���7���Q��D&өqN�_(�q�c[Tw�QR�崜J�\0n��T���.��956c�܌�Sz�H���7�R�}�Sr8�N���\"b�T��Q�5MN���#����ES§-H��7\"�T��_S�}G�̕?*yԩ��S�P*�5#���܍�T:�]Pʟ�C*�ԉ�T:�-K8�5C����R�--MȾ�H��� �'T���H���H���ы�T���R���,���܋GTک-SJ��M*�ԩ�UTکmMH��M���>�gSD�5M�R���H�wU\"��K8��R���ڌ�U*�-U*��n¾T�IR�,t�Z���Y�IUF�51���W)v�k�_KƫpJ�5Zj�ů�R�4r\n�^jI�CK����}Uʓ_��ԛ��O�=N�R*�F-��R��%W���c��\\�aV>�EYj��d���ëUά�WX�5*�Ջ��Uy��Z��1k�ը�7V��R\\H�5h*�U���UƧM[���k�vո�3V�}[(�5W�zո�iB�O��1��T���V�;�[��pR�Gu�;T@0>\0��/I���W`�]��\0���8��P��]��1m*��ǍyUz�mW��|�ݓ[��֯�]J�ш��U������Z*�5\\j����Z��`Z�5~��E�W��4Z��5h�Q�^�cXZ��S��1o�V��U&��T��5}cU^��X��dm*���kUu��SfG=[��j�sտ��X�Kc\n�iR�H�i#��uWt��������X�cĹ��U���rڢ�UZ�Շ�NE���X���4��ud�E�eV^��K��n��V8�sX¥�f��/�hJ�-J]ӂ������zO��<Eh�\$勓���\0K��<bw��>���N�\")]b�	�+z�.cS.�iF�	���QNQ���V*������O[X�nx��P	k��oN��}<aO�Iߓ�h���T;�r񉉤�VD6Q�;z�]j�~'�:�[Iv��7^ʑ����j�w[������ņ�:u �Ds#���\\w�<n|*�h�m�Kv;Y҈��3�]��^#�Z�j�gy�jħY,�%;3������.�W\"��\$�3>gڜ���Ϧ�V�T�Zj�hY�j�kD*!�h&Xz�i���+GV��\"��Z�:Ҥ�+�NoG�Zjj�i�]ʞkO�_�֬ԐmjI����t��#�[�j\rn�����n��Z�_,���g�Ě�:���9����[L2�W=T��0��f�\0P�U6\ns%7isY�?��u�3���nb5�����X|G~l�&�k���M��������y�S��)�]�ܭr��ٸ�������?�}u'n0W-ι��b��Ǫ���k?�vQ�7��}p\n�����ٮZ*�9)��5ޕZW�-ZB���:��㫊W�\0WZfp�Gp���ٮ:�Fp����U��SN/��\\��%s9�S{� �8��Z�as�ۓ�+�N^��9�M�{�P5�� �Q���J���y����;����z����Y�V �3�:�D�I���+����19M;�������V���\rQ{��ծ���+��F�CLĹ�N���Ԉ�\\��)\$i���N'\0���P����]X�^�s1�f�&�\"'<O���̡�L\0�\"�@���%�6��UA�1�i(z��݁�\r�Ղ��bZ��+IQO�3���\r=*ĉ��)�!����`��h��,ЫmGPC��A��ٲ�A��(ZŰ%�t�,h/���i��k���XEJ6�ID�Ȭ\"�\n�aU- ��\nv�y��_���ګ�k	a�B<�V�D�/P���a��)9L�(Z��8�vvù�k	�o�ZXk���|�&�.�東C�����`�1�]7&ę+�H�CBcX�B7xX�|1��0��a�6��ubpJLǅ�(���mbl�8I�*R��@tk0�����xX���;�� al]4s�t��Ū�0�c�'��l�`8M�8����D4w`p?@706g̈~K�\r�� �P���bh�\"&��\n�q�PD����\$�(�0QP<�����Q�!X��x��5���R�`w/2�2#���� `���1�/�܁\r���:²����B7�V7Z��gMY�H3� ��b�	Z��J���G�w�gl�^�-�R-!�l�7̲L��ư<1 �QC/ղh��)�W�6C	�*d��6]VK!m����05G\$�R��4��=Cw&[��YP��dɚ�')VK,�5e�\r���K+�1�X)b�e)��uF2A#E�&g~�e�y�fp5�lYl�Ԝ5�����\n�m}`�(�M �Pl9Y��f����]�Vl-4�é����>`��/��fPE�i�\0k�v�\0�fhS0�&�¦lͼ�#fu���5	i%�:Fd��9��؀G<�	{�}��s[7\0�Ξ3�ft:+.Ȕ�p�>�ձ�@!Pas6q,���1bǬŋ�ZK���-��ar`�?RxX�鑡�V���#Ĥ�z�; �D���H��1��6D`��Y�`�R�P֋>-�!\$�����~π���`>���h�0�1����&\0�h���I�wl�Z�\$�\\\r��8�~,�\n�o_��B2D����a1��ǩ�=�v<�kF�p`�`�kBF�6� ����h��T T֎�	�@?dr�剀J�H@1�G�dn��w���%��JG��0b�Tf]m(�k�qg\\���������ш3vk'�^d��AX��~�W�Vs�*�ʱ�d��M����@?���}�6\\��m9<��i�ݧ��Ԭh�^s}�-�[K�s�q�b��-��OORm8\$�yw��##��@❷\0��ؤ 5F7����X\n��|J�/-S�W!f�� 0�,w��D4١RU�T������ZX�=�`�W\$@�ԥ(�XG��Ҋ��a>�*�Y���\n��\n��!�[mj���0,mu�W@ FX������=��(���b��<!\n\"��83�'��(R��\n>��@�W�r!L�H�k�\r�E\nW��\r��'FH�\$�����m���=�ۥ{LY��&���_\0����#�䔀[�9\0�\"��@8�iK���0�l���p\ng��'qbF��y�c�l@9�(#JU�ݲ�{io���.{�ͳ4�V́�VnF�x���z� Q�ޞ\$kSa~ʨ0s@���%�y@��5H��N�ͦ�@�x�#	ܫ /\\��?<hڂ���I�T��:�3�\n%��"
        );
    } else {
        header("Content-Type: image/gif");
        switch ($_GET["file"]) {
            case "plus.gif":
                echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0!�����M��*)�o��) q��e���#��L�\0;";
                break;
            case "cross.gif":
                echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0#�����#\na�Fo~y�.�_wa��1�J�G�L�6]\0\0;";
                break;
            case "up.gif":
                echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����MQN\n�}��a8�y�aŶ�\0��\0;";
                break;
            case "down.gif":
                echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����M��*)�[W�\\��L&ٜƶ�\0��\0;";
                break;
            case "arrow.gif":
                echo "GIF89a\0\n\0�\0\0������!�\0\0\0,\0\0\0\0\0\n\0\0�i������Ӳ޻\0\0;";
                break;
        }
    }
    exit();
}
if ($_GET["script"] == "version") {
    $id = file_open_lock(get_temp_dir() . "/adminer.version");
    if ($id) {
        file_write_unlock(
            $id,
            serialize([
                "signature" => $_POST["signature"],
                "version" => $_POST["version"],
            ])
        );
    }
    exit();
}
global $b,
    $g,
    $m,
    $ec,
    $mc,
    $wc,
    $n,
    $kd,
    $qd,
    $ba,
    $Rd,
    $x,
    $ca,
    $me,
    $qf,
    $bg,
    $Hh,
    $vd,
    $oi,
    $ui,
    $U,
    $Ii,
    $ia;
if (!$_SERVER["REQUEST_URI"]) {
    $_SERVER["REQUEST_URI"] = $_SERVER["ORIG_PATH_INFO"];
}
if (!strpos($_SERVER["REQUEST_URI"], '?') && $_SERVER["QUERY_STRING"] != "") {
    $_SERVER["REQUEST_URI"] .= "?$_SERVER[QUERY_STRING]";
}
if ($_SERVER["HTTP_X_FORWARDED_PREFIX"]) {
    $_SERVER["REQUEST_URI"] =
        $_SERVER["HTTP_X_FORWARDED_PREFIX"] . $_SERVER["REQUEST_URI"];
}
$ba =
    ($_SERVER["HTTPS"] && strcasecmp($_SERVER["HTTPS"], "off")) ||
    ini_bool("session.cookie_secure");
@ini_set("session.use_trans_sid", false);
if (!defined("SID")) {
    session_cache_limiter("");
    session_name("adminer_sid");
    $Of = [0, preg_replace('~\?.*~', '', $_SERVER["REQUEST_URI"]), "", $ba];
    if (version_compare(PHP_VERSION, '5.2.0') >= 0) {
        $Of[] = true;
    }
    call_user_func_array('session_set_cookie_params', $Of);
    session_start();
}
remove_slashes([&$_GET, &$_POST, &$_COOKIE], $Vc);
if (get_magic_quotes_runtime()) {
    set_magic_quotes_runtime(false);
}
@set_time_limit(0);
@ini_set("zend.ze1_compatibility_mode", false);
@ini_set("precision", 15);
function get_lang()
{
    return 'en';
}
function lang($ti, $hf = null)
{
    if (is_array($ti)) {
        $eg = $hf == 1 ? 0 : 1;
        $ti = $ti[$eg];
    }
    $ti = str_replace("%d", "%s", $ti);
    $hf = format_number($hf);
    return sprintf($ti, $hf);
}
if (extension_loaded('pdo')) {
    class Min_PDO extends PDO
    {
        var $_result, $server_info, $affected_rows, $errno, $error;
        function __construct()
        {
            global $b;
            $eg = array_search("SQL", $b->operators);
            if ($eg !== false) {
                unset($b->operators[$eg]);
            }
        }
        function dsn($jc, $V, $E, $yf = [])
        {
            try {
                parent::__construct($jc, $V, $E, $yf);
            } catch (Exception $Ac) {
                auth_error(h($Ac->getMessage()));
            }
            $this->setAttribute(13, ['Min_PDOStatement']);
            $this->server_info = @$this->getAttribute(4);
        }
        function query($F, $Ci = false)
        {
            $G = parent::query($F);
            $this->error = "";
            if (!$G) {
                list(, $this->errno, $this->error) = $this->errorInfo();
                if (!$this->error) {
                    $this->error = 'Unknown error.';
                }
                return false;
            }
            $this->store_result($G);
            return $G;
        }
        function multi_query($F)
        {
            return $this->_result = $this->query($F);
        }
        function store_result($G = null)
        {
            if (!$G) {
                $G = $this->_result;
                if (!$G) {
                    return false;
                }
            }
            if ($G->columnCount()) {
                $G->num_rows = $G->rowCount();
                return $G;
            }
            $this->affected_rows = $G->rowCount();
            return true;
        }
        function next_result()
        {
            if (!$this->_result) {
                return false;
            }
            $this->_result->_offset = 0;
            return @$this->_result->nextRowset();
        }
        function result($F, $o = 0)
        {
            $G = $this->query($F);
            if (!$G) {
                return false;
            }
            $I = $G->fetch();
            return $I[$o];
        }
    }
    class Min_PDOStatement extends PDOStatement
    {
        var $_offset = 0,
            $num_rows;
        function fetch_assoc()
        {
            return $this->fetch(2);
        }
        function fetch_row()
        {
            return $this->fetch(3);
        }
        function fetch_field()
        {
            $I = (object) $this->getColumnMeta($this->_offset++);
            $I->orgtable = $I->table;
            $I->orgname = $I->name;
            $I->charsetnr = in_array("blob", (array) $I->flags) ? 63 : 0;
            return $I;
        }
    }
}
$ec = [];
class Min_SQL
{
    var $_conn;
    function __construct($g)
    {
        $this->_conn = $g;
    }
    function select($Q, $K, $Z, $nd, $_f = [], $z = 1, $D = 0, $mg = false)
    {
        global $b, $x;
        $Yd = count($nd) < count($K);
        $F = $b->selectQueryBuild($K, $Z, $nd, $_f, $z, $D);
        if (!$F) {
            $F =
                "SELECT" .
                limit(
                    ($_GET["page"] != "last" &&
                    $z != "" &&
                    $nd &&
                    $Yd &&
                    $x == "sql"
                        ? "SQL_CALC_FOUND_ROWS "
                        : "") .
                        implode(", ", $K) .
                        "\nFROM " .
                        table($Q),
                    ($Z ? "\nWHERE " . implode(" AND ", $Z) : "") .
                        ($nd && $Yd ? "\nGROUP BY " . implode(", ", $nd) : "") .
                        ($_f ? "\nORDER BY " . implode(", ", $_f) : ""),
                    $z != "" ? +$z : null,
                    $D ? $z * $D : 0,
                    "\n"
                );
        }
        $Dh = microtime(true);
        $H = $this->_conn->query($F);
        if ($mg) {
            echo $b->selectQuery($F, $Dh, !$H);
        }
        return $H;
    }
    function delete($Q, $wg, $z = 0)
    {
        $F = "FROM " . table($Q);
        return queries("DELETE" . ($z ? limit1($Q, $F, $wg) : " $F$wg"));
    }
    function update($Q, $N, $wg, $z = 0, $L = "\n")
    {
        $Vi = [];
        foreach ($N as $y => $X) {
            $Vi[] = "$y = $X";
        }
        $F = table($Q) . " SET$L" . implode(",$L", $Vi);
        return queries("UPDATE" . ($z ? limit1($Q, $F, $wg, $L) : " $F$wg"));
    }
    function insert($Q, $N)
    {
        return queries(
            "INSERT INTO " .
                table($Q) .
                ($N
                    ? " (" .
                        implode(", ", array_keys($N)) .
                        ")\nVALUES (" .
                        implode(", ", $N) .
                        ")"
                    : " DEFAULT VALUES")
        );
    }
    function insertUpdate($Q, $J, $kg)
    {
        return false;
    }
    function begin()
    {
        return queries("BEGIN");
    }
    function commit()
    {
        return queries("COMMIT");
    }
    function rollback()
    {
        return queries("ROLLBACK");
    }
    function slowQuery($F, $fi)
    {
    }
    function convertSearch($u, $X, $o)
    {
        return $u;
    }
    function value($X, $o)
    {
        return method_exists($this->_conn, 'value')
            ? $this->_conn->value($X, $o)
            : (is_resource($X)
                ? stream_get_contents($X)
                : $X);
    }
    function quoteBinary($Yg)
    {
        return q($Yg);
    }
    function warnings()
    {
        return '';
    }
    function tableHelp($B)
    {
    }
}
$ec["sqlite"] = "SQLite 3";
$ec["sqlite2"] = "SQLite 2";
if (isset($_GET["sqlite"]) || isset($_GET["sqlite2"])) {
    $hg = [isset($_GET["sqlite"]) ? "SQLite3" : "SQLite", "PDO_SQLite"];
    define("DRIVER", isset($_GET["sqlite"]) ? "sqlite" : "sqlite2");
    if (class_exists(isset($_GET["sqlite"]) ? "SQLite3" : "SQLiteDatabase")) {
        if (isset($_GET["sqlite"])) {
            class Min_SQLite
            {
                var $extension = "SQLite3",
                    $server_info,
                    $affected_rows,
                    $errno,
                    $error,
                    $_link;
                function __construct($Uc)
                {
                    $this->_link = new SQLite3($Uc);
                    $Yi = $this->_link->version();
                    $this->server_info = $Yi["versionString"];
                }
                function query($F)
                {
                    $G = @$this->_link->query($F);
                    $this->error = "";
                    if (!$G) {
                        $this->errno = $this->_link->lastErrorCode();
                        $this->error = $this->_link->lastErrorMsg();
                        return false;
                    } elseif ($G->numColumns()) {
                        return new Min_Result($G);
                    }
                    $this->affected_rows = $this->_link->changes();
                    return true;
                }
                function quote($P)
                {
                    return is_utf8($P)
                        ? "'" . $this->_link->escapeString($P) . "'"
                        : "x'" . reset(unpack('H*', $P)) . "'";
                }
                function store_result()
                {
                    return $this->_result;
                }
                function result($F, $o = 0)
                {
                    $G = $this->query($F);
                    if (!is_object($G)) {
                        return false;
                    }
                    $I = $G->_result->fetchArray();
                    return $I[$o];
                }
            }
            class Min_Result
            {
                var $_result,
                    $_offset = 0,
                    $num_rows;
                function __construct($G)
                {
                    $this->_result = $G;
                }
                function fetch_assoc()
                {
                    return $this->_result->fetchArray(SQLITE3_ASSOC);
                }
                function fetch_row()
                {
                    return $this->_result->fetchArray(SQLITE3_NUM);
                }
                function fetch_field()
                {
                    $e = $this->_offset++;
                    $T = $this->_result->columnType($e);
                    return (object) [
                        "name" => $this->_result->columnName($e),
                        "type" => $T,
                        "charsetnr" => $T == SQLITE3_BLOB ? 63 : 0,
                    ];
                }
                function __desctruct()
                {
                    return $this->_result->finalize();
                }
            }
        } else {
            class Min_SQLite
            {
                var $extension = "SQLite",
                    $server_info,
                    $affected_rows,
                    $error,
                    $_link;
                function __construct($Uc)
                {
                    $this->server_info = sqlite_libversion();
                    $this->_link = new SQLiteDatabase($Uc);
                }
                function query($F, $Ci = false)
                {
                    $Re = $Ci ? "unbufferedQuery" : "query";
                    $G = @$this->_link->$Re($F, SQLITE_BOTH, $n);
                    $this->error = "";
                    if (!$G) {
                        $this->error = $n;
                        return false;
                    } elseif ($G === true) {
                        $this->affected_rows = $this->changes();
                        return true;
                    }
                    return new Min_Result($G);
                }
                function quote($P)
                {
                    return "'" . sqlite_escape_string($P) . "'";
                }
                function store_result()
                {
                    return $this->_result;
                }
                function result($F, $o = 0)
                {
                    $G = $this->query($F);
                    if (!is_object($G)) {
                        return false;
                    }
                    $I = $G->_result->fetch();
                    return $I[$o];
                }
            }
            class Min_Result
            {
                var $_result,
                    $_offset = 0,
                    $num_rows;
                function __construct($G)
                {
                    $this->_result = $G;
                    if (method_exists($G, 'numRows')) {
                        $this->num_rows = $G->numRows();
                    }
                }
                function fetch_assoc()
                {
                    $I = $this->_result->fetch(SQLITE_ASSOC);
                    if (!$I) {
                        return false;
                    }
                    $H = [];
                    foreach ($I as $y => $X) {
                        $H[$y[0] == '"' ? idf_unescape($y) : $y] = $X;
                    }
                    return $H;
                }
                function fetch_row()
                {
                    return $this->_result->fetch(SQLITE_NUM);
                }
                function fetch_field()
                {
                    $B = $this->_result->fieldName($this->_offset++);
                    $ag = '(\[.*]|"(?:[^"]|"")*"|(.+))';
                    if (preg_match("~^($ag\\.)?$ag\$~", $B, $A)) {
                        $Q = $A[3] != "" ? $A[3] : idf_unescape($A[2]);
                        $B = $A[5] != "" ? $A[5] : idf_unescape($A[4]);
                    }
                    return (object) [
                        "name" => $B,
                        "orgname" => $B,
                        "orgtable" => $Q,
                    ];
                }
            }
        }
    } elseif (extension_loaded("pdo_sqlite")) {
        class Min_SQLite extends Min_PDO
        {
            var $extension = "PDO_SQLite";
            function __construct($Uc)
            {
                $this->dsn(DRIVER . ":$Uc", "", "");
            }
        }
    }
    if (class_exists("Min_SQLite")) {
        class Min_DB extends Min_SQLite
        {
            function __construct()
            {
                parent::__construct(":memory:");
                $this->query("PRAGMA foreign_keys = 1");
            }
            function select_db($Uc)
            {
                if (
                    is_readable($Uc) &&
                    $this->query(
                        "ATTACH " .
                            $this->quote(
                                preg_match("~(^[/\\\\]|:)~", $Uc)
                                    ? $Uc
                                    : dirname($_SERVER["SCRIPT_FILENAME"]) .
                                        "/$Uc"
                            ) .
                            " AS a"
                    )
                ) {
                    parent::__construct($Uc);
                    $this->query("PRAGMA foreign_keys = 1");
                    return true;
                }
                return false;
            }
            function multi_query($F)
            {
                return $this->_result = $this->query($F);
            }
            function next_result()
            {
                return false;
            }
        }
    }
    class Min_Driver extends Min_SQL
    {
        function insertUpdate($Q, $J, $kg)
        {
            $Vi = [];
            foreach ($J as $N) {
                $Vi[] = "(" . implode(", ", $N) . ")";
            }
            return queries(
                "REPLACE INTO " .
                    table($Q) .
                    " (" .
                    implode(", ", array_keys(reset($J))) .
                    ") VALUES\n" .
                    implode(",\n", $Vi)
            );
        }
        function tableHelp($B)
        {
            if ($B == "sqlite_sequence") {
                return "fileformat2.html#seqtab";
            }
            if ($B == "sqlite_master") {
                return "fileformat2.html#$B";
            }
        }
    }
    function idf_escape($u)
    {
        return '"' . str_replace('"', '""', $u) . '"';
    }
    function table($u)
    {
        return idf_escape($u);
    }
    function connect()
    {
        global $b;
        list(, , $E) = $b->credentials();
        if ($E != "") {
            return 'Database does not support password.';
        }
        return new Min_DB();
    }
    function get_databases()
    {
        return [];
    }
    function limit($F, $Z, $z, $C = 0, $L = " ")
    {
        return " $F$Z" .
            ($z !== null ? $L . "LIMIT $z" . ($C ? " OFFSET $C" : "") : "");
    }
    function limit1($Q, $F, $Z, $L = "\n")
    {
        global $g;
        return preg_match('~^INTO~', $F) ||
            $g->result(
                "SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')"
            )
            ? limit($F, $Z, 1, 0, $L)
            : " $F WHERE rowid = (SELECT rowid FROM " .
                    table($Q) .
                    $Z .
                    $L .
                    "LIMIT 1)";
    }
    function db_collation($l, $pb)
    {
        global $g;
        return $g->result("PRAGMA encoding");
    }
    function engines()
    {
        return [];
    }
    function logged_user()
    {
        return get_current_user();
    }
    function tables_list()
    {
        return get_key_vals(
            "SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name = 'sqlite_sequence'), name"
        );
    }
    function count_tables($k)
    {
        return [];
    }
    function table_status($B = "")
    {
        global $g;
        $H = [];
        foreach (
            get_rows(
                "SELECT name AS Name, type AS Engine, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') " .
                    ($B != "" ? "AND name = " . q($B) : "ORDER BY name")
            )
            as $I
        ) {
            $I["Rows"] = $g->result(
                "SELECT COUNT(*) FROM " . idf_escape($I["Name"])
            );
            $H[$I["Name"]] = $I;
        }
        foreach (get_rows("SELECT * FROM sqlite_sequence", null, "") as $I) {
            $H[$I["name"]]["Auto_increment"] = $I["seq"];
        }
        return $B != "" ? $H[$B] : $H;
    }
    function is_view($R)
    {
        return $R["Engine"] == "view";
    }
    function fk_support($R)
    {
        global $g;
        return !$g->result(
            "SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')"
        );
    }
    function fields($Q)
    {
        global $g;
        $H = [];
        $kg = "";
        foreach (get_rows("PRAGMA table_info(" . table($Q) . ")") as $I) {
            $B = $I["name"];
            $T = strtolower($I["type"]);
            $Tb = $I["dflt_value"];
            $H[$B] = [
                "field" => $B,
                "type" => preg_match('~int~i', $T)
                    ? "integer"
                    : (preg_match('~char|clob|text~i', $T)
                        ? "text"
                        : (preg_match('~blob~i', $T)
                            ? "blob"
                            : (preg_match('~real|floa|doub~i', $T)
                                ? "real"
                                : "numeric"))),
                "full_type" => $T,
                "default" => preg_match("~'(.*)'~", $Tb, $A)
                    ? str_replace("''", "'", $A[1])
                    : ($Tb == "NULL"
                        ? null
                        : $Tb),
                "null" => !$I["notnull"],
                "privileges" => ["select" => 1, "insert" => 1, "update" => 1],
                "primary" => $I["pk"],
            ];
            if ($I["pk"]) {
                if ($kg != "") {
                    $H[$kg]["auto_increment"] = false;
                } elseif (preg_match('~^integer$~i', $T)) {
                    $H[$B]["auto_increment"] = true;
                }
                $kg = $B;
            }
        }
        $zh = $g->result(
            "SELECT sql FROM sqlite_master WHERE type = 'table' AND name = " .
                q($Q)
        );
        preg_match_all(
            '~(("[^"]*+")+|[a-z0-9_]+)\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i',
            $zh,
            $De,
            PREG_SET_ORDER
        );
        foreach ($De as $A) {
            $B = str_replace('""', '"', preg_replace('~^"|"$~', '', $A[1]));
            if ($H[$B]) {
                $H[$B]["collation"] = trim($A[3], "'");
            }
        }
        return $H;
    }
    function indexes($Q, $h = null)
    {
        global $g;
        if (!is_object($h)) {
            $h = $g;
        }
        $H = [];
        $zh = $h->result(
            "SELECT sql FROM sqlite_master WHERE type = 'table' AND name = " .
                q($Q)
        );
        if (
            preg_match(
                '~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',
                $zh,
                $A
            )
        ) {
            $H[""] = [
                "type" => "PRIMARY",
                "columns" => [],
                "lengths" => [],
                "descs" => [],
            ];
            preg_match_all(
                '~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',
                $A[1],
                $De,
                PREG_SET_ORDER
            );
            foreach ($De as $A) {
                $H[""]["columns"][] = idf_unescape($A[2]) . $A[4];
                $H[""]["descs"][] = preg_match('~DESC~i', $A[5]) ? '1' : null;
            }
        }
        if (!$H) {
            foreach (fields($Q) as $B => $o) {
                if ($o["primary"]) {
                    $H[""] = [
                        "type" => "PRIMARY",
                        "columns" => [$B],
                        "lengths" => [],
                        "descs" => [null],
                    ];
                }
            }
        }
        $Bh = get_key_vals(
            "SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = " .
                q($Q),
            $h
        );
        foreach (get_rows("PRAGMA index_list(" . table($Q) . ")", $h) as $I) {
            $B = $I["name"];
            $v = ["type" => $I["unique"] ? "UNIQUE" : "INDEX"];
            $v["lengths"] = [];
            $v["descs"] = [];
            foreach (
                get_rows("PRAGMA index_info(" . idf_escape($B) . ")", $h)
                as $Xg
            ) {
                $v["columns"][] = $Xg["name"];
                $v["descs"][] = null;
            }
            if (
                preg_match(
                    '~^CREATE( UNIQUE)? INDEX ' .
                        preg_quote(
                            idf_escape($B) . ' ON ' . idf_escape($Q),
                            '~'
                        ) .
                        ' \((.*)\)$~i',
                    $Bh[$B],
                    $Hg
                )
            ) {
                preg_match_all('/("[^"]*+")+( DESC)?/', $Hg[2], $De);
                foreach ($De[2] as $y => $X) {
                    if ($X) {
                        $v["descs"][$y] = '1';
                    }
                }
            }
            if (
                !$H[""] ||
                $v["type"] != "UNIQUE" ||
                $v["columns"] != $H[""]["columns"] ||
                $v["descs"] != $H[""]["descs"] ||
                !preg_match("~^sqlite_~", $B)
            ) {
                $H[$B] = $v;
            }
        }
        return $H;
    }
    function foreign_keys($Q)
    {
        $H = [];
        foreach (get_rows("PRAGMA foreign_key_list(" . table($Q) . ")") as $I) {
            $q = &$H[$I["id"]];
            if (!$q) {
                $q = $I;
            }
            $q["source"][] = $I["from"];
            $q["target"][] = $I["to"];
        }
        return $H;
    }
    function view($B)
    {
        global $g;
        return [
            "select" => preg_replace(
                '~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU',
                '',
                $g->result(
                    "SELECT sql FROM sqlite_master WHERE name = " . q($B)
                )
            ),
        ];
    }
    function collations()
    {
        return isset($_GET["create"])
            ? get_vals("PRAGMA collation_list", 1)
            : [];
    }
    function information_schema($l)
    {
        return false;
    }
    function error()
    {
        global $g;
        return h($g->error);
    }
    function check_sqlite_name($B)
    {
        global $g;
        $Kc = "db|sdb|sqlite";
        if (!preg_match("~^[^\\0]*\\.($Kc)\$~", $B)) {
            $g->error = sprintf(
                'Please use one of the extensions %s.',
                str_replace("|", ", ", $Kc)
            );
            return false;
        }
        return true;
    }
    function create_database($l, $d)
    {
        global $g;
        if (file_exists($l)) {
            $g->error = 'File exists.';
            return false;
        }
        if (!check_sqlite_name($l)) {
            return false;
        }
        try {
            $_ = new Min_SQLite($l);
        } catch (Exception $Ac) {
            $g->error = $Ac->getMessage();
            return false;
        }
        $_->query('PRAGMA encoding = "UTF-8"');
        $_->query('CREATE TABLE adminer (i)');
        $_->query('DROP TABLE adminer');
        return true;
    }
    function drop_databases($k)
    {
        global $g;
        $g->__construct(":memory:");
        foreach ($k as $l) {
            if (!@unlink($l)) {
                $g->error = 'File exists.';
                return false;
            }
        }
        return true;
    }
    function rename_database($B, $d)
    {
        global $g;
        if (!check_sqlite_name($B)) {
            return false;
        }
        $g->__construct(":memory:");
        $g->error = 'File exists.';
        return @rename(DB, $B);
    }
    function auto_increment()
    {
        return " PRIMARY KEY" . (DRIVER == "sqlite" ? " AUTOINCREMENT" : "");
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        global $g;
        $Oi = $Q == "" || $cd;
        foreach ($p as $o) {
            if ($o[0] != "" || !$o[1] || $o[2]) {
                $Oi = true;
                break;
            }
        }
        $c = [];
        $If = [];
        foreach ($p as $o) {
            if ($o[1]) {
                $c[] = $Oi ? $o[1] : "ADD " . implode($o[1]);
                if ($o[0] != "") {
                    $If[$o[0]] = $o[1][0];
                }
            }
        }
        if (!$Oi) {
            foreach ($c as $X) {
                if (!queries("ALTER TABLE " . table($Q) . " $X")) {
                    return false;
                }
            }
            if (
                $Q != $B &&
                !queries("ALTER TABLE " . table($Q) . " RENAME TO " . table($B))
            ) {
                return false;
            }
        } elseif (!recreate_table($Q, $B, $c, $If, $cd, $Ma)) {
            return false;
        }
        if ($Ma) {
            queries("BEGIN");
            queries(
                "UPDATE sqlite_sequence SET seq = $Ma WHERE name = " . q($B)
            );
            if (!$g->affected_rows) {
                queries(
                    "INSERT INTO sqlite_sequence (name, seq) VALUES (" .
                        q($B) .
                        ", $Ma)"
                );
            }
            queries("COMMIT");
        }
        return true;
    }
    function recreate_table($Q, $B, $p, $If, $cd, $Ma, $w = [])
    {
        global $g;
        if ($Q != "") {
            if (!$p) {
                foreach (fields($Q) as $y => $o) {
                    if ($w) {
                        $o["auto_increment"] = 0;
                    }
                    $p[] = process_field($o, $o);
                    $If[$y] = idf_escape($y);
                }
            }
            $lg = false;
            foreach ($p as $o) {
                if ($o[6]) {
                    $lg = true;
                }
            }
            $hc = [];
            foreach ($w as $y => $X) {
                if ($X[2] == "DROP") {
                    $hc[$X[1]] = true;
                    unset($w[$y]);
                }
            }
            foreach (indexes($Q) as $ge => $v) {
                $f = [];
                foreach ($v["columns"] as $y => $e) {
                    if (!$If[$e]) {
                        continue 2;
                    }
                    $f[] = $If[$e] . ($v["descs"][$y] ? " DESC" : "");
                }
                if (!$hc[$ge]) {
                    if ($v["type"] != "PRIMARY" || !$lg) {
                        $w[] = [$v["type"], $ge, $f];
                    }
                }
            }
            foreach ($w as $y => $X) {
                if ($X[0] == "PRIMARY") {
                    unset($w[$y]);
                    $cd[] = "  PRIMARY KEY (" . implode(", ", $X[2]) . ")";
                }
            }
            foreach (foreign_keys($Q) as $ge => $q) {
                foreach ($q["source"] as $y => $e) {
                    if (!$If[$e]) {
                        continue 2;
                    }
                    $q["source"][$y] = idf_unescape($If[$e]);
                }
                if (!isset($cd[" $ge"])) {
                    $cd[] = " " . format_foreign_key($q);
                }
            }
            queries("BEGIN");
        }
        foreach ($p as $y => $o) {
            $p[$y] = "  " . implode($o);
        }
        $p = array_merge($p, array_filter($cd));
        $Zh = $Q == $B ? "adminer_$B" : $B;
        if (
            !queries(
                "CREATE TABLE " .
                    table($Zh) .
                    " (\n" .
                    implode(",\n", $p) .
                    "\n)"
            )
        ) {
            return false;
        }
        if ($Q != "") {
            if (
                $If &&
                !queries(
                    "INSERT INTO " .
                        table($Zh) .
                        " (" .
                        implode(", ", $If) .
                        ") SELECT " .
                        implode(
                            ", ",
                            array_map('idf_escape', array_keys($If))
                        ) .
                        " FROM " .
                        table($Q)
                )
            ) {
                return false;
            }
            $_i = [];
            foreach (triggers($Q) as $yi => $gi) {
                $xi = trigger($yi);
                $_i[] =
                    "CREATE TRIGGER " .
                    idf_escape($yi) .
                    " " .
                    implode(" ", $gi) .
                    " ON " .
                    table($B) .
                    "\n$xi[Statement]";
            }
            $Ma = $Ma
                ? 0
                : $g->result(
                    "SELECT seq FROM sqlite_sequence WHERE name = " . q($Q)
                );
            if (
                !queries("DROP TABLE " . table($Q)) ||
                ($Q == $B &&
                    !queries(
                        "ALTER TABLE " . table($Zh) . " RENAME TO " . table($B)
                    )) ||
                !alter_indexes($B, $w)
            ) {
                return false;
            }
            if ($Ma) {
                queries(
                    "UPDATE sqlite_sequence SET seq = $Ma WHERE name = " . q($B)
                );
            }
            foreach ($_i as $xi) {
                if (!queries($xi)) {
                    return false;
                }
            }
            queries("COMMIT");
        }
        return true;
    }
    function index_sql($Q, $T, $B, $f)
    {
        return "CREATE $T " .
            ($T != "INDEX" ? "INDEX " : "") .
            idf_escape($B != "" ? $B : uniqid($Q . "_")) .
            " ON " .
            table($Q) .
            " $f";
    }
    function alter_indexes($Q, $c)
    {
        foreach ($c as $kg) {
            if ($kg[0] == "PRIMARY") {
                return recreate_table($Q, $Q, [], [], [], 0, $c);
            }
        }
        foreach (array_reverse($c) as $X) {
            if (
                !queries(
                    $X[2] == "DROP"
                        ? "DROP INDEX " . idf_escape($X[1])
                        : index_sql(
                            $Q,
                            $X[0],
                            $X[1],
                            "(" . implode(", ", $X[2]) . ")"
                        )
                )
            ) {
                return false;
            }
        }
        return true;
    }
    function truncate_tables($S)
    {
        return apply_queries("DELETE FROM", $S);
    }
    function drop_views($aj)
    {
        return apply_queries("DROP VIEW", $aj);
    }
    function drop_tables($S)
    {
        return apply_queries("DROP TABLE", $S);
    }
    function move_tables($S, $aj, $Xh)
    {
        return false;
    }
    function trigger($B)
    {
        global $g;
        if ($B == "") {
            return ["Statement" => "BEGIN\n\t;\nEND"];
        }
        $u = '(?:[^`"\s]+|`[^`]*`|"[^"]*")+';
        $zi = trigger_options();
        preg_match(
            "~^CREATE\\s+TRIGGER\\s*$u\\s*(" .
                implode("|", $zi["Timing"]) .
                ")\\s+([a-z]+)(?:\\s+OF\\s+($u))?\\s+ON\\s*$u\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is",
            $g->result(
                "SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = " .
                    q($B)
            ),
            $A
        );
        $jf = $A[3];
        return [
            "Timing" => strtoupper($A[1]),
            "Event" => strtoupper($A[2]) . ($jf ? " OF" : ""),
            "Of" => $jf[0] == '`' || $jf[0] == '"' ? idf_unescape($jf) : $jf,
            "Trigger" => $B,
            "Statement" => $A[4],
        ];
    }
    function triggers($Q)
    {
        $H = [];
        $zi = trigger_options();
        foreach (
            get_rows(
                "SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = " .
                    q($Q)
            )
            as $I
        ) {
            preg_match(
                '~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*(' .
                    implode("|", $zi["Timing"]) .
                    ')\s*(.*?)\s+ON\b~i',
                $I["sql"],
                $A
            );
            $H[$I["name"]] = [$A[1], $A[2]];
        }
        return $H;
    }
    function trigger_options()
    {
        return [
            "Timing" => ["BEFORE", "AFTER", "INSTEAD OF"],
            "Event" => ["INSERT", "UPDATE", "UPDATE OF", "DELETE"],
            "Type" => ["FOR EACH ROW"],
        ];
    }
    function begin()
    {
        return queries("BEGIN");
    }
    function last_id()
    {
        global $g;
        return $g->result("SELECT LAST_INSERT_ROWID()");
    }
    function explain($g, $F)
    {
        return $g->query("EXPLAIN QUERY PLAN $F");
    }
    function found_rows($R, $Z)
    {
    }
    function types()
    {
        return [];
    }
    function schemas()
    {
        return [];
    }
    function get_schema()
    {
        return "";
    }
    function set_schema($bh)
    {
        return true;
    }
    function create_sql($Q, $Ma, $Ih)
    {
        global $g;
        $H = $g->result(
            "SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = " .
                q($Q)
        );
        foreach (indexes($Q) as $B => $v) {
            if ($B == '') {
                continue;
            }
            $H .=
                ";\n\n" .
                index_sql(
                    $Q,
                    $v['type'],
                    $B,
                    "(" .
                        implode(", ", array_map('idf_escape', $v['columns'])) .
                        ")"
                );
        }
        return $H;
    }
    function truncate_sql($Q)
    {
        return "DELETE FROM " . table($Q);
    }
    function use_sql($j)
    {
    }
    function trigger_sql($Q)
    {
        return implode(
            get_vals(
                "SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = " .
                    q($Q)
            )
        );
    }
    function show_variables()
    {
        global $g;
        $H = [];
        foreach (
            [
                "auto_vacuum",
                "cache_size",
                "count_changes",
                "default_cache_size",
                "empty_result_callbacks",
                "encoding",
                "foreign_keys",
                "full_column_names",
                "fullfsync",
                "journal_mode",
                "journal_size_limit",
                "legacy_file_format",
                "locking_mode",
                "page_size",
                "max_page_count",
                "read_uncommitted",
                "recursive_triggers",
                "reverse_unordered_selects",
                "secure_delete",
                "short_column_names",
                "synchronous",
                "temp_store",
                "temp_store_directory",
                "schema_version",
                "integrity_check",
                "quick_check",
            ]
            as $y
        ) {
            $H[$y] = $g->result("PRAGMA $y");
        }
        return $H;
    }
    function show_status()
    {
        $H = [];
        foreach (get_vals("PRAGMA compile_options") as $xf) {
            list($y, $X) = explode("=", $xf, 2);
            $H[$y] = $X;
        }
        return $H;
    }
    function convert_field($o)
    {
    }
    function unconvert_field($o, $H)
    {
        return $H;
    }
    function support($Pc)
    {
        return preg_match(
            '~^(columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|trigger|variables|view|view_trigger)$~',
            $Pc
        );
    }
    $x = "sqlite";
    $U = [
        "integer" => 0,
        "real" => 0,
        "numeric" => 0,
        "text" => 0,
        "blob" => 0,
    ];
    $Hh = array_keys($U);
    $Ii = [];
    $vf = [
        "=",
        "<",
        ">",
        "<=",
        ">=",
        "!=",
        "LIKE",
        "LIKE %%",
        "IN",
        "IS NULL",
        "NOT LIKE",
        "NOT IN",
        "IS NOT NULL",
        "SQL",
    ];
    $kd = ["hex", "length", "lower", "round", "unixepoch", "upper"];
    $qd = [
        "avg",
        "count",
        "count distinct",
        "group_concat",
        "max",
        "min",
        "sum",
    ];
    $mc = [[], ["integer|real|numeric" => "+/-", "text" => "||"]];
}
$ec["pgsql"] = "PostgreSQL";
if (isset($_GET["pgsql"])) {
    $hg = ["PgSQL", "PDO_PgSQL"];
    define("DRIVER", "pgsql");
    if (extension_loaded("pgsql")) {
        class Min_DB
        {
            var $extension = "PgSQL",
                $_link,
                $_result,
                $_string,
                $_database = true,
                $server_info,
                $affected_rows,
                $error,
                $timeout;
            function _error($xc, $n)
            {
                if (ini_bool("html_errors")) {
                    $n = html_entity_decode(strip_tags($n));
                }
                $n = preg_replace('~^[^:]*: ~', '', $n);
                $this->error = $n;
            }
            function connect($M, $V, $E)
            {
                global $b;
                $l = $b->database();
                set_error_handler([$this, '_error']);
                $this->_string =
                    "host='" .
                    str_replace(":", "' port='", addcslashes($M, "'\\")) .
                    "' user='" .
                    addcslashes($V, "'\\") .
                    "' password='" .
                    addcslashes($E, "'\\") .
                    "'";
                $this->_link = @pg_connect(
                    "$this->_string dbname='" .
                        ($l != "" ? addcslashes($l, "'\\") : "postgres") .
                        "'",
                    PGSQL_CONNECT_FORCE_NEW
                );
                if (!$this->_link && $l != "") {
                    $this->_database = false;
                    $this->_link = @pg_connect(
                        "$this->_string dbname='postgres'",
                        PGSQL_CONNECT_FORCE_NEW
                    );
                }
                restore_error_handler();
                if ($this->_link) {
                    $Yi = pg_version($this->_link);
                    $this->server_info = $Yi["server"];
                    pg_set_client_encoding($this->_link, "UTF8");
                }
                return (bool) $this->_link;
            }
            function quote($P)
            {
                return "'" . pg_escape_string($this->_link, $P) . "'";
            }
            function value($X, $o)
            {
                return $o["type"] == "bytea" ? pg_unescape_bytea($X) : $X;
            }
            function quoteBinary($P)
            {
                return "'" . pg_escape_bytea($this->_link, $P) . "'";
            }
            function select_db($j)
            {
                global $b;
                if ($j == $b->database()) {
                    return $this->_database;
                }
                $H = @pg_connect(
                    "$this->_string dbname='" . addcslashes($j, "'\\") . "'",
                    PGSQL_CONNECT_FORCE_NEW
                );
                if ($H) {
                    $this->_link = $H;
                }
                return $H;
            }
            function close()
            {
                $this->_link = @pg_connect("$this->_string dbname='postgres'");
            }
            function query($F, $Ci = false)
            {
                $G = @pg_query($this->_link, $F);
                $this->error = "";
                if (!$G) {
                    $this->error = pg_last_error($this->_link);
                    $H = false;
                } elseif (!pg_num_fields($G)) {
                    $this->affected_rows = pg_affected_rows($G);
                    $H = true;
                } else {
                    $H = new Min_Result($G);
                }
                if ($this->timeout) {
                    $this->timeout = 0;
                    $this->query("RESET statement_timeout");
                }
                return $H;
            }
            function multi_query($F)
            {
                return $this->_result = $this->query($F);
            }
            function store_result()
            {
                return $this->_result;
            }
            function next_result()
            {
                return false;
            }
            function result($F, $o = 0)
            {
                $G = $this->query($F);
                if (!$G || !$G->num_rows) {
                    return false;
                }
                return pg_fetch_result($G->_result, 0, $o);
            }
            function warnings()
            {
                return h(pg_last_notice($this->_link));
            }
        }
        class Min_Result
        {
            var $_result,
                $_offset = 0,
                $num_rows;
            function __construct($G)
            {
                $this->_result = $G;
                $this->num_rows = pg_num_rows($G);
            }
            function fetch_assoc()
            {
                return pg_fetch_assoc($this->_result);
            }
            function fetch_row()
            {
                return pg_fetch_row($this->_result);
            }
            function fetch_field()
            {
                $e = $this->_offset++;
                $H = new stdClass();
                if (function_exists('pg_field_table')) {
                    $H->orgtable = pg_field_table($this->_result, $e);
                }
                $H->name = pg_field_name($this->_result, $e);
                $H->orgname = $H->name;
                $H->type = pg_field_type($this->_result, $e);
                $H->charsetnr = $H->type == "bytea" ? 63 : 0;
                return $H;
            }
            function __destruct()
            {
                pg_free_result($this->_result);
            }
        }
    } elseif (extension_loaded("pdo_pgsql")) {
        class Min_DB extends Min_PDO
        {
            var $extension = "PDO_PgSQL",
                $timeout;
            function connect($M, $V, $E)
            {
                global $b;
                $l = $b->database();
                $P =
                    "pgsql:host='" .
                    str_replace(":", "' port='", addcslashes($M, "'\\")) .
                    "' options='-c client_encoding=utf8'";
                $this->dsn(
                    "$P dbname='" .
                        ($l != "" ? addcslashes($l, "'\\") : "postgres") .
                        "'",
                    $V,
                    $E
                );
                return true;
            }
            function select_db($j)
            {
                global $b;
                return $b->database() == $j;
            }
            function quoteBinary($Yg)
            {
                return q($Yg);
            }
            function query($F, $Ci = false)
            {
                $H = parent::query($F, $Ci);
                if ($this->timeout) {
                    $this->timeout = 0;
                    parent::query("RESET statement_timeout");
                }
                return $H;
            }
            function warnings()
            {
                return '';
            }
            function close()
            {
            }
        }
    }
    class Min_Driver extends Min_SQL
    {
        function insertUpdate($Q, $J, $kg)
        {
            global $g;
            foreach ($J as $N) {
                $Ji = [];
                $Z = [];
                foreach ($N as $y => $X) {
                    $Ji[] = "$y = $X";
                    if (isset($kg[idf_unescape($y)])) {
                        $Z[] = "$y = $X";
                    }
                }
                if (
                    !(
                        ($Z &&
                            queries(
                                "UPDATE " .
                                    table($Q) .
                                    " SET " .
                                    implode(", ", $Ji) .
                                    " WHERE " .
                                    implode(" AND ", $Z)
                            ) &&
                            $g->affected_rows) ||
                        queries(
                            "INSERT INTO " .
                                table($Q) .
                                " (" .
                                implode(", ", array_keys($N)) .
                                ") VALUES (" .
                                implode(", ", $N) .
                                ")"
                        )
                    )
                ) {
                    return false;
                }
            }
            return true;
        }
        function slowQuery($F, $fi)
        {
            $this->_conn->query("SET statement_timeout = " . 1000 * $fi);
            $this->_conn->timeout = 1000 * $fi;
            return $F;
        }
        function convertSearch($u, $X, $o)
        {
            return preg_match(
                '~char|text' .
                    (!preg_match('~LIKE~', $X["op"])
                        ? '|date|time(stamp)?|boolean|uuid|' . number_type()
                        : '') .
                    '~',
                $o["type"]
            )
                ? $u
                : "CAST($u AS text)";
        }
        function quoteBinary($Yg)
        {
            return $this->_conn->quoteBinary($Yg);
        }
        function warnings()
        {
            return $this->_conn->warnings();
        }
        function tableHelp($B)
        {
            $we = [
                "information_schema" => "infoschema",
                "pg_catalog" => "catalog",
            ];
            $_ = $we[$_GET["ns"]];
            if ($_) {
                return "$_-" . str_replace("_", "-", $B) . ".html";
            }
        }
    }
    function idf_escape($u)
    {
        return '"' . str_replace('"', '""', $u) . '"';
    }
    function table($u)
    {
        return idf_escape($u);
    }
    function connect()
    {
        global $b, $U, $Hh;
        $g = new Min_DB();
        $Hb = $b->credentials();
        if ($g->connect($Hb[0], $Hb[1], $Hb[2])) {
            if (min_version(9, 0, $g)) {
                $g->query("SET application_name = 'Adminer'");
                if (min_version(9.2, 0, $g)) {
                    $Hh['Strings'][] = "json";
                    $U["json"] = 4294967295;
                    if (min_version(9.4, 0, $g)) {
                        $Hh['Strings'][] = "jsonb";
                        $U["jsonb"] = 4294967295;
                    }
                }
            }
            return $g;
        }
        return $g->error;
    }
    function get_databases()
    {
        return get_vals(
            "SELECT datname FROM pg_database WHERE has_database_privilege(datname, 'CONNECT') ORDER BY datname"
        );
    }
    function limit($F, $Z, $z, $C = 0, $L = " ")
    {
        return " $F$Z" .
            ($z !== null ? $L . "LIMIT $z" . ($C ? " OFFSET $C" : "") : "");
    }
    function limit1($Q, $F, $Z, $L = "\n")
    {
        return preg_match('~^INTO~', $F)
            ? limit($F, $Z, 1, 0, $L)
            : " $F" .
                    (is_view(table_status1($Q))
                        ? $Z
                        : " WHERE ctid = (SELECT ctid FROM " .
                            table($Q) .
                            $Z .
                            $L .
                            "LIMIT 1)");
    }
    function db_collation($l, $pb)
    {
        global $g;
        return $g->result("SHOW LC_COLLATE");
    }
    function engines()
    {
        return [];
    }
    function logged_user()
    {
        global $g;
        return $g->result("SELECT user");
    }
    function tables_list()
    {
        $F =
            "SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";
        if (support('materializedview')) {
            $F .= "
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";
        }
        $F .= "
ORDER BY 1";
        return get_key_vals($F);
    }
    function count_tables($k)
    {
        return [];
    }
    function table_status($B = "")
    {
        $H = [];
        foreach (
            get_rows(
                "SELECT c.relname AS \"Name\", CASE c.relkind WHEN 'r' THEN 'table' WHEN 'm' THEN 'materialized view' ELSE 'view' END AS \"Engine\", pg_relation_size(c.oid) AS \"Data_length\", pg_total_relation_size(c.oid) - pg_relation_size(c.oid) AS \"Index_length\", obj_description(c.oid, 'pg_class') AS \"Comment\", " .
                    (min_version(12)
                        ? "''"
                        : "CASE WHEN c.relhasoids THEN 'oid' ELSE '' END") .
                    " AS \"Oid\", c.reltuples as \"Rows\", n.nspname
FROM pg_class c
JOIN pg_namespace n ON(n.nspname = current_schema() AND n.oid = c.relnamespace)
WHERE relkind IN ('r', 'm', 'v', 'f')
" .
                    ($B != "" ? "AND relname = " . q($B) : "ORDER BY relname")
            )
            as $I
        ) {
            $H[$I["Name"]] = $I;
        }
        return $B != "" ? $H[$B] : $H;
    }
    function is_view($R)
    {
        return in_array($R["Engine"], ["view", "materialized view"]);
    }
    function fk_support($R)
    {
        return true;
    }
    function fields($Q)
    {
        $H = [];
        $Ca = [
            'timestamp without time zone' => 'timestamp',
            'timestamp with time zone' => 'timestamptz',
        ];
        $Dd = min_version(10) ? "(a.attidentity = 'd')::int" : '0';
        foreach (
            get_rows(
                "SELECT a.attname AS field, format_type(a.atttypid, a.atttypmod) AS full_type, pg_get_expr(d.adbin, d.adrelid) AS default, a.attnotnull::int, col_description(c.oid, a.attnum) AS comment, $Dd AS identity
FROM pg_class c
JOIN pg_namespace n ON c.relnamespace = n.oid
JOIN pg_attribute a ON c.oid = a.attrelid
LEFT JOIN pg_attrdef d ON c.oid = d.adrelid AND a.attnum = d.adnum
WHERE c.relname = " .
                    q($Q) .
                    "
AND n.nspname = current_schema()
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum"
            )
            as $I
        ) {
            preg_match(
                '~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',
                $I["full_type"],
                $A
            );
            list(, $T, $te, $I["length"], $wa, $Fa) = $A;
            $I["length"] .= $Fa;
            $eb = $T . $wa;
            if (isset($Ca[$eb])) {
                $I["type"] = $Ca[$eb];
                $I["full_type"] = $I["type"] . $te . $Fa;
            } else {
                $I["type"] = $T;
                $I["full_type"] = $I["type"] . $te . $wa . $Fa;
            }
            if ($I['identity']) {
                $I['default'] = 'GENERATED BY DEFAULT AS IDENTITY';
            }
            $I["null"] = !$I["attnotnull"];
            $I["auto_increment"] =
                $I['identity'] || preg_match('~^nextval\(~i', $I["default"]);
            $I["privileges"] = ["insert" => 1, "select" => 1, "update" => 1];
            if (preg_match('~(.+)::[^)]+(.*)~', $I["default"], $A)) {
                $I["default"] =
                    $A[1] == "NULL"
                        ? null
                        : ($A[1][0] == "'" ? idf_unescape($A[1]) : $A[1]) .
                            $A[2];
            }
            $H[$I["field"]] = $I;
        }
        return $H;
    }
    function indexes($Q, $h = null)
    {
        global $g;
        if (!is_object($h)) {
            $h = $g;
        }
        $H = [];
        $Qh = $h->result(
            "SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = " .
                q($Q)
        );
        $f = get_key_vals(
            "SELECT attnum, attname FROM pg_attribute WHERE attrelid = $Qh AND attnum > 0",
            $h
        );
        foreach (
            get_rows(
                "SELECT relname, indisunique::int, indisprimary::int, indkey, indoption , (indpred IS NOT NULL)::int as indispartial FROM pg_index i, pg_class ci WHERE i.indrelid = $Qh AND ci.oid = i.indexrelid",
                $h
            )
            as $I
        ) {
            $Ig = $I["relname"];
            $H[$Ig]["type"] = $I["indispartial"]
                ? "INDEX"
                : ($I["indisprimary"]
                    ? "PRIMARY"
                    : ($I["indisunique"]
                        ? "UNIQUE"
                        : "INDEX"));
            $H[$Ig]["columns"] = [];
            foreach (explode(" ", $I["indkey"]) as $Nd) {
                $H[$Ig]["columns"][] = $f[$Nd];
            }
            $H[$Ig]["descs"] = [];
            foreach (explode(" ", $I["indoption"]) as $Od) {
                $H[$Ig]["descs"][] = $Od & 1 ? '1' : null;
            }
            $H[$Ig]["lengths"] = [];
        }
        return $H;
    }
    function foreign_keys($Q)
    {
        global $qf;
        $H = [];
        foreach (
            get_rows(
                "SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = (SELECT pc.oid FROM pg_class AS pc INNER JOIN pg_namespace AS pn ON (pn.oid = pc.relnamespace) WHERE pc.relname = " .
                    q($Q) .
                    " AND pn.nspname = current_schema())
AND contype = 'f'::char
ORDER BY conkey, conname"
            )
            as $I
        ) {
            if (
                preg_match(
                    '~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',
                    $I['definition'],
                    $A
                )
            ) {
                $I['source'] = array_map('trim', explode(',', $A[1]));
                if (
                    preg_match(
                        '~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',
                        $A[2],
                        $Ce
                    )
                ) {
                    $I['ns'] = str_replace(
                        '""',
                        '"',
                        preg_replace('~^"(.+)"$~', '\1', $Ce[2])
                    );
                    $I['table'] = str_replace(
                        '""',
                        '"',
                        preg_replace('~^"(.+)"$~', '\1', $Ce[4])
                    );
                }
                $I['target'] = array_map('trim', explode(',', $A[3]));
                $I['on_delete'] = preg_match("~ON DELETE ($qf)~", $A[4], $Ce)
                    ? $Ce[1]
                    : 'NO ACTION';
                $I['on_update'] = preg_match("~ON UPDATE ($qf)~", $A[4], $Ce)
                    ? $Ce[1]
                    : 'NO ACTION';
                $H[$I['conname']] = $I;
            }
        }
        return $H;
    }
    function view($B)
    {
        global $g;
        return [
            "select" => trim(
                $g->result(
                    "SELECT pg_get_viewdef(" .
                        $g->result(
                            "SELECT oid FROM pg_class WHERE relname = " . q($B)
                        ) .
                        ")"
                )
            ),
        ];
    }
    function collations()
    {
        return [];
    }
    function information_schema($l)
    {
        return $l == "information_schema";
    }
    function error()
    {
        global $g;
        $H = h($g->error);
        if (preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s', $H, $A)) {
            $H =
                $A[1] .
                preg_replace(
                    '~((?:[^&]|&[^;]*;){' . strlen($A[3]) . '})(.*)~',
                    '\1<b>\2</b>',
                    $A[2]
                ) .
                $A[4];
        }
        return nl_br($H);
    }
    function create_database($l, $d)
    {
        return queries(
            "CREATE DATABASE " .
                idf_escape($l) .
                ($d ? " ENCODING " . idf_escape($d) : "")
        );
    }
    function drop_databases($k)
    {
        global $g;
        $g->close();
        return apply_queries("DROP DATABASE", $k, 'idf_escape');
    }
    function rename_database($B, $d)
    {
        return queries(
            "ALTER DATABASE " . idf_escape(DB) . " RENAME TO " . idf_escape($B)
        );
    }
    function auto_increment()
    {
        return "";
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = [];
        $vg = [];
        if ($Q != "" && $Q != $B) {
            $vg[] = "ALTER TABLE " . table($Q) . " RENAME TO " . table($B);
        }
        foreach ($p as $o) {
            $e = idf_escape($o[0]);
            $X = $o[1];
            if (!$X) {
                $c[] = "DROP $e";
            } else {
                $Ui = $X[5];
                unset($X[5]);
                if (isset($X[6]) && $o[0] == "") {
                    $X[1] = ($X[1] == "bigint" ? " big" : " ") . "serial";
                }
                if ($o[0] == "") {
                    $c[] = ($Q != "" ? "ADD " : "  ") . implode($X);
                } else {
                    if ($e != $X[0]) {
                        $vg[] =
                            "ALTER TABLE " . table($B) . " RENAME $e TO $X[0]";
                    }
                    $c[] = "ALTER $e TYPE$X[1]";
                    if (!$X[6]) {
                        $c[] =
                            "ALTER $e " . ($X[3] ? "SET$X[3]" : "DROP DEFAULT");
                        $c[] =
                            "ALTER $e " .
                            ($X[2] == " NULL" ? "DROP NOT" : "SET") .
                            $X[2];
                    }
                }
                if ($o[0] != "" || $Ui != "") {
                    $vg[] =
                        "COMMENT ON COLUMN " .
                        table($B) .
                        ".$X[0] IS " .
                        ($Ui != "" ? substr($Ui, 9) : "''");
                }
            }
        }
        $c = array_merge($c, $cd);
        if ($Q == "") {
            array_unshift(
                $vg,
                "CREATE TABLE " .
                    table($B) .
                    " (\n" .
                    implode(",\n", $c) .
                    "\n)"
            );
        } elseif ($c) {
            array_unshift(
                $vg,
                "ALTER TABLE " . table($Q) . "\n" . implode(",\n", $c)
            );
        }
        if ($Q != "" || $ub != "") {
            $vg[] = "COMMENT ON TABLE " . table($B) . " IS " . q($ub);
        }
        if ($Ma != "") {
        }
        foreach ($vg as $F) {
            if (!queries($F)) {
                return false;
            }
        }
        return true;
    }
    function alter_indexes($Q, $c)
    {
        $i = [];
        $fc = [];
        $vg = [];
        foreach ($c as $X) {
            if ($X[0] != "INDEX") {
                $i[] =
                    $X[2] == "DROP"
                        ? "\nDROP CONSTRAINT " . idf_escape($X[1])
                        : "\nADD" .
                            ($X[1] != ""
                                ? " CONSTRAINT " . idf_escape($X[1])
                                : "") .
                            " $X[0] " .
                            ($X[0] == "PRIMARY" ? "KEY " : "") .
                            "(" .
                            implode(", ", $X[2]) .
                            ")";
            } elseif ($X[2] == "DROP") {
                $fc[] = idf_escape($X[1]);
            } else {
                $vg[] =
                    "CREATE INDEX " .
                    idf_escape($X[1] != "" ? $X[1] : uniqid($Q . "_")) .
                    " ON " .
                    table($Q) .
                    " (" .
                    implode(", ", $X[2]) .
                    ")";
            }
        }
        if ($i) {
            array_unshift($vg, "ALTER TABLE " . table($Q) . implode(",", $i));
        }
        if ($fc) {
            array_unshift($vg, "DROP INDEX " . implode(", ", $fc));
        }
        foreach ($vg as $F) {
            if (!queries($F)) {
                return false;
            }
        }
        return true;
    }
    function truncate_tables($S)
    {
        return queries("TRUNCATE " . implode(", ", array_map('table', $S)));
        return true;
    }
    function drop_views($aj)
    {
        return drop_tables($aj);
    }
    function drop_tables($S)
    {
        foreach ($S as $Q) {
            $O = table_status($Q);
            if (
                !queries("DROP " . strtoupper($O["Engine"]) . " " . table($Q))
            ) {
                return false;
            }
        }
        return true;
    }
    function move_tables($S, $aj, $Xh)
    {
        foreach (array_merge($S, $aj) as $Q) {
            $O = table_status($Q);
            if (
                !queries(
                    "ALTER " .
                        strtoupper($O["Engine"]) .
                        " " .
                        table($Q) .
                        " SET SCHEMA " .
                        idf_escape($Xh)
                )
            ) {
                return false;
            }
        }
        return true;
    }
    function trigger($B, $Q = null)
    {
        if ($B == "") {
            return ["Statement" => "EXECUTE PROCEDURE ()"];
        }
        if ($Q === null) {
            $Q = $_GET['trigger'];
        }
        $J = get_rows(
            'SELECT t.trigger_name AS "Trigger", t.action_timing AS "Timing", (SELECT STRING_AGG(event_manipulation, \' OR \') FROM information_schema.triggers WHERE event_object_table = t.event_object_table AND trigger_name = t.trigger_name ) AS "Events", t.event_manipulation AS "Event", \'FOR EACH \' || t.action_orientation AS "Type", t.action_statement AS "Statement" FROM information_schema.triggers t WHERE t.event_object_table = ' .
                q($Q) .
                ' AND t.trigger_name = ' .
                q($B)
        );
        return reset($J);
    }
    function triggers($Q)
    {
        $H = [];
        foreach (
            get_rows(
                "SELECT * FROM information_schema.triggers WHERE event_object_table = " .
                    q($Q)
            )
            as $I
        ) {
            $H[$I["trigger_name"]] = [
                $I["action_timing"],
                $I["event_manipulation"],
            ];
        }
        return $H;
    }
    function trigger_options()
    {
        return [
            "Timing" => ["BEFORE", "AFTER"],
            "Event" => ["INSERT", "UPDATE", "DELETE"],
            "Type" => ["FOR EACH ROW", "FOR EACH STATEMENT"],
        ];
    }
    function routine($B, $T)
    {
        $J = get_rows(
            'SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = ' . q($B)
        );
        $H = $J[0];
        $H["returns"] = ["type" => $H["type_udt_name"]];
        $H["fields"] = get_rows(
            'SELECT parameter_name AS field, data_type AS type, character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = ' .
                q($B) .
                '
ORDER BY ordinal_position'
        );
        return $H;
    }
    function routines()
    {
        return get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()
ORDER BY SPECIFIC_NAME');
    }
    function routine_languages()
    {
        return get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");
    }
    function routine_id($B, $I)
    {
        $H = [];
        foreach ($I["fields"] as $o) {
            $H[] = $o["type"];
        }
        return idf_escape($B) . "(" . implode(", ", $H) . ")";
    }
    function last_id()
    {
        return 0;
    }
    function explain($g, $F)
    {
        return $g->query("EXPLAIN $F");
    }
    function found_rows($R, $Z)
    {
        global $g;
        if (
            preg_match(
                "~ rows=([0-9]+)~",
                $g->result(
                    "EXPLAIN SELECT * FROM " .
                        idf_escape($R["Name"]) .
                        ($Z ? " WHERE " . implode(" AND ", $Z) : "")
                ),
                $Hg
            )
        ) {
            return $Hg[1];
        }
        return false;
    }
    function types()
    {
        return get_vals("SELECT typname
FROM pg_type
WHERE typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema())
AND typtype IN ('b','d','e')
AND typelem = 0");
    }
    function schemas()
    {
        return get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");
    }
    function get_schema()
    {
        global $g;
        return $g->result("SELECT current_schema()");
    }
    function set_schema($ah, $h = null)
    {
        global $g, $U, $Hh;
        if (!$h) {
            $h = $g;
        }
        $H = $h->query("SET search_path TO " . idf_escape($ah));
        foreach (types() as $T) {
            if (!isset($U[$T])) {
                $U[$T] = 0;
                $Hh['User types'][] = $T;
            }
        }
        return $H;
    }
    function create_sql($Q, $Ma, $Ih)
    {
        global $g;
        $H = '';
        $Qg = [];
        $kh = [];
        $O = table_status($Q);
        $p = fields($Q);
        $w = indexes($Q);
        ksort($w);
        $Zc = foreign_keys($Q);
        ksort($Zc);
        if (!$O || empty($p)) {
            return false;
        }
        $H =
            "CREATE TABLE " .
            idf_escape($O['nspname']) .
            "." .
            idf_escape($O['Name']) .
            " (\n    ";
        foreach ($p as $Rc => $o) {
            $Rf =
                idf_escape($o['field']) .
                ' ' .
                $o['full_type'] .
                default_value($o) .
                ($o['attnotnull'] ? " NOT NULL" : "");
            $Qg[] = $Rf;
            if (preg_match('~nextval\(\'([^\']+)\'\)~', $o['default'], $De)) {
                $jh = $De[1];
                $yh = reset(
                    get_rows(
                        min_version(10)
                            ? "SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = " .
                                q($jh)
                            : "SELECT * FROM $jh"
                    )
                );
                $kh[] =
                    ($Ih == "DROP+CREATE"
                        ? "DROP SEQUENCE IF EXISTS $jh;\n"
                        : "") .
                    "CREATE SEQUENCE $jh INCREMENT $yh[increment_by] MINVALUE $yh[min_value] MAXVALUE $yh[max_value] START " .
                    ($Ma ? $yh['last_value'] : 1) .
                    " CACHE $yh[cache_value];";
            }
        }
        if (!empty($kh)) {
            $H = implode("\n\n", $kh) . "\n\n$H";
        }
        foreach ($w as $Id => $v) {
            switch ($v['type']) {
                case 'UNIQUE':
                    $Qg[] =
                        "CONSTRAINT " .
                        idf_escape($Id) .
                        " UNIQUE (" .
                        implode(', ', array_map('idf_escape', $v['columns'])) .
                        ")";
                    break;
                case 'PRIMARY':
                    $Qg[] =
                        "CONSTRAINT " .
                        idf_escape($Id) .
                        " PRIMARY KEY (" .
                        implode(', ', array_map('idf_escape', $v['columns'])) .
                        ")";
                    break;
            }
        }
        foreach ($Zc as $Yc => $Xc) {
            $Qg[] =
                "CONSTRAINT " .
                idf_escape($Yc) .
                " $Xc[definition] " .
                ($Xc['deferrable'] ? 'DEFERRABLE' : 'NOT DEFERRABLE');
        }
        $H .=
            implode(",\n    ", $Qg) .
            "\n) WITH (oids = " .
            ($O['Oid'] ? 'true' : 'false') .
            ");";
        foreach ($w as $Id => $v) {
            if ($v['type'] == 'INDEX') {
                $f = [];
                foreach ($v['columns'] as $y => $X) {
                    $f[] = idf_escape($X) . ($v['descs'][$y] ? " DESC" : "");
                }
                $H .=
                    "\n\nCREATE INDEX " .
                    idf_escape($Id) .
                    " ON " .
                    idf_escape($O['nspname']) .
                    "." .
                    idf_escape($O['Name']) .
                    " USING btree (" .
                    implode(', ', $f) .
                    ");";
            }
        }
        if ($O['Comment']) {
            $H .=
                "\n\nCOMMENT ON TABLE " .
                idf_escape($O['nspname']) .
                "." .
                idf_escape($O['Name']) .
                " IS " .
                q($O['Comment']) .
                ";";
        }
        foreach ($p as $Rc => $o) {
            if ($o['comment']) {
                $H .=
                    "\n\nCOMMENT ON COLUMN " .
                    idf_escape($O['nspname']) .
                    "." .
                    idf_escape($O['Name']) .
                    "." .
                    idf_escape($Rc) .
                    " IS " .
                    q($o['comment']) .
                    ";";
            }
        }
        return rtrim($H, ';');
    }
    function truncate_sql($Q)
    {
        return "TRUNCATE " . table($Q);
    }
    function trigger_sql($Q)
    {
        $O = table_status($Q);
        $H = "";
        foreach (triggers($Q) as $wi => $vi) {
            $xi = trigger($wi, $O['Name']);
            $H .=
                "\nCREATE TRIGGER " .
                idf_escape($xi['Trigger']) .
                " $xi[Timing] $xi[Events] ON " .
                idf_escape($O["nspname"]) .
                "." .
                idf_escape($O['Name']) .
                " $xi[Type] $xi[Statement];;\n";
        }
        return $H;
    }
    function use_sql($j)
    {
        return "\connect " . idf_escape($j);
    }
    function show_variables()
    {
        return get_key_vals("SHOW ALL");
    }
    function process_list()
    {
        return get_rows(
            "SELECT * FROM pg_stat_activity ORDER BY " .
                (min_version(9.2) ? "pid" : "procpid")
        );
    }
    function show_status()
    {
    }
    function convert_field($o)
    {
    }
    function unconvert_field($o, $H)
    {
        return $H;
    }
    function support($Pc)
    {
        return preg_match(
            '~^(database|table|columns|sql|indexes|descidx|comment|view|' .
                (min_version(9.3) ? 'materializedview|' : '') .
                'scheme|routine|processlist|sequence|trigger|type|variables|drop_col|kill|dump)$~',
            $Pc
        );
    }
    function kill_process($X)
    {
        return queries("SELECT pg_terminate_backend(" . number($X) . ")");
    }
    function connection_id()
    {
        return "SELECT pg_backend_pid()";
    }
    function max_connections()
    {
        global $g;
        return $g->result("SHOW max_connections");
    }
    $x = "pgsql";
    $U = [];
    $Hh = [];
    foreach (
        [
            'Numbers' => [
                "smallint" => 5,
                "integer" => 10,
                "bigint" => 19,
                "boolean" => 1,
                "numeric" => 0,
                "real" => 7,
                "double precision" => 16,
                "money" => 20,
            ],
            'Date and time' => [
                "date" => 13,
                "time" => 17,
                "timestamp" => 20,
                "timestamptz" => 21,
                "interval" => 0,
            ],
            'Strings' => [
                "character" => 0,
                "character varying" => 0,
                "text" => 0,
                "tsquery" => 0,
                "tsvector" => 0,
                "uuid" => 0,
                "xml" => 0,
            ],
            'Binary' => ["bit" => 0, "bit varying" => 0, "bytea" => 0],
            'Network' => [
                "cidr" => 43,
                "inet" => 43,
                "macaddr" => 17,
                "txid_snapshot" => 0,
            ],
            'Geometry' => [
                "box" => 0,
                "circle" => 0,
                "line" => 0,
                "lseg" => 0,
                "path" => 0,
                "point" => 0,
                "polygon" => 0,
            ],
        ]
        as $y => $X
    ) {
        $U += $X;
        $Hh[$y] = array_keys($X);
    }
    $Ii = [];
    $vf = [
        "=",
        "<",
        ">",
        "<=",
        ">=",
        "!=",
        "~",
        "!~",
        "LIKE",
        "LIKE %%",
        "ILIKE",
        "ILIKE %%",
        "IN",
        "IS NULL",
        "NOT LIKE",
        "NOT IN",
        "IS NOT NULL",
    ];
    $kd = ["char_length", "lower", "round", "to_hex", "to_timestamp", "upper"];
    $qd = ["avg", "count", "count distinct", "max", "min", "sum"];
    $mc = [
        ["char" => "md5", "date|time" => "now"],
        [
            number_type() => "+/-",
            "date|time" => "+ interval/- interval",
            "char|text" => "||",
        ],
    ];
}
$ec["oracle"] = "Oracle (beta)";
if (isset($_GET["oracle"])) {
    $hg = ["OCI8", "PDO_OCI"];
    define("DRIVER", "oracle");
    if (extension_loaded("oci8")) {
        class Min_DB
        {
            var $extension = "oci8",
                $_link,
                $_result,
                $server_info,
                $affected_rows,
                $errno,
                $error;
            function _error($xc, $n)
            {
                if (ini_bool("html_errors")) {
                    $n = html_entity_decode(strip_tags($n));
                }
                $n = preg_replace('~^[^:]*: ~', '', $n);
                $this->error = $n;
            }
            function connect($M, $V, $E)
            {
                $this->_link = @oci_new_connect($V, $E, $M, "AL32UTF8");
                if ($this->_link) {
                    $this->server_info = oci_server_version($this->_link);
                    return true;
                }
                $n = oci_error();
                $this->error = $n["message"];
                return false;
            }
            function quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }
            function select_db($j)
            {
                return true;
            }
            function query($F, $Ci = false)
            {
                $G = oci_parse($this->_link, $F);
                $this->error = "";
                if (!$G) {
                    $n = oci_error($this->_link);
                    $this->errno = $n["code"];
                    $this->error = $n["message"];
                    return false;
                }
                set_error_handler([$this, '_error']);
                $H = @oci_execute($G);
                restore_error_handler();
                if ($H) {
                    if (oci_num_fields($G)) {
                        return new Min_Result($G);
                    }
                    $this->affected_rows = oci_num_rows($G);
                }
                return $H;
            }
            function multi_query($F)
            {
                return $this->_result = $this->query($F);
            }
            function store_result()
            {
                return $this->_result;
            }
            function next_result()
            {
                return false;
            }
            function result($F, $o = 1)
            {
                $G = $this->query($F);
                if (!is_object($G) || !oci_fetch($G->_result)) {
                    return false;
                }
                return oci_result($G->_result, $o);
            }
        }
        class Min_Result
        {
            var $_result,
                $_offset = 1,
                $num_rows;
            function __construct($G)
            {
                $this->_result = $G;
            }
            function _convert($I)
            {
                foreach ((array) $I as $y => $X) {
                    if (is_a($X, 'OCI-Lob')) {
                        $I[$y] = $X->load();
                    }
                }
                return $I;
            }
            function fetch_assoc()
            {
                return $this->_convert(oci_fetch_assoc($this->_result));
            }
            function fetch_row()
            {
                return $this->_convert(oci_fetch_row($this->_result));
            }
            function fetch_field()
            {
                $e = $this->_offset++;
                $H = new stdClass();
                $H->name = oci_field_name($this->_result, $e);
                $H->orgname = $H->name;
                $H->type = oci_field_type($this->_result, $e);
                $H->charsetnr = preg_match("~raw|blob|bfile~", $H->type)
                    ? 63
                    : 0;
                return $H;
            }
            function __destruct()
            {
                oci_free_statement($this->_result);
            }
        }
    } elseif (extension_loaded("pdo_oci")) {
        class Min_DB extends Min_PDO
        {
            var $extension = "PDO_OCI";
            function connect($M, $V, $E)
            {
                $this->dsn("oci:dbname=//$M;charset=AL32UTF8", $V, $E);
                return true;
            }
            function select_db($j)
            {
                return true;
            }
        }
    }
    class Min_Driver extends Min_SQL
    {
        function begin()
        {
            return true;
        }
    }
    function idf_escape($u)
    {
        return '"' . str_replace('"', '""', $u) . '"';
    }
    function table($u)
    {
        return idf_escape($u);
    }
    function connect()
    {
        global $b;
        $g = new Min_DB();
        $Hb = $b->credentials();
        if ($g->connect($Hb[0], $Hb[1], $Hb[2])) {
            return $g;
        }
        return $g->error;
    }
    function get_databases()
    {
        return get_vals("SELECT tablespace_name FROM user_tablespaces");
    }
    function limit($F, $Z, $z, $C = 0, $L = " ")
    {
        return $C
            ? " * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $F$Z) t WHERE rownum <= " .
                    ($z + $C) .
                    ") WHERE rnum > $C"
            : ($z !== null
                ? " * FROM (SELECT $F$Z) WHERE rownum <= " . ($z + $C)
                : " $F$Z");
    }
    function limit1($Q, $F, $Z, $L = "\n")
    {
        return " $F$Z";
    }
    function db_collation($l, $pb)
    {
        global $g;
        return $g->result(
            "SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'"
        );
    }
    function engines()
    {
        return [];
    }
    function logged_user()
    {
        global $g;
        return $g->result("SELECT USER FROM DUAL");
    }
    function tables_list()
    {
        return get_key_vals(
            "SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = " .
                q(DB) .
                "
UNION SELECT view_name, 'view' FROM user_views
ORDER BY 1"
        );
    }
    function count_tables($k)
    {
        return [];
    }
    function table_status($B = "")
    {
        $H = [];
        $ch = q($B);
        foreach (
            get_rows(
                'SELECT table_name "Name", \'table\' "Engine", avg_row_len * num_rows "Data_length", num_rows "Rows" FROM all_tables WHERE tablespace_name = ' .
                    q(DB) .
                    ($B != "" ? " AND table_name = $ch" : "") .
                    "
UNION SELECT view_name, 'view', 0, 0 FROM user_views" .
                    ($B != "" ? " WHERE view_name = $ch" : "") .
                    "
ORDER BY 1"
            )
            as $I
        ) {
            if ($B != "") {
                return $I;
            }
            $H[$I["Name"]] = $I;
        }
        return $H;
    }
    function is_view($R)
    {
        return $R["Engine"] == "view";
    }
    function fk_support($R)
    {
        return true;
    }
    function fields($Q)
    {
        $H = [];
        foreach (
            get_rows(
                "SELECT * FROM all_tab_columns WHERE table_name = " .
                    q($Q) .
                    " ORDER BY column_id"
            )
            as $I
        ) {
            $T = $I["DATA_TYPE"];
            $te = "$I[DATA_PRECISION],$I[DATA_SCALE]";
            if ($te == ",") {
                $te = $I["DATA_LENGTH"];
            }
            $H[$I["COLUMN_NAME"]] = [
                "field" => $I["COLUMN_NAME"],
                "full_type" => $T . ($te ? "($te)" : ""),
                "type" => strtolower($T),
                "length" => $te,
                "default" => $I["DATA_DEFAULT"],
                "null" => $I["NULLABLE"] == "Y",
                "privileges" => ["insert" => 1, "select" => 1, "update" => 1],
            ];
        }
        return $H;
    }
    function indexes($Q, $h = null)
    {
        $H = [];
        foreach (
            get_rows(
                "SELECT uic.*, uc.constraint_type
FROM user_ind_columns uic
LEFT JOIN user_constraints uc ON uic.index_name = uc.constraint_name AND uic.table_name = uc.table_name
WHERE uic.table_name = " .
                    q($Q) .
                    "
ORDER BY uc.constraint_type, uic.column_position",
                $h
            )
            as $I
        ) {
            $Id = $I["INDEX_NAME"];
            $H[$Id]["type"] =
                $I["CONSTRAINT_TYPE"] == "P"
                    ? "PRIMARY"
                    : ($I["CONSTRAINT_TYPE"] == "U"
                        ? "UNIQUE"
                        : "INDEX");
            $H[$Id]["columns"][] = $I["COLUMN_NAME"];
            $H[$Id]["lengths"][] =
                $I["CHAR_LENGTH"] && $I["CHAR_LENGTH"] != $I["COLUMN_LENGTH"]
                    ? $I["CHAR_LENGTH"]
                    : null;
            $H[$Id]["descs"][] = $I["DESCEND"] ? '1' : null;
        }
        return $H;
    }
    function view($B)
    {
        $J = get_rows(
            'SELECT text "select" FROM user_views WHERE view_name = ' . q($B)
        );
        return reset($J);
    }
    function collations()
    {
        return [];
    }
    function information_schema($l)
    {
        return false;
    }
    function error()
    {
        global $g;
        return h($g->error);
    }
    function explain($g, $F)
    {
        $g->query("EXPLAIN PLAN FOR $F");
        return $g->query("SELECT * FROM plan_table");
    }
    function found_rows($R, $Z)
    {
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = $fc = [];
        foreach ($p as $o) {
            $X = $o[1];
            if ($X && $o[0] != "" && idf_escape($o[0]) != $X[0]) {
                queries(
                    "ALTER TABLE " .
                        table($Q) .
                        " RENAME COLUMN " .
                        idf_escape($o[0]) .
                        " TO $X[0]"
                );
            }
            if ($X) {
                $c[] =
                    ($Q != "" ? ($o[0] != "" ? "MODIFY (" : "ADD (") : "  ") .
                    implode($X) .
                    ($Q != "" ? ")" : "");
            } else {
                $fc[] = idf_escape($o[0]);
            }
        }
        if ($Q == "") {
            return queries(
                "CREATE TABLE " .
                    table($B) .
                    " (\n" .
                    implode(",\n", $c) .
                    "\n)"
            );
        }
        return (!$c ||
            queries("ALTER TABLE " . table($Q) . "\n" . implode("\n", $c))) &&
            (!$fc ||
                queries(
                    "ALTER TABLE " .
                        table($Q) .
                        " DROP (" .
                        implode(", ", $fc) .
                        ")"
                )) &&
            ($Q == $B ||
                queries(
                    "ALTER TABLE " . table($Q) . " RENAME TO " . table($B)
                ));
    }
    function foreign_keys($Q)
    {
        $H = [];
        $F =
            "SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = " . q($Q);
        foreach (get_rows($F) as $I) {
            $H[$I['NAME']] = [
                "db" => $I['DEST_DB'],
                "table" => $I['DEST_TABLE'],
                "source" => [$I['SRC_COLUMN']],
                "target" => [$I['DEST_COLUMN']],
                "on_delete" => $I['ON_DELETE'],
                "on_update" => null,
            ];
        }
        return $H;
    }
    function truncate_tables($S)
    {
        return apply_queries("TRUNCATE TABLE", $S);
    }
    function drop_views($aj)
    {
        return apply_queries("DROP VIEW", $aj);
    }
    function drop_tables($S)
    {
        return apply_queries("DROP TABLE", $S);
    }
    function last_id()
    {
        return 0;
    }
    function schemas()
    {
        return get_vals(
            "SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX'))"
        );
    }
    function get_schema()
    {
        global $g;
        return $g->result(
            "SELECT sys_context('USERENV', 'SESSION_USER') FROM dual"
        );
    }
    function set_schema($bh, $h = null)
    {
        global $g;
        if (!$h) {
            $h = $g;
        }
        return $h->query(
            "ALTER SESSION SET CURRENT_SCHEMA = " . idf_escape($bh)
        );
    }
    function show_variables()
    {
        return get_key_vals('SELECT name, display_value FROM v$parameter');
    }
    function process_list()
    {
        return get_rows('SELECT sess.process AS "process", sess.username AS "user", sess.schemaname AS "schema", sess.status AS "status", sess.wait_class AS "wait_class", sess.seconds_in_wait AS "seconds_in_wait", sql.sql_text AS "sql_text", sess.machine AS "machine", sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');
    }
    function show_status()
    {
        $J = get_rows('SELECT * FROM v$instance');
        return reset($J);
    }
    function convert_field($o)
    {
    }
    function unconvert_field($o, $H)
    {
        return $H;
    }
    function support($Pc)
    {
        return preg_match(
            '~^(columns|database|drop_col|indexes|descidx|processlist|scheme|sql|status|table|variables|view|view_trigger)$~',
            $Pc
        );
    }
    $x = "oracle";
    $U = [];
    $Hh = [];
    foreach (
        [
            'Numbers' => [
                "number" => 38,
                "binary_float" => 12,
                "binary_double" => 21,
            ],
            'Date and time' => [
                "date" => 10,
                "timestamp" => 29,
                "interval year" => 12,
                "interval day" => 28,
            ],
            'Strings' => [
                "char" => 2000,
                "varchar2" => 4000,
                "nchar" => 2000,
                "nvarchar2" => 4000,
                "clob" => 4294967295,
                "nclob" => 4294967295,
            ],
            'Binary' => [
                "raw" => 2000,
                "long raw" => 2147483648,
                "blob" => 4294967295,
                "bfile" => 4294967296,
            ],
        ]
        as $y => $X
    ) {
        $U += $X;
        $Hh[$y] = array_keys($X);
    }
    $Ii = [];
    $vf = [
        "=",
        "<",
        ">",
        "<=",
        ">=",
        "!=",
        "LIKE",
        "LIKE %%",
        "IN",
        "IS NULL",
        "NOT LIKE",
        "NOT REGEXP",
        "NOT IN",
        "IS NOT NULL",
        "SQL",
    ];
    $kd = ["length", "lower", "round", "upper"];
    $qd = ["avg", "count", "count distinct", "max", "min", "sum"];
    $mc = [
        ["date" => "current_date", "timestamp" => "current_timestamp"],
        [
            "number|float|double" => "+/-",
            "date|timestamp" => "+ interval/- interval",
            "char|clob" => "||",
        ],
    ];
}
$ec["mssql"] = "MS SQL (beta)";
if (isset($_GET["mssql"])) {
    $hg = ["SQLSRV", "MSSQL", "PDO_DBLIB"];
    define("DRIVER", "mssql");
    if (extension_loaded("sqlsrv")) {
        class Min_DB
        {
            var $extension = "sqlsrv",
                $_link,
                $_result,
                $server_info,
                $affected_rows,
                $errno,
                $error;
            function _get_error()
            {
                $this->error = "";
                foreach (sqlsrv_errors() as $n) {
                    $this->errno = $n["code"];
                    $this->error .= "$n[message]\n";
                }
                $this->error = rtrim($this->error);
            }
            function connect($M, $V, $E)
            {
                global $b;
                $l = $b->database();
                $yb = ["UID" => $V, "PWD" => $E, "CharacterSet" => "UTF-8"];
                if ($l != "") {
                    $yb["Database"] = $l;
                }
                $this->_link = @sqlsrv_connect(
                    preg_replace('~:~', ',', $M),
                    $yb
                );
                if ($this->_link) {
                    $Pd = sqlsrv_server_info($this->_link);
                    $this->server_info = $Pd['SQLServerVersion'];
                } else {
                    $this->_get_error();
                }
                return (bool) $this->_link;
            }
            function quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }
            function select_db($j)
            {
                return $this->query("USE " . idf_escape($j));
            }
            function query($F, $Ci = false)
            {
                $G = sqlsrv_query($this->_link, $F);
                $this->error = "";
                if (!$G) {
                    $this->_get_error();
                    return false;
                }
                return $this->store_result($G);
            }
            function multi_query($F)
            {
                $this->_result = sqlsrv_query($this->_link, $F);
                $this->error = "";
                if (!$this->_result) {
                    $this->_get_error();
                    return false;
                }
                return true;
            }
            function store_result($G = null)
            {
                if (!$G) {
                    $G = $this->_result;
                }
                if (!$G) {
                    return false;
                }
                if (sqlsrv_field_metadata($G)) {
                    return new Min_Result($G);
                }
                $this->affected_rows = sqlsrv_rows_affected($G);
                return true;
            }
            function next_result()
            {
                return $this->_result
                    ? sqlsrv_next_result($this->_result)
                    : null;
            }
            function result($F, $o = 0)
            {
                $G = $this->query($F);
                if (!is_object($G)) {
                    return false;
                }
                $I = $G->fetch_row();
                return $I[$o];
            }
        }
        class Min_Result
        {
            var $_result,
                $_offset = 0,
                $_fields,
                $num_rows;
            function __construct($G)
            {
                $this->_result = $G;
            }
            function _convert($I)
            {
                foreach ((array) $I as $y => $X) {
                    if (is_a($X, 'DateTime')) {
                        $I[$y] = $X->format("Y-m-d H:i:s");
                    }
                }
                return $I;
            }
            function fetch_assoc()
            {
                return $this->_convert(
                    sqlsrv_fetch_array($this->_result, SQLSRV_FETCH_ASSOC)
                );
            }
            function fetch_row()
            {
                return $this->_convert(
                    sqlsrv_fetch_array($this->_result, SQLSRV_FETCH_NUMERIC)
                );
            }
            function fetch_field()
            {
                if (!$this->_fields) {
                    $this->_fields = sqlsrv_field_metadata($this->_result);
                }
                $o = $this->_fields[$this->_offset++];
                $H = new stdClass();
                $H->name = $o["Name"];
                $H->orgname = $o["Name"];
                $H->type = $o["Type"] == 1 ? 254 : 0;
                return $H;
            }
            function seek($C)
            {
                for ($s = 0; $s < $C; $s++) {
                    sqlsrv_fetch($this->_result);
                }
            }
            function __destruct()
            {
                sqlsrv_free_stmt($this->_result);
            }
        }
    } elseif (extension_loaded("mssql")) {
        class Min_DB
        {
            var $extension = "MSSQL",
                $_link,
                $_result,
                $server_info,
                $affected_rows,
                $error;
            function connect($M, $V, $E)
            {
                $this->_link = @mssql_connect($M, $V, $E);
                if ($this->_link) {
                    $G = $this->query(
                        "SELECT SERVERPROPERTY('ProductLevel'), SERVERPROPERTY('Edition')"
                    );
                    if ($G) {
                        $I = $G->fetch_row();
                        $this->server_info =
                            $this->result("sp_server_info 2", 2) .
                            " [$I[0]] $I[1]";
                    }
                } else {
                    $this->error = mssql_get_last_message();
                }
                return (bool) $this->_link;
            }
            function quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }
            function select_db($j)
            {
                return mssql_select_db($j);
            }
            function query($F, $Ci = false)
            {
                $G = @mssql_query($F, $this->_link);
                $this->error = "";
                if (!$G) {
                    $this->error = mssql_get_last_message();
                    return false;
                }
                if ($G === true) {
                    $this->affected_rows = mssql_rows_affected($this->_link);
                    return true;
                }
                return new Min_Result($G);
            }
            function multi_query($F)
            {
                return $this->_result = $this->query($F);
            }
            function store_result()
            {
                return $this->_result;
            }
            function next_result()
            {
                return mssql_next_result($this->_result->_result);
            }
            function result($F, $o = 0)
            {
                $G = $this->query($F);
                if (!is_object($G)) {
                    return false;
                }
                return mssql_result($G->_result, 0, $o);
            }
        }
        class Min_Result
        {
            var $_result,
                $_offset = 0,
                $_fields,
                $num_rows;
            function __construct($G)
            {
                $this->_result = $G;
                $this->num_rows = mssql_num_rows($G);
            }
            function fetch_assoc()
            {
                return mssql_fetch_assoc($this->_result);
            }
            function fetch_row()
            {
                return mssql_fetch_row($this->_result);
            }
            function num_rows()
            {
                return mssql_num_rows($this->_result);
            }
            function fetch_field()
            {
                $H = mssql_fetch_field($this->_result);
                $H->orgtable = $H->table;
                $H->orgname = $H->name;
                return $H;
            }
            function seek($C)
            {
                mssql_data_seek($this->_result, $C);
            }
            function __destruct()
            {
                mssql_free_result($this->_result);
            }
        }
    } elseif (extension_loaded("pdo_dblib")) {
        class Min_DB extends Min_PDO
        {
            var $extension = "PDO_DBLIB";
            function connect($M, $V, $E)
            {
                $this->dsn(
                    "dblib:charset=utf8;host=" .
                        str_replace(
                            ":",
                            ";unix_socket=",
                            preg_replace('~:(\d)~', ';port=\1', $M)
                        ),
                    $V,
                    $E
                );
                return true;
            }
            function select_db($j)
            {
                return $this->query("USE " . idf_escape($j));
            }
        }
    }
    class Min_Driver extends Min_SQL
    {
        function insertUpdate($Q, $J, $kg)
        {
            foreach ($J as $N) {
                $Ji = [];
                $Z = [];
                foreach ($N as $y => $X) {
                    $Ji[] = "$y = $X";
                    if (isset($kg[idf_unescape($y)])) {
                        $Z[] = "$y = $X";
                    }
                }
                if (
                    !queries(
                        "MERGE " .
                            table($Q) .
                            " USING (VALUES(" .
                            implode(", ", $N) .
                            ")) AS source (c" .
                            implode(", c", range(1, count($N))) .
                            ") ON " .
                            implode(" AND ", $Z) .
                            " WHEN MATCHED THEN UPDATE SET " .
                            implode(", ", $Ji) .
                            " WHEN NOT MATCHED THEN INSERT (" .
                            implode(", ", array_keys($N)) .
                            ") VALUES (" .
                            implode(", ", $N) .
                            ");"
                    )
                ) {
                    return false;
                }
            }
            return true;
        }
        function begin()
        {
            return queries("BEGIN TRANSACTION");
        }
    }
    function idf_escape($u)
    {
        return "[" . str_replace("]", "]]", $u) . "]";
    }
    function table($u)
    {
        return ($_GET["ns"] != "" ? idf_escape($_GET["ns"]) . "." : "") .
            idf_escape($u);
    }
    function connect()
    {
        global $b;
        $g = new Min_DB();
        $Hb = $b->credentials();
        if ($g->connect($Hb[0], $Hb[1], $Hb[2])) {
            return $g;
        }
        return $g->error;
    }
    function get_databases()
    {
        return get_vals(
            "SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')"
        );
    }
    function limit($F, $Z, $z, $C = 0, $L = " ")
    {
        return ($z !== null ? " TOP (" . ($z + $C) . ")" : "") . " $F$Z";
    }
    function limit1($Q, $F, $Z, $L = "\n")
    {
        return limit($F, $Z, 1, 0, $L);
    }
    function db_collation($l, $pb)
    {
        global $g;
        return $g->result(
            "SELECT collation_name FROM sys.databases WHERE name = " . q($l)
        );
    }
    function engines()
    {
        return [];
    }
    function logged_user()
    {
        global $g;
        return $g->result("SELECT SUSER_NAME()");
    }
    function tables_list()
    {
        return get_key_vals(
            "SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(" .
                q(get_schema()) .
                ") AND type IN ('S', 'U', 'V') ORDER BY name"
        );
    }
    function count_tables($k)
    {
        global $g;
        $H = [];
        foreach ($k as $l) {
            $g->select_db($l);
            $H[$l] = $g->result(
                "SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES"
            );
        }
        return $H;
    }
    function table_status($B = "")
    {
        $H = [];
        foreach (
            get_rows(
                "SELECT ao.name AS Name, ao.type_desc AS Engine, (SELECT value FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment FROM sys.all_objects AS ao WHERE schema_id = SCHEMA_ID(" .
                    q(get_schema()) .
                    ") AND type IN ('S', 'U', 'V') " .
                    ($B != "" ? "AND name = " . q($B) : "ORDER BY name")
            )
            as $I
        ) {
            if ($B != "") {
                return $I;
            }
            $H[$I["Name"]] = $I;
        }
        return $H;
    }
    function is_view($R)
    {
        return $R["Engine"] == "VIEW";
    }
    function fk_support($R)
    {
        return true;
    }
    function fields($Q)
    {
        $vb = get_key_vals(
            "SELECT objname, cast(value as varchar) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', " .
                q(get_schema()) .
                ", 'table', " .
                q($Q) .
                ", 'column', NULL)"
        );
        $H = [];
        foreach (
            get_rows(
                "SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name, t.name type, CAST(d.definition as text) [default]
FROM sys.all_columns c
JOIN sys.all_objects o ON c.object_id = o.object_id
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.parent_column_id
WHERE o.schema_id = SCHEMA_ID(" .
                    q(get_schema()) .
                    ") AND o.type IN ('S', 'U', 'V') AND o.name = " .
                    q($Q)
            )
            as $I
        ) {
            $T = $I["type"];
            $te = preg_match("~char|binary~", $T)
                ? $I["max_length"]
                : ($T == "decimal"
                    ? "$I[precision],$I[scale]"
                    : "");
            $H[$I["name"]] = [
                "field" => $I["name"],
                "full_type" => $T . ($te ? "($te)" : ""),
                "type" => $T,
                "length" => $te,
                "default" => $I["default"],
                "null" => $I["is_nullable"],
                "auto_increment" => $I["is_identity"],
                "collation" => $I["collation_name"],
                "privileges" => ["insert" => 1, "select" => 1, "update" => 1],
                "primary" => $I["is_identity"],
                "comment" => $vb[$I["name"]],
            ];
        }
        return $H;
    }
    function indexes($Q, $h = null)
    {
        $H = [];
        foreach (
            get_rows(
                "SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = " . q($Q),
                $h
            )
            as $I
        ) {
            $B = $I["name"];
            $H[$B]["type"] = $I["is_primary_key"]
                ? "PRIMARY"
                : ($I["is_unique"]
                    ? "UNIQUE"
                    : "INDEX");
            $H[$B]["lengths"] = [];
            $H[$B]["columns"][$I["key_ordinal"]] = $I["column_name"];
            $H[$B]["descs"][$I["key_ordinal"]] = $I["is_descending_key"]
                ? '1'
                : null;
        }
        return $H;
    }
    function view($B)
    {
        global $g;
        return [
            "select" => preg_replace(
                '~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU',
                '',
                $g->result(
                    "SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = " .
                        q($B)
                )
            ),
        ];
    }
    function collations()
    {
        $H = [];
        foreach (get_vals("SELECT name FROM fn_helpcollations()") as $d) {
            $H[preg_replace('~_.*~', '', $d)][] = $d;
        }
        return $H;
    }
    function information_schema($l)
    {
        return false;
    }
    function error()
    {
        global $g;
        return nl_br(h(preg_replace('~^(\[[^]]*])+~m', '', $g->error)));
    }
    function create_database($l, $d)
    {
        return queries(
            "CREATE DATABASE " .
                idf_escape($l) .
                (preg_match('~^[a-z0-9_]+$~i', $d) ? " COLLATE $d" : "")
        );
    }
    function drop_databases($k)
    {
        return queries(
            "DROP DATABASE " . implode(", ", array_map('idf_escape', $k))
        );
    }
    function rename_database($B, $d)
    {
        if (preg_match('~^[a-z0-9_]+$~i', $d)) {
            queries("ALTER DATABASE " . idf_escape(DB) . " COLLATE $d");
        }
        queries(
            "ALTER DATABASE " .
                idf_escape(DB) .
                " MODIFY NAME = " .
                idf_escape($B)
        );
        return true;
    }
    function auto_increment()
    {
        return " IDENTITY" .
            ($_POST["Auto_increment"] != ""
                ? "(" . number($_POST["Auto_increment"]) . ",1)"
                : "") .
            " PRIMARY KEY";
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = [];
        $vb = [];
        foreach ($p as $o) {
            $e = idf_escape($o[0]);
            $X = $o[1];
            if (!$X) {
                $c["DROP"][] = " COLUMN $e";
            } else {
                $X[1] = preg_replace("~( COLLATE )'(\\w+)'~", '\1\2', $X[1]);
                $vb[$o[0]] = $X[5];
                unset($X[5]);
                if ($o[0] == "") {
                    $c["ADD"][] =
                        "\n  " .
                        implode("", $X) .
                        ($Q == ""
                            ? substr($cd[$X[0]], 16 + strlen($X[0]))
                            : "");
                } else {
                    unset($X[6]);
                    if ($e != $X[0]) {
                        queries(
                            "EXEC sp_rename " .
                                q(table($Q) . ".$e") .
                                ", " .
                                q(idf_unescape($X[0])) .
                                ", 'COLUMN'"
                        );
                    }
                    $c["ALTER COLUMN " . implode("", $X)][] = "";
                }
            }
        }
        if ($Q == "") {
            return queries(
                "CREATE TABLE " .
                    table($B) .
                    " (" .
                    implode(",", (array) $c["ADD"]) .
                    "\n)"
            );
        }
        if ($Q != $B) {
            queries("EXEC sp_rename " . q(table($Q)) . ", " . q($B));
        }
        if ($cd) {
            $c[""] = $cd;
        }
        foreach ($c as $y => $X) {
            if (
                !queries(
                    "ALTER TABLE " . idf_escape($B) . " $y" . implode(",", $X)
                )
            ) {
                return false;
            }
        }
        foreach ($vb as $y => $X) {
            $ub = substr($X, 9);
            queries(
                "EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = " .
                    q(get_schema()) .
                    ", @level1type = N'Table',  @level1name = " .
                    q($B) .
                    ", @level2type = N'Column', @level2name = " .
                    q($y)
            );
            queries(
                "EXEC sp_addextendedproperty @name = N'MS_Description', @value = " .
                    $ub .
                    ", @level0type = N'Schema', @level0name = " .
                    q(get_schema()) .
                    ", @level1type = N'Table',  @level1name = " .
                    q($B) .
                    ", @level2type = N'Column', @level2name = " .
                    q($y)
            );
        }
        return true;
    }
    function alter_indexes($Q, $c)
    {
        $v = [];
        $fc = [];
        foreach ($c as $X) {
            if ($X[2] == "DROP") {
                if ($X[0] == "PRIMARY") {
                    $fc[] = idf_escape($X[1]);
                } else {
                    $v[] = idf_escape($X[1]) . " ON " . table($Q);
                }
            } elseif (
                !queries(
                    ($X[0] != "PRIMARY"
                        ? "CREATE $X[0] " .
                            ($X[0] != "INDEX" ? "INDEX " : "") .
                            idf_escape($X[1] != "" ? $X[1] : uniqid($Q . "_")) .
                            " ON " .
                            table($Q)
                        : "ALTER TABLE " . table($Q) . " ADD PRIMARY KEY") .
                        " (" .
                        implode(", ", $X[2]) .
                        ")"
                )
            ) {
                return false;
            }
        }
        return (!$v || queries("DROP INDEX " . implode(", ", $v))) &&
            (!$fc ||
                queries(
                    "ALTER TABLE " . table($Q) . " DROP " . implode(", ", $fc)
                ));
    }
    function last_id()
    {
        global $g;
        return $g->result("SELECT SCOPE_IDENTITY()");
    }
    function explain($g, $F)
    {
        $g->query("SET SHOWPLAN_ALL ON");
        $H = $g->query($F);
        $g->query("SET SHOWPLAN_ALL OFF");
        return $H;
    }
    function found_rows($R, $Z)
    {
    }
    function foreign_keys($Q)
    {
        $H = [];
        foreach (get_rows("EXEC sp_fkeys @fktable_name = " . q($Q)) as $I) {
            $q = &$H[$I["FK_NAME"]];
            $q["db"] = $I["PKTABLE_QUALIFIER"];
            $q["table"] = $I["PKTABLE_NAME"];
            $q["source"][] = $I["FKCOLUMN_NAME"];
            $q["target"][] = $I["PKCOLUMN_NAME"];
        }
        return $H;
    }
    function truncate_tables($S)
    {
        return apply_queries("TRUNCATE TABLE", $S);
    }
    function drop_views($aj)
    {
        return queries("DROP VIEW " . implode(", ", array_map('table', $aj)));
    }
    function drop_tables($S)
    {
        return queries("DROP TABLE " . implode(", ", array_map('table', $S)));
    }
    function move_tables($S, $aj, $Xh)
    {
        return apply_queries(
            "ALTER SCHEMA " . idf_escape($Xh) . " TRANSFER",
            array_merge($S, $aj)
        );
    }
    function trigger($B)
    {
        if ($B == "") {
            return [];
        }
        $J = get_rows(
            "SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = " . q($B)
        );
        $H = reset($J);
        if ($H) {
            $H["Statement"] = preg_replace('~^.+\s+AS\s+~isU', '', $H["text"]);
        }
        return $H;
    }
    function triggers($Q)
    {
        $H = [];
        foreach (
            get_rows(
                "SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = " . q($Q)
            )
            as $I
        ) {
            $H[$I["name"]] = [$I["Timing"], $I["Event"]];
        }
        return $H;
    }
    function trigger_options()
    {
        return [
            "Timing" => ["AFTER", "INSTEAD OF"],
            "Event" => ["INSERT", "UPDATE", "DELETE"],
            "Type" => ["AS"],
        ];
    }
    function schemas()
    {
        return get_vals("SELECT name FROM sys.schemas");
    }
    function get_schema()
    {
        global $g;
        if ($_GET["ns"] != "") {
            return $_GET["ns"];
        }
        return $g->result("SELECT SCHEMA_NAME()");
    }
    function set_schema($ah)
    {
        return true;
    }
    function use_sql($j)
    {
        return "USE " . idf_escape($j);
    }
    function show_variables()
    {
        return [];
    }
    function show_status()
    {
        return [];
    }
    function convert_field($o)
    {
    }
    function unconvert_field($o, $H)
    {
        return $H;
    }
    function support($Pc)
    {
        return preg_match(
            '~^(comment|columns|database|drop_col|indexes|descidx|scheme|sql|table|trigger|view|view_trigger)$~',
            $Pc
        );
    }
    $x = "mssql";
    $U = [];
    $Hh = [];
    foreach (
        [
            'Numbers' => [
                "tinyint" => 3,
                "smallint" => 5,
                "int" => 10,
                "bigint" => 20,
                "bit" => 1,
                "decimal" => 0,
                "real" => 12,
                "float" => 53,
                "smallmoney" => 10,
                "money" => 20,
            ],
            'Date and time' => [
                "date" => 10,
                "smalldatetime" => 19,
                "datetime" => 19,
                "datetime2" => 19,
                "time" => 8,
                "datetimeoffset" => 10,
            ],
            'Strings' => [
                "char" => 8000,
                "varchar" => 8000,
                "text" => 2147483647,
                "nchar" => 4000,
                "nvarchar" => 4000,
                "ntext" => 1073741823,
            ],
            'Binary' => [
                "binary" => 8000,
                "varbinary" => 8000,
                "image" => 2147483647,
            ],
        ]
        as $y => $X
    ) {
        $U += $X;
        $Hh[$y] = array_keys($X);
    }
    $Ii = [];
    $vf = [
        "=",
        "<",
        ">",
        "<=",
        ">=",
        "!=",
        "LIKE",
        "LIKE %%",
        "IN",
        "IS NULL",
        "NOT LIKE",
        "NOT IN",
        "IS NOT NULL",
    ];
    $kd = ["len", "lower", "round", "upper"];
    $qd = ["avg", "count", "count distinct", "max", "min", "sum"];
    $mc = [
        ["date|time" => "getdate"],
        ["int|decimal|real|float|money|datetime" => "+/-", "char|text" => "+"],
    ];
}
$ec['firebird'] = 'Firebird (alpha)';
if (isset($_GET["firebird"])) {
    $hg = ["interbase"];
    define("DRIVER", "firebird");
    if (extension_loaded("interbase")) {
        class Min_DB
        {
            var $extension = "Firebird",
                $server_info,
                $affected_rows,
                $errno,
                $error,
                $_link,
                $_result;
            function connect($M, $V, $E)
            {
                $this->_link = ibase_connect($M, $V, $E);
                if ($this->_link) {
                    $Mi = explode(':', $M);
                    $this->service_link = ibase_service_attach($Mi[0], $V, $E);
                    $this->server_info = ibase_server_info(
                        $this->service_link,
                        IBASE_SVC_SERVER_VERSION
                    );
                } else {
                    $this->errno = ibase_errcode();
                    $this->error = ibase_errmsg();
                }
                return (bool) $this->_link;
            }
            function quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }
            function select_db($j)
            {
                return $j == "domain";
            }
            function query($F, $Ci = false)
            {
                $G = ibase_query($F, $this->_link);
                if (!$G) {
                    $this->errno = ibase_errcode();
                    $this->error = ibase_errmsg();
                    return false;
                }
                $this->error = "";
                if ($G === true) {
                    $this->affected_rows = ibase_affected_rows($this->_link);
                    return true;
                }
                return new Min_Result($G);
            }
            function multi_query($F)
            {
                return $this->_result = $this->query($F);
            }
            function store_result()
            {
                return $this->_result;
            }
            function next_result()
            {
                return false;
            }
            function result($F, $o = 0)
            {
                $G = $this->query($F);
                if (!$G || !$G->num_rows) {
                    return false;
                }
                $I = $G->fetch_row();
                return $I[$o];
            }
        }
        class Min_Result
        {
            var $num_rows,
                $_result,
                $_offset = 0;
            function __construct($G)
            {
                $this->_result = $G;
            }
            function fetch_assoc()
            {
                return ibase_fetch_assoc($this->_result);
            }
            function fetch_row()
            {
                return ibase_fetch_row($this->_result);
            }
            function fetch_field()
            {
                $o = ibase_field_info($this->_result, $this->_offset++);
                return (object) [
                    'name' => $o['name'],
                    'orgname' => $o['name'],
                    'type' => $o['type'],
                    'charsetnr' => $o['length'],
                ];
            }
            function __destruct()
            {
                ibase_free_result($this->_result);
            }
        }
    }
    class Min_Driver extends Min_SQL
    {
    }
    function idf_escape($u)
    {
        return '"' . str_replace('"', '""', $u) . '"';
    }
    function table($u)
    {
        return idf_escape($u);
    }
    function connect()
    {
        global $b;
        $g = new Min_DB();
        $Hb = $b->credentials();
        if ($g->connect($Hb[0], $Hb[1], $Hb[2])) {
            return $g;
        }
        return $g->error;
    }
    function get_databases($ad)
    {
        return ["domain"];
    }
    function limit($F, $Z, $z, $C = 0, $L = " ")
    {
        $H = '';
        $H .= $z !== null ? $L . "FIRST $z" . ($C ? " SKIP $C" : "") : "";
        $H .= " $F$Z";
        return $H;
    }
    function limit1($Q, $F, $Z, $L = "\n")
    {
        return limit($F, $Z, 1, 0, $L);
    }
    function db_collation($l, $pb)
    {
    }
    function engines()
    {
        return [];
    }
    function logged_user()
    {
        global $b;
        $Hb = $b->credentials();
        return $Hb[1];
    }
    function tables_list()
    {
        global $g;
        $F =
            'SELECT RDB$RELATION_NAME FROM rdb$relations WHERE rdb$system_flag = 0';
        $G = ibase_query($g->_link, $F);
        $H = [];
        while ($I = ibase_fetch_assoc($G)) {
            $H[$I['RDB$RELATION_NAME']] = 'table';
        }
        ksort($H);
        return $H;
    }
    function count_tables($k)
    {
        return [];
    }
    function table_status($B = "", $Oc = false)
    {
        global $g;
        $H = [];
        $Mb = tables_list();
        foreach ($Mb as $v => $X) {
            $v = trim($v);
            $H[$v] = ['Name' => $v, 'Engine' => 'standard'];
            if ($B == $v) {
                return $H[$v];
            }
        }
        return $H;
    }
    function is_view($R)
    {
        return false;
    }
    function fk_support($R)
    {
        return preg_match('~InnoDB|IBMDB2I~i', $R["Engine"]);
    }
    function fields($Q)
    {
        global $g;
        $H = [];
        $F =
            'SELECT r.RDB$FIELD_NAME AS field_name,
r.RDB$DESCRIPTION AS field_description,
r.RDB$DEFAULT_VALUE AS field_default_value,
r.RDB$NULL_FLAG AS field_not_null_constraint,
f.RDB$FIELD_LENGTH AS field_length,
f.RDB$FIELD_PRECISION AS field_precision,
f.RDB$FIELD_SCALE AS field_scale,
CASE f.RDB$FIELD_TYPE
WHEN 261 THEN \'BLOB\'
WHEN 14 THEN \'CHAR\'
WHEN 40 THEN \'CSTRING\'
WHEN 11 THEN \'D_FLOAT\'
WHEN 27 THEN \'DOUBLE\'
WHEN 10 THEN \'FLOAT\'
WHEN 16 THEN \'INT64\'
WHEN 8 THEN \'INTEGER\'
WHEN 9 THEN \'QUAD\'
WHEN 7 THEN \'SMALLINT\'
WHEN 12 THEN \'DATE\'
WHEN 13 THEN \'TIME\'
WHEN 35 THEN \'TIMESTAMP\'
WHEN 37 THEN \'VARCHAR\'
ELSE \'UNKNOWN\'
END AS field_type,
f.RDB$FIELD_SUB_TYPE AS field_subtype,
coll.RDB$COLLATION_NAME AS field_collation,
cset.RDB$CHARACTER_SET_NAME AS field_charset
FROM RDB$RELATION_FIELDS r
LEFT JOIN RDB$FIELDS f ON r.RDB$FIELD_SOURCE = f.RDB$FIELD_NAME
LEFT JOIN RDB$COLLATIONS coll ON f.RDB$COLLATION_ID = coll.RDB$COLLATION_ID
LEFT JOIN RDB$CHARACTER_SETS cset ON f.RDB$CHARACTER_SET_ID = cset.RDB$CHARACTER_SET_ID
WHERE r.RDB$RELATION_NAME = ' .
            q($Q) .
            '
ORDER BY r.RDB$FIELD_POSITION';
        $G = ibase_query($g->_link, $F);
        while ($I = ibase_fetch_assoc($G)) {
            $H[trim($I['FIELD_NAME'])] = [
                "field" => trim($I["FIELD_NAME"]),
                "full_type" => trim($I["FIELD_TYPE"]),
                "type" => trim($I["FIELD_SUB_TYPE"]),
                "default" => trim($I['FIELD_DEFAULT_VALUE']),
                "null" => trim($I["FIELD_NOT_NULL_CONSTRAINT"]) == "YES",
                "auto_increment" => '0',
                "collation" => trim($I["FIELD_COLLATION"]),
                "privileges" => ["insert" => 1, "select" => 1, "update" => 1],
                "comment" => trim($I["FIELD_DESCRIPTION"]),
            ];
        }
        return $H;
    }
    function indexes($Q, $h = null)
    {
        $H = [];
        return $H;
    }
    function foreign_keys($Q)
    {
        return [];
    }
    function collations()
    {
        return [];
    }
    function information_schema($l)
    {
        return false;
    }
    function error()
    {
        global $g;
        return h($g->error);
    }
    function types()
    {
        return [];
    }
    function schemas()
    {
        return [];
    }
    function get_schema()
    {
        return "";
    }
    function set_schema($ah)
    {
        return true;
    }
    function support($Pc)
    {
        return preg_match("~^(columns|sql|status|table)$~", $Pc);
    }
    $x = "firebird";
    $vf = ["="];
    $kd = [];
    $qd = [];
    $mc = [];
}
$ec["simpledb"] = "SimpleDB";
if (isset($_GET["simpledb"])) {
    $hg = ["SimpleXML + allow_url_fopen"];
    define("DRIVER", "simpledb");
    if (class_exists('SimpleXMLElement') && ini_bool('allow_url_fopen')) {
        class Min_DB
        {
            var $extension = "SimpleXML",
                $server_info = '2009-04-15',
                $error,
                $timeout,
                $next,
                $affected_rows,
                $_result;
            function select_db($j)
            {
                return $j == "domain";
            }
            function query($F, $Ci = false)
            {
                $Of = ['SelectExpression' => $F, 'ConsistentRead' => 'true'];
                if ($this->next) {
                    $Of['NextToken'] = $this->next;
                }
                $G = sdb_request_all('Select', 'Item', $Of, $this->timeout);
                $this->timeout = 0;
                if ($G === false) {
                    return $G;
                }
                if (preg_match('~^\s*SELECT\s+COUNT\(~i', $F)) {
                    $Lh = 0;
                    foreach ($G as $be) {
                        $Lh += $be->Attribute->Value;
                    }
                    $G = [
                        (object) [
                            'Attribute' => [
                                (object) ['Name' => 'Count', 'Value' => $Lh],
                            ],
                        ],
                    ];
                }
                return new Min_Result($G);
            }
            function multi_query($F)
            {
                return $this->_result = $this->query($F);
            }
            function store_result()
            {
                return $this->_result;
            }
            function next_result()
            {
                return false;
            }
            function quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }
        }
        class Min_Result
        {
            var $num_rows,
                $_rows = [],
                $_offset = 0;
            function __construct($G)
            {
                foreach ($G as $be) {
                    $I = [];
                    if ($be->Name != '') {
                        $I['itemName()'] = (string) $be->Name;
                    }
                    foreach ($be->Attribute as $Ia) {
                        $B = $this->_processValue($Ia->Name);
                        $Y = $this->_processValue($Ia->Value);
                        if (isset($I[$B])) {
                            $I[$B] = (array) $I[$B];
                            $I[$B][] = $Y;
                        } else {
                            $I[$B] = $Y;
                        }
                    }
                    $this->_rows[] = $I;
                    foreach ($I as $y => $X) {
                        if (!isset($this->_rows[0][$y])) {
                            $this->_rows[0][$y] = null;
                        }
                    }
                }
                $this->num_rows = count($this->_rows);
            }
            function _processValue($pc)
            {
                return is_object($pc) && $pc['encoding'] == 'base64'
                    ? base64_decode($pc)
                    : (string) $pc;
            }
            function fetch_assoc()
            {
                $I = current($this->_rows);
                if (!$I) {
                    return $I;
                }
                $H = [];
                foreach ($this->_rows[0] as $y => $X) {
                    $H[$y] = $I[$y];
                }
                next($this->_rows);
                return $H;
            }
            function fetch_row()
            {
                $H = $this->fetch_assoc();
                if (!$H) {
                    return $H;
                }
                return array_values($H);
            }
            function fetch_field()
            {
                $he = array_keys($this->_rows[0]);
                return (object) ['name' => $he[$this->_offset++]];
            }
        }
    }
    class Min_Driver extends Min_SQL
    {
        public $kg = "itemName()";
        function _chunkRequest($Ed, $va, $Of, $Ec = [])
        {
            global $g;
            foreach (array_chunk($Ed, 25) as $ib) {
                $Pf = $Of;
                foreach ($ib as $s => $t) {
                    $Pf["Item.$s.ItemName"] = $t;
                    foreach ($Ec as $y => $X) {
                        $Pf["Item.$s.$y"] = $X;
                    }
                }
                if (!sdb_request($va, $Pf)) {
                    return false;
                }
            }
            $g->affected_rows = count($Ed);
            return true;
        }
        function _extractIds($Q, $wg, $z)
        {
            $H = [];
            if (preg_match_all("~itemName\(\) = (('[^']*+')+)~", $wg, $De)) {
                $H = array_map('idf_unescape', $De[1]);
            } else {
                foreach (
                    sdb_request_all('Select', 'Item', [
                        'SelectExpression' =>
                            'SELECT itemName() FROM ' .
                            table($Q) .
                            $wg .
                            ($z ? " LIMIT 1" : ""),
                    ])
                    as $be
                ) {
                    $H[] = $be->Name;
                }
            }
            return $H;
        }
        function select($Q, $K, $Z, $nd, $_f = [], $z = 1, $D = 0, $mg = false)
        {
            global $g;
            $g->next = $_GET["next"];
            $H = parent::select($Q, $K, $Z, $nd, $_f, $z, $D, $mg);
            $g->next = 0;
            return $H;
        }
        function delete($Q, $wg, $z = 0)
        {
            return $this->_chunkRequest(
                $this->_extractIds($Q, $wg, $z),
                'BatchDeleteAttributes',
                ['DomainName' => $Q]
            );
        }
        function update($Q, $N, $wg, $z = 0, $L = "\n")
        {
            $Vb = [];
            $Td = [];
            $s = 0;
            $Ed = $this->_extractIds($Q, $wg, $z);
            $t = idf_unescape($N["`itemName()`"]);
            unset($N["`itemName()`"]);
            foreach ($N as $y => $X) {
                $y = idf_unescape($y);
                if ($X == "NULL" || ($t != "" && [$t] != $Ed)) {
                    $Vb["Attribute." . count($Vb) . ".Name"] = $y;
                }
                if ($X != "NULL") {
                    foreach ((array) $X as $de => $W) {
                        $Td["Attribute.$s.Name"] = $y;
                        $Td["Attribute.$s.Value"] = is_array($X)
                            ? $W
                            : idf_unescape($W);
                        if (!$de) {
                            $Td["Attribute.$s.Replace"] = "true";
                        }
                        $s++;
                    }
                }
            }
            $Of = ['DomainName' => $Q];
            return (!$Td ||
                $this->_chunkRequest(
                    $t != "" ? [$t] : $Ed,
                    'BatchPutAttributes',
                    $Of,
                    $Td
                )) &&
                (!$Vb ||
                    $this->_chunkRequest(
                        $Ed,
                        'BatchDeleteAttributes',
                        $Of,
                        $Vb
                    ));
        }
        function insert($Q, $N)
        {
            $Of = ["DomainName" => $Q];
            $s = 0;
            foreach ($N as $B => $Y) {
                if ($Y != "NULL") {
                    $B = idf_unescape($B);
                    if ($B == "itemName()") {
                        $Of["ItemName"] = idf_unescape($Y);
                    } else {
                        foreach ((array) $Y as $X) {
                            $Of["Attribute.$s.Name"] = $B;
                            $Of["Attribute.$s.Value"] = is_array($Y)
                                ? $X
                                : idf_unescape($Y);
                            $s++;
                        }
                    }
                }
            }
            return sdb_request('PutAttributes', $Of);
        }
        function insertUpdate($Q, $J, $kg)
        {
            foreach ($J as $N) {
                if (
                    !$this->update(
                        $Q,
                        $N,
                        "WHERE `itemName()` = " . q($N["`itemName()`"])
                    )
                ) {
                    return false;
                }
            }
            return true;
        }
        function begin()
        {
            return false;
        }
        function commit()
        {
            return false;
        }
        function rollback()
        {
            return false;
        }
        function slowQuery($F, $fi)
        {
            $this->_conn->timeout = $fi;
            return $F;
        }
    }
    function connect()
    {
        global $b;
        list(, , $E) = $b->credentials();
        if ($E != "") {
            return 'Database does not support password.';
        }
        return new Min_DB();
    }
    function support($Pc)
    {
        return preg_match('~sql~', $Pc);
    }
    function logged_user()
    {
        global $b;
        $Hb = $b->credentials();
        return $Hb[1];
    }
    function get_databases()
    {
        return ["domain"];
    }
    function collations()
    {
        return [];
    }
    function db_collation($l, $pb)
    {
    }
    function tables_list()
    {
        global $g;
        $H = [];
        foreach (sdb_request_all('ListDomains', 'DomainName') as $Q) {
            $H[(string) $Q] = 'table';
        }
        if ($g->error && defined("PAGE_HEADER")) {
            echo "<p class='error'>" . error() . "\n";
        }
        return $H;
    }
    function table_status($B = "", $Oc = false)
    {
        $H = [];
        foreach ($B != "" ? [$B => true] : tables_list() as $Q => $T) {
            $I = ["Name" => $Q, "Auto_increment" => ""];
            if (!$Oc) {
                $Qe = sdb_request('DomainMetadata', ['DomainName' => $Q]);
                if ($Qe) {
                    foreach (
                        [
                            "Rows" => "ItemCount",
                            "Data_length" => "ItemNamesSizeBytes",
                            "Index_length" => "AttributeValuesSizeBytes",
                            "Data_free" => "AttributeNamesSizeBytes",
                        ]
                        as $y => $X
                    ) {
                        $I[$y] = (string) $Qe->$X;
                    }
                }
            }
            if ($B != "") {
                return $I;
            }
            $H[$Q] = $I;
        }
        return $H;
    }
    function explain($g, $F)
    {
    }
    function error()
    {
        global $g;
        return h($g->error);
    }
    function information_schema()
    {
    }
    function is_view($R)
    {
    }
    function indexes($Q, $h = null)
    {
        return [["type" => "PRIMARY", "columns" => ["itemName()"]]];
    }
    function fields($Q)
    {
        return fields_from_edit();
    }
    function foreign_keys($Q)
    {
        return [];
    }
    function table($u)
    {
        return idf_escape($u);
    }
    function idf_escape($u)
    {
        return "`" . str_replace("`", "``", $u) . "`";
    }
    function limit($F, $Z, $z, $C = 0, $L = " ")
    {
        return " $F$Z" . ($z !== null ? $L . "LIMIT $z" : "");
    }
    function unconvert_field($o, $H)
    {
        return $H;
    }
    function fk_support($R)
    {
    }
    function engines()
    {
        return [];
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        return $Q == "" && sdb_request('CreateDomain', ['DomainName' => $B]);
    }
    function drop_tables($S)
    {
        foreach ($S as $Q) {
            if (!sdb_request('DeleteDomain', ['DomainName' => $Q])) {
                return false;
            }
        }
        return true;
    }
    function count_tables($k)
    {
        foreach ($k as $l) {
            return [$l => count(tables_list())];
        }
    }
    function found_rows($R, $Z)
    {
        return $Z ? null : $R["Rows"];
    }
    function last_id()
    {
    }
    function hmac($Ba, $Mb, $y, $_g = false)
    {
        $Va = 64;
        if (strlen($y) > $Va) {
            $y = pack("H*", $Ba($y));
        }
        $y = str_pad($y, $Va, "\0");
        $ee = $y ^ str_repeat("\x36", $Va);
        $fe = $y ^ str_repeat("\x5C", $Va);
        $H = $Ba($fe . pack("H*", $Ba($ee . $Mb)));
        if ($_g) {
            $H = pack("H*", $H);
        }
        return $H;
    }
    function sdb_request($va, $Of = [])
    {
        global $b, $g;
        list($Ad, $Of['AWSAccessKeyId'], $dh) = $b->credentials();
        $Of['Action'] = $va;
        $Of['Timestamp'] = gmdate('Y-m-d\TH:i:s+00:00');
        $Of['Version'] = '2009-04-15';
        $Of['SignatureVersion'] = 2;
        $Of['SignatureMethod'] = 'HmacSHA1';
        ksort($Of);
        $F = '';
        foreach ($Of as $y => $X) {
            $F .= '&' . rawurlencode($y) . '=' . rawurlencode($X);
        }
        $F = str_replace('%7E', '~', substr($F, 1));
        $F .=
            "&Signature=" .
            urlencode(
                base64_encode(
                    hmac(
                        'sha1',
                        "POST\n" .
                            preg_replace('~^https?://~', '', $Ad) .
                            "\n/\n$F",
                        $dh,
                        true
                    )
                )
            );
        @ini_set('track_errors', 1);
        $Tc = @file_get_contents(
            preg_match('~^https?://~', $Ad) ? $Ad : "http://$Ad",
            false,
            stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'content' => $F,
                    'ignore_errors' => 1,
                ],
            ])
        );
        if (!$Tc) {
            $g->error = $php_errormsg;
            return false;
        }
        libxml_use_internal_errors(true);
        $nj = simplexml_load_string($Tc);
        if (!$nj) {
            $n = libxml_get_last_error();
            $g->error = $n->message;
            return false;
        }
        if ($nj->Errors) {
            $n = $nj->Errors->Error;
            $g->error = "$n->Message ($n->Code)";
            return false;
        }
        $g->error = '';
        $Wh = $va . "Result";
        return $nj->$Wh ? $nj->$Wh : true;
    }
    function sdb_request_all($va, $Wh, $Of = [], $fi = 0)
    {
        $H = [];
        $Dh = $fi ? microtime(true) : 0;
        $z = preg_match('~LIMIT\s+(\d+)\s*$~i', $Of['SelectExpression'], $A)
            ? $A[1]
            : 0;
        do {
            $nj = sdb_request($va, $Of);
            if (!$nj) {
                break;
            }
            foreach ($nj->$Wh as $pc) {
                $H[] = $pc;
            }
            if ($z && count($H) >= $z) {
                $_GET["next"] = $nj->NextToken;
                break;
            }
            if ($fi && microtime(true) - $Dh > $fi) {
                return false;
            }
            $Of['NextToken'] = $nj->NextToken;
            if ($z) {
                $Of['SelectExpression'] = preg_replace(
                    '~\d+\s*$~',
                    $z - count($H),
                    $Of['SelectExpression']
                );
            }
        } while ($nj->NextToken);
        return $H;
    }
    $x = "simpledb";
    $vf = [
        "=",
        "<",
        ">",
        "<=",
        ">=",
        "!=",
        "LIKE",
        "LIKE %%",
        "IN",
        "IS NULL",
        "NOT LIKE",
        "IS NOT NULL",
    ];
    $kd = [];
    $qd = ["count"];
    $mc = [["json"]];
}
$ec["mongo"] = "MongoDB";
if (isset($_GET["mongo"])) {
    $hg = ["mongo", "mongodb"];
    define("DRIVER", "mongo");
    if (class_exists('MongoDB')) {
        class Min_DB
        {
            var $extension = "Mongo",
                $server_info = MongoClient::VERSION,
                $error,
                $last_id,
                $_link,
                $_db;
            function connect($Ki, $yf)
            {
                return @new MongoClient($Ki, $yf);
            }
            function query($F)
            {
                return false;
            }
            function select_db($j)
            {
                try {
                    $this->_db = $this->_link->selectDB($j);
                    return true;
                } catch (Exception $Ac) {
                    $this->error = $Ac->getMessage();
                    return false;
                }
            }
            function quote($P)
            {
                return $P;
            }
        }
        class Min_Result
        {
            var $num_rows,
                $_rows = [],
                $_offset = 0,
                $_charset = [];
            function __construct($G)
            {
                foreach ($G as $be) {
                    $I = [];
                    foreach ($be as $y => $X) {
                        if (is_a($X, 'MongoBinData')) {
                            $this->_charset[$y] = 63;
                        }
                        $I[$y] = is_a($X, 'MongoId')
                            ? 'ObjectId("' . strval($X) . '")'
                            : (is_a($X, 'MongoDate')
                                ? gmdate("Y-m-d H:i:s", $X->sec) . " GMT"
                                : (is_a($X, 'MongoBinData')
                                    ? $X->bin
                                    : (is_a($X, 'MongoRegex')
                                        ? strval($X)
                                        : (is_object($X)
                                            ? get_class($X)
                                            : $X))));
                    }
                    $this->_rows[] = $I;
                    foreach ($I as $y => $X) {
                        if (!isset($this->_rows[0][$y])) {
                            $this->_rows[0][$y] = null;
                        }
                    }
                }
                $this->num_rows = count($this->_rows);
            }
            function fetch_assoc()
            {
                $I = current($this->_rows);
                if (!$I) {
                    return $I;
                }
                $H = [];
                foreach ($this->_rows[0] as $y => $X) {
                    $H[$y] = $I[$y];
                }
                next($this->_rows);
                return $H;
            }
            function fetch_row()
            {
                $H = $this->fetch_assoc();
                if (!$H) {
                    return $H;
                }
                return array_values($H);
            }
            function fetch_field()
            {
                $he = array_keys($this->_rows[0]);
                $B = $he[$this->_offset++];
                return (object) [
                    'name' => $B,
                    'charsetnr' => $this->_charset[$B],
                ];
            }
        }
        class Min_Driver extends Min_SQL
        {
            public $kg = "_id";
            function select(
                $Q,
                $K,
                $Z,
                $nd,
                $_f = [],
                $z = 1,
                $D = 0,
                $mg = false
            ) {
                $K = $K == ["*"] ? [] : array_fill_keys($K, true);
                $vh = [];
                foreach ($_f as $X) {
                    $X = preg_replace('~ DESC$~', '', $X, 1, $Eb);
                    $vh[$X] = $Eb ? -1 : 1;
                }
                return new Min_Result(
                    $this->_conn->_db
                        ->selectCollection($Q)
                        ->find([], $K)
                        ->sort($vh)
                        ->limit($z != "" ? +$z : 0)
                        ->skip($D * $z)
                );
            }
            function insert($Q, $N)
            {
                try {
                    $H = $this->_conn->_db->selectCollection($Q)->insert($N);
                    $this->_conn->errno = $H['code'];
                    $this->_conn->error = $H['err'];
                    $this->_conn->last_id = $N['_id'];
                    return !$H['err'];
                } catch (Exception $Ac) {
                    $this->_conn->error = $Ac->getMessage();
                    return false;
                }
            }
        }
        function get_databases($ad)
        {
            global $g;
            $H = [];
            $Rb = $g->_link->listDBs();
            foreach ($Rb['databases'] as $l) {
                $H[] = $l['name'];
            }
            return $H;
        }
        function count_tables($k)
        {
            global $g;
            $H = [];
            foreach ($k as $l) {
                $H[$l] = count(
                    $g->_link->selectDB($l)->getCollectionNames(true)
                );
            }
            return $H;
        }
        function tables_list()
        {
            global $g;
            return array_fill_keys($g->_db->getCollectionNames(true), 'table');
        }
        function drop_databases($k)
        {
            global $g;
            foreach ($k as $l) {
                $Mg = $g->_link->selectDB($l)->drop();
                if (!$Mg['ok']) {
                    return false;
                }
            }
            return true;
        }
        function indexes($Q, $h = null)
        {
            global $g;
            $H = [];
            foreach ($g->_db->selectCollection($Q)->getIndexInfo() as $v) {
                $Yb = [];
                foreach ($v["key"] as $e => $T) {
                    $Yb[] = $T == -1 ? '1' : null;
                }
                $H[$v["name"]] = [
                    "type" =>
                        $v["name"] == "_id_"
                            ? "PRIMARY"
                            : ($v["unique"]
                                ? "UNIQUE"
                                : "INDEX"),
                    "columns" => array_keys($v["key"]),
                    "lengths" => [],
                    "descs" => $Yb,
                ];
            }
            return $H;
        }
        function fields($Q)
        {
            return fields_from_edit();
        }
        function found_rows($R, $Z)
        {
            global $g;
            return $g->_db->selectCollection($_GET["select"])->count($Z);
        }
        $vf = ["="];
    } elseif (class_exists('MongoDB\Driver\Manager')) {
        class Min_DB
        {
            var $extension = "MongoDB",
                $server_info = MONGODB_VERSION,
                $error,
                $last_id;
            var $_link;
            var $_db, $_db_name;
            function connect($Ki, $yf)
            {
                $kb = 'MongoDB\Driver\Manager';
                return new $kb($Ki, $yf);
            }
            function query($F)
            {
                return false;
            }
            function select_db($j)
            {
                $this->_db_name = $j;
                return true;
            }
            function quote($P)
            {
                return $P;
            }
        }
        class Min_Result
        {
            var $num_rows,
                $_rows = [],
                $_offset = 0,
                $_charset = [];
            function __construct($G)
            {
                foreach ($G as $be) {
                    $I = [];
                    foreach ($be as $y => $X) {
                        if (is_a($X, 'MongoDB\BSON\Binary')) {
                            $this->_charset[$y] = 63;
                        }
                        $I[$y] = is_a($X, 'MongoDB\BSON\ObjectID')
                            ? 'MongoDB\BSON\ObjectID("' . strval($X) . '")'
                            : (is_a($X, 'MongoDB\BSON\UTCDatetime')
                                ? $X->toDateTime()->format('Y-m-d H:i:s')
                                : (is_a($X, 'MongoDB\BSON\Binary')
                                    ? $X->bin
                                    : (is_a($X, 'MongoDB\BSON\Regex')
                                        ? strval($X)
                                        : (is_object($X)
                                            ? json_encode($X, 256)
                                            : $X))));
                    }
                    $this->_rows[] = $I;
                    foreach ($I as $y => $X) {
                        if (!isset($this->_rows[0][$y])) {
                            $this->_rows[0][$y] = null;
                        }
                    }
                }
                $this->num_rows = $G->count;
            }
            function fetch_assoc()
            {
                $I = current($this->_rows);
                if (!$I) {
                    return $I;
                }
                $H = [];
                foreach ($this->_rows[0] as $y => $X) {
                    $H[$y] = $I[$y];
                }
                next($this->_rows);
                return $H;
            }
            function fetch_row()
            {
                $H = $this->fetch_assoc();
                if (!$H) {
                    return $H;
                }
                return array_values($H);
            }
            function fetch_field()
            {
                $he = array_keys($this->_rows[0]);
                $B = $he[$this->_offset++];
                return (object) [
                    'name' => $B,
                    'charsetnr' => $this->_charset[$B],
                ];
            }
        }
        class Min_Driver extends Min_SQL
        {
            public $kg = "_id";
            function select(
                $Q,
                $K,
                $Z,
                $nd,
                $_f = [],
                $z = 1,
                $D = 0,
                $mg = false
            ) {
                global $g;
                $K = $K == ["*"] ? [] : array_fill_keys($K, 1);
                if (count($K) && !isset($K['_id'])) {
                    $K['_id'] = 0;
                }
                $Z = where_to_query($Z);
                $vh = [];
                foreach ($_f as $X) {
                    $X = preg_replace('~ DESC$~', '', $X, 1, $Eb);
                    $vh[$X] = $Eb ? -1 : 1;
                }
                if (
                    isset($_GET['limit']) &&
                    is_numeric($_GET['limit']) &&
                    $_GET['limit'] > 0
                ) {
                    $z = $_GET['limit'];
                }
                $z = min(200, max(1, (int) $z));
                $sh = $D * $z;
                $kb = 'MongoDB\Driver\Query';
                $F = new $kb($Z, [
                    'projection' => $K,
                    'limit' => $z,
                    'skip' => $sh,
                    'sort' => $vh,
                ]);
                $Pg = $g->_link->executeQuery("$g->_db_name.$Q", $F);
                return new Min_Result($Pg);
            }
            function update($Q, $N, $wg, $z = 0, $L = "\n")
            {
                global $g;
                $l = $g->_db_name;
                $Z = sql_query_where_parser($wg);
                $kb = 'MongoDB\Driver\BulkWrite';
                $Za = new $kb([]);
                if (isset($N['_id'])) {
                    unset($N['_id']);
                }
                $Jg = [];
                foreach ($N as $y => $Y) {
                    if ($Y == 'NULL') {
                        $Jg[$y] = 1;
                        unset($N[$y]);
                    }
                }
                $Ji = ['$set' => $N];
                if (count($Jg)) {
                    $Ji['$unset'] = $Jg;
                }
                $Za->update($Z, $Ji, ['upsert' => false]);
                $Pg = $g->_link->executeBulkWrite("$l.$Q", $Za);
                $g->affected_rows = $Pg->getModifiedCount();
                return true;
            }
            function delete($Q, $wg, $z = 0)
            {
                global $g;
                $l = $g->_db_name;
                $Z = sql_query_where_parser($wg);
                $kb = 'MongoDB\Driver\BulkWrite';
                $Za = new $kb([]);
                $Za->delete($Z, ['limit' => $z]);
                $Pg = $g->_link->executeBulkWrite("$l.$Q", $Za);
                $g->affected_rows = $Pg->getDeletedCount();
                return true;
            }
            function insert($Q, $N)
            {
                global $g;
                $l = $g->_db_name;
                $kb = 'MongoDB\Driver\BulkWrite';
                $Za = new $kb([]);
                if (isset($N['_id']) && empty($N['_id'])) {
                    unset($N['_id']);
                }
                $Za->insert($N);
                $Pg = $g->_link->executeBulkWrite("$l.$Q", $Za);
                $g->affected_rows = $Pg->getInsertedCount();
                return true;
            }
        }
        function get_databases($ad)
        {
            global $g;
            $H = [];
            $kb = 'MongoDB\Driver\Command';
            $sb = new $kb(['listDatabases' => 1]);
            $Pg = $g->_link->executeCommand('admin', $sb);
            foreach ($Pg as $Rb) {
                foreach ($Rb->databases as $l) {
                    $H[] = $l->name;
                }
            }
            return $H;
        }
        function count_tables($k)
        {
            $H = [];
            return $H;
        }
        function tables_list()
        {
            global $g;
            $kb = 'MongoDB\Driver\Command';
            $sb = new $kb(['listCollections' => 1]);
            $Pg = $g->_link->executeCommand($g->_db_name, $sb);
            $qb = [];
            foreach ($Pg as $G) {
                $qb[$G->name] = 'table';
            }
            return $qb;
        }
        function drop_databases($k)
        {
            return false;
        }
        function indexes($Q, $h = null)
        {
            global $g;
            $H = [];
            $kb = 'MongoDB\Driver\Command';
            $sb = new $kb(['listIndexes' => $Q]);
            $Pg = $g->_link->executeCommand($g->_db_name, $sb);
            foreach ($Pg as $v) {
                $Yb = [];
                $f = [];
                foreach (get_object_vars($v->key) as $e => $T) {
                    $Yb[] = $T == -1 ? '1' : null;
                    $f[] = $e;
                }
                $H[$v->name] = [
                    "type" =>
                        $v->name == "_id_"
                            ? "PRIMARY"
                            : (isset($v->unique)
                                ? "UNIQUE"
                                : "INDEX"),
                    "columns" => $f,
                    "lengths" => [],
                    "descs" => $Yb,
                ];
            }
            return $H;
        }
        function fields($Q)
        {
            $p = fields_from_edit();
            if (!count($p)) {
                global $m;
                $G = $m->select($Q, ["*"], null, null, [], 10);
                while ($I = $G->fetch_assoc()) {
                    foreach ($I as $y => $X) {
                        $I[$y] = null;
                        $p[$y] = [
                            "field" => $y,
                            "type" => "string",
                            "null" => $y != $m->primary,
                            "auto_increment" => $y == $m->primary,
                            "privileges" => [
                                "insert" => 1,
                                "select" => 1,
                                "update" => 1,
                            ],
                        ];
                    }
                }
            }
            return $p;
        }
        function found_rows($R, $Z)
        {
            global $g;
            $Z = where_to_query($Z);
            $kb = 'MongoDB\Driver\Command';
            $sb = new $kb(['count' => $R['Name'], 'query' => $Z]);
            $Pg = $g->_link->executeCommand($g->_db_name, $sb);
            $ni = $Pg->toArray();
            return $ni[0]->n;
        }
        function sql_query_where_parser($wg)
        {
            $wg = trim(preg_replace('/WHERE[\s]?[(]?\(?/', '', $wg));
            $wg = preg_replace('/\)\)\)$/', ')', $wg);
            $kj = explode(' AND ', $wg);
            $lj = explode(') OR (', $wg);
            $Z = [];
            foreach ($kj as $ij) {
                $Z[] = trim($ij);
            }
            if (count($lj) == 1) {
                $lj = [];
            } elseif (count($lj) > 1) {
                $Z = [];
            }
            return where_to_query($Z, $lj);
        }
        function where_to_query($gj = [], $hj = [])
        {
            global $b;
            $Mb = [];
            foreach (['and' => $gj, 'or' => $hj] as $T => $Z) {
                if (is_array($Z)) {
                    foreach ($Z as $Hc) {
                        list($nb, $tf, $X) = explode(" ", $Hc, 3);
                        if ($nb == "_id") {
                            $X = str_replace('MongoDB\BSON\ObjectID("', "", $X);
                            $X = str_replace('")', "", $X);
                            $kb = 'MongoDB\BSON\ObjectID';
                            $X = new $kb($X);
                        }
                        if (!in_array($tf, $b->operators)) {
                            continue;
                        }
                        if (preg_match('~^\(f\)(.+)~', $tf, $A)) {
                            $X = (float) $X;
                            $tf = $A[1];
                        } elseif (preg_match('~^\(date\)(.+)~', $tf, $A)) {
                            $Ob = new DateTime($X);
                            $kb = 'MongoDB\BSON\UTCDatetime';
                            $X = new $kb($Ob->getTimestamp() * 1000);
                            $tf = $A[1];
                        }
                        switch ($tf) {
                            case '=':
                                $tf = '$eq';
                                break;
                            case '!=':
                                $tf = '$ne';
                                break;
                            case '>':
                                $tf = '$gt';
                                break;
                            case '<':
                                $tf = '$lt';
                                break;
                            case '>=':
                                $tf = '$gte';
                                break;
                            case '<=':
                                $tf = '$lte';
                                break;
                            case 'regex':
                                $tf = '$regex';
                                break;
                            default:
                                continue 2;
                        }
                        if ($T == 'and') {
                            $Mb['$and'][] = [$nb => [$tf => $X]];
                        } elseif ($T == 'or') {
                            $Mb['$or'][] = [$nb => [$tf => $X]];
                        }
                    }
                }
            }
            return $Mb;
        }
        $vf = [
            "=",
            "!=",
            ">",
            "<",
            ">=",
            "<=",
            "regex",
            "(f)=",
            "(f)!=",
            "(f)>",
            "(f)<",
            "(f)>=",
            "(f)<=",
            "(date)=",
            "(date)!=",
            "(date)>",
            "(date)<",
            "(date)>=",
            "(date)<=",
        ];
    }
    function table($u)
    {
        return $u;
    }
    function idf_escape($u)
    {
        return $u;
    }
    function table_status($B = "", $Oc = false)
    {
        $H = [];
        foreach (tables_list() as $Q => $T) {
            $H[$Q] = ["Name" => $Q];
            if ($B == $Q) {
                return $H[$Q];
            }
        }
        return $H;
    }
    function create_database($l, $d)
    {
        return true;
    }
    function last_id()
    {
        global $g;
        return $g->last_id;
    }
    function error()
    {
        global $g;
        return h($g->error);
    }
    function collations()
    {
        return [];
    }
    function logged_user()
    {
        global $b;
        $Hb = $b->credentials();
        return $Hb[1];
    }
    function connect()
    {
        global $b;
        $g = new Min_DB();
        list($M, $V, $E) = $b->credentials();
        $yf = [];
        if ($V . $E != "") {
            $yf["username"] = $V;
            $yf["password"] = $E;
        }
        $l = $b->database();
        if ($l != "") {
            $yf["db"] = $l;
        }
        if ($La = getenv("MONGO_AUTH_SOURCE")) {
            $yf["authSource"] = $La;
        }
        try {
            $g->_link = $g->connect("mongodb://$M", $yf);
            if ($E != "") {
                $yf["password"] = "";
                try {
                    $g->connect("mongodb://$M", $yf);
                    return 'Database does not support password.';
                } catch (Exception $Ac) {
                }
            }
            return $g;
        } catch (Exception $Ac) {
            return $Ac->getMessage();
        }
    }
    function alter_indexes($Q, $c)
    {
        global $g;
        foreach ($c as $X) {
            list($T, $B, $N) = $X;
            if ($N == "DROP") {
                $H = $g->_db->command(["deleteIndexes" => $Q, "index" => $B]);
            } else {
                $f = [];
                foreach ($N as $e) {
                    $e = preg_replace('~ DESC$~', '', $e, 1, $Eb);
                    $f[$e] = $Eb ? -1 : 1;
                }
                $H = $g->_db
                    ->selectCollection($Q)
                    ->ensureIndex($f, [
                        "unique" => $T == "UNIQUE",
                        "name" => $B,
                    ]);
            }
            if ($H['errmsg']) {
                $g->error = $H['errmsg'];
                return false;
            }
        }
        return true;
    }
    function support($Pc)
    {
        return preg_match("~database|indexes|descidx~", $Pc);
    }
    function db_collation($l, $pb)
    {
    }
    function information_schema()
    {
    }
    function is_view($R)
    {
    }
    function convert_field($o)
    {
    }
    function unconvert_field($o, $H)
    {
        return $H;
    }
    function foreign_keys($Q)
    {
        return [];
    }
    function fk_support($R)
    {
    }
    function engines()
    {
        return [];
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        global $g;
        if ($Q == "") {
            $g->_db->createCollection($B);
            return true;
        }
    }
    function drop_tables($S)
    {
        global $g;
        foreach ($S as $Q) {
            $Mg = $g->_db->selectCollection($Q)->drop();
            if (!$Mg['ok']) {
                return false;
            }
        }
        return true;
    }
    function truncate_tables($S)
    {
        global $g;
        foreach ($S as $Q) {
            $Mg = $g->_db->selectCollection($Q)->remove();
            if (!$Mg['ok']) {
                return false;
            }
        }
        return true;
    }
    $x = "mongo";
    $kd = [];
    $qd = [];
    $mc = [["json"]];
}
$ec["elastic"] = "Elasticsearch (beta)";
if (isset($_GET["elastic"])) {
    $hg = ["json + allow_url_fopen"];
    define("DRIVER", "elastic");
    if (function_exists('json_decode') && ini_bool('allow_url_fopen')) {
        class Min_DB
        {
            var $extension = "JSON",
                $server_info,
                $errno,
                $error,
                $_url;
            function rootQuery($Yf, $_b = [], $Re = 'GET')
            {
                @ini_set('track_errors', 1);
                $Tc = @file_get_contents(
                    "$this->_url/" . ltrim($Yf, '/'),
                    false,
                    stream_context_create([
                        'http' => [
                            'method' => $Re,
                            'content' => $_b === null ? $_b : json_encode($_b),
                            'header' => 'Content-Type: application/json',
                            'ignore_errors' => 1,
                        ],
                    ])
                );
                if (!$Tc) {
                    $this->error = $php_errormsg;
                    return $Tc;
                }
                if (
                    !preg_match('~^HTTP/[0-9.]+ 2~i', $http_response_header[0])
                ) {
                    $this->error = $Tc;
                    return false;
                }
                $H = json_decode($Tc, true);
                if ($H === null) {
                    $this->errno = json_last_error();
                    if (function_exists('json_last_error_msg')) {
                        $this->error = json_last_error_msg();
                    } else {
                        $zb = get_defined_constants(true);
                        foreach ($zb['json'] as $B => $Y) {
                            if (
                                $Y == $this->errno &&
                                preg_match('~^JSON_ERROR_~', $B)
                            ) {
                                $this->error = $B;
                                break;
                            }
                        }
                    }
                }
                return $H;
            }
            function query($Yf, $_b = [], $Re = 'GET')
            {
                return $this->rootQuery(
                    ($this->_db != "" ? "$this->_db/" : "/") . ltrim($Yf, '/'),
                    $_b,
                    $Re
                );
            }
            function connect($M, $V, $E)
            {
                preg_match('~^(https?://)?(.*)~', $M, $A);
                $this->_url = ($A[1] ? $A[1] : "http://") . "$V:$E@$A[2]";
                $H = $this->query('');
                if ($H) {
                    $this->server_info = $H['version']['number'];
                }
                return (bool) $H;
            }
            function select_db($j)
            {
                $this->_db = $j;
                return true;
            }
            function quote($P)
            {
                return $P;
            }
        }
        class Min_Result
        {
            var $num_rows, $_rows;
            function __construct($J)
            {
                $this->num_rows = count($J);
                $this->_rows = $J;
                reset($this->_rows);
            }
            function fetch_assoc()
            {
                $H = current($this->_rows);
                next($this->_rows);
                return $H;
            }
            function fetch_row()
            {
                return array_values($this->fetch_assoc());
            }
        }
    }
    class Min_Driver extends Min_SQL
    {
        function select($Q, $K, $Z, $nd, $_f = [], $z = 1, $D = 0, $mg = false)
        {
            global $b;
            $Mb = [];
            $F = "$Q/_search";
            if ($K != ["*"]) {
                $Mb["fields"] = $K;
            }
            if ($_f) {
                $vh = [];
                foreach ($_f as $nb) {
                    $nb = preg_replace('~ DESC$~', '', $nb, 1, $Eb);
                    $vh[] = $Eb ? [$nb => "desc"] : $nb;
                }
                $Mb["sort"] = $vh;
            }
            if ($z) {
                $Mb["size"] = +$z;
                if ($D) {
                    $Mb["from"] = $D * $z;
                }
            }
            foreach ($Z as $X) {
                list($nb, $tf, $X) = explode(" ", $X, 3);
                if ($nb == "_id") {
                    $Mb["query"]["ids"]["values"][] = $X;
                } elseif ($nb . $X != "") {
                    $ai = ["term" => [$nb != "" ? $nb : "_all" => $X]];
                    if ($tf == "=") {
                        $Mb["query"]["filtered"]["filter"]["and"][] = $ai;
                    } else {
                        $Mb["query"]["filtered"]["query"]["bool"][
                            "must"
                        ][] = $ai;
                    }
                }
            }
            if (
                $Mb["query"] &&
                !$Mb["query"]["filtered"]["query"] &&
                !$Mb["query"]["ids"]
            ) {
                $Mb["query"]["filtered"]["query"] = ["match_all" => []];
            }
            $Dh = microtime(true);
            $ch = $this->_conn->query($F, $Mb);
            if ($mg) {
                echo $b->selectQuery("$F: " . json_encode($Mb), $Dh, !$ch);
            }
            if (!$ch) {
                return false;
            }
            $H = [];
            foreach ($ch['hits']['hits'] as $_d) {
                $I = [];
                if ($K == ["*"]) {
                    $I["_id"] = $_d["_id"];
                }
                $p = $_d['_source'];
                if ($K != ["*"]) {
                    $p = [];
                    foreach ($K as $y) {
                        $p[$y] = $_d['fields'][$y];
                    }
                }
                foreach ($p as $y => $X) {
                    if ($Mb["fields"]) {
                        $X = $X[0];
                    }
                    $I[$y] = is_array($X) ? json_encode($X) : $X;
                }
                $H[] = $I;
            }
            return new Min_Result($H);
        }
        function update($T, $Ag, $wg, $z = 0, $L = "\n")
        {
            $Wf = preg_split('~ *= *~', $wg);
            if (count($Wf) == 2) {
                $t = trim($Wf[1]);
                $F = "$T/$t";
                return $this->_conn->query($F, $Ag, 'POST');
            }
            return false;
        }
        function insert($T, $Ag)
        {
            $t = "";
            $F = "$T/$t";
            $Mg = $this->_conn->query($F, $Ag, 'POST');
            $this->_conn->last_id = $Mg['_id'];
            return $Mg['created'];
        }
        function delete($T, $wg, $z = 0)
        {
            $Ed = [];
            if (is_array($_GET["where"]) && $_GET["where"]["_id"]) {
                $Ed[] = $_GET["where"]["_id"];
            }
            if (is_array($_POST['check'])) {
                foreach ($_POST['check'] as $db) {
                    $Wf = preg_split('~ *= *~', $db);
                    if (count($Wf) == 2) {
                        $Ed[] = trim($Wf[1]);
                    }
                }
            }
            $this->_conn->affected_rows = 0;
            foreach ($Ed as $t) {
                $F = "{$T}/{$t}";
                $Mg = $this->_conn->query($F, '{}', 'DELETE');
                if (is_array($Mg) && $Mg['found'] == true) {
                    $this->_conn->affected_rows++;
                }
            }
            return $this->_conn->affected_rows;
        }
    }
    function connect()
    {
        global $b;
        $g = new Min_DB();
        list($M, $V, $E) = $b->credentials();
        if ($E != "" && $g->connect($M, $V, "")) {
            return 'Database does not support password.';
        }
        if ($g->connect($M, $V, $E)) {
            return $g;
        }
        return $g->error;
    }
    function support($Pc)
    {
        return preg_match("~database|table|columns~", $Pc);
    }
    function logged_user()
    {
        global $b;
        $Hb = $b->credentials();
        return $Hb[1];
    }
    function get_databases()
    {
        global $g;
        $H = $g->rootQuery('_aliases');
        if ($H) {
            $H = array_keys($H);
            sort($H, SORT_STRING);
        }
        return $H;
    }
    function collations()
    {
        return [];
    }
    function db_collation($l, $pb)
    {
    }
    function engines()
    {
        return [];
    }
    function count_tables($k)
    {
        global $g;
        $H = [];
        $G = $g->query('_stats');
        if ($G && $G['indices']) {
            $Md = $G['indices'];
            foreach ($Md as $Ld => $Eh) {
                $Kd = $Eh['total']['indexing'];
                $H[$Ld] = $Kd['index_total'];
            }
        }
        return $H;
    }
    function tables_list()
    {
        global $g;
        $H = $g->query('_mapping');
        if ($H) {
            $H = array_fill_keys(array_keys($H[$g->_db]["mappings"]), 'table');
        }
        return $H;
    }
    function table_status($B = "", $Oc = false)
    {
        global $g;
        $ch = $g->query(
            "_search",
            [
                "size" => 0,
                "aggregations" => [
                    "count_by_type" => ["terms" => ["field" => "_type"]],
                ],
            ],
            "POST"
        );
        $H = [];
        if ($ch) {
            $S = $ch["aggregations"]["count_by_type"]["buckets"];
            foreach ($S as $Q) {
                $H[$Q["key"]] = [
                    "Name" => $Q["key"],
                    "Engine" => "table",
                    "Rows" => $Q["doc_count"],
                ];
                if ($B != "" && $B == $Q["key"]) {
                    return $H[$B];
                }
            }
        }
        return $H;
    }
    function error()
    {
        global $g;
        return h($g->error);
    }
    function information_schema()
    {
    }
    function is_view($R)
    {
    }
    function indexes($Q, $h = null)
    {
        return [["type" => "PRIMARY", "columns" => ["_id"]]];
    }
    function fields($Q)
    {
        global $g;
        $G = $g->query("$Q/_mapping");
        $H = [];
        if ($G) {
            $_e = $G[$Q]['properties'];
            if (!$_e) {
                $_e = $G[$g->_db]['mappings'][$Q]['properties'];
            }
            if ($_e) {
                foreach ($_e as $B => $o) {
                    $H[$B] = [
                        "field" => $B,
                        "full_type" => $o["type"],
                        "type" => $o["type"],
                        "privileges" => [
                            "insert" => 1,
                            "select" => 1,
                            "update" => 1,
                        ],
                    ];
                    if ($o["properties"]) {
                        unset($H[$B]["privileges"]["insert"]);
                        unset($H[$B]["privileges"]["update"]);
                    }
                }
            }
        }
        return $H;
    }
    function foreign_keys($Q)
    {
        return [];
    }
    function table($u)
    {
        return $u;
    }
    function idf_escape($u)
    {
        return $u;
    }
    function convert_field($o)
    {
    }
    function unconvert_field($o, $H)
    {
        return $H;
    }
    function fk_support($R)
    {
    }
    function found_rows($R, $Z)
    {
        return null;
    }
    function create_database($l)
    {
        global $g;
        return $g->rootQuery(urlencode($l), null, 'PUT');
    }
    function drop_databases($k)
    {
        global $g;
        return $g->rootQuery(urlencode(implode(',', $k)), [], 'DELETE');
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        global $g;
        $sg = [];
        foreach ($p as $Mc) {
            $Rc = trim($Mc[1][0]);
            $Sc = trim($Mc[1][1] ? $Mc[1][1] : "text");
            $sg[$Rc] = ['type' => $Sc];
        }
        if (!empty($sg)) {
            $sg = ['properties' => $sg];
        }
        return $g->query("_mapping/{$B}", $sg, 'PUT');
    }
    function drop_tables($S)
    {
        global $g;
        $H = true;
        foreach ($S as $Q) {
            $H = $H && $g->query(urlencode($Q), [], 'DELETE');
        }
        return $H;
    }
    function last_id()
    {
        global $g;
        return $g->last_id;
    }
    $x = "elastic";
    $vf = ["=", "query"];
    $kd = [];
    $qd = [];
    $mc = [["json"]];
    $U = [];
    $Hh = [];
    foreach (
        [
            'Numbers' => [
                "long" => 3,
                "integer" => 5,
                "short" => 8,
                "byte" => 10,
                "double" => 20,
                "float" => 66,
                "half_float" => 12,
                "scaled_float" => 21,
            ],
            'Date and time' => ["date" => 10],
            'Strings' => ["string" => 65535, "text" => 65535],
            'Binary' => ["binary" => 255],
        ]
        as $y => $X
    ) {
        $U += $X;
        $Hh[$y] = array_keys($X);
    }
}
$ec["clickhouse"] = "ClickHouse (alpha)";
if (isset($_GET["clickhouse"])) {
    define("DRIVER", "clickhouse");
    class Min_DB
    {
        var $extension = "JSON",
            $server_info,
            $errno,
            $_result,
            $error,
            $_url;
        var $_db = 'default';
        function rootQuery($l, $F)
        {
            @ini_set('track_errors', 1);
            $Tc = @file_get_contents(
                "$this->_url/?database=$l",
                false,
                stream_context_create([
                    'http' => [
                        'method' => 'POST',
                        'content' => $this->isQuerySelectLike($F)
                            ? "$F FORMAT JSONCompact"
                            : $F,
                        'header' =>
                            'Content-type: application/x-www-form-urlencoded',
                        'ignore_errors' => 1,
                    ],
                ])
            );
            if ($Tc === false) {
                $this->error = $php_errormsg;
                return $Tc;
            }
            if (!preg_match('~^HTTP/[0-9.]+ 2~i', $http_response_header[0])) {
                $this->error = $Tc;
                return false;
            }
            $H = json_decode($Tc, true);
            if ($H === null) {
                if (!$this->isQuerySelectLike($F) && $Tc === '') {
                    return true;
                }
                $this->errno = json_last_error();
                if (function_exists('json_last_error_msg')) {
                    $this->error = json_last_error_msg();
                } else {
                    $zb = get_defined_constants(true);
                    foreach ($zb['json'] as $B => $Y) {
                        if (
                            $Y == $this->errno &&
                            preg_match('~^JSON_ERROR_~', $B)
                        ) {
                            $this->error = $B;
                            break;
                        }
                    }
                }
            }
            return new Min_Result($H);
        }
        function isQuerySelectLike($F)
        {
            return (bool) preg_match('~^(select|show)~i', $F);
        }
        function query($F)
        {
            return $this->rootQuery($this->_db, $F);
        }
        function connect($M, $V, $E)
        {
            preg_match('~^(https?://)?(.*)~', $M, $A);
            $this->_url = ($A[1] ? $A[1] : "http://") . "$V:$E@$A[2]";
            $H = $this->query('SELECT 1');
            return (bool) $H;
        }
        function select_db($j)
        {
            $this->_db = $j;
            return true;
        }
        function quote($P)
        {
            return "'" . addcslashes($P, "\\'") . "'";
        }
        function multi_query($F)
        {
            return $this->_result = $this->query($F);
        }
        function store_result()
        {
            return $this->_result;
        }
        function next_result()
        {
            return false;
        }
        function result($F, $o = 0)
        {
            $G = $this->query($F);
            return $G['data'];
        }
    }
    class Min_Result
    {
        var $num_rows,
            $_rows,
            $columns,
            $meta,
            $_offset = 0;
        function __construct($G)
        {
            $this->num_rows = $G['rows'];
            $this->_rows = $G['data'];
            $this->meta = $G['meta'];
            $this->columns = array_column($this->meta, 'name');
            reset($this->_rows);
        }
        function fetch_assoc()
        {
            $I = current($this->_rows);
            next($this->_rows);
            return $I === false ? false : array_combine($this->columns, $I);
        }
        function fetch_row()
        {
            $I = current($this->_rows);
            next($this->_rows);
            return $I;
        }
        function fetch_field()
        {
            $e = $this->_offset++;
            $H = new stdClass();
            if ($e < count($this->columns)) {
                $H->name = $this->meta[$e]['name'];
                $H->orgname = $H->name;
                $H->type = $this->meta[$e]['type'];
            }
            return $H;
        }
    }
    class Min_Driver extends Min_SQL
    {
        function delete($Q, $wg, $z = 0)
        {
            if ($wg === '') {
                $wg = 'WHERE 1=1';
            }
            return queries("ALTER TABLE " . table($Q) . " DELETE $wg");
        }
        function update($Q, $N, $wg, $z = 0, $L = "\n")
        {
            $Vi = [];
            foreach ($N as $y => $X) {
                $Vi[] = "$y = $X";
            }
            $F = $L . implode(",$L", $Vi);
            return queries("ALTER TABLE " . table($Q) . " UPDATE $F$wg");
        }
    }
    function idf_escape($u)
    {
        return "`" . str_replace("`", "``", $u) . "`";
    }
    function table($u)
    {
        return idf_escape($u);
    }
    function explain($g, $F)
    {
        return '';
    }
    function found_rows($R, $Z)
    {
        $J = get_vals(
            "SELECT COUNT(*) FROM " .
                idf_escape($R["Name"]) .
                ($Z ? " WHERE " . implode(" AND ", $Z) : "")
        );
        return empty($J) ? false : $J[0];
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = $_f = [];
        foreach ($p as $o) {
            if ($o[1][2] === " NULL") {
                $o[1][1] = " Nullable({$o[1][1]})";
            } elseif ($o[1][2] === ' NOT NULL') {
                $o[1][2] = '';
            }
            if ($o[1][3]) {
                $o[1][3] = '';
            }
            $c[] = $o[1]
                ? ($Q != ""
                        ? ($o[0] != ""
                            ? "MODIFY COLUMN "
                            : "ADD COLUMN ")
                        : " ") . implode($o[1])
                : "DROP COLUMN " . idf_escape($o[0]);
            $_f[] = $o[1][0];
        }
        $c = array_merge($c, $cd);
        $O = $uc ? " ENGINE " . $uc : "";
        if ($Q == "") {
            return queries(
                "CREATE TABLE " .
                    table($B) .
                    " (\n" .
                    implode(",\n", $c) .
                    "\n)$O$Uf" .
                    ' ORDER BY (' .
                    implode(',', $_f) .
                    ')'
            );
        }
        if ($Q != $B) {
            $G = queries("RENAME TABLE " . table($Q) . " TO " . table($B));
            if ($c) {
                $Q = $B;
            } else {
                return $G;
            }
        }
        if ($O) {
            $c[] = ltrim($O);
        }
        return $c || $Uf
            ? queries(
                "ALTER TABLE " . table($Q) . "\n" . implode(",\n", $c) . $Uf
            )
            : true;
    }
    function truncate_tables($S)
    {
        return apply_queries("TRUNCATE TABLE", $S);
    }
    function drop_views($aj)
    {
        return drop_tables($aj);
    }
    function drop_tables($S)
    {
        return apply_queries("DROP TABLE", $S);
    }
    function connect()
    {
        global $b;
        $g = new Min_DB();
        $Hb = $b->credentials();
        if ($g->connect($Hb[0], $Hb[1], $Hb[2])) {
            return $g;
        }
        return $g->error;
    }
    function get_databases($ad)
    {
        global $g;
        $G = get_rows('SHOW DATABASES');
        $H = [];
        foreach ($G as $I) {
            $H[] = $I['name'];
        }
        sort($H);
        return $H;
    }
    function limit($F, $Z, $z, $C = 0, $L = " ")
    {
        return " $F$Z" .
            ($z !== null ? $L . "LIMIT $z" . ($C ? ", $C" : "") : "");
    }
    function limit1($Q, $F, $Z, $L = "\n")
    {
        return limit($F, $Z, 1, 0, $L);
    }
    function db_collation($l, $pb)
    {
    }
    function engines()
    {
        return ['MergeTree'];
    }
    function logged_user()
    {
        global $b;
        $Hb = $b->credentials();
        return $Hb[1];
    }
    function tables_list()
    {
        $G = get_rows('SHOW TABLES');
        $H = [];
        foreach ($G as $I) {
            $H[$I['name']] = 'table';
        }
        ksort($H);
        return $H;
    }
    function count_tables($k)
    {
        return [];
    }
    function table_status($B = "", $Oc = false)
    {
        global $g;
        $H = [];
        $S = get_rows(
            "SELECT name, engine FROM system.tables WHERE database = " .
                q($g->_db)
        );
        foreach ($S as $Q) {
            $H[$Q['name']] = ['Name' => $Q['name'], 'Engine' => $Q['engine']];
            if ($B === $Q['name']) {
                return $H[$Q['name']];
            }
        }
        return $H;
    }
    function is_view($R)
    {
        return false;
    }
    function fk_support($R)
    {
        return false;
    }
    function convert_field($o)
    {
    }
    function unconvert_field($o, $H)
    {
        if (
            in_array($o['type'], [
                "Int8",
                "Int16",
                "Int32",
                "Int64",
                "UInt8",
                "UInt16",
                "UInt32",
                "UInt64",
                "Float32",
                "Float64",
            ])
        ) {
            return "to$o[type]($H)";
        }
        return $H;
    }
    function fields($Q)
    {
        $H = [];
        $G = get_rows(
            "SELECT name, type, default_expression FROM system.columns WHERE " .
                idf_escape('table') .
                " = " .
                q($Q)
        );
        foreach ($G as $I) {
            $T = trim($I['type']);
            $ff = strpos($T, 'Nullable(') === 0;
            $H[trim($I['name'])] = [
                "field" => trim($I['name']),
                "full_type" => $T,
                "type" => $T,
                "default" => trim($I['default_expression']),
                "null" => $ff,
                "auto_increment" => '0',
                "privileges" => ["insert" => 1, "select" => 1, "update" => 0],
            ];
        }
        return $H;
    }
    function indexes($Q, $h = null)
    {
        return [];
    }
    function foreign_keys($Q)
    {
        return [];
    }
    function collations()
    {
        return [];
    }
    function information_schema($l)
    {
        return false;
    }
    function error()
    {
        global $g;
        return h($g->error);
    }
    function types()
    {
        return [];
    }
    function schemas()
    {
        return [];
    }
    function get_schema()
    {
        return "";
    }
    function set_schema($ah)
    {
        return true;
    }
    function auto_increment()
    {
        return '';
    }
    function last_id()
    {
        return 0;
    }
    function support($Pc)
    {
        return preg_match("~^(columns|sql|status|table|drop_col)$~", $Pc);
    }
    $x = "clickhouse";
    $U = [];
    $Hh = [];
    foreach (
        [
            'Numbers' => [
                "Int8" => 3,
                "Int16" => 5,
                "Int32" => 10,
                "Int64" => 19,
                "UInt8" => 3,
                "UInt16" => 5,
                "UInt32" => 10,
                "UInt64" => 20,
                "Float32" => 7,
                "Float64" => 16,
                'Decimal' => 38,
                'Decimal32' => 9,
                'Decimal64' => 18,
                'Decimal128' => 38,
            ],
            'Date and time' => ["Date" => 13, "DateTime" => 20],
            'Strings' => ["String" => 0],
            'Binary' => ["FixedString" => 0],
        ]
        as $y => $X
    ) {
        $U += $X;
        $Hh[$y] = array_keys($X);
    }
    $Ii = [];
    $vf = [
        "=",
        "<",
        ">",
        "<=",
        ">=",
        "!=",
        "~",
        "!~",
        "LIKE",
        "LIKE %%",
        "IN",
        "IS NULL",
        "NOT LIKE",
        "NOT IN",
        "IS NOT NULL",
        "SQL",
    ];
    $kd = [];
    $qd = ["avg", "count", "count distinct", "max", "min", "sum"];
    $mc = [];
}
$ec = ["server" => "MySQL"] + $ec;
if (!defined("DRIVER")) {
    $hg = ["MySQLi", "MySQL", "PDO_MySQL"];
    define("DRIVER", "server");
    if (extension_loaded("mysqli")) {
        class Min_DB extends MySQLi
        {
            var $extension = "MySQLi";
            function __construct()
            {
                parent::init();
            }
            function connect(
                $M = "",
                $V = "",
                $E = "",
                $j = null,
                $dg = null,
                $uh = null
            ) {
                global $b;
                mysqli_report(MYSQLI_REPORT_OFF);
                list($Ad, $dg) = explode(":", $M, 2);
                $Ch = $b->connectSsl();
                if ($Ch) {
                    $this->ssl_set($Ch['key'], $Ch['cert'], $Ch['ca'], '', '');
                }
                $H = @$this->real_connect(
                    $M != "" ? $Ad : ini_get("mysqli.default_host"),
                    $M . $V != "" ? $V : ini_get("mysqli.default_user"),
                    $M . $V . $E != "" ? $E : ini_get("mysqli.default_pw"),
                    $j,
                    is_numeric($dg) ? $dg : ini_get("mysqli.default_port"),
                    !is_numeric($dg) ? $dg : $uh,
                    $Ch ? 64 : 0
                );
                $this->options(MYSQLI_OPT_LOCAL_INFILE, false);
                return $H;
            }
            function set_charset($cb)
            {
                if (parent::set_charset($cb)) {
                    return true;
                }
                parent::set_charset('utf8');
                return $this->query("SET NAMES $cb");
            }
            function result($F, $o = 0)
            {
                $G = $this->query($F);
                if (!$G) {
                    return false;
                }
                $I = $G->fetch_array();
                return $I[$o];
            }
            function quote($P)
            {
                return "'" . $this->escape_string($P) . "'";
            }
        }
    } elseif (
        extension_loaded("mysql") &&
        !(
            (ini_bool("sql.safe_mode") ||
                ini_bool("mysql.allow_local_infile")) &&
            extension_loaded("pdo_mysql")
        )
    ) {
        class Min_DB
        {
            var $extension = "MySQL",
                $server_info,
                $affected_rows,
                $errno,
                $error,
                $_link,
                $_result;
            function connect($M, $V, $E)
            {
                if (ini_bool("mysql.allow_local_infile")) {
                    $this->error = sprintf(
                        'Disable %s or enable %s or %s extensions.',
                        "'mysql.allow_local_infile'",
                        "MySQLi",
                        "PDO_MySQL"
                    );
                    return false;
                }
                $this->_link = @mysql_connect(
                    $M != "" ? $M : ini_get("mysql.default_host"),
                    "$M$V" != "" ? $V : ini_get("mysql.default_user"),
                    "$M$V$E" != "" ? $E : ini_get("mysql.default_password"),
                    true,
                    131072
                );
                if ($this->_link) {
                    $this->server_info = mysql_get_server_info($this->_link);
                } else {
                    $this->error = mysql_error();
                }
                return (bool) $this->_link;
            }
            function set_charset($cb)
            {
                if (function_exists('mysql_set_charset')) {
                    if (mysql_set_charset($cb, $this->_link)) {
                        return true;
                    }
                    mysql_set_charset('utf8', $this->_link);
                }
                return $this->query("SET NAMES $cb");
            }
            function quote($P)
            {
                return "'" . mysql_real_escape_string($P, $this->_link) . "'";
            }
            function select_db($j)
            {
                return mysql_select_db($j, $this->_link);
            }
            function query($F, $Ci = false)
            {
                $G = @$Ci
                    ? mysql_unbuffered_query($F, $this->_link)
                    : mysql_query($F, $this->_link);
                $this->error = "";
                if (!$G) {
                    $this->errno = mysql_errno($this->_link);
                    $this->error = mysql_error($this->_link);
                    return false;
                }
                if ($G === true) {
                    $this->affected_rows = mysql_affected_rows($this->_link);
                    $this->info = mysql_info($this->_link);
                    return true;
                }
                return new Min_Result($G);
            }
            function multi_query($F)
            {
                return $this->_result = $this->query($F);
            }
            function store_result()
            {
                return $this->_result;
            }
            function next_result()
            {
                return false;
            }
            function result($F, $o = 0)
            {
                $G = $this->query($F);
                if (!$G || !$G->num_rows) {
                    return false;
                }
                return mysql_result($G->_result, 0, $o);
            }
        }
        class Min_Result
        {
            var $num_rows,
                $_result,
                $_offset = 0;
            function __construct($G)
            {
                $this->_result = $G;
                $this->num_rows = mysql_num_rows($G);
            }
            function fetch_assoc()
            {
                return mysql_fetch_assoc($this->_result);
            }
            function fetch_row()
            {
                return mysql_fetch_row($this->_result);
            }
            function fetch_field()
            {
                $H = mysql_fetch_field($this->_result, $this->_offset++);
                $H->orgtable = $H->table;
                $H->orgname = $H->name;
                $H->charsetnr = $H->blob ? 63 : 0;
                return $H;
            }
            function __destruct()
            {
                mysql_free_result($this->_result);
            }
        }
    } elseif (extension_loaded("pdo_mysql")) {
        class Min_DB extends Min_PDO
        {
            var $extension = "PDO_MySQL";
            function connect($M, $V, $E)
            {
                global $b;
                $yf = [PDO::MYSQL_ATTR_LOCAL_INFILE => false];
                $Ch = $b->connectSsl();
                if ($Ch) {
                    if (!empty($Ch['key'])) {
                        $yf[PDO::MYSQL_ATTR_SSL_KEY] = $Ch['key'];
                    }
                    if (!empty($Ch['cert'])) {
                        $yf[PDO::MYSQL_ATTR_SSL_CERT] = $Ch['cert'];
                    }
                    if (!empty($Ch['ca'])) {
                        $yf[PDO::MYSQL_ATTR_SSL_CA] = $Ch['ca'];
                    }
                }
                $this->dsn(
                    "mysql:charset=utf8;host=" .
                        str_replace(
                            ":",
                            ";unix_socket=",
                            preg_replace('~:(\d)~', ';port=\1', $M)
                        ),
                    $V,
                    $E,
                    $yf
                );
                return true;
            }
            function set_charset($cb)
            {
                $this->query("SET NAMES $cb");
            }
            function select_db($j)
            {
                return $this->query("USE " . idf_escape($j));
            }
            function query($F, $Ci = false)
            {
                $this->setAttribute(1000, !$Ci);
                return parent::query($F, $Ci);
            }
        }
    }
    class Min_Driver extends Min_SQL
    {
        function insert($Q, $N)
        {
            return $N
                ? parent::insert($Q, $N)
                : queries("INSERT INTO " . table($Q) . " ()\nVALUES ()");
        }
        function insertUpdate($Q, $J, $kg)
        {
            $f = array_keys(reset($J));
            $ig =
                "INSERT INTO " .
                table($Q) .
                " (" .
                implode(", ", $f) .
                ") VALUES\n";
            $Vi = [];
            foreach ($f as $y) {
                $Vi[$y] = "$y = VALUES($y)";
            }
            $Kh = "\nON DUPLICATE KEY UPDATE " . implode(", ", $Vi);
            $Vi = [];
            $te = 0;
            foreach ($J as $N) {
                $Y = "(" . implode(", ", $N) . ")";
                if ($Vi && strlen($ig) + $te + strlen($Y) + strlen($Kh) > 1e6) {
                    if (!queries($ig . implode(",\n", $Vi) . $Kh)) {
                        return false;
                    }
                    $Vi = [];
                    $te = 0;
                }
                $Vi[] = $Y;
                $te += strlen($Y) + 2;
            }
            return queries($ig . implode(",\n", $Vi) . $Kh);
        }
        function slowQuery($F, $fi)
        {
            if (min_version('5.7.8', '10.1.2')) {
                if (preg_match('~MariaDB~', $this->_conn->server_info)) {
                    return "SET STATEMENT max_statement_time=$fi FOR $F";
                } elseif (preg_match('~^(SELECT\b)(.+)~is', $F, $A)) {
                    return "$A[1] /*+ MAX_EXECUTION_TIME(" .
                        $fi * 1000 .
                        ") */ $A[2]";
                }
            }
        }
        function convertSearch($u, $X, $o)
        {
            return preg_match('~char|text|enum|set~', $o["type"]) &&
                !preg_match("~^utf8~", $o["collation"]) &&
                preg_match('~[\x80-\xFF]~', $X['val'])
                ? "CONVERT($u USING " . charset($this->_conn) . ")"
                : $u;
        }
        function warnings()
        {
            $G = $this->_conn->query("SHOW WARNINGS");
            if ($G && $G->num_rows) {
                ob_start();
                select($G);
                return ob_get_clean();
            }
        }
        function tableHelp($B)
        {
            $Ae = preg_match('~MariaDB~', $this->_conn->server_info);
            if (information_schema(DB)) {
                return strtolower(
                    $Ae
                        ? "information-schema-$B-table/"
                        : str_replace("_", "-", $B) . "-table.html"
                );
            }
            if (DB == "mysql") {
                return $Ae ? "mysql$B-table/" : "system-database.html";
            }
        }
    }
    function idf_escape($u)
    {
        return "`" . str_replace("`", "``", $u) . "`";
    }
    function table($u)
    {
        return idf_escape($u);
    }
    function connect()
    {
        global $b, $U, $Hh;
        $g = new Min_DB();
        $Hb = $b->credentials();
        if ($g->connect($Hb[0], $Hb[1], $Hb[2])) {
            $g->set_charset(charset($g));
            $g->query("SET sql_quote_show_create = 1, autocommit = 1");
            if (min_version('5.7.8', 10.2, $g)) {
                $Hh['Strings'][] = "json";
                $U["json"] = 4294967295;
            }
            return $g;
        }
        $H = $g->error;
        if (
            function_exists('iconv') &&
            !is_utf8($H) &&
            strlen($Yg = iconv("windows-1250", "utf-8", $H)) > strlen($H)
        ) {
            $H = $Yg;
        }
        return $H;
    }
    function get_databases($ad)
    {
        $H = get_session("dbs");
        if ($H === null) {
            $F = min_version(5)
                ? "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME"
                : "SHOW DATABASES";
            $H = $ad ? slow_query($F) : get_vals($F);
            restart_session();
            set_session("dbs", $H);
            stop_session();
        }
        return $H;
    }
    function limit($F, $Z, $z, $C = 0, $L = " ")
    {
        return " $F$Z" .
            ($z !== null ? $L . "LIMIT $z" . ($C ? " OFFSET $C" : "") : "");
    }
    function limit1($Q, $F, $Z, $L = "\n")
    {
        return limit($F, $Z, 1, 0, $L);
    }
    function db_collation($l, $pb)
    {
        global $g;
        $H = null;
        $i = $g->result("SHOW CREATE DATABASE " . idf_escape($l), 1);
        if (preg_match('~ COLLATE ([^ ]+)~', $i, $A)) {
            $H = $A[1];
        } elseif (preg_match('~ CHARACTER SET ([^ ]+)~', $i, $A)) {
            $H = $pb[$A[1]][-1];
        }
        return $H;
    }
    function engines()
    {
        $H = [];
        foreach (get_rows("SHOW ENGINES") as $I) {
            if (preg_match("~YES|DEFAULT~", $I["Support"])) {
                $H[] = $I["Engine"];
            }
        }
        return $H;
    }
    function logged_user()
    {
        global $g;
        return $g->result("SELECT USER()");
    }
    function tables_list()
    {
        return get_key_vals(
            min_version(5)
                ? "SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME"
                : "SHOW TABLES"
        );
    }
    function count_tables($k)
    {
        $H = [];
        foreach ($k as $l) {
            $H[$l] = count(get_vals("SHOW TABLES IN " . idf_escape($l)));
        }
        return $H;
    }
    function table_status($B = "", $Oc = false)
    {
        $H = [];
        foreach (
            get_rows(
                $Oc && min_version(5)
                    ? "SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() " .
                        ($B != ""
                            ? "AND TABLE_NAME = " . q($B)
                            : "ORDER BY Name")
                    : "SHOW TABLE STATUS" .
                        ($B != "" ? " LIKE " . q(addcslashes($B, "%_\\")) : "")
            )
            as $I
        ) {
            if ($I["Engine"] == "InnoDB") {
                $I["Comment"] = preg_replace(
                    '~(?:(.+); )?InnoDB free: .*~',
                    '\1',
                    $I["Comment"]
                );
            }
            if (!isset($I["Engine"])) {
                $I["Comment"] = "";
            }
            if ($B != "") {
                return $I;
            }
            $H[$I["Name"]] = $I;
        }
        return $H;
    }
    function is_view($R)
    {
        return $R["Engine"] === null;
    }
    function fk_support($R)
    {
        return preg_match('~InnoDB|IBMDB2I~i', $R["Engine"]) ||
            (preg_match('~NDB~i', $R["Engine"]) && min_version(5.6));
    }
    function fields($Q)
    {
        $H = [];
        foreach (get_rows("SHOW FULL COLUMNS FROM " . table($Q)) as $I) {
            preg_match(
                '~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',
                $I["Type"],
                $A
            );
            $H[$I["Field"]] = [
                "field" => $I["Field"],
                "full_type" => $I["Type"],
                "type" => $A[1],
                "length" => $A[2],
                "unsigned" => ltrim($A[3] . $A[4]),
                "default" =>
                    $I["Default"] != "" || preg_match("~char|set~", $A[1])
                        ? $I["Default"]
                        : null,
                "null" => $I["Null"] == "YES",
                "auto_increment" => $I["Extra"] == "auto_increment",
                "on_update" => preg_match('~^on update (.+)~i', $I["Extra"], $A)
                    ? $A[1]
                    : "",
                "collation" => $I["Collation"],
                "privileges" => array_flip(
                    preg_split('~, *~', $I["Privileges"])
                ),
                "comment" => $I["Comment"],
                "primary" => $I["Key"] == "PRI",
                "generated" => preg_match(
                    '~^(VIRTUAL|PERSISTENT|STORED)~',
                    $I["Extra"]
                ),
            ];
        }
        return $H;
    }
    function indexes($Q, $h = null)
    {
        $H = [];
        foreach (get_rows("SHOW INDEX FROM " . table($Q), $h) as $I) {
            $B = $I["Key_name"];
            $H[$B]["type"] =
                $B == "PRIMARY"
                    ? "PRIMARY"
                    : ($I["Index_type"] == "FULLTEXT"
                        ? "FULLTEXT"
                        : ($I["Non_unique"]
                            ? ($I["Index_type"] == "SPATIAL"
                                ? "SPATIAL"
                                : "INDEX")
                            : "UNIQUE"));
            $H[$B]["columns"][] = $I["Column_name"];
            $H[$B]["lengths"][] =
                $I["Index_type"] == "SPATIAL" ? null : $I["Sub_part"];
            $H[$B]["descs"][] = null;
        }
        return $H;
    }
    function foreign_keys($Q)
    {
        global $g, $qf;
        static $ag = '(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';
        $H = [];
        $Fb = $g->result("SHOW CREATE TABLE " . table($Q), 1);
        if ($Fb) {
            preg_match_all(
                "~CONSTRAINT ($ag) FOREIGN KEY ?\\(((?:$ag,? ?)+)\\) REFERENCES ($ag)(?:\\.($ag))? \\(((?:$ag,? ?)+)\\)(?: ON DELETE ($qf))?(?: ON UPDATE ($qf))?~",
                $Fb,
                $De,
                PREG_SET_ORDER
            );
            foreach ($De as $A) {
                preg_match_all("~$ag~", $A[2], $wh);
                preg_match_all("~$ag~", $A[5], $Xh);
                $H[idf_unescape($A[1])] = [
                    "db" => idf_unescape($A[4] != "" ? $A[3] : $A[4]),
                    "table" => idf_unescape($A[4] != "" ? $A[4] : $A[3]),
                    "source" => array_map('idf_unescape', $wh[0]),
                    "target" => array_map('idf_unescape', $Xh[0]),
                    "on_delete" => $A[6] ? $A[6] : "RESTRICT",
                    "on_update" => $A[7] ? $A[7] : "RESTRICT",
                ];
            }
        }
        return $H;
    }
    function view($B)
    {
        global $g;
        return [
            "select" => preg_replace(
                '~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU',
                '',
                $g->result("SHOW CREATE VIEW " . table($B), 1)
            ),
        ];
    }
    function collations()
    {
        $H = [];
        foreach (get_rows("SHOW COLLATION") as $I) {
            if ($I["Default"]) {
                $H[$I["Charset"]][-1] = $I["Collation"];
            } else {
                $H[$I["Charset"]][] = $I["Collation"];
            }
        }
        ksort($H);
        foreach ($H as $y => $X) {
            asort($H[$y]);
        }
        return $H;
    }
    function information_schema($l)
    {
        return (min_version(5) && $l == "information_schema") ||
            (min_version(5.5) && $l == "performance_schema");
    }
    function error()
    {
        global $g;
        return h(
            preg_replace(
                '~^You have an error.*syntax to use~U',
                "Syntax error",
                $g->error
            )
        );
    }
    function create_database($l, $d)
    {
        return queries(
            "CREATE DATABASE " .
                idf_escape($l) .
                ($d ? " COLLATE " . q($d) : "")
        );
    }
    function drop_databases($k)
    {
        $H = apply_queries("DROP DATABASE", $k, 'idf_escape');
        restart_session();
        set_session("dbs", null);
        return $H;
    }
    function rename_database($B, $d)
    {
        $H = false;
        if (create_database($B, $d)) {
            $Kg = [];
            foreach (tables_list() as $Q => $T) {
                $Kg[] = table($Q) . " TO " . idf_escape($B) . "." . table($Q);
            }
            $H = !$Kg || queries("RENAME TABLE " . implode(", ", $Kg));
            if ($H) {
                queries("DROP DATABASE " . idf_escape(DB));
            }
            restart_session();
            set_session("dbs", null);
        }
        return $H;
    }
    function auto_increment()
    {
        $Na = " PRIMARY KEY";
        if ($_GET["create"] != "" && $_POST["auto_increment_col"]) {
            foreach (indexes($_GET["create"]) as $v) {
                if (
                    in_array(
                        $_POST["fields"][$_POST["auto_increment_col"]]["orig"],
                        $v["columns"],
                        true
                    )
                ) {
                    $Na = "";
                    break;
                }
                if ($v["type"] == "PRIMARY") {
                    $Na = " UNIQUE";
                }
            }
        }
        return " AUTO_INCREMENT$Na";
    }
    function alter_table($Q, $B, $p, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = [];
        foreach ($p as $o) {
            $c[] = $o[1]
                ? ($Q != ""
                        ? ($o[0] != ""
                            ? "CHANGE " . idf_escape($o[0])
                            : "ADD")
                        : " ") .
                    " " .
                    implode($o[1]) .
                    ($Q != "" ? $o[2] : "")
                : "DROP " . idf_escape($o[0]);
        }
        $c = array_merge($c, $cd);
        $O =
            ($ub !== null ? " COMMENT=" . q($ub) : "") .
            ($uc ? " ENGINE=" . q($uc) : "") .
            ($d ? " COLLATE " . q($d) : "") .
            ($Ma != "" ? " AUTO_INCREMENT=$Ma" : "");
        if ($Q == "") {
            return queries(
                "CREATE TABLE " .
                    table($B) .
                    " (\n" .
                    implode(",\n", $c) .
                    "\n)$O$Uf"
            );
        }
        if ($Q != $B) {
            $c[] = "RENAME TO " . table($B);
        }
        if ($O) {
            $c[] = ltrim($O);
        }
        return $c || $Uf
            ? queries(
                "ALTER TABLE " . table($Q) . "\n" . implode(",\n", $c) . $Uf
            )
            : true;
    }
    function alter_indexes($Q, $c)
    {
        foreach ($c as $y => $X) {
            $c[$y] =
                $X[2] == "DROP"
                    ? "\nDROP INDEX " . idf_escape($X[1])
                    : "\nADD $X[0] " .
                        ($X[0] == "PRIMARY" ? "KEY " : "") .
                        ($X[1] != "" ? idf_escape($X[1]) . " " : "") .
                        "(" .
                        implode(", ", $X[2]) .
                        ")";
        }
        return queries("ALTER TABLE " . table($Q) . implode(",", $c));
    }
    function truncate_tables($S)
    {
        return apply_queries("TRUNCATE TABLE", $S);
    }
    function drop_views($aj)
    {
        return queries("DROP VIEW " . implode(", ", array_map('table', $aj)));
    }
    function drop_tables($S)
    {
        return queries("DROP TABLE " . implode(", ", array_map('table', $S)));
    }
    function move_tables($S, $aj, $Xh)
    {
        $Kg = [];
        foreach (array_merge($S, $aj) as $Q) {
            $Kg[] = table($Q) . " TO " . idf_escape($Xh) . "." . table($Q);
        }
        return queries("RENAME TABLE " . implode(", ", $Kg));
    }
    function copy_tables($S, $aj, $Xh)
    {
        queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");
        foreach ($S as $Q) {
            $B =
                $Xh == DB
                    ? table("copy_$Q")
                    : idf_escape($Xh) . "." . table($Q);
            if (
                ($_POST["overwrite"] &&
                    !queries("\nDROP TABLE IF EXISTS $B")) ||
                !queries("CREATE TABLE $B LIKE " . table($Q)) ||
                !queries("INSERT INTO $B SELECT * FROM " . table($Q))
            ) {
                return false;
            }
            foreach (
                get_rows("SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\")))
                as $I
            ) {
                $xi = $I["Trigger"];
                if (
                    !queries(
                        "CREATE TRIGGER " .
                            ($Xh == DB
                                ? idf_escape("copy_$xi")
                                : idf_escape($Xh) . "." . idf_escape($xi)) .
                            " $I[Timing] $I[Event] ON $B FOR EACH ROW\n$I[Statement];"
                    )
                ) {
                    return false;
                }
            }
        }
        foreach ($aj as $Q) {
            $B =
                $Xh == DB
                    ? table("copy_$Q")
                    : idf_escape($Xh) . "." . table($Q);
            $Zi = view($Q);
            if (
                ($_POST["overwrite"] && !queries("DROP VIEW IF EXISTS $B")) ||
                !queries("CREATE VIEW $B AS $Zi[select]")
            ) {
                return false;
            }
        }
        return true;
    }
    function trigger($B)
    {
        if ($B == "") {
            return [];
        }
        $J = get_rows("SHOW TRIGGERS WHERE `Trigger` = " . q($B));
        return reset($J);
    }
    function triggers($Q)
    {
        $H = [];
        foreach (
            get_rows("SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\")))
            as $I
        ) {
            $H[$I["Trigger"]] = [$I["Timing"], $I["Event"]];
        }
        return $H;
    }
    function trigger_options()
    {
        return [
            "Timing" => ["BEFORE", "AFTER"],
            "Event" => ["INSERT", "UPDATE", "DELETE"],
            "Type" => ["FOR EACH ROW"],
        ];
    }
    function routine($B, $T)
    {
        global $g, $wc, $Rd, $U;
        $Ca = [
            "bool",
            "boolean",
            "integer",
            "double precision",
            "real",
            "dec",
            "numeric",
            "fixed",
            "national char",
            "national varchar",
        ];
        $xh = "(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";
        $Bi =
            "((" .
            implode("|", array_merge(array_keys($U), $Ca)) .
            ")\\b(?:\\s*\\(((?:[^'\")]|$wc)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";
        $ag =
            "$xh*(" .
            ($T == "FUNCTION" ? "" : $Rd) .
            ")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Bi";
        $i = $g->result("SHOW CREATE $T " . idf_escape($B), 2);
        preg_match(
            "~\\(((?:$ag\\s*,?)*)\\)\\s*" .
                ($T == "FUNCTION" ? "RETURNS\\s+$Bi\\s+" : "") .
                "(.*)~is",
            $i,
            $A
        );
        $p = [];
        preg_match_all("~$ag\\s*,?~is", $A[1], $De, PREG_SET_ORDER);
        foreach ($De as $Nf) {
            $p[] = [
                "field" => str_replace("``", "`", $Nf[2]) . $Nf[3],
                "type" => strtolower($Nf[5]),
                "length" => preg_replace_callback(
                    "~$wc~s",
                    'normalize_enum',
                    $Nf[6]
                ),
                "unsigned" => strtolower(
                    preg_replace('~\s+~', ' ', trim("$Nf[8] $Nf[7]"))
                ),
                "null" => 1,
                "full_type" => $Nf[4],
                "inout" => strtoupper($Nf[1]),
                "collation" => strtolower($Nf[9]),
            ];
        }
        if ($T != "FUNCTION") {
            return ["fields" => $p, "definition" => $A[11]];
        }
        return [
            "fields" => $p,
            "returns" => [
                "type" => $A[12],
                "length" => $A[13],
                "unsigned" => $A[15],
                "collation" => $A[16],
            ],
            "definition" => $A[17],
            "language" => "SQL",
        ];
    }
    function routines()
    {
        return get_rows(
            "SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = " .
                q(DB)
        );
    }
    function routine_languages()
    {
        return [];
    }
    function routine_id($B, $I)
    {
        return idf_escape($B);
    }
    function last_id()
    {
        global $g;
        return $g->result("SELECT LAST_INSERT_ID()");
    }
    function explain($g, $F)
    {
        return $g->query(
            "EXPLAIN " . (min_version(5.1) ? "PARTITIONS " : "") . $F
        );
    }
    function found_rows($R, $Z)
    {
        return $Z || $R["Engine"] != "InnoDB" ? null : $R["Rows"];
    }
    function types()
    {
        return [];
    }
    function schemas()
    {
        return [];
    }
    function get_schema()
    {
        return "";
    }
    function set_schema($ah, $h = null)
    {
        return true;
    }
    function create_sql($Q, $Ma, $Ih)
    {
        global $g;
        $H = $g->result("SHOW CREATE TABLE " . table($Q), 1);
        if (!$Ma) {
            $H = preg_replace('~ AUTO_INCREMENT=\d+~', '', $H);
        }
        return $H;
    }
    function truncate_sql($Q)
    {
        return "TRUNCATE " . table($Q);
    }
    function use_sql($j)
    {
        return "USE " . idf_escape($j);
    }
    function trigger_sql($Q)
    {
        $H = "";
        foreach (
            get_rows(
                "SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\")),
                null,
                "-- "
            )
            as $I
        ) {
            $H .=
                "\nCREATE TRIGGER " .
                idf_escape($I["Trigger"]) .
                " $I[Timing] $I[Event] ON " .
                table($I["Table"]) .
                " FOR EACH ROW\n$I[Statement];;\n";
        }
        return $H;
    }
    function show_variables()
    {
        return get_key_vals("SHOW VARIABLES");
    }
    function process_list()
    {
        return get_rows("SHOW FULL PROCESSLIST");
    }
    function show_status()
    {
        return get_key_vals("SHOW STATUS");
    }
    function convert_field($o)
    {
        if (preg_match("~binary~", $o["type"])) {
            return "HEX(" . idf_escape($o["field"]) . ")";
        }
        if ($o["type"] == "bit") {
            return "BIN(" . idf_escape($o["field"]) . " + 0)";
        }
        if (preg_match("~geometry|point|linestring|polygon~", $o["type"])) {
            return (min_version(8) ? "ST_" : "") .
                "AsWKT(" .
                idf_escape($o["field"]) .
                ")";
        }
    }
    function unconvert_field($o, $H)
    {
        if (preg_match("~binary~", $o["type"])) {
            $H = "UNHEX($H)";
        }
        if ($o["type"] == "bit") {
            $H = "CONV($H, 2, 10) + 0";
        }
        if (preg_match("~geometry|point|linestring|polygon~", $o["type"])) {
            $H =
                (min_version(8) ? "ST_" : "") .
                "GeomFromText($H, SRID($o[field]))";
        }
        return $H;
    }
    function support($Pc)
    {
        return !preg_match(
            "~scheme|sequence|type|view_trigger|materializedview" .
                (min_version(8)
                    ? ""
                    : "|descidx" .
                        (min_version(5.1)
                            ? ""
                            : "|event|partitioning" .
                                (min_version(5)
                                    ? ""
                                    : "|routine|trigger|view"))) .
                "~",
            $Pc
        );
    }
    function kill_process($X)
    {
        return queries("KILL " . number($X));
    }
    function connection_id()
    {
        return "SELECT CONNECTION_ID()";
    }
    function max_connections()
    {
        global $g;
        return $g->result("SELECT @@max_connections");
    }
    $x = "sql";
    $U = [];
    $Hh = [];
    foreach (
        [
            'Numbers' => [
                "tinyint" => 3,
                "smallint" => 5,
                "mediumint" => 8,
                "int" => 10,
                "bigint" => 20,
                "decimal" => 66,
                "float" => 12,
                "double" => 21,
            ],
            'Date and time' => [
                "date" => 10,
                "datetime" => 19,
                "timestamp" => 19,
                "time" => 10,
                "year" => 4,
            ],
            'Strings' => [
                "char" => 255,
                "varchar" => 65535,
                "tinytext" => 255,
                "text" => 65535,
                "mediumtext" => 16777215,
                "longtext" => 4294967295,
            ],
            'Lists' => ["enum" => 65535, "set" => 64],
            'Binary' => [
                "bit" => 20,
                "binary" => 255,
                "varbinary" => 65535,
                "tinyblob" => 255,
                "blob" => 65535,
                "mediumblob" => 16777215,
                "longblob" => 4294967295,
            ],
            'Geometry' => [
                "geometry" => 0,
                "point" => 0,
                "linestring" => 0,
                "polygon" => 0,
                "multipoint" => 0,
                "multilinestring" => 0,
                "multipolygon" => 0,
                "geometrycollection" => 0,
            ],
        ]
        as $y => $X
    ) {
        $U += $X;
        $Hh[$y] = array_keys($X);
    }
    $Ii = ["unsigned", "zerofill", "unsigned zerofill"];
    $vf = [
        "=",
        "<",
        ">",
        "<=",
        ">=",
        "!=",
        "LIKE",
        "LIKE %%",
        "REGEXP",
        "IN",
        "FIND_IN_SET",
        "IS NULL",
        "NOT LIKE",
        "NOT REGEXP",
        "NOT IN",
        "IS NOT NULL",
        "SQL",
    ];
    $kd = [
        "char_length",
        "date",
        "from_unixtime",
        "lower",
        "round",
        "floor",
        "ceil",
        "sec_to_time",
        "time_to_sec",
        "upper",
    ];
    $qd = [
        "avg",
        "count",
        "count distinct",
        "group_concat",
        "max",
        "min",
        "sum",
    ];
    $mc = [
        [
            "char" => "md5/sha1/password/encrypt/uuid",
            "binary" => "md5/sha1",
            "date|time" => "now",
        ],
        [
            number_type() => "+/-",
            "date" => "+ interval/- interval",
            "time" => "addtime/subtime",
            "char|text" => "concat",
        ],
    ];
}
define("SERVER", $_GET[DRIVER]);
define("DB", $_GET["db"]);
define(
    "ME",
    str_replace(
        ":",
        "%3a",
        preg_replace('~^[^?]*/([^?]*).*~', '\1', $_SERVER["REQUEST_URI"])
    ) .
        '?' .
        (sid() ? SID . '&' : '') .
        (SERVER !== null ? DRIVER . "=" . urlencode(SERVER) . '&' : '') .
        (isset($_GET["username"])
            ? "username=" . urlencode($_GET["username"]) . '&'
            : '') .
        (DB != ""
            ? 'db=' .
                urlencode(DB) .
                '&' .
                (isset($_GET["ns"]) ? "ns=" . urlencode($_GET["ns"]) . "&" : "")
            : '')
);
$ia = "4.7.5";
class Adminer
{
    var $operators;
    function name()
    {
        return "<a href='https://www.adminer.org/'" .
            target_blank() .
            " id='h1'>Adminer</a>";
    }
    function credentials()
    {
        return [SERVER, $_GET["username"], get_password()];
    }
    function connectSsl()
    {
    }
    function permanentLogin($i = false)
    {
        return password_file($i);
    }
    function bruteForceKey()
    {
        return $_SERVER["REMOTE_ADDR"];
    }
    function serverName($M)
    {
        return h($M);
    }
    function database()
    {
        return DB;
    }
    function databases($ad = true)
    {
        return get_databases($ad);
    }
    function schemas()
    {
        return schemas();
    }
    function queryTimeout()
    {
        return 2;
    }
    function headers()
    {
    }
    function csp()
    {
        return csp();
    }
    function head()
    {
        return true;
    }
    function css()
    {
        $H = [];
        $Uc = "adminer.css";
        if (file_exists($Uc)) {
            $H[] = "$Uc?v=" . crc32(file_get_contents($Uc));
        }
        return $H;
    }
    function loginForm()
    {
        global $ec;
        echo "<table cellspacing='0' class='layout'>\n",
            $this->loginFormField(
                'driver',
                '<tr><th>' . 'System' . '<td>',
                html_select("auth[driver]", $ec, DRIVER, "loginDriver(this);") .
                    "\n"
            ),
            $this->loginFormField(
                'server',
                '<tr><th>' . 'Server' . '<td>',
                '<input name="auth[server]" value="' .
                    h(SERVER) .
                    '" title="hostname[:port]" placeholder="localhost" autocapitalize="off">' .
                    "\n"
            ),
            $this->loginFormField(
                'username',
                '<tr><th>' . 'Username' . '<td>',
                '<input name="auth[username]" id="username" value="' .
                    h($_GET["username"]) .
                    '" autocomplete="username" autocapitalize="off">' .
                    script(
                        "focus(qs('#username')); qs('#username').form['auth[driver]'].onchange();"
                    )
            ),
            $this->loginFormField(
                'password',
                '<tr><th>' . 'Password' . '<td>',
                '<input type="password" name="auth[password]" autocomplete="current-password">' .
                    "\n"
            ),
            $this->loginFormField(
                'db',
                '<tr><th>' . 'Database' . '<td>',
                '<input name="auth[db]" value="' .
                    h($_GET["db"]) .
                    '" autocapitalize="off">' .
                    "\n"
            ),
            "</table>\n",
            "<p><input type='submit' value='" . 'Login' . "'>\n",
            checkbox(
                "auth[permanent]",
                1,
                $_COOKIE["adminer_permanent"],
                'Permanent login'
            ) . "\n";
    }
    function loginFormField($B, $xd, $Y)
    {
        return $xd . $Y;
    }
    function login($ye, $E)
    {
        if ($E == "") {
            return sprintf(
                'Adminer does not support accessing a database without a password, <a href="https://www.adminer.org/en/password/"%s>more information</a>.',
                target_blank()
            );
        }
        return true;
    }
    function tableName($Oh)
    {
        return h($Oh["Name"]);
    }
    function fieldName($o, $_f = 0)
    {
        return '<span title="' .
            h($o["full_type"]) .
            '">' .
            h($o["field"]) .
            '</span>';
    }
    function selectLinks($Oh, $N = "")
    {
        global $x, $m;
        echo '<p class="links">';
        $we = ["select" => 'Select data'];
        if (support("table") || support("indexes")) {
            $we["table"] = 'Show structure';
        }
        if (support("table")) {
            if (is_view($Oh)) {
                $we["view"] = 'Alter view';
            } else {
                $we["create"] = 'Alter table';
            }
        }
        if ($N !== null) {
            $we["edit"] = 'New item';
        }
        $B = $Oh["Name"];
        foreach ($we as $y => $X) {
            echo " <a href='" .
                h(ME) .
                "$y=" .
                urlencode($B) .
                ($y == "edit" ? $N : "") .
                "'" .
                bold(isset($_GET[$y])) .
                ">$X</a>";
        }
        echo doc_link([$x => $m->tableHelp($B)], "?"), "\n";
    }
    function foreignKeys($Q)
    {
        return foreign_keys($Q);
    }
    function backwardKeys($Q, $Nh)
    {
        return [];
    }
    function backwardKeysPrint($Pa, $I)
    {
    }
    function selectQuery($F, $Dh, $Nc = false)
    {
        global $x, $m;
        $H = "</p>\n";
        if (!$Nc && ($dj = $m->warnings())) {
            $t = "warnings";
            $H =
                ", <a href='#$t'>" .
                'Warnings' .
                "</a>" .
                script("qsl('a').onclick = partial(toggle, '$t');", "") .
                "$H<div id='$t' class='hidden'>\n$dj</div>\n";
        }
        return "<p><code class='jush-$x'>" .
            h(str_replace("\n", " ", $F)) .
            "</code> <span class='time'>(" .
            format_time($Dh) .
            ")</span>" .
            (support("sql")
                ? " <a href='" .
                    h(ME) .
                    "sql=" .
                    urlencode($F) .
                    "'>" .
                    'Edit' .
                    "</a>"
                : "") .
            $H;
    }
    function sqlCommandQuery($F)
    {
        return shorten_utf8(trim($F), 1000);
    }
    function rowDescription($Q)
    {
        return "";
    }
    function rowDescriptions($J, $dd)
    {
        return $J;
    }
    function selectLink($X, $o)
    {
    }
    function selectVal($X, $_, $o, $Hf)
    {
        $H =
            $X === null
                ? "<i>NULL</i>"
                : (preg_match("~char|binary|boolean~", $o["type"]) &&
                !preg_match("~var~", $o["type"])
                    ? "<code>$X</code>"
                    : $X);
        if (preg_match('~blob|bytea|raw|file~', $o["type"]) && !is_utf8($X)) {
            $H = "<i>" . lang(['%d byte', '%d bytes'], strlen($Hf)) . "</i>";
        }
        if (preg_match('~json~', $o["type"])) {
            $H = "<code class='jush-js'>$H</code>";
        }
        return $_
            ? "<a href='" .
                    h($_) .
                    "'" .
                    (is_url($_) ? target_blank() : "") .
                    ">$H</a>"
            : $H;
    }
    function editVal($X, $o)
    {
        return $X;
    }
    function tableStructurePrint($p)
    {
        echo "<div class='scrollable'>\n",
            "<table cellspacing='0' class='nowrap'>\n",
            "<thead><tr><th>" .
                'Column' .
                "<td>" .
                'Type' .
                (support("comment") ? "<td>" . 'Comment' : "") .
                "</thead>\n";
        foreach ($p as $o) {
            echo "<tr" . odd() . "><th>" . h($o["field"]),
                "<td><span title='" .
                    h($o["collation"]) .
                    "'>" .
                    h($o["full_type"]) .
                    "</span>",
                $o["null"] ? " <i>NULL</i>" : "",
                $o["auto_increment"] ? " <i>" . 'Auto Increment' . "</i>" : "",
                isset($o["default"])
                    ? " <span title='" .
                        'Default value' .
                        "'>[<b>" .
                        h($o["default"]) .
                        "</b>]</span>"
                    : "",
                support("comment") ? "<td>" . h($o["comment"]) : "",
                "\n";
        }
        echo "</table>\n", "</div>\n";
    }
    function tableIndexesPrint($w)
    {
        echo "<table cellspacing='0'>\n";
        foreach ($w as $B => $v) {
            ksort($v["columns"]);
            $mg = [];
            foreach ($v["columns"] as $y => $X) {
                $mg[] =
                    "<i>" .
                    h($X) .
                    "</i>" .
                    ($v["lengths"][$y] ? "(" . $v["lengths"][$y] . ")" : "") .
                    ($v["descs"][$y] ? " DESC" : "");
            }
            echo "<tr title='" .
                h($B) .
                "'><th>$v[type]<td>" .
                implode(", ", $mg) .
                "\n";
        }
        echo "</table>\n";
    }
    function selectColumnsPrint($K, $f)
    {
        global $kd, $qd;
        print_fieldset("select", 'Select', $K);
        $s = 0;
        $K[""] = [];
        foreach ($K as $y => $X) {
            $X = $_GET["columns"][$y];
            $e = select_input(
                " name='columns[$s][col]'",
                $f,
                $X["col"],
                $y !== "" ? "selectFieldChange" : "selectAddRow"
            );
            echo "<div>" .
                ($kd || $qd
                    ? "<select name='columns[$s][fun]'>" .
                        optionlist(
                            [-1 => ""] +
                                array_filter([
                                    'Functions' => $kd,
                                    'Aggregation' => $qd,
                                ]),
                            $X["fun"]
                        ) .
                        "</select>" .
                        on_help(
                            "getTarget(event).value && getTarget(event).value.replace(/ |\$/, '(') + ')'",
                            1
                        ) .
                        script(
                            "qsl('select').onchange = function () { helpClose();" .
                                ($y !== ""
                                    ? ""
                                    : " qsl('select, input', this.parentNode).onchange();") .
                                " };",
                            ""
                        ) .
                        "($e)"
                    : $e) .
                "</div>\n";
            $s++;
        }
        echo "</div></fieldset>\n";
    }
    function selectSearchPrint($Z, $f, $w)
    {
        print_fieldset("search", 'Search', $Z);
        foreach ($w as $s => $v) {
            if ($v["type"] == "FULLTEXT") {
                echo "<div>(<i>" .
                implode("</i>, <i>", array_map('h', $v["columns"])) .
                "</i>) AGAINST",
                    " <input type='search' name='fulltext[$s]' value='" .
                        h($_GET["fulltext"][$s]) .
                        "'>",
                    script("qsl('input').oninput = selectFieldChange;", ""),
                    checkbox(
                        "boolean[$s]",
                        1,
                        isset($_GET["boolean"][$s]),
                        "BOOL"
                    ),
                    "</div>\n";
            }
        }
        $bb = "this.parentNode.firstChild.onchange();";
        foreach (array_merge((array) $_GET["where"], [[]]) as $s => $X) {
            if (
                !$X ||
                ("$X[col]$X[val]" != "" && in_array($X["op"], $this->operators))
            ) {
                echo "<div>" .
                select_input(
                    " name='where[$s][col]'",
                    $f,
                    $X["col"],
                    $X ? "selectFieldChange" : "selectAddRow",
                    "(" . 'anywhere' . ")"
                ),
                    html_select(
                        "where[$s][op]",
                        $this->operators,
                        $X["op"],
                        $bb
                    ),
                    "<input type='search' name='where[$s][val]' value='" .
                        h($X["val"]) .
                        "'>",
                    script(
                        "mixin(qsl('input'), {oninput: function () { $bb }, onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});",
                        ""
                    ),
                    "</div>\n";
            }
        }
        echo "</div></fieldset>\n";
    }
    function selectOrderPrint($_f, $f, $w)
    {
        print_fieldset("sort", 'Sort', $_f);
        $s = 0;
        foreach ((array) $_GET["order"] as $y => $X) {
            if ($X != "") {
                echo "<div>" .
                select_input(" name='order[$s]'", $f, $X, "selectFieldChange"),
                    checkbox(
                        "desc[$s]",
                        1,
                        isset($_GET["desc"][$y]),
                        'descending'
                    ) . "</div>\n";
                $s++;
            }
        }
        echo "<div>" .
        select_input(" name='order[$s]'", $f, "", "selectAddRow"),
            checkbox("desc[$s]", 1, false, 'descending') . "</div>\n",
            "</div></fieldset>\n";
    }
    function selectLimitPrint($z)
    {
        echo "<fieldset><legend>" . 'Limit' . "</legend><div>";
        echo "<input type='number' name='limit' class='size' value='" .
        h($z) .
        "'>",
            script("qsl('input').oninput = selectFieldChange;", ""),
            "</div></fieldset>\n";
    }
    function selectLengthPrint($di)
    {
        if ($di !== null) {
            echo "<fieldset><legend>" . 'Text length' . "</legend><div>",
                "<input type='number' name='text_length' class='size' value='" .
                    h($di) .
                    "'>",
                "</div></fieldset>\n";
        }
    }
    function selectActionPrint($w)
    {
        echo "<fieldset><legend>" . 'Action' . "</legend><div>",
            "<input type='submit' value='" . 'Select' . "'>",
            " <span id='noindex' title='" . 'Full table scan' . "'></span>",
            "<script" . nonce() . ">\n",
            "var indexColumns = ";
        $f = [];
        foreach ($w as $v) {
            $Lb = reset($v["columns"]);
            if ($v["type"] != "FULLTEXT" && $Lb) {
                $f[$Lb] = 1;
            }
        }
        $f[""] = 1;
        foreach ($f as $y => $X) {
            json_row($y);
        }
        echo ";\n",
            "selectFieldChange.call(qs('#form')['select']);\n",
            "</script>\n",
            "</div></fieldset>\n";
    }
    function selectCommandPrint()
    {
        return !information_schema(DB);
    }
    function selectImportPrint()
    {
        return !information_schema(DB);
    }
    function selectEmailPrint($rc, $f)
    {
    }
    function selectColumnsProcess($f, $w)
    {
        global $kd, $qd;
        $K = [];
        $nd = [];
        foreach ((array) $_GET["columns"] as $y => $X) {
            if (
                $X["fun"] == "count" ||
                ($X["col"] != "" &&
                    (!$X["fun"] ||
                        in_array($X["fun"], $kd) ||
                        in_array($X["fun"], $qd)))
            ) {
                $K[$y] = apply_sql_function(
                    $X["fun"],
                    $X["col"] != "" ? idf_escape($X["col"]) : "*"
                );
                if (!in_array($X["fun"], $qd)) {
                    $nd[] = $K[$y];
                }
            }
        }
        return [$K, $nd];
    }
    function selectSearchProcess($p, $w)
    {
        global $g, $m;
        $H = [];
        foreach ($w as $s => $v) {
            if ($v["type"] == "FULLTEXT" && $_GET["fulltext"][$s] != "") {
                $H[] =
                    "MATCH (" .
                    implode(", ", array_map('idf_escape', $v["columns"])) .
                    ") AGAINST (" .
                    q($_GET["fulltext"][$s]) .
                    (isset($_GET["boolean"][$s]) ? " IN BOOLEAN MODE" : "") .
                    ")";
            }
        }
        foreach ((array) $_GET["where"] as $y => $X) {
            if (
                "$X[col]$X[val]" != "" &&
                in_array($X["op"], $this->operators)
            ) {
                $ig = "";
                $wb = " $X[op]";
                if (preg_match('~IN$~', $X["op"])) {
                    $Hd = process_length($X["val"]);
                    $wb .= " " . ($Hd != "" ? $Hd : "(NULL)");
                } elseif ($X["op"] == "SQL") {
                    $wb = " $X[val]";
                } elseif ($X["op"] == "LIKE %%") {
                    $wb =
                        " LIKE " .
                        $this->processInput($p[$X["col"]], "%$X[val]%");
                } elseif ($X["op"] == "ILIKE %%") {
                    $wb =
                        " ILIKE " .
                        $this->processInput($p[$X["col"]], "%$X[val]%");
                } elseif ($X["op"] == "FIND_IN_SET") {
                    $ig = "$X[op](" . q($X["val"]) . ", ";
                    $wb = ")";
                } elseif (!preg_match('~NULL$~', $X["op"])) {
                    $wb .= " " . $this->processInput($p[$X["col"]], $X["val"]);
                }
                if ($X["col"] != "") {
                    $H[] =
                        $ig .
                        $m->convertSearch(
                            idf_escape($X["col"]),
                            $X,
                            $p[$X["col"]]
                        ) .
                        $wb;
                } else {
                    $rb = [];
                    foreach ($p as $B => $o) {
                        if (
                            (preg_match(
                                '~^[-\d.' .
                                    (preg_match('~IN$~', $X["op"]) ? ',' : '') .
                                    ']+$~',
                                $X["val"]
                            ) ||
                                !preg_match(
                                    '~' . number_type() . '|bit~',
                                    $o["type"]
                                )) &&
                            (!preg_match("~[\x80-\xFF]~", $X["val"]) ||
                                preg_match('~char|text|enum|set~', $o["type"]))
                        ) {
                            $rb[] =
                                $ig .
                                $m->convertSearch(idf_escape($B), $X, $o) .
                                $wb;
                        }
                    }
                    $H[] = $rb ? "(" . implode(" OR ", $rb) . ")" : "1 = 0";
                }
            }
        }
        return $H;
    }
    function selectOrderProcess($p, $w)
    {
        $H = [];
        foreach ((array) $_GET["order"] as $y => $X) {
            if ($X != "") {
                $H[] =
                    (preg_match(
                        '~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~',
                        $X
                    )
                        ? $X
                        : idf_escape($X)) .
                    (isset($_GET["desc"][$y]) ? " DESC" : "");
            }
        }
        return $H;
    }
    function selectLimitProcess()
    {
        return isset($_GET["limit"]) ? $_GET["limit"] : "50";
    }
    function selectLengthProcess()
    {
        return isset($_GET["text_length"]) ? $_GET["text_length"] : "100";
    }
    function selectEmailProcess($Z, $dd)
    {
        return false;
    }
    function selectQueryBuild($K, $Z, $nd, $_f, $z, $D)
    {
        return "";
    }
    function messageQuery($F, $ei, $Nc = false)
    {
        global $x, $m;
        restart_session();
        $yd = &get_session("queries");
        if (!$yd[$_GET["db"]]) {
            $yd[$_GET["db"]] = [];
        }
        if (strlen($F) > 1e6) {
            $F =
                preg_replace('~[\x80-\xFF]+$~', '', substr($F, 0, 1e6)) . "\n…";
        }
        $yd[$_GET["db"]][] = [$F, time(), $ei];
        $Ah = "sql-" . count($yd[$_GET["db"]]);
        $H = "<a href='#$Ah' class='toggle'>" . 'SQL command' . "</a>\n";
        if (!$Nc && ($dj = $m->warnings())) {
            $t = "warnings-" . count($yd[$_GET["db"]]);
            $H =
                "<a href='#$t' class='toggle'>" .
                'Warnings' .
                "</a>, $H<div id='$t' class='hidden'>\n$dj</div>\n";
        }
        return " <span class='time'>" .
            @date("H:i:s") .
            "</span>" .
            " $H<div id='$Ah' class='hidden'><pre><code class='jush-$x'>" .
            shorten_utf8($F, 1000) .
            "</code></pre>" .
            ($ei ? " <span class='time'>($ei)</span>" : '') .
            (support("sql")
                ? '<p><a href="' .
                    h(
                        str_replace(
                            "db=" . urlencode(DB),
                            "db=" . urlencode($_GET["db"]),
                            ME
                        ) .
                            'sql=&history=' .
                            (count($yd[$_GET["db"]]) - 1)
                    ) .
                    '">' .
                    'Edit' .
                    '</a>'
                : '') .
            '</div>';
    }
    function editFunctions($o)
    {
        global $mc;
        $H = $o["null"] ? "NULL/" : "";
        foreach ($mc as $y => $kd) {
            if (
                !$y ||
                (!isset($_GET["call"]) &&
                    (isset($_GET["select"]) || where($_GET)))
            ) {
                foreach ($kd as $ag => $X) {
                    if (!$ag || preg_match("~$ag~", $o["type"])) {
                        $H .= "/$X";
                    }
                }
                if (
                    $y &&
                    !preg_match('~set|blob|bytea|raw|file~', $o["type"])
                ) {
                    $H .= "/SQL";
                }
            }
        }
        if ($o["auto_increment"] && !isset($_GET["select"]) && !where($_GET)) {
            $H = 'Auto Increment';
        }
        return explode("/", $H);
    }
    function editInput($Q, $o, $Ja, $Y)
    {
        if ($o["type"] == "enum") {
            return (isset($_GET["select"])
                ? "<label><input type='radio'$Ja value='-1' checked><i>" .
                    'original' .
                    "</i></label> "
                : "") .
                ($o["null"]
                    ? "<label><input type='radio'$Ja value=''" .
                        ($Y !== null || isset($_GET["select"])
                            ? ""
                            : " checked") .
                        "><i>NULL</i></label> "
                    : "") .
                enum_input("radio", $Ja, $o, $Y, 0);
        }
        return "";
    }
    function editHint($Q, $o, $Y)
    {
        return "";
    }
    function processInput($o, $Y, $r = "")
    {
        if ($r == "SQL") {
            return $Y;
        }
        $B = $o["field"];
        $H = q($Y);
        if (preg_match('~^(now|getdate|uuid)$~', $r)) {
            $H = "$r()";
        } elseif (preg_match('~^current_(date|timestamp)$~', $r)) {
            $H = $r;
        } elseif (preg_match('~^([+-]|\|\|)$~', $r)) {
            $H = idf_escape($B) . " $r $H";
        } elseif (preg_match('~^[+-] interval$~', $r)) {
            $H =
                idf_escape($B) .
                " $r " .
                (preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i", $Y) ? $Y : $H);
        } elseif (preg_match('~^(addtime|subtime|concat)$~', $r)) {
            $H = "$r(" . idf_escape($B) . ", $H)";
        } elseif (preg_match('~^(md5|sha1|password|encrypt)$~', $r)) {
            $H = "$r($H)";
        }
        return unconvert_field($o, $H);
    }
    function dumpOutput()
    {
        $H = ['text' => 'open', 'file' => 'save'];
        if (function_exists('gzencode')) {
            $H['gz'] = 'gzip';
        }
        return $H;
    }
    function dumpFormat()
    {
        return [
            'sql' => 'SQL',
            'csv' => 'CSV,',
            'csv;' => 'CSV;',
            'tsv' => 'TSV',
        ];
    }
    function dumpDatabase($l)
    {
    }
    function dumpTable($Q, $Ih, $ae = 0)
    {
        if ($_POST["format"] != "sql") {
            echo "\xef\xbb\xbf";
            if ($Ih) {
                dump_csv(array_keys(fields($Q)));
            }
        } else {
            if ($ae == 2) {
                $p = [];
                foreach (fields($Q) as $B => $o) {
                    $p[] = idf_escape($B) . " $o[full_type]";
                }
                $i =
                    "CREATE TABLE " .
                    table($Q) .
                    " (" .
                    implode(", ", $p) .
                    ")";
            } else {
                $i = create_sql($Q, $_POST["auto_increment"], $Ih);
            }
            set_utf8mb4($i);
            if ($Ih && $i) {
                if ($Ih == "DROP+CREATE" || $ae == 1) {
                    echo "DROP " .
                        ($ae == 2 ? "VIEW" : "TABLE") .
                        " IF EXISTS " .
                        table($Q) .
                        ";\n";
                }
                if ($ae == 1) {
                    $i = remove_definer($i);
                }
                echo "$i;\n\n";
            }
        }
    }
    function dumpData($Q, $Ih, $F)
    {
        global $g, $x;
        $Fe = $x == "sqlite" ? 0 : 1048576;
        if ($Ih) {
            if ($_POST["format"] == "sql") {
                if ($Ih == "TRUNCATE+INSERT") {
                    echo truncate_sql($Q) . ";\n";
                }
                $p = fields($Q);
            }
            $G = $g->query($F, 1);
            if ($G) {
                $Td = "";
                $Ya = "";
                $he = [];
                $Kh = "";
                $Qc = $Q != '' ? 'fetch_assoc' : 'fetch_row';
                while ($I = $G->$Qc()) {
                    if (!$he) {
                        $Vi = [];
                        foreach ($I as $X) {
                            $o = $G->fetch_field();
                            $he[] = $o->name;
                            $y = idf_escape($o->name);
                            $Vi[] = "$y = VALUES($y)";
                        }
                        $Kh =
                            ($Ih == "INSERT+UPDATE"
                                ? "\nON DUPLICATE KEY UPDATE " .
                                    implode(", ", $Vi)
                                : "") . ";\n";
                    }
                    if ($_POST["format"] != "sql") {
                        if ($Ih == "table") {
                            dump_csv($he);
                            $Ih = "INSERT";
                        }
                        dump_csv($I);
                    } else {
                        if (!$Td) {
                            $Td =
                                "INSERT INTO " .
                                table($Q) .
                                " (" .
                                implode(", ", array_map('idf_escape', $he)) .
                                ") VALUES";
                        }
                        foreach ($I as $y => $X) {
                            $o = $p[$y];
                            $I[$y] =
                                $X !== null
                                    ? unconvert_field(
                                        $o,
                                        preg_match(number_type(), $o["type"]) &&
                                        !preg_match('~\[~', $o["full_type"]) &&
                                        is_numeric($X)
                                            ? $X
                                            : q($X === false ? 0 : $X)
                                    )
                                    : "NULL";
                        }
                        $Yg =
                            ($Fe ? "\n" : " ") . "(" . implode(",\t", $I) . ")";
                        if (!$Ya) {
                            $Ya = $Td . $Yg;
                        } elseif (
                            strlen($Ya) + 4 + strlen($Yg) + strlen($Kh) <
                            $Fe
                        ) {
                            $Ya .= ",$Yg";
                        } else {
                            echo $Ya . $Kh;
                            $Ya = $Td . $Yg;
                        }
                    }
                }
                if ($Ya) {
                    echo $Ya . $Kh;
                }
            } elseif ($_POST["format"] == "sql") {
                echo "-- " . str_replace("\n", " ", $g->error) . "\n";
            }
        }
    }
    function dumpFilename($Cd)
    {
        return friendly_url(
            $Cd != "" ? $Cd : (SERVER != "" ? SERVER : "localhost")
        );
    }
    function dumpHeaders($Cd, $Ue = false)
    {
        $Kf = $_POST["output"];
        $Ic = preg_match('~sql~', $_POST["format"])
            ? "sql"
            : ($Ue
                ? "tar"
                : "csv");
        header(
            "Content-Type: " .
                ($Kf == "gz"
                    ? "application/x-gzip"
                    : ($Ic == "tar"
                        ? "application/x-tar"
                        : ($Ic == "sql" || $Kf != "file"
                                ? "text/plain"
                                : "text/csv") . "; charset=utf-8"))
        );
        if ($Kf == "gz") {
            ob_start('ob_gzencode', 1e6);
        }
        return $Ic;
    }
    function importServerPath()
    {
        return "adminer.sql";
    }
    function homepage()
    {
        echo '<p class="links">' .
        ($_GET["ns"] == "" && support("database")
            ? '<a href="' . h(ME) . 'database=">' . 'Alter database' . "</a>\n"
            : ""),
            support("scheme")
                ? "<a href='" .
                    h(ME) .
                    "scheme='>" .
                    ($_GET["ns"] != "" ? 'Alter schema' : 'Create schema') .
                    "</a>\n"
                : "",
            $_GET["ns"] !== ""
                ? '<a href="' .
                    h(ME) .
                    'schema=">' .
                    'Database schema' .
                    "</a>\n"
                : "",
            support("privileges")
                ? "<a href='" .
                    h(ME) .
                    "privileges='>" .
                    'Privileges' .
                    "</a>\n"
                : "";
        return true;
    }
    function navigation($Te)
    {
        global $ia, $x, $ec, $g;
        echo '<h1>
',
            $this->name(),
            ' <span class="version">',
            $ia,
            '</span>
<a href="https://www.adminer.org/#download"',
            target_blank(),
            ' id="version">',
            version_compare($ia, $_COOKIE["adminer_version"]) < 0
                ? h($_COOKIE["adminer_version"])
                : "",
            '</a>
</h1>
';
        if ($Te == "auth") {
            $Kf = "";
            foreach ((array) $_SESSION["pwds"] as $Xi => $mh) {
                foreach ($mh as $M => $Si) {
                    foreach ($Si as $V => $E) {
                        if ($E !== null) {
                            $Rb = $_SESSION["db"][$Xi][$M][$V];
                            foreach ($Rb ? array_keys($Rb) : [""] as $l) {
                                $Kf .=
                                    "<li><a href='" .
                                    h(auth_url($Xi, $M, $V, $l)) .
                                    "'>($ec[$Xi]) " .
                                    h(
                                        $V .
                                            ($M != ""
                                                ? "@" . $this->serverName($M)
                                                : "") .
                                            ($l != "" ? " - $l" : "")
                                    ) .
                                    "</a>\n";
                            }
                        }
                    }
                }
            }
            if ($Kf) {
                echo "<ul id='logins'>\n$Kf</ul>\n" .
                    script(
                        "mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});"
                    );
            }
        } else {
            if ($_GET["ns"] !== "" && !$Te && DB != "") {
                $g->select_db(DB);
                $S = table_status('', true);
            }
            echo script_src(
                preg_replace("~\\?.*~", "", ME) . "?file=jush.js&version=4.7.5"
            );
            if (support("sql")) {
                echo '<script',
                    nonce(),
                    '>
';
                if ($S) {
                    $we = [];
                    foreach ($S as $Q => $T) {
                        $we[] = preg_quote($Q, '/');
                    }
                    echo "var jushLinks = { $x: [ '" .
                        js_escape(ME) .
                        (support("table") ? "table=" : "select=") .
                        "\$&', /\\b(" .
                        implode("|", $we) .
                        ")\\b/g ] };\n";
                    foreach (["bac", "bra", "sqlite_quo", "mssql_bra"] as $X) {
                        echo "jushLinks.$X = jushLinks.$x;\n";
                    }
                }
                $lh = $g->server_info;
                echo 'bodyLoad(\'',
                    is_object($g)
                        ? preg_replace('~^(\d\.?\d).*~s', '\1', $lh)
                        : "",
                    '\'',
                    preg_match('~MariaDB~', $lh) ? ", true" : "",
                    ');
</script>
';
            }
            $this->databasesPrint($Te);
            if (DB == "" || !$Te) {
                echo "<p class='links'>" .
                    (support("sql")
                        ? "<a href='" .
                            h(ME) .
                            "sql='" .
                            bold(
                                isset($_GET["sql"]) && !isset($_GET["import"])
                            ) .
                            ">" .
                            'SQL command' .
                            "</a>\n<a href='" .
                            h(ME) .
                            "import='" .
                            bold(isset($_GET["import"])) .
                            ">" .
                            'Import' .
                            "</a>\n"
                        : "") .
                    "";
                if (support("dump")) {
                    echo "<a href='" .
                        h(ME) .
                        "dump=" .
                        urlencode(
                            isset($_GET["table"])
                                ? $_GET["table"]
                                : $_GET["select"]
                        ) .
                        "' id='dump'" .
                        bold(isset($_GET["dump"])) .
                        ">" .
                        'Export' .
                        "</a>\n";
                }
            }
            if ($_GET["ns"] !== "" && !$Te && DB != "") {
                echo '<a href="' .
                    h(ME) .
                    'create="' .
                    bold($_GET["create"] === "") .
                    ">" .
                    'Create table' .
                    "</a>\n";
                if (!$S) {
                    echo "<p class='message'>" . 'No tables.' . "\n";
                } else {
                    $this->tablesPrint($S);
                }
            }
        }
    }
    function databasesPrint($Te)
    {
        global $b, $g;
        $k = $this->databases();
        if ($k && !in_array(DB, $k)) {
            array_unshift($k, DB);
        }
        echo '<form action="">
<p id="dbs">
';
        hidden_fields_get();
        $Pb = script(
            "mixin(qsl('select'), {onmousedown: dbMouseDown, onchange: dbChange});"
        );
        echo "<span title='" .
        'database' .
        "'>" .
        'DB' .
        "</span>: " .
        ($k
            ? "<select name='db'>" .
                optionlist(["" => ""] + $k, DB) .
                "</select>$Pb"
            : "<input name='db' value='" . h(DB) . "' autocapitalize='off'>\n"),
            "<input type='submit' value='" .
                'Use' .
                "'" .
                ($k ? " class='hidden'" : "") .
                ">\n";
        if ($Te != "db" && DB != "" && $g->select_db(DB)) {
            if (support("scheme")) {
                echo "<br>" .
                    'Schema' .
                    ": <select name='ns'>" .
                    optionlist(["" => ""] + $b->schemas(), $_GET["ns"]) .
                    "</select>$Pb";
                if ($_GET["ns"] != "") {
                    set_schema($_GET["ns"]);
                }
            }
        }
        foreach (["import", "sql", "schema", "dump", "privileges"] as $X) {
            if (isset($_GET[$X])) {
                echo "<input type='hidden' name='$X' value=''>";
                break;
            }
        }
        echo "</p></form>\n";
    }
    function tablesPrint($S)
    {
        echo "<ul id='tables'>" .
            script(
                "mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});"
            );
        foreach ($S as $Q => $O) {
            $B = $this->tableName($O);
            if ($B != "") {
                echo '<li><a href="' .
                h(ME) .
                'select=' .
                urlencode($Q) .
                '"' .
                bold($_GET["select"] == $Q || $_GET["edit"] == $Q, "select") .
                ">" .
                'select' .
                "</a> ",
                    (support("table") || support("indexes")
                        ? '<a href="' .
                            h(ME) .
                            'table=' .
                            urlencode($Q) .
                            '"' .
                            bold(
                                in_array($Q, [
                                    $_GET["table"],
                                    $_GET["create"],
                                    $_GET["indexes"],
                                    $_GET["foreign"],
                                    $_GET["trigger"],
                                ]),
                                is_view($O) ? "view" : "structure"
                            ) .
                            " title='" .
                            'Show structure' .
                            "'>$B</a>"
                        : "<span>$B</span>") . "\n";
            }
        }
        echo "</ul>\n";
    }
}
$b = function_exists('adminer_object') ? adminer_object() : new Adminer();
if ($b->operators === null) {
    $b->operators = $vf;
}
function page_header($hi, $n = "", $Xa = [], $ii = "")
{
    global $ca, $ia, $b, $ec, $x;
    page_headers();
    if (is_ajax() && $n) {
        page_messages($n);
        exit();
    }
    $ji = $hi . ($ii != "" ? ": $ii" : "");
    $ki = strip_tags(
        $ji .
            (SERVER != "" && SERVER != "localhost" ? h(" - " . SERVER) : "") .
            " - " .
            $b->name()
    );
    echo '<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<title>',
        $ki,
        '</title>
<link rel="stylesheet" type="text/css" href="',
        h(preg_replace("~\\?.*~", "", ME) . "?file=default.css&version=4.7.5"),
        '">
',
        script_src(
            preg_replace("~\\?.*~", "", ME) . "?file=functions.js&version=4.7.5"
        );
    if ($b->head()) {
        echo '<link rel="shortcut icon" type="image/x-icon" href="',
            h(
                preg_replace("~\\?.*~", "", ME) .
                    "?file=favicon.ico&version=4.7.5"
            ),
            '">
<link rel="apple-touch-icon" href="',
            h(
                preg_replace("~\\?.*~", "", ME) .
                    "?file=favicon.ico&version=4.7.5"
            ),
            '">
';
        foreach ($b->css() as $Jb) {
            echo '<link rel="stylesheet" type="text/css" href="',
                h($Jb),
                '">
';
        }
    }
    echo '
<body class="ltr nojs">
';
    $Uc = get_temp_dir() . "/adminer.version";
    if (
        !$_COOKIE["adminer_version"] &&
        function_exists('openssl_verify') &&
        file_exists($Uc) &&
        filemtime($Uc) + 86400 > time()
    ) {
        $Yi = unserialize(file_get_contents($Uc));
        $tg = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";
        if (
            openssl_verify(
                $Yi["version"],
                base64_decode($Yi["signature"]),
                $tg
            ) == 1
        ) {
            $_COOKIE["adminer_version"] = $Yi["version"];
        }
    }
    echo '<script',
        nonce(),
        '>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick',
        isset($_COOKIE["adminer_version"])
            ? ""
            : ", onload: partial(verifyVersion, '$ia', '" .
                js_escape(ME) .
                "', '" .
                get_token() .
                "')";
    ?>});
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = '<?php
echo js_escape('You are offline.'),
    '\';
var thousandsSeparator = \'',
    js_escape(','),
    '\';
</script>

<div id="help" class="jush-',
    $x,
    ' jsonly hidden"></div>
',
    script(
        "mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"
    ),
    '
<div id="content">
';
if ($Xa !== null) {
    $_ = substr(preg_replace('~\b(username|db|ns)=[^&]*&~', '', ME), 0, -1);
    echo '<p id="breadcrumb"><a href="' .
        h($_ ? $_ : ".") .
        '">' .
        $ec[DRIVER] .
        '</a> &raquo; ';
    $_ = substr(preg_replace('~\b(db|ns)=[^&]*&~', '', ME), 0, -1);
    $M = $b->serverName(SERVER);
    $M = $M != "" ? $M : 'Server';
    if ($Xa === false) {
        echo "$M\n";
    } else {
        echo "<a href='" .
            ($_ ? h($_) : ".") .
            "' accesskey='1' title='Alt+Shift+1'>$M</a> &raquo; ";
        if ($_GET["ns"] != "" || (DB != "" && is_array($Xa))) {
            echo '<a href="' .
                h(
                    $_ .
                        "&db=" .
                        urlencode(DB) .
                        (support("scheme") ? "&ns=" : "")
                ) .
                '">' .
                h(DB) .
                '</a> &raquo; ';
        }
        if (is_array($Xa)) {
            if ($_GET["ns"] != "") {
                echo '<a href="' .
                    h(substr(ME, 0, -1)) .
                    '">' .
                    h($_GET["ns"]) .
                    '</a> &raquo; ';
            }
            foreach ($Xa as $y => $X) {
                $Xb = is_array($X) ? $X[1] : h($X);
                if ($Xb != "") {
                    echo "<a href='" .
                        h(ME . "$y=") .
                        urlencode(is_array($X) ? $X[0] : $X) .
                        "'>$Xb</a> &raquo; ";
                }
            }
        }
        echo "$hi\n";
    }
}
echo "<h2>$ji</h2>\n", "<div id='ajaxstatus' class='jsonly hidden'></div>\n";
restart_session();
page_messages($n);
$k = &get_session("dbs");
if (DB != "" && $k && !in_array(DB, $k, true)) {
    $k = null;
}
stop_session();
define("PAGE_HEADER", 1);
}
function page_headers()
{
    global $b;
    header("Content-Type: text/html; charset=utf-8");
    header("Cache-Control: no-cache");
    header("X-Frame-Options: deny");
    header("X-XSS-Protection: 0");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: origin-when-cross-origin");
    foreach ($b->csp() as $Ib) {
        $wd = [];
        foreach ($Ib as $y => $X) {
            $wd[] = "$y $X";
        }
        header("Content-Security-Policy: " . implode("; ", $wd));
    }
    $b->headers();
}
function csp()
{
    return [
        [
            "script-src" =>
                "'self' 'unsafe-inline' 'nonce-" .
                get_nonce() .
                "' 'strict-dynamic'",
            "connect-src" => "'self'",
            "frame-src" => "https://www.adminer.org",
            "object-src" => "'none'",
            "base-uri" => "'none'",
            "form-action" => "'self'",
        ],
    ];
}
function get_nonce()
{
    static $df;
    if (!$df) {
        $df = base64_encode(rand_string());
    }
    return $df;
}
function page_messages($n)
{
    $Ki = preg_replace('~^[^?]*~', '', $_SERVER["REQUEST_URI"]);
    $Pe = $_SESSION["messages"][$Ki];
    if ($Pe) {
        echo "<div class='message'>" .
            implode("</div>\n<div class='message'>", $Pe) .
            "</div>" .
            script("messagesPrint();");
        unset($_SESSION["messages"][$Ki]);
    }
    if ($n) {
        echo "<div class='error'>$n</div>\n";
    }
}
function page_footer($Te = "")
{
    global $b, $oi;
    echo '</div>

';
    if ($Te != "auth") {
        echo '<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="',
            $oi,
            '">
</p>
</form>
';
    }
    echo '<div id="menu">
';
    $b->navigation($Te);
    echo '</div>
',
        script("setupSubmitHighlight(document);");
}
function int32($We)
{
    while ($We >= 2147483648) {
        $We -= 4294967296;
    }
    while ($We <= -2147483649) {
        $We += 4294967296;
    }
    return (int) $We;
}
function long2str($W, $cj)
{
    $Yg = '';
    foreach ($W as $X) {
        $Yg .= pack('V', $X);
    }
    if ($cj) {
        return substr($Yg, 0, end($W));
    }
    return $Yg;
}
function str2long($Yg, $cj)
{
    $W = array_values(
        unpack('V*', str_pad($Yg, 4 * ceil(strlen($Yg) / 4), "\0"))
    );
    if ($cj) {
        $W[] = strlen($Yg);
    }
    return $W;
}
function xxtea_mx($pj, $oj, $Lh, $de)
{
    return int32(
        ((($pj >> 5) & 0x7ffffff) ^ ($oj << 2)) +
            ((($oj >> 3) & 0x1fffffff) ^ ($pj << 4))
    ) ^ int32(($Lh ^ $oj) + ($de ^ $pj));
}
function encrypt_string($Gh, $y)
{
    if ($Gh == "") {
        return "";
    }
    $y = array_values(unpack("V*", pack("H*", md5($y))));
    $W = str2long($Gh, true);
    $We = count($W) - 1;
    $pj = $W[$We];
    $oj = $W[0];
    $ug = floor(6 + 52 / ($We + 1));
    $Lh = 0;
    while ($ug-- > 0) {
        $Lh = int32($Lh + 0x9e3779b9);
        $lc = ($Lh >> 2) & 3;
        for ($Lf = 0; $Lf < $We; $Lf++) {
            $oj = $W[$Lf + 1];
            $Ve = xxtea_mx($pj, $oj, $Lh, $y[($Lf & 3) ^ $lc]);
            $pj = int32($W[$Lf] + $Ve);
            $W[$Lf] = $pj;
        }
        $oj = $W[0];
        $Ve = xxtea_mx($pj, $oj, $Lh, $y[($Lf & 3) ^ $lc]);
        $pj = int32($W[$We] + $Ve);
        $W[$We] = $pj;
    }
    return long2str($W, false);
}
function decrypt_string($Gh, $y)
{
    if ($Gh == "") {
        return "";
    }
    if (!$y) {
        return false;
    }
    $y = array_values(unpack("V*", pack("H*", md5($y))));
    $W = str2long($Gh, false);
    $We = count($W) - 1;
    $pj = $W[$We];
    $oj = $W[0];
    $ug = floor(6 + 52 / ($We + 1));
    $Lh = int32($ug * 0x9e3779b9);
    while ($Lh) {
        $lc = ($Lh >> 2) & 3;
        for ($Lf = $We; $Lf > 0; $Lf--) {
            $pj = $W[$Lf - 1];
            $Ve = xxtea_mx($pj, $oj, $Lh, $y[($Lf & 3) ^ $lc]);
            $oj = int32($W[$Lf] - $Ve);
            $W[$Lf] = $oj;
        }
        $pj = $W[$We];
        $Ve = xxtea_mx($pj, $oj, $Lh, $y[($Lf & 3) ^ $lc]);
        $oj = int32($W[0] - $Ve);
        $W[0] = $oj;
        $Lh = int32($Lh - 0x9e3779b9);
    }
    return long2str($W, true);
}
$g = '';
$vd = $_SESSION["token"];
if (!$vd) {
    $_SESSION["token"] = rand(1, 1e6);
}
$oi = get_token();
$bg = [];
if ($_COOKIE["adminer_permanent"]) {
    foreach (explode(" ", $_COOKIE["adminer_permanent"]) as $X) {
        list($y) = explode(":", $X);
        $bg[$y] = $X;
    }
}
function add_invalid_login()
{
    global $b;
    $id = file_open_lock(get_temp_dir() . "/adminer.invalid");
    if (!$id) {
        return;
    }
    $Wd = unserialize(stream_get_contents($id));
    $ei = time();
    if ($Wd) {
        foreach ($Wd as $Xd => $X) {
            if ($X[0] < $ei) {
                unset($Wd[$Xd]);
            }
        }
    }
    $Vd = &$Wd[$b->bruteForceKey()];
    if (!$Vd) {
        $Vd = [$ei + 30 * 60, 0];
    }
    $Vd[1]++;
    file_write_unlock($id, serialize($Wd));
}
function check_invalid_login()
{
    global $b;
    $Wd = unserialize(@file_get_contents(get_temp_dir() . "/adminer.invalid"));
    $Vd = $Wd[$b->bruteForceKey()];
    $cf = $Vd[1] > 29 ? $Vd[0] - time() : 0;
    if ($cf > 0) {
        auth_error(
            lang(
                [
                    'Too many unsuccessful logins, try again in %d minute.',
                    'Too many unsuccessful logins, try again in %d minutes.',
                ],
                ceil($cf / 60)
            )
        );
    }
}
$Ka = $_POST["auth"];
if ($Ka) {
    session_regenerate_id();
    $Xi = $Ka["driver"];
    $M = $Ka["server"];
    $V = $Ka["username"];
    $E = (string) $Ka["password"];
    $l = $Ka["db"];
    set_password($Xi, $M, $V, $E);
    $_SESSION["db"][$Xi][$M][$V][$l] = true;
    if ($Ka["permanent"]) {
        $y =
            base64_encode($Xi) .
            "-" .
            base64_encode($M) .
            "-" .
            base64_encode($V) .
            "-" .
            base64_encode($l);
        $ng = $b->permanentLogin(true);
        $bg[$y] = "$y:" . base64_encode($ng ? encrypt_string($E, $ng) : "");
        cookie("adminer_permanent", implode(" ", $bg));
    }
    if (
        count($_POST) == 1 ||
        DRIVER != $Xi ||
        SERVER != $M ||
        $_GET["username"] !== $V ||
        DB != $l
    ) {
        redirect(auth_url($Xi, $M, $V, $l));
    }
} elseif ($_POST["logout"]) {
    if ($vd && !verify_token()) {
        page_header('Logout', 'Invalid CSRF token. Send the form again.');
        page_footer("db");
        exit();
    } else {
        foreach (["pwds", "db", "dbs", "queries"] as $y) {
            set_session($y, null);
        }
        unset_permanent();
        redirect(
            substr(preg_replace('~\b(username|db|ns)=[^&]*&~', '', ME), 0, -1),
            'Logout successful.' .
                ' ' .
                'Thanks for using Adminer, consider <a href="https://www.adminer.org/en/donation/">donating</a>.'
        );
    }
} elseif ($bg && !$_SESSION["pwds"]) {
    session_regenerate_id();
    $ng = $b->permanentLogin();
    foreach ($bg as $y => $X) {
        list(, $jb) = explode(":", $X);
        list($Xi, $M, $V, $l) = array_map('base64_decode', explode("-", $y));
        set_password($Xi, $M, $V, decrypt_string(base64_decode($jb), $ng));
        $_SESSION["db"][$Xi][$M][$V][$l] = true;
    }
}
function unset_permanent()
{
    global $bg;
    foreach ($bg as $y => $X) {
        list($Xi, $M, $V, $l) = array_map('base64_decode', explode("-", $y));
        if (
            $Xi == DRIVER &&
            $M == SERVER &&
            $V == $_GET["username"] &&
            $l == DB
        ) {
            unset($bg[$y]);
        }
    }
    cookie("adminer_permanent", implode(" ", $bg));
}
function auth_error($n)
{
    global $b, $vd;
    $nh = session_name();
    if (isset($_GET["username"])) {
        header("HTTP/1.1 403 Forbidden");
        if (($_COOKIE[$nh] || $_GET[$nh]) && !$vd) {
            $n = 'Session expired, please login again.';
        } else {
            restart_session();
            add_invalid_login();
            $E = get_password();
            if ($E !== null) {
                if ($E === false) {
                    $n .=
                        '<br>' .
                        sprintf(
                            'Master password expired. <a href="https://www.adminer.org/en/extension/"%s>Implement</a> %s method to make it permanent.',
                            target_blank(),
                            '<code>permanentLogin()</code>'
                        );
                }
                set_password(DRIVER, SERVER, $_GET["username"], null);
            }
            unset_permanent();
        }
    }
    if (!$_COOKIE[$nh] && $_GET[$nh] && ini_bool("session.use_only_cookies")) {
        $n = 'Session support must be enabled.';
    }
    $Of = session_get_cookie_params();
    cookie(
        "adminer_key",
        $_COOKIE["adminer_key"] ? $_COOKIE["adminer_key"] : rand_string(),
        $Of["lifetime"]
    );
    page_header('Login', $n, null);
    echo "<form action='' method='post'>\n", "<div>";
    if (hidden_fields($_POST, ["auth"])) {
        echo "<p class='message'>" .
            'The action will be performed after successful login with the same credentials.' .
            "\n";
    }
    echo "</div>\n";
    $b->loginForm();
    echo "</form>\n";
    page_footer("auth");
    exit();
}
if (isset($_GET["username"]) && !class_exists("Min_DB")) {
    unset($_SESSION["pwds"][DRIVER]);
    unset_permanent();
    page_header(
        'No extension',
        sprintf(
            'None of the supported PHP extensions (%s) are available.',
            implode(", ", $hg)
        ),
        false
    );
    page_footer("auth");
    exit();
}
stop_session(true);
if (isset($_GET["username"]) && is_string(get_password())) {
    list($Ad, $dg) = explode(":", SERVER, 2);
    if (is_numeric($dg) && $dg < 1024) {
        auth_error('Connecting to privileged ports is not allowed.');
    }
    check_invalid_login();
    $g = connect();
    $m = new Min_Driver($g);
}
$ye = null;
if (
    !is_object($g) ||
    ($ye = $b->login($_GET["username"], get_password())) !== true
) {
    $n = is_string($g)
        ? h($g)
        : (is_string($ye)
            ? $ye
            : 'Invalid credentials.');
    auth_error(
        $n .
            (preg_match('~^ | $~', get_password())
                ? '<br>' .
                    'There is a space in the input password which might be the cause.'
                : '')
    );
}
if ($Ka && $_POST["token"]) {
    $_POST["token"] = $oi;
}
$n = '';
if ($_POST) {
    if (!verify_token()) {
        $Qd = "max_input_vars";
        $Je = ini_get($Qd);
        if (extension_loaded("suhosin")) {
            foreach (
                ["suhosin.request.max_vars", "suhosin.post.max_vars"]
                as $y
            ) {
                $X = ini_get($y);
                if ($X && (!$Je || $X < $Je)) {
                    $Qd = $y;
                    $Je = $X;
                }
            }
        }
        $n =
            !$_POST["token"] && $Je
                ? sprintf(
                    'Maximum number of allowed fields exceeded. Please increase %s.',
                    "'$Qd'"
                )
                : 'Invalid CSRF token. Send the form again.' .
                    ' ' .
                    'If you did not send this request from Adminer then close this page.';
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $n = sprintf(
        'Too big POST data. Reduce the data or increase the %s configuration directive.',
        "'post_max_size'"
    );
    if (isset($_GET["sql"])) {
        $n .=
            ' ' .
            'You can upload a big SQL file via FTP and import it from server.';
    }
}
function select($G, $h = null, $Cf = [], $z = 0)
{
    global $x;
    $we = [];
    $w = [];
    $f = [];
    $Ua = [];
    $U = [];
    $H = [];
    odd('');
    for ($s = 0; (!$z || $s < $z) && ($I = $G->fetch_row()); $s++) {
        if (!$s) {
            echo "<div class='scrollable'>\n",
                "<table cellspacing='0' class='nowrap'>\n",
                "<thead><tr>";
            for ($ce = 0; $ce < count($I); $ce++) {
                $o = $G->fetch_field();
                $B = $o->name;
                $Bf = $o->orgtable;
                $Af = $o->orgname;
                $H[$o->table] = $Bf;
                if ($Cf && $x == "sql") {
                    $we[$ce] =
                        $B == "table"
                            ? "table="
                            : ($B == "possible_keys"
                                ? "indexes="
                                : null);
                } elseif ($Bf != "") {
                    if (!isset($w[$Bf])) {
                        $w[$Bf] = [];
                        foreach (indexes($Bf, $h) as $v) {
                            if ($v["type"] == "PRIMARY") {
                                $w[$Bf] = array_flip($v["columns"]);
                                break;
                            }
                        }
                        $f[$Bf] = $w[$Bf];
                    }
                    if (isset($f[$Bf][$Af])) {
                        unset($f[$Bf][$Af]);
                        $w[$Bf][$Af] = $ce;
                        $we[$ce] = $Bf;
                    }
                }
                if ($o->charsetnr == 63) {
                    $Ua[$ce] = true;
                }
                $U[$ce] = $o->type;
                echo "<th" .
                    ($Bf != "" || $o->name != $Af
                        ? " title='" . h(($Bf != "" ? "$Bf." : "") . $Af) . "'"
                        : "") .
                    ">" .
                    h($B) .
                    ($Cf
                        ? doc_link([
                            'sql' =>
                                "explain-output.html#explain_" . strtolower($B),
                            'mariadb' =>
                                "explain/#the-columns-in-explain-select",
                        ])
                        : "");
            }
            echo "</thead>\n";
        }
        echo "<tr" . odd() . ">";
        foreach ($I as $y => $X) {
            if ($X === null) {
                $X = "<i>NULL</i>";
            } elseif ($Ua[$y] && !is_utf8($X)) {
                $X = "<i>" . lang(['%d byte', '%d bytes'], strlen($X)) . "</i>";
            } else {
                $X = h($X);
                if ($U[$y] == 254) {
                    $X = "<code>$X</code>";
                }
            }
            if (isset($we[$y]) && !$f[$we[$y]]) {
                if ($Cf && $x == "sql") {
                    $Q = $I[array_search("table=", $we)];
                    $_ = $we[$y] . urlencode($Cf[$Q] != "" ? $Cf[$Q] : $Q);
                } else {
                    $_ = "edit=" . urlencode($we[$y]);
                    foreach ($w[$we[$y]] as $nb => $ce) {
                        $_ .=
                            "&where" .
                            urlencode("[" . bracket_escape($nb) . "]") .
                            "=" .
                            urlencode($I[$ce]);
                    }
                }
                $X = "<a href='" . h(ME . $_) . "'>$X</a>";
            }
            echo "<td>$X";
        }
    }
    echo ($s ? "</table>\n</div>" : "<p class='message'>" . 'No rows.') . "\n";
    return $H;
}
function referencable_primary($hh)
{
    $H = [];
    foreach (table_status('', true) as $Ph => $Q) {
        if ($Ph != $hh && fk_support($Q)) {
            foreach (fields($Ph) as $o) {
                if ($o["primary"]) {
                    if ($H[$Ph]) {
                        unset($H[$Ph]);
                        break;
                    }
                    $H[$Ph] = $o;
                }
            }
        }
    }
    return $H;
}
function adminer_settings()
{
    parse_str($_COOKIE["adminer_settings"], $ph);
    return $ph;
}
function adminer_setting($y)
{
    $ph = adminer_settings();
    return $ph[$y];
}
function set_adminer_settings($ph)
{
    return cookie(
        "adminer_settings",
        http_build_query($ph + adminer_settings())
    );
}
function textarea($B, $Y, $J = 10, $rb = 80)
{
    global $x;
    echo "<textarea name='$B' rows='$J' cols='$rb' class='sqlarea jush-$x' spellcheck='false' wrap='off'>";
    if (is_array($Y)) {
        foreach ($Y as $X) {
            echo h($X[0]) . "\n\n\n";
        }
    } else {
        echo h($Y);
    }
    echo "</textarea>";
}
function edit_type($y, $o, $pb, $ed = [], $Lc = [])
{
    global $Hh, $U, $Ii, $qf;
    $T = $o["type"];
    echo '<td><select name="',
        h($y),
        '[type]" class="type" aria-labelledby="label-type">';
    if ($T && !isset($U[$T]) && !isset($ed[$T]) && !in_array($T, $Lc)) {
        $Lc[] = $T;
    }
    if ($ed) {
        $Hh['Foreign keys'] = $ed;
    }
    echo optionlist(array_merge($Lc, $Hh), $T),
        '</select>',
        on_help("getTarget(event).value", 1),
        script(
            "mixin(qsl('select'), {onfocus: function () { lastType = selectValue(this); }, onchange: editingTypeChange});",
            ""
        ),
        '<td><input name="',
        h($y),
        '[length]" value="',
        h($o["length"]),
        '" size="3"',
        !$o["length"] && preg_match('~var(char|binary)$~', $T)
            ? " class='required'"
            : "";
    echo ' aria-labelledby="label-length">',
        script(
            "mixin(qsl('input'), {onfocus: editingLengthFocus, oninput: editingLengthChange});",
            ""
        ),
        '<td class="options">',
        "<select name='" .
            h($y) .
            "[collation]'" .
            (preg_match('~(char|text|enum|set)$~', $T)
                ? ""
                : " class='hidden'") .
            '><option value="">(' .
            'collation' .
            ')' .
            optionlist($pb, $o["collation"]) .
            '</select>',
        $Ii
            ? "<select name='" .
                h($y) .
                "[unsigned]'" .
                (!$T || preg_match(number_type(), $T)
                    ? ""
                    : " class='hidden'") .
                '><option>' .
                optionlist($Ii, $o["unsigned"]) .
                '</select>'
            : '',
        isset($o['on_update'])
            ? "<select name='" .
                h($y) .
                "[on_update]'" .
                (preg_match('~timestamp|datetime~', $T)
                    ? ""
                    : " class='hidden'") .
                '>' .
                optionlist(
                    ["" => "(" . 'ON UPDATE' . ")", "CURRENT_TIMESTAMP"],
                    preg_match('~^CURRENT_TIMESTAMP~i', $o["on_update"])
                        ? "CURRENT_TIMESTAMP"
                        : $o["on_update"]
                ) .
                '</select>'
            : '',
        $ed
            ? "<select name='" .
                h($y) .
                "[on_delete]'" .
                (preg_match("~`~", $T) ? "" : " class='hidden'") .
                "><option value=''>(" .
                'ON DELETE' .
                ")" .
                optionlist(explode("|", $qf), $o["on_delete"]) .
                "</select> "
            : " ";
}
function process_length($te)
{
    global $wc;
    return preg_match(
        "~^\\s*\\(?\\s*$wc(?:\\s*,\\s*$wc)*+\\s*\\)?\\s*\$~",
        $te
    ) && preg_match_all("~$wc~", $te, $De)
        ? "(" . implode(",", $De[0]) . ")"
        : preg_replace(
            '~^[0-9].*~',
            '(\0)',
            preg_replace('~[^-0-9,+()[\]]~', '', $te)
        );
}
function process_type($o, $ob = "COLLATE")
{
    global $Ii;
    return " $o[type]" .
        process_length($o["length"]) .
        (preg_match(number_type(), $o["type"]) && in_array($o["unsigned"], $Ii)
            ? " $o[unsigned]"
            : "") .
        (preg_match('~char|text|enum|set~', $o["type"]) && $o["collation"]
            ? " $ob " . q($o["collation"])
            : "");
}
function process_field($o, $Ai)
{
    return [
        idf_escape(trim($o["field"])),
        process_type($Ai),
        $o["null"] ? " NULL" : " NOT NULL",
        default_value($o),
        preg_match('~timestamp|datetime~', $o["type"]) && $o["on_update"]
            ? " ON UPDATE $o[on_update]"
            : "",
        support("comment") && $o["comment"] != ""
            ? " COMMENT " . q($o["comment"])
            : "",
        $o["auto_increment"] ? auto_increment() : null,
    ];
}
function default_value($o)
{
    $Tb = $o["default"];
    return $Tb === null
        ? ""
        : " DEFAULT " .
                (preg_match('~char|binary|text|enum|set~', $o["type"]) ||
                preg_match('~^(?![a-z])~i', $Tb)
                    ? q($Tb)
                    : $Tb);
}
function type_class($T)
{
    foreach (
        [
            'char' => 'text',
            'date' => 'time|year',
            'binary' => 'blob',
            'enum' => 'set',
        ]
        as $y => $X
    ) {
        if (preg_match("~$y|$X~", $T)) {
            return " class='$y'";
        }
    }
}
function edit_fields($p, $pb, $T = "TABLE", $ed = [])
{
    global $Rd;
    $p = array_values($p);
    echo '<thead><tr>
';
    if ($T == "PROCEDURE") {
        echo '<td>';
    }
    echo '<th id="label-name">',
        $T == "TABLE" ? 'Column name' : 'Parameter name',
        '<td id="label-type">Type<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;"></textarea>',
        script("qs('#enum-edit').onblur = editingLengthBlur;"),
        '<td id="label-length">Length
<td>',
        'Options';
    if ($T == "TABLE") {
        echo '<td id="label-null">NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym id="label-ai" title="Auto Increment">AI</acronym>',
            doc_link([
                'sql' => "example-auto-increment.html",
                'mariadb' => "auto_increment/",
                'sqlite' => "autoinc.html",
                'pgsql' => "datatype.html#DATATYPE-SERIAL",
                'mssql' => "ms186775.aspx",
            ]),
            '<td id="label-default">Default value
',
            support("comment") ? "<td id='label-comment'>" . 'Comment' : "";
    }
    echo '<td>',
        "<input type='image' class='icon' name='add[" .
            (support("move_col") ? 0 : count($p)) .
            "]' src='" .
            h(
                preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.7.5"
            ) .
            "' alt='+' title='" .
            'Add next' .
            "'>" .
            script("row_count = " . count($p) . ";"),
        '</thead>
<tbody>
',
        script(
            "mixin(qsl('tbody'), {onclick: editingClick, onkeydown: editingKeydown, oninput: editingInput});"
        );
    foreach ($p as $s => $o) {
        $s++;
        $Df = $o[$_POST ? "orig" : "field"];
        $bc =
            (isset($_POST["add"][$s - 1]) ||
                (isset($o["field"]) && !$_POST["drop_col"][$s])) &&
            (support("drop_col") || $Df == "");
        echo '<tr',
            $bc ? "" : " style='display: none;'",
            '>
',
            $T == "PROCEDURE"
                ? "<td>" .
                    html_select(
                        "fields[$s][inout]",
                        explode("|", $Rd),
                        $o["inout"]
                    )
                : "",
            '<th>';
        if ($bc) {
            echo '<input name="fields[',
                $s,
                '][field]" value="',
                h($o["field"]),
                '" data-maxlength="64" autocapitalize="off" aria-labelledby="label-name">',
                script(
                    "qsl('input').oninput = function () { editingNameChange.call(this);" .
                        ($o["field"] != "" || count($p) > 1
                            ? ""
                            : " editingAddRow.call(this);") .
                        " };",
                    ""
                );
        }
        echo '<input type="hidden" name="fields[',
            $s,
            '][orig]" value="',
            h($Df),
            '">';
        edit_type("fields[$s]", $o, $pb, $ed);
        if ($T == "TABLE") {
            echo '<td>',
                checkbox(
                    "fields[$s][null]",
                    1,
                    $o["null"],
                    "",
                    "",
                    "block",
                    "label-null"
                ),
                '<td><label class="block"><input type="radio" name="auto_increment_col" value="',
                $s,
                '"';
            if ($o["auto_increment"]) {
                echo ' checked';
            }
            echo ' aria-labelledby="label-ai"></label><td>',
                checkbox(
                    "fields[$s][has_default]",
                    1,
                    $o["has_default"],
                    "",
                    "",
                    "",
                    "label-default"
                ),
                '<input name="fields[',
                $s,
                '][default]" value="',
                h($o["default"]),
                '" aria-labelledby="label-default">',
                support("comment")
                    ? "<td><input name='fields[$s][comment]' value='" .
                        h($o["comment"]) .
                        "' data-maxlength='" .
                        (min_version(5.5) ? 1024 : 255) .
                        "' aria-labelledby='label-comment'>"
                    : "";
        }
        echo "<td>",
            support("move_col")
                ? "<input type='image' class='icon' name='add[$s]' src='" .
                    h(
                        preg_replace("~\\?.*~", "", ME) .
                            "?file=plus.gif&version=4.7.5"
                    ) .
                    "' alt='+' title='" .
                    'Add next' .
                    "'> " .
                    "<input type='image' class='icon' name='up[$s]' src='" .
                    h(
                        preg_replace("~\\?.*~", "", ME) .
                            "?file=up.gif&version=4.7.5"
                    ) .
                    "' alt='↑' title='" .
                    'Move up' .
                    "'> " .
                    "<input type='image' class='icon' name='down[$s]' src='" .
                    h(
                        preg_replace("~\\?.*~", "", ME) .
                            "?file=down.gif&version=4.7.5"
                    ) .
                    "' alt='↓' title='" .
                    'Move down' .
                    "'> "
                : "",
            $Df == "" || support("drop_col")
                ? "<input type='image' class='icon' name='drop_col[$s]' src='" .
                    h(
                        preg_replace("~\\?.*~", "", ME) .
                            "?file=cross.gif&version=4.7.5"
                    ) .
                    "' alt='x' title='" .
                    'Remove' .
                    "'>"
                : "";
    }
}
function process_fields(&$p)
{
    $C = 0;
    if ($_POST["up"]) {
        $ne = 0;
        foreach ($p as $y => $o) {
            if (key($_POST["up"]) == $y) {
                unset($p[$y]);
                array_splice($p, $ne, 0, [$o]);
                break;
            }
            if (isset($o["field"])) {
                $ne = $C;
            }
            $C++;
        }
    } elseif ($_POST["down"]) {
        $gd = false;
        foreach ($p as $y => $o) {
            if (isset($o["field"]) && $gd) {
                unset($p[key($_POST["down"])]);
                array_splice($p, $C, 0, [$gd]);
                break;
            }
            if (key($_POST["down"]) == $y) {
                $gd = $o;
            }
            $C++;
        }
    } elseif ($_POST["add"]) {
        $p = array_values($p);
        array_splice($p, key($_POST["add"]), 0, [[]]);
    } elseif (!$_POST["drop_col"]) {
        return false;
    }
    return true;
}
function normalize_enum($A)
{
    return "'" .
        str_replace(
            "'",
            "''",
            addcslashes(
                stripcslashes(
                    str_replace(
                        $A[0][0] . $A[0][0],
                        $A[0][0],
                        substr($A[0], 1, -1)
                    )
                ),
                '\\'
            )
        ) .
        "'";
}
function grant($ld, $pg, $f, $pf)
{
    if (!$pg) {
        return true;
    }
    if ($pg == ["ALL PRIVILEGES", "GRANT OPTION"]) {
        return $ld == "GRANT"
            ? queries("$ld ALL PRIVILEGES$pf WITH GRANT OPTION")
            : queries("$ld ALL PRIVILEGES$pf") &&
                    queries("$ld GRANT OPTION$pf");
    }
    return queries(
        "$ld " .
            preg_replace(
                '~(GRANT OPTION)\([^)]*\)~',
                '\1',
                implode("$f, ", $pg) . $f
            ) .
            $pf
    );
}
function drop_create($fc, $i, $gc, $bi, $ic, $xe, $Oe, $Me, $Ne, $mf, $Ze)
{
    if ($_POST["drop"]) {
        query_redirect($fc, $xe, $Oe);
    } elseif ($mf == "") {
        query_redirect($i, $xe, $Ne);
    } elseif ($mf != $Ze) {
        $Gb = queries($i);
        queries_redirect($xe, $Me, $Gb && queries($fc));
        if ($Gb) {
            queries($gc);
        }
    } else {
        queries_redirect(
            $xe,
            $Me,
            queries($bi) && queries($ic) && queries($fc) && queries($i)
        );
    }
}
function create_trigger($pf, $I)
{
    global $x;
    $gi =
        " $I[Timing] $I[Event]" .
        ($I["Event"] == "UPDATE OF" ? " " . idf_escape($I["Of"]) : "");
    return "CREATE TRIGGER " .
        idf_escape($I["Trigger"]) .
        ($x == "mssql" ? $pf . $gi : $gi . $pf) .
        rtrim(" $I[Type]\n$I[Statement]", ";") .
        ";";
}
function create_routine($Ug, $I)
{
    global $Rd, $x;
    $N = [];
    $p = (array) $I["fields"];
    ksort($p);
    foreach ($p as $o) {
        if ($o["field"] != "") {
            $N[] =
                (preg_match("~^($Rd)\$~", $o["inout"]) ? "$o[inout] " : "") .
                idf_escape($o["field"]) .
                process_type($o, "CHARACTER SET");
        }
    }
    $Ub = rtrim("\n$I[definition]", ";");
    return "CREATE $Ug " .
        idf_escape(trim($I["name"])) .
        " (" .
        implode(", ", $N) .
        ")" .
        (isset($_GET["function"])
            ? " RETURNS" . process_type($I["returns"], "CHARACTER SET")
            : "") .
        ($I["language"] ? " LANGUAGE $I[language]" : "") .
        ($x == "pgsql" ? " AS " . q($Ub) : "$Ub;");
}
function remove_definer($F)
{
    return preg_replace(
        '~^([A-Z =]+) DEFINER=`' .
            preg_replace('~@(.*)~', '`@`(%|\1)', logged_user()) .
            '`~',
        '\1',
        $F
    );
}
function format_foreign_key($q)
{
    global $qf;
    $l = $q["db"];
    $ef = $q["ns"];
    return " FOREIGN KEY (" .
        implode(", ", array_map('idf_escape', $q["source"])) .
        ") REFERENCES " .
        ($l != "" && $l != $_GET["db"] ? idf_escape($l) . "." : "") .
        ($ef != "" && $ef != $_GET["ns"] ? idf_escape($ef) . "." : "") .
        table($q["table"]) .
        " (" .
        implode(", ", array_map('idf_escape', $q["target"])) .
        ")" .
        (preg_match("~^($qf)\$~", $q["on_delete"])
            ? " ON DELETE $q[on_delete]"
            : "") .
        (preg_match("~^($qf)\$~", $q["on_update"])
            ? " ON UPDATE $q[on_update]"
            : "");
}
function tar_file($Uc, $li)
{
    $H = pack(
        "a100a8a8a8a12a12",
        $Uc,
        644,
        0,
        0,
        decoct($li->size),
        decoct(time())
    );
    $hb = 8 * 32;
    for ($s = 0; $s < strlen($H); $s++) {
        $hb += ord($H[$s]);
    }
    $H .= sprintf("%06o", $hb) . "\0 ";
    echo $H, str_repeat("\0", 512 - strlen($H));
    $li->send();
    echo str_repeat("\0", 511 - (($li->size + 511) % 512));
}
function ini_bytes($Qd)
{
    $X = ini_get($Qd);
    switch (strtolower(substr($X, -1))) {
        case 'g':
            $X *= 1024;
        case 'm':
            $X *= 1024;
        case 'k':
            $X *= 1024;
    }
    return $X;
}
function doc_link($Zf, $ci = "<sup>?</sup>")
{
    global $x, $g;
    $lh = $g->server_info;
    $Yi = preg_replace('~^(\d\.?\d).*~s', '\1', $lh);
    $Ni = [
        'sql' => "https://dev.mysql.com/doc/refman/$Yi/en/",
        'sqlite' => "https://www.sqlite.org/",
        'pgsql' => "https://www.postgresql.org/docs/$Yi/",
        'mssql' => "https://msdn.microsoft.com/library/",
        'oracle' =>
            "https://www.oracle.com/pls/topic/lookup?ctx=db" .
            preg_replace('~^.* (\d+)\.(\d+)\.\d+\.\d+\.\d+.*~s', '\1\2', $lh) .
            "&id=",
    ];
    if (preg_match('~MariaDB~', $lh)) {
        $Ni['sql'] = "https://mariadb.com/kb/en/library/";
        $Zf['sql'] = isset($Zf['mariadb'])
            ? $Zf['mariadb']
            : str_replace(".html", "/", $Zf['sql']);
    }
    return $Zf[$x]
        ? "<a href='$Ni[$x]$Zf[$x]'" . target_blank() . ">$ci</a>"
        : "";
}
function ob_gzencode($P)
{
    return gzencode($P);
}
function db_size($l)
{
    global $g;
    if (!$g->select_db($l)) {
        return "?";
    }
    $H = 0;
    foreach (table_status() as $R) {
        $H += $R["Data_length"] + $R["Index_length"];
    }
    return format_number($H);
}
function set_utf8mb4($i)
{
    global $g;
    static $N = false;
    if (!$N && preg_match('~\butf8mb4~i', $i)) {
        $N = true;
        echo "SET NAMES " . charset($g) . ";\n\n";
    }
}
function connect_error()
{
    global $b, $g, $oi, $n, $ec;
    if (DB != "") {
        header("HTTP/1.1 404 Not Found");
        page_header('Database' . ": " . h(DB), 'Invalid database.', true);
    } else {
        if ($_POST["db"] && !$n) {
            queries_redirect(
                substr(ME, 0, -1),
                'Databases have been dropped.',
                drop_databases($_POST["db"])
            );
        }
        page_header('Select database', $n, false);
        echo "<p class='links'>\n";
        foreach (
            [
                'database' => 'Create database',
                'privileges' => 'Privileges',
                'processlist' => 'Process list',
                'variables' => 'Variables',
                'status' => 'Status',
            ]
            as $y => $X
        ) {
            if (support($y)) {
                echo "<a href='" . h(ME) . "$y='>$X</a>\n";
            }
        }
        echo "<p>" .
        sprintf(
            '%s version: %s through PHP extension %s',
            $ec[DRIVER],
            "<b>" . h($g->server_info) . "</b>",
            "<b>$g->extension</b>"
        ) .
        "\n",
            "<p>" .
                sprintf('Logged as: %s', "<b>" . h(logged_user()) . "</b>") .
                "\n";
        $k = $b->databases();
        if ($k) {
            $bh = support("scheme");
            $pb = collations();
            echo "<form action='' method='post'>\n",
                "<table cellspacing='0' class='checkable'>\n",
                script(
                    "mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"
                ),
                "<thead><tr>" .
                    (support("database") ? "<td>" : "") .
                    "<th>" .
                    'Database' .
                    " - <a href='" .
                    h(ME) .
                    "refresh=1'>" .
                    'Refresh' .
                    "</a>" .
                    "<td>" .
                    'Collation' .
                    "<td>" .
                    'Tables' .
                    "<td>" .
                    'Size' .
                    " - <a href='" .
                    h(ME) .
                    "dbsize=1'>" .
                    'Compute' .
                    "</a>" .
                    script(
                        "qsl('a').onclick = partial(ajaxSetHtml, '" .
                            js_escape(ME) .
                            "script=connect');",
                        ""
                    ) .
                    "</thead>\n";
            $k = $_GET["dbsize"] ? count_tables($k) : array_flip($k);
            foreach ($k as $l => $S) {
                $Tg = h(ME) . "db=" . urlencode($l);
                $t = h("Db-" . $l);
                echo "<tr" .
                odd() .
                ">" .
                (support("database")
                    ? "<td>" .
                        checkbox(
                            "db[]",
                            $l,
                            in_array($l, (array) $_POST["db"]),
                            "",
                            "",
                            "",
                            $t
                        )
                    : ""),
                    "<th><a href='$Tg' id='$t'>" . h($l) . "</a>";
                $d = h(db_collation($l, $pb));
                echo "<td>" .
                (support("database")
                    ? "<a href='$Tg" .
                        ($bh ? "&amp;ns=" : "") .
                        "&amp;database=' title='" .
                        'Alter database' .
                        "'>$d</a>"
                    : $d),
                    "<td align='right'><a href='$Tg&amp;schema=' id='tables-" .
                        h($l) .
                        "' title='" .
                        'Database schema' .
                        "'>" .
                        ($_GET["dbsize"] ? $S : "?") .
                        "</a>",
                    "<td align='right' id='size-" .
                        h($l) .
                        "'>" .
                        ($_GET["dbsize"] ? db_size($l) : "?"),
                    "\n";
            }
            echo "</table>\n",
                support("database")
                    ? "<div class='footer'><div>\n" .
                        "<fieldset><legend>" .
                        'Selected' .
                        " <span id='selected'></span></legend><div>\n" .
                        "<input type='hidden' name='all' value=''>" .
                        script(
                            "qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^db/)); };"
                        ) .
                        "<input type='submit' name='drop' value='" .
                        'Drop' .
                        "'>" .
                        confirm() .
                        "\n" .
                        "</div></fieldset>\n" .
                        "</div></div>\n"
                    : "",
                "<input type='hidden' name='token' value='$oi'>\n",
                "</form>\n",
                script("tableCheck();");
        }
    }
    page_footer("db");
}
if (isset($_GET["status"])) {
    $_GET["variables"] = $_GET["status"];
}
if (isset($_GET["import"])) {
    $_GET["sql"] = $_GET["import"];
}
if (
    !(DB != ""
        ? $g->select_db(DB)
        : isset($_GET["sql"]) ||
            isset($_GET["dump"]) ||
            isset($_GET["database"]) ||
            isset($_GET["processlist"]) ||
            isset($_GET["privileges"]) ||
            isset($_GET["user"]) ||
            isset($_GET["variables"]) ||
            $_GET["script"] == "connect" ||
            $_GET["script"] == "kill")
) {
    if (DB != "" || $_GET["refresh"]) {
        restart_session();
        set_session("dbs", null);
    }
    connect_error();
    exit();
}
if (support("scheme") && DB != "" && $_GET["ns"] !== "") {
    if (!isset($_GET["ns"])) {
        redirect(preg_replace('~ns=[^&]*&~', '', ME) . "ns=" . get_schema());
    }
    if (!set_schema($_GET["ns"])) {
        header("HTTP/1.1 404 Not Found");
        page_header('Schema' . ": " . h($_GET["ns"]), 'Invalid schema.', true);
        page_footer("ns");
        exit();
    }
}
$qf = "RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";
class TmpFile
{
    var $handler;
    var $size;
    function __construct()
    {
        $this->handler = tmpfile();
    }
    function write($Ab)
    {
        $this->size += strlen($Ab);
        fwrite($this->handler, $Ab);
    }
    function send()
    {
        fseek($this->handler, 0);
        fpassthru($this->handler);
        fclose($this->handler);
    }
}
$wc = "'(?:''|[^'\\\\]|\\\\.)*'";
$Rd = "IN|OUT|INOUT";
if (
    isset($_GET["select"]) &&
    ($_POST["edit"] || $_POST["clone"]) &&
    !$_POST["save"]
) {
    $_GET["edit"] = $_GET["select"];
}
if (isset($_GET["callf"])) {
    $_GET["call"] = $_GET["callf"];
}
if (isset($_GET["function"])) {
    $_GET["procedure"] = $_GET["function"];
}
if (isset($_GET["download"])) {
    $a = $_GET["download"];
    $p = fields($a);
    header("Content-Type: application/octet-stream");
    header(
        "Content-Disposition: attachment; filename=" .
            friendly_url("$a-" . implode("_", $_GET["where"])) .
            "." .
            friendly_url($_GET["field"])
    );
    $K = [idf_escape($_GET["field"])];
    $G = $m->select($a, $K, [where($_GET, $p)], $K);
    $I = $G ? $G->fetch_row() : [];
    echo $m->value($I[0], $p[$_GET["field"]]);
    exit();
} elseif (isset($_GET["table"])) {
    $a = $_GET["table"];
    $p = fields($a);
    if (!$p) {
        $n = error();
    }
    $R = table_status1($a, true);
    $B = $b->tableName($R);
    page_header(
        ($p && is_view($R)
            ? ($R['Engine'] == 'materialized view'
                ? 'Materialized view'
                : 'View')
            : 'Table') .
            ": " .
            ($B != "" ? $B : h($a)),
        $n
    );
    $b->selectLinks($R);
    $ub = $R["Comment"];
    if ($ub != "") {
        echo "<p class='nowrap'>" . 'Comment' . ": " . h($ub) . "\n";
    }
    if ($p) {
        $b->tableStructurePrint($p);
    }
    if (!is_view($R)) {
        if (support("indexes")) {
            echo "<h3 id='indexes'>" . 'Indexes' . "</h3>\n";
            $w = indexes($a);
            if ($w) {
                $b->tableIndexesPrint($w);
            }
            echo '<p class="links"><a href="' .
                h(ME) .
                'indexes=' .
                urlencode($a) .
                '">' .
                'Alter indexes' .
                "</a>\n";
        }
        if (fk_support($R)) {
            echo "<h3 id='foreign-keys'>" . 'Foreign keys' . "</h3>\n";
            $ed = foreign_keys($a);
            if ($ed) {
                echo "<table cellspacing='0'>\n",
                    "<thead><tr><th>" .
                        'Source' .
                        "<td>" .
                        'Target' .
                        "<td>" .
                        'ON DELETE' .
                        "<td>" .
                        'ON UPDATE' .
                        "<td></thead>\n";
                foreach ($ed as $B => $q) {
                    echo "<tr title='" . h($B) . "'>",
                        "<th><i>" .
                            implode("</i>, <i>", array_map('h', $q["source"])) .
                            "</i>",
                        "<td><a href='" .
                            h(
                                $q["db"] != ""
                                    ? preg_replace(
                                        '~db=[^&]*~',
                                        "db=" . urlencode($q["db"]),
                                        ME
                                    )
                                    : ($q["ns"] != ""
                                        ? preg_replace(
                                            '~ns=[^&]*~',
                                            "ns=" . urlencode($q["ns"]),
                                            ME
                                        )
                                        : ME)
                            ) .
                            "table=" .
                            urlencode($q["table"]) .
                            "'>" .
                            ($q["db"] != ""
                                ? "<b>" . h($q["db"]) . "</b>."
                                : "") .
                            ($q["ns"] != ""
                                ? "<b>" . h($q["ns"]) . "</b>."
                                : "") .
                            h($q["table"]) .
                            "</a>",
                        "(<i>" .
                            implode("</i>, <i>", array_map('h', $q["target"])) .
                            "</i>)",
                        "<td>" . h($q["on_delete"]) . "\n",
                        "<td>" . h($q["on_update"]) . "\n",
                        '<td><a href="' .
                            h(
                                ME .
                                    'foreign=' .
                                    urlencode($a) .
                                    '&name=' .
                                    urlencode($B)
                            ) .
                            '">' .
                            'Alter' .
                            '</a>';
                }
                echo "</table>\n";
            }
            echo '<p class="links"><a href="' .
                h(ME) .
                'foreign=' .
                urlencode($a) .
                '">' .
                'Add foreign key' .
                "</a>\n";
        }
    }
    if (support(is_view($R) ? "view_trigger" : "trigger")) {
        echo "<h3 id='triggers'>" . 'Triggers' . "</h3>\n";
        $_i = triggers($a);
        if ($_i) {
            echo "<table cellspacing='0'>\n";
            foreach ($_i as $y => $X) {
                echo "<tr valign='top'><td>" .
                    h($X[0]) .
                    "<td>" .
                    h($X[1]) .
                    "<th>" .
                    h($y) .
                    "<td><a href='" .
                    h(
                        ME .
                            'trigger=' .
                            urlencode($a) .
                            '&name=' .
                            urlencode($y)
                    ) .
                    "'>" .
                    'Alter' .
                    "</a>\n";
            }
            echo "</table>\n";
        }
        echo '<p class="links"><a href="' .
            h(ME) .
            'trigger=' .
            urlencode($a) .
            '">' .
            'Add trigger' .
            "</a>\n";
    }
} elseif (isset($_GET["schema"])) {
    page_header(
        'Database schema',
        "",
        [],
        h(DB . ($_GET["ns"] ? ".$_GET[ns]" : ""))
    );
    $Rh = [];
    $Sh = [];
    $ea = $_GET["schema"]
        ? $_GET["schema"]
        : $_COOKIE["adminer_schema-" . str_replace(".", "_", DB)];
    preg_match_all(
        '~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',
        $ea,
        $De,
        PREG_SET_ORDER
    );
    foreach ($De as $s => $A) {
        $Rh[$A[1]] = [$A[2], $A[3]];
        $Sh[] = "\n\t'" . js_escape($A[1]) . "': [ $A[2], $A[3] ]";
    }
    $pi = 0;
    $Ra = -1;
    $ah = [];
    $Fg = [];
    $re = [];
    foreach (table_status('', true) as $Q => $R) {
        if (is_view($R)) {
            continue;
        }
        $eg = 0;
        $ah[$Q]["fields"] = [];
        foreach (fields($Q) as $B => $o) {
            $eg += 1.25;
            $o["pos"] = $eg;
            $ah[$Q]["fields"][$B] = $o;
        }
        $ah[$Q]["pos"] = $Rh[$Q] ? $Rh[$Q] : [$pi, 0];
        foreach ($b->foreignKeys($Q) as $X) {
            if (!$X["db"]) {
                $pe = $Ra;
                if ($Rh[$Q][1] || $Rh[$X["table"]][1]) {
                    $pe =
                        min(
                            floatval($Rh[$Q][1]),
                            floatval($Rh[$X["table"]][1])
                        ) - 1;
                } else {
                    $Ra -= 0.1;
                }
                while ($re[(string) $pe]) {
                    $pe -= 0.0001;
                }
                $ah[$Q]["references"][$X["table"]][(string) $pe] = [
                    $X["source"],
                    $X["target"],
                ];
                $Fg[$X["table"]][$Q][(string) $pe] = $X["target"];
                $re[(string) $pe] = true;
            }
        }
        $pi = max($pi, $ah[$Q]["pos"][0] + 2.5 + $eg);
    }
    echo '<div id="schema" style="height: ',
        $pi,
        'em;">
<script',
        nonce(),
        '>
qs(\'#schema\').onselectstart = function () { return false; };
var tablePos = {',
        implode(",", $Sh) . "\n",
        '};
var em = qs(\'#schema\').offsetHeight / ',
        $pi,
        ';
document.onmousemove = schemaMousemove;
document.onmouseup = partialArg(schemaMouseup, \'',
        js_escape(DB),
        '\');
</script>
';
    foreach ($ah as $B => $Q) {
        echo "<div class='table' style='top: " .
        $Q["pos"][0] .
        "em; left: " .
        $Q["pos"][1] .
        "em;'>",
            '<a href="' .
                h(ME) .
                'table=' .
                urlencode($B) .
                '"><b>' .
                h($B) .
                "</b></a>",
            script("qsl('div').onmousedown = schemaMousedown;");
        foreach ($Q["fields"] as $o) {
            $X =
                '<span' .
                type_class($o["type"]) .
                ' title="' .
                h($o["full_type"] . ($o["null"] ? " NULL" : '')) .
                '">' .
                h($o["field"]) .
                '</span>';
            echo "<br>" . ($o["primary"] ? "<i>$X</i>" : $X);
        }
        foreach ((array) $Q["references"] as $Yh => $Gg) {
            foreach ($Gg as $pe => $Cg) {
                $qe = $pe - $Rh[$B][1];
                $s = 0;
                foreach ($Cg[0] as $wh) {
                    echo "\n<div class='references' title='" .
                        h($Yh) .
                        "' id='refs$pe-" .
                        $s++ .
                        "' style='left: $qe" .
                        "em; top: " .
                        $Q["fields"][$wh]["pos"] .
                        "em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: " .
                        -$qe .
                        "em;'></div></div>";
                }
            }
        }
        foreach ((array) $Fg[$B] as $Yh => $Gg) {
            foreach ($Gg as $pe => $f) {
                $qe = $pe - $Rh[$B][1];
                $s = 0;
                foreach ($f as $Xh) {
                    echo "\n<div class='references' title='" .
                        h($Yh) .
                        "' id='refd$pe-" .
                        $s++ .
                        "' style='left: $qe" .
                        "em; top: " .
                        $Q["fields"][$Xh]["pos"] .
                        "em; height: 1.25em; background: url(" .
                        h(
                            preg_replace("~\\?.*~", "", ME) .
                                "?file=arrow.gif) no-repeat right center;&version=4.7.5"
                        ) .
                        "'><div style='height: .5em; border-bottom: 1px solid Gray; width: " .
                        -$qe .
                        "em;'></div></div>";
                }
            }
        }
        echo "\n</div>\n";
    }
    foreach ($ah as $B => $Q) {
        foreach ((array) $Q["references"] as $Yh => $Gg) {
            foreach ($Gg as $pe => $Cg) {
                $Se = $pi;
                $He = -10;
                foreach ($Cg[0] as $y => $wh) {
                    $fg = $Q["pos"][0] + $Q["fields"][$wh]["pos"];
                    $gg =
                        $ah[$Yh]["pos"][0] +
                        $ah[$Yh]["fields"][$Cg[1][$y]]["pos"];
                    $Se = min($Se, $fg, $gg);
                    $He = max($He, $fg, $gg);
                }
                echo "<div class='references' id='refl$pe' style='left: $pe" .
                    "em; top: $Se" .
                    "em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: " .
                    ($He - $Se) .
                    "em;'></div></div>\n";
            }
        }
    }
    echo '</div>
<p class="links"><a href="',
        h(ME . "schema=" . urlencode($ea)),
        '" id="schema-link">Permanent link</a>
';
} elseif (isset($_GET["dump"])) {
    $a = $_GET["dump"];
    if ($_POST && !$n) {
        $Db = "";
        foreach (
            [
                "output",
                "format",
                "db_style",
                "routines",
                "events",
                "table_style",
                "auto_increment",
                "triggers",
                "data_style",
            ]
            as $y
        ) {
            $Db .= "&$y=" . urlencode($_POST[$y]);
        }
        cookie("adminer_export", substr($Db, 1));
        $S =
            array_flip((array) $_POST["tables"]) +
            array_flip((array) $_POST["data"]);
        $Ic = dump_headers(
            count($S) == 1 ? key($S) : DB,
            DB == "" || count($S) > 1
        );
        $Zd = preg_match('~sql~', $_POST["format"]);
        if ($Zd) {
            echo "-- Adminer $ia " . $ec[DRIVER] . " dump\n\n";
            if ($x == "sql") {
                echo "SET NAMES utf8;
SET time_zone = '+00:00';
" .
                    ($_POST["data_style"]
                        ? "SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
"
                        : "") .
                    "
";
                $g->query("SET time_zone = '+00:00';");
            }
        }
        $Ih = $_POST["db_style"];
        $k = [DB];
        if (DB == "") {
            $k = $_POST["databases"];
            if (is_string($k)) {
                $k = explode("\n", rtrim(str_replace("\r", "", $k), "\n"));
            }
        }
        foreach ((array) $k as $l) {
            $b->dumpDatabase($l);
            if ($g->select_db($l)) {
                if (
                    $Zd &&
                    preg_match('~CREATE~', $Ih) &&
                    ($i = $g->result(
                        "SHOW CREATE DATABASE " . idf_escape($l),
                        1
                    ))
                ) {
                    set_utf8mb4($i);
                    if ($Ih == "DROP+CREATE") {
                        echo "DROP DATABASE IF EXISTS " .
                            idf_escape($l) .
                            ";\n";
                    }
                    echo "$i;\n";
                }
                if ($Zd) {
                    if ($Ih) {
                        echo use_sql($l) . ";\n\n";
                    }
                    $Jf = "";
                    if ($_POST["routines"]) {
                        foreach (["FUNCTION", "PROCEDURE"] as $Ug) {
                            foreach (
                                get_rows(
                                    "SHOW $Ug STATUS WHERE Db = " . q($l),
                                    null,
                                    "-- "
                                )
                                as $I
                            ) {
                                $i = remove_definer(
                                    $g->result(
                                        "SHOW CREATE $Ug " .
                                            idf_escape($I["Name"]),
                                        2
                                    )
                                );
                                set_utf8mb4($i);
                                $Jf .=
                                    ($Ih != 'DROP+CREATE'
                                        ? "DROP $Ug IF EXISTS " .
                                            idf_escape($I["Name"]) .
                                            ";;\n"
                                        : "") . "$i;;\n\n";
                            }
                        }
                    }
                    if ($_POST["events"]) {
                        foreach (get_rows("SHOW EVENTS", null, "-- ") as $I) {
                            $i = remove_definer(
                                $g->result(
                                    "SHOW CREATE EVENT " .
                                        idf_escape($I["Name"]),
                                    3
                                )
                            );
                            set_utf8mb4($i);
                            $Jf .=
                                ($Ih != 'DROP+CREATE'
                                    ? "DROP EVENT IF EXISTS " .
                                        idf_escape($I["Name"]) .
                                        ";;\n"
                                    : "") . "$i;;\n\n";
                        }
                    }
                    if ($Jf) {
                        echo "DELIMITER ;;\n\n$Jf" . "DELIMITER ;\n\n";
                    }
                }
                if ($_POST["table_style"] || $_POST["data_style"]) {
                    $aj = [];
                    foreach (table_status('', true) as $B => $R) {
                        $Q = DB == "" || in_array($B, (array) $_POST["tables"]);
                        $Mb = DB == "" || in_array($B, (array) $_POST["data"]);
                        if ($Q || $Mb) {
                            if ($Ic == "tar") {
                                $li = new TmpFile();
                                ob_start([$li, 'write'], 1e5);
                            }
                            $b->dumpTable(
                                $B,
                                $Q ? $_POST["table_style"] : "",
                                is_view($R) ? 2 : 0
                            );
                            if (is_view($R)) {
                                $aj[] = $B;
                            } elseif ($Mb) {
                                $p = fields($B);
                                $b->dumpData(
                                    $B,
                                    $_POST["data_style"],
                                    "SELECT *" .
                                        convert_fields($p, $p) .
                                        " FROM " .
                                        table($B)
                                );
                            }
                            if (
                                $Zd &&
                                $_POST["triggers"] &&
                                $Q &&
                                ($_i = trigger_sql($B))
                            ) {
                                echo "\nDELIMITER ;;\n$_i\nDELIMITER ;\n";
                            }
                            if ($Ic == "tar") {
                                ob_end_flush();
                                tar_file(
                                    (DB != "" ? "" : "$l/") . "$B.csv",
                                    $li
                                );
                            } elseif ($Zd) {
                                echo "\n";
                            }
                        }
                    }
                    foreach ($aj as $Zi) {
                        $b->dumpTable($Zi, $_POST["table_style"], 1);
                    }
                    if ($Ic == "tar") {
                        echo pack("x512");
                    }
                }
            }
        }
        if ($Zd) {
            echo "-- " . $g->result("SELECT NOW()") . "\n";
        }
        exit();
    }
    page_header(
        'Export',
        $n,
        $_GET["export"] != "" ? ["table" => $_GET["export"]] : [],
        h(DB)
    );
    echo '
<form action="" method="post">
<table cellspacing="0" class="layout">
';
    $Qb = ['', 'USE', 'DROP+CREATE', 'CREATE'];
    $Th = ['', 'DROP+CREATE', 'CREATE'];
    $Nb = ['', 'TRUNCATE+INSERT', 'INSERT'];
    if ($x == "sql") {
        $Nb[] = 'INSERT+UPDATE';
    }
    parse_str($_COOKIE["adminer_export"], $I);
    if (!$I) {
        $I = [
            "output" => "text",
            "format" => "sql",
            "db_style" => DB != "" ? "" : "CREATE",
            "table_style" => "DROP+CREATE",
            "data_style" => "INSERT",
        ];
    }
    if (!isset($I["events"])) {
        $I["routines"] = $I["events"] = $_GET["dump"] == "";
        $I["triggers"] = $I["table_style"];
    }
    echo "<tr><th>" .
        'Output' .
        "<td>" .
        html_select("output", $b->dumpOutput(), $I["output"], 0) .
        "\n";
    echo "<tr><th>" .
        'Format' .
        "<td>" .
        html_select("format", $b->dumpFormat(), $I["format"], 0) .
        "\n";
    echo $x == "sqlite"
    ? ""
    : "<tr><th>" .
        'Database' .
        "<td>" .
        html_select('db_style', $Qb, $I["db_style"]) .
        (support("routine")
            ? checkbox("routines", 1, $I["routines"], 'Routines')
            : "") .
        (support("event") ? checkbox("events", 1, $I["events"], 'Events') : ""),
        "<tr><th>" .
            'Tables' .
            "<td>" .
            html_select('table_style', $Th, $I["table_style"]) .
            checkbox(
                "auto_increment",
                1,
                $I["auto_increment"],
                'Auto Increment'
            ) .
            (support("trigger")
                ? checkbox("triggers", 1, $I["triggers"], 'Triggers')
                : ""),
        "<tr><th>" .
            'Data' .
            "<td>" .
            html_select('data_style', $Nb, $I["data_style"]),
        '</table>
<p><input type="submit" value="Export">
<input type="hidden" name="token" value="',
        $oi,
        '">

<table cellspacing="0">
',
        script("qsl('table').onclick = dumpClick;");
    $jg = [];
    if (DB != "") {
        $fb = $a != "" ? "" : " checked";
        echo "<thead><tr>",
            "<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$fb>" .
                'Tables' .
                "</label>" .
                script(
                    "qs('#check-tables').onclick = partial(formCheck, /^tables\\[/);",
                    ""
                ),
            "<th style='text-align: right;'><label class='block'>" .
                'Data' .
                "<input type='checkbox' id='check-data'$fb></label>" .
                script(
                    "qs('#check-data').onclick = partial(formCheck, /^data\\[/);",
                    ""
                ),
            "</thead>\n";
        $aj = "";
        $Uh = tables_list();
        foreach ($Uh as $B => $T) {
            $ig = preg_replace('~_.*~', '', $B);
            $fb = $a == "" || $a == (substr($a, -1) == "%" ? "$ig%" : $B);
            $mg = "<tr><td>" . checkbox("tables[]", $B, $fb, $B, "", "block");
            if ($T !== null && !preg_match('~table~i', $T)) {
                $aj .= "$mg\n";
            } else {
                echo "$mg<td align='right'><label class='block'><span id='Rows-" .
                    h($B) .
                    "'></span>" .
                    checkbox("data[]", $B, $fb) .
                    "</label>\n";
            }
            $jg[$ig]++;
        }
        echo $aj;
        if ($Uh) {
            echo script("ajaxSetHtml('" . js_escape(ME) . "script=db');");
        }
    } else {
        echo "<thead><tr><th style='text-align: left;'>",
            "<label class='block'><input type='checkbox' id='check-databases'" .
                ($a == "" ? " checked" : "") .
                ">" .
                'Database' .
                "</label>",
            script(
                "qs('#check-databases').onclick = partial(formCheck, /^databases\\[/);",
                ""
            ),
            "</thead>\n";
        $k = $b->databases();
        if ($k) {
            foreach ($k as $l) {
                if (!information_schema($l)) {
                    $ig = preg_replace('~_.*~', '', $l);
                    echo "<tr><td>" .
                        checkbox(
                            "databases[]",
                            $l,
                            $a == "" || $a == "$ig%",
                            $l,
                            "",
                            "block"
                        ) .
                        "\n";
                    $jg[$ig]++;
                }
            }
        } else {
            echo "<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";
        }
    }
    echo '</table>
</form>
';
    $Wc = true;
    foreach ($jg as $y => $X) {
        if ($y != "" && $X > 1) {
            echo ($Wc ? "<p>" : " ") .
                "<a href='" .
                h(ME) .
                "dump=" .
                urlencode("$y%") .
                "'>" .
                h($y) .
                "</a>";
            $Wc = false;
        }
    }
} elseif (isset($_GET["privileges"])) {
    page_header('Privileges');
    echo '<p class="links"><a href="' .
        h(ME) .
        'user=">' .
        'Create user' .
        "</a>";
    $G = $g->query(
        "SELECT User, Host FROM mysql." .
            (DB == "" ? "user" : "db WHERE " . q(DB) . " LIKE Db") .
            " ORDER BY Host, User"
    );
    $ld = $G;
    if (!$G) {
        $G = $g->query(
            "SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host"
        );
    }
    echo "<form action=''><p>\n";
    hidden_fields_get();
    echo "<input type='hidden' name='db' value='" . h(DB) . "'>\n",
        $ld ? "" : "<input type='hidden' name='grant' value=''>\n",
        "<table cellspacing='0'>\n",
        "<thead><tr><th>" . 'Username' . "<th>" . 'Server' . "<th></thead>\n";
    while ($I = $G->fetch_assoc()) {
        echo '<tr' .
            odd() .
            '><td>' .
            h($I["User"]) .
            "<td>" .
            h($I["Host"]) .
            '<td><a href="' .
            h(
                ME .
                    'user=' .
                    urlencode($I["User"]) .
                    '&host=' .
                    urlencode($I["Host"])
            ) .
            '">' .
            'Edit' .
            "</a>\n";
    }
    if (!$ld || DB != "") {
        echo "<tr" .
            odd() .
            "><td><input name='user' autocapitalize='off'><td><input name='host' value='localhost' autocapitalize='off'><td><input type='submit' value='" .
            'Edit' .
            "'>\n";
    }
    echo "</table>\n", "</form>\n";
} elseif (isset($_GET["sql"])) {
    if (!$n && $_POST["export"]) {
        dump_headers("sql");
        $b->dumpTable("", "");
        $b->dumpData("", "table", $_POST["query"]);
        exit();
    }
    restart_session();
    $zd = &get_session("queries");
    $yd = &$zd[DB];
    if (!$n && $_POST["clear"]) {
        $yd = [];
        redirect(remove_from_uri("history"));
    }
    page_header(isset($_GET["import"]) ? 'Import' : 'SQL command', $n);
    if (!$n && $_POST) {
        $id = false;
        if (!isset($_GET["import"])) {
            $F = $_POST["query"];
        } elseif ($_POST["webfile"]) {
            $_h = $b->importServerPath();
            $id = @fopen(
                file_exists($_h) ? $_h : "compress.zlib://$_h.gz",
                "rb"
            );
            $F = $id ? fread($id, 1e6) : false;
        } else {
            $F = get_file("sql_file", true);
        }
        if (is_string($F)) {
            if (function_exists('memory_get_usage')) {
                @ini_set(
                    "memory_limit",
                    max(
                        ini_bytes("memory_limit"),
                        2 * strlen($F) + memory_get_usage() + 8e6
                    )
                );
            }
            if ($F != "" && strlen($F) < 1e6) {
                $ug = $F . (preg_match("~;[ \t\r\n]*\$~", $F) ? "" : ";");
                if (!$yd || reset(end($yd)) != $ug) {
                    restart_session();
                    $yd[] = [$ug, time()];
                    set_session("queries", $zd);
                    stop_session();
                }
            }
            $xh = "(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";
            $Wb = ";";
            $C = 0;
            $tc = true;
            $h = connect();
            if (is_object($h) && DB != "") {
                $h->select_db(DB);
                if ($_GET["ns"] != "") {
                    set_schema($_GET["ns"], $h);
                }
            }
            $tb = 0;
            $yc = [];
            $Qf =
                '[\'"' .
                ($x == "sql"
                    ? '`#'
                    : ($x == "sqlite"
                        ? '`['
                        : ($x == "mssql"
                            ? '['
                            : ''))) .
                ']|/\*|-- |$' .
                ($x == "pgsql" ? '|\$[^$]*\$' : '');
            $qi = microtime(true);
            parse_str($_COOKIE["adminer_export"], $xa);
            $kc = $b->dumpFormat();
            unset($kc["sql"]);
            while ($F != "") {
                if (!$C && preg_match("~^$xh*+DELIMITER\\s+(\\S+)~i", $F, $A)) {
                    $Wb = $A[1];
                    $F = substr($F, strlen($A[0]));
                } else {
                    preg_match(
                        '(' . preg_quote($Wb) . "\\s*|$Qf)",
                        $F,
                        $A,
                        PREG_OFFSET_CAPTURE,
                        $C
                    );
                    list($gd, $eg) = $A[0];
                    if (!$gd && $id && !feof($id)) {
                        $F .= fread($id, 1e5);
                    } else {
                        if (!$gd && rtrim($F) == "") {
                            break;
                        }
                        $C = $eg + strlen($gd);
                        if ($gd && rtrim($gd) != $Wb) {
                            while (
                                preg_match(
                                    '(' .
                                        ($gd == '/*'
                                            ? '\*/'
                                            : ($gd == '['
                                                ? ']'
                                                : (preg_match('~^-- |^#~', $gd)
                                                    ? "\n"
                                                    : preg_quote($gd) .
                                                        "|\\\\."))) .
                                        '|$)s',
                                    $F,
                                    $A,
                                    PREG_OFFSET_CAPTURE,
                                    $C
                                )
                            ) {
                                $Yg = $A[0][0];
                                if (!$Yg && $id && !feof($id)) {
                                    $F .= fread($id, 1e5);
                                } else {
                                    $C = $A[0][1] + strlen($Yg);
                                    if ($Yg[0] != "\\") {
                                        break;
                                    }
                                }
                            }
                        } else {
                            $tc = false;
                            $ug = substr($F, 0, $eg);
                            $tb++;
                            $mg =
                                "<pre id='sql-$tb'><code class='jush-$x'>" .
                                $b->sqlCommandQuery($ug) .
                                "</code></pre>\n";
                            if (
                                $x == "sqlite" &&
                                preg_match("~^$xh*+ATTACH\\b~i", $ug, $A)
                            ) {
                                echo $mg,
                                    "<p class='error'>" .
                                        'ATTACH queries are not supported.' .
                                        "\n";
                                $yc[] = " <a href='#sql-$tb'>$tb</a>";
                                if ($_POST["error_stops"]) {
                                    break;
                                }
                            } else {
                                if (!$_POST["only_errors"]) {
                                    echo $mg;
                                    ob_flush();
                                    flush();
                                }
                                $Dh = microtime(true);
                                if (
                                    $g->multi_query($ug) &&
                                    is_object($h) &&
                                    preg_match("~^$xh*+USE\\b~i", $ug)
                                ) {
                                    $h->query($ug);
                                }
                                do {
                                    $G = $g->store_result();
                                    if ($g->error) {
                                        echo $_POST["only_errors"] ? $mg : "",
                                            "<p class='error'>" .
                                                'Error in query' .
                                                ($g->errno
                                                    ? " ($g->errno)"
                                                    : "") .
                                                ": " .
                                                error() .
                                                "\n";
                                        $yc[] = " <a href='#sql-$tb'>$tb</a>";
                                        if ($_POST["error_stops"]) {
                                            break 2;
                                        }
                                    } else {
                                        $ei =
                                            " <span class='time'>(" .
                                            format_time($Dh) .
                                            ")</span>" .
                                            (strlen($ug) < 1000
                                                ? " <a href='" .
                                                    h(ME) .
                                                    "sql=" .
                                                    urlencode(trim($ug)) .
                                                    "'>" .
                                                    'Edit' .
                                                    "</a>"
                                                : "");
                                        $za = $g->affected_rows;
                                        $dj = $_POST["only_errors"]
                                            ? ""
                                            : $m->warnings();
                                        $ej = "warnings-$tb";
                                        if ($dj) {
                                            $ei .=
                                                ", <a href='#$ej'>" .
                                                'Warnings' .
                                                "</a>" .
                                                script(
                                                    "qsl('a').onclick = partial(toggle, '$ej');",
                                                    ""
                                                );
                                        }
                                        $Fc = null;
                                        $Gc = "explain-$tb";
                                        if (is_object($G)) {
                                            $z = $_POST["limit"];
                                            $Cf = select($G, $h, [], $z);
                                            if (!$_POST["only_errors"]) {
                                                echo "<form action='' method='post'>\n";
                                                $gf = $G->num_rows;
                                                echo "<p>" .
                                                ($gf
                                                    ? ($z && $gf > $z
                                                            ? sprintf(
                                                                '%d / ',
                                                                $z
                                                            )
                                                            : "") .
                                                        lang(
                                                            [
                                                                '%d row',
                                                                '%d rows',
                                                            ],
                                                            $gf
                                                        )
                                                    : ""),
                                                    $ei;
                                                if (
                                                    $h &&
                                                    preg_match(
                                                        "~^($xh|\\()*+SELECT\\b~i",
                                                        $ug
                                                    ) &&
                                                    ($Fc = explain($h, $ug))
                                                ) {
                                                    echo ", <a href='#$Gc'>Explain</a>" .
                                                        script(
                                                            "qsl('a').onclick = partial(toggle, '$Gc');",
                                                            ""
                                                        );
                                                }
                                                $t = "export-$tb";
                                                echo ", <a href='#$t'>" .
                                                    'Export' .
                                                    "</a>" .
                                                    script(
                                                        "qsl('a').onclick = partial(toggle, '$t');",
                                                        ""
                                                    ) .
                                                    "<span id='$t' class='hidden'>: " .
                                                    html_select(
                                                        "output",
                                                        $b->dumpOutput(),
                                                        $xa["output"]
                                                    ) .
                                                    " " .
                                                    html_select(
                                                        "format",
                                                        $kc,
                                                        $xa["format"]
                                                    ) .
                                                    "<input type='hidden' name='query' value='" .
                                                    h($ug) .
                                                    "'>" .
                                                    " <input type='submit' name='export' value='" .
                                                    'Export' .
                                                    "'><input type='hidden' name='token' value='$oi'></span>\n" .
                                                    "</form>\n";
                                            }
                                        } else {
                                            if (
                                                preg_match(
                                                    "~^$xh*+(CREATE|DROP|ALTER)$xh++(DATABASE|SCHEMA)\\b~i",
                                                    $ug
                                                )
                                            ) {
                                                restart_session();
                                                set_session("dbs", null);
                                                stop_session();
                                            }
                                            if (!$_POST["only_errors"]) {
                                                echo "<p class='message' title='" .
                                                    h($g->info) .
                                                    "'>" .
                                                    lang(
                                                        [
                                                            'Query executed OK, %d row affected.',
                                                            'Query executed OK, %d rows affected.',
                                                        ],
                                                        $za
                                                    ) .
                                                    "$ei\n";
                                            }
                                        }
                                        echo $dj
                                            ? "<div id='$ej' class='hidden'>\n$dj</div>\n"
                                            : "";
                                        if ($Fc) {
                                            echo "<div id='$Gc' class='hidden'>\n";
                                            select($Fc, $h, $Cf);
                                            echo "</div>\n";
                                        }
                                    }
                                    $Dh = microtime(true);
                                } while ($g->next_result());
                            }
                            $F = substr($F, $C);
                            $C = 0;
                        }
                    }
                }
            }
            if ($tc) {
                echo "<p class='message'>" . 'No commands to execute.' . "\n";
            } elseif ($_POST["only_errors"]) {
                echo "<p class='message'>" .
                lang(
                    ['%d query executed OK.', '%d queries executed OK.'],
                    $tb - count($yc)
                ),
                    " <span class='time'>(" . format_time($qi) . ")</span>\n";
            } elseif ($yc && $tb > 1) {
                echo "<p class='error'>" .
                    'Error in query' .
                    ": " .
                    implode("", $yc) .
                    "\n";
            }
        } else {
            echo "<p class='error'>" . upload_error($F) . "\n";
        }
    }
    echo '
<form action="" method="post" enctype="multipart/form-data" id="form">
';
    $Cc = "<input type='submit' value='" . 'Execute' . "' title='Ctrl+Enter'>";
    if (!isset($_GET["import"])) {
        $ug = $_GET["sql"];
        if ($_POST) {
            $ug = $_POST["query"];
        } elseif ($_GET["history"] == "all") {
            $ug = $yd;
        } elseif ($_GET["history"] != "") {
            $ug = $yd[$_GET["history"]][0];
        }
        echo "<p>";
        textarea("query", $ug, 20);
        echo script(
        ($_POST ? "" : "qs('textarea').focus();\n") .
            "qs('#form').onsubmit = partial(sqlSubmit, qs('#form'), '" .
            remove_from_uri("sql|limit|error_stops|only_errors") .
            "');"
    ),
            "<p>$Cc\n",
            'Limit rows' .
                ": <input type='number' name='limit' class='size' value='" .
                h($_POST ? $_POST["limit"] : $_GET["limit"]) .
                "'>\n";
    } else {
        echo "<fieldset><legend>" . 'File upload' . "</legend><div>";
        $rd = extension_loaded("zlib") ? "[.gz]" : "";
        echo ini_bool("file_uploads")
        ? "SQL$rd (&lt; " .
            ini_get("upload_max_filesize") .
            "B): <input type='file' name='sql_file[]' multiple>\n$Cc"
        : 'File uploads are disabled.',
            "</div></fieldset>\n";
        $Gd = $b->importServerPath();
        if ($Gd) {
            echo "<fieldset><legend>" . 'From server' . "</legend><div>",
                sprintf('Webserver file %s', "<code>" . h($Gd) . "$rd</code>"),
                ' <input type="submit" name="webfile" value="' .
                    'Run file' .
                    '">',
                "</div></fieldset>\n";
        }
        echo "<p>";
    }
    echo checkbox(
    "error_stops",
    1,
    $_POST ? $_POST["error_stops"] : isset($_GET["import"]),
    'Stop on error'
) . "\n",
        checkbox(
            "only_errors",
            1,
            $_POST ? $_POST["only_errors"] : isset($_GET["import"]),
            'Show only errors'
        ) . "\n",
        "<input type='hidden' name='token' value='$oi'>\n";
    if (!isset($_GET["import"]) && $yd) {
        print_fieldset("history", 'History', $_GET["history"] != "");
        for ($X = end($yd); $X; $X = prev($yd)) {
            $y = key($yd);
            list($ug, $ei, $oc) = $X;
            echo '<a href="' .
                h(ME . "sql=&history=$y") .
                '">' .
                'Edit' .
                "</a>" .
                " <span class='time' title='" .
                @date('Y-m-d', $ei) .
                "'>" .
                @date("H:i:s", $ei) .
                "</span>" .
                " <code class='jush-$x'>" .
                shorten_utf8(
                    ltrim(
                        str_replace(
                            "\n",
                            " ",
                            str_replace(
                                "\r",
                                "",
                                preg_replace('~^(#|-- ).*~m', '', $ug)
                            )
                        )
                    ),
                    80,
                    "</code>"
                ) .
                ($oc ? " <span class='time'>($oc)</span>" : "") .
                "<br>\n";
        }
        echo "<input type='submit' name='clear' value='" . 'Clear' . "'>\n",
            "<a href='" .
                h(ME . "sql=&history=all") .
                "'>" .
                'Edit all' .
                "</a>\n",
            "</div></fieldset>\n";
    }
    echo '</form>
';
} elseif (isset($_GET["edit"])) {
    $a = $_GET["edit"];
    $p = fields($a);
    $Z = isset($_GET["select"])
        ? ($_POST["check"] && count($_POST["check"]) == 1
            ? where_check($_POST["check"][0], $p)
            : "")
        : where($_GET, $p);
    $Ji = isset($_GET["select"]) ? $_POST["edit"] : $Z;
    foreach ($p as $B => $o) {
        if (
            !isset($o["privileges"][$Ji ? "update" : "insert"]) ||
            $b->fieldName($o) == "" ||
            $o["generated"]
        ) {
            unset($p[$B]);
        }
    }
    if ($_POST && !$n && !isset($_GET["select"])) {
        $xe = $_POST["referer"];
        if ($_POST["insert"]) {
            $xe = $Ji ? null : $_SERVER["REQUEST_URI"];
        } elseif (!preg_match('~^.+&select=.+$~', $xe)) {
            $xe = ME . "select=" . urlencode($a);
        }
        $w = indexes($a);
        $Ei = unique_array($_GET["where"], $w);
        $xg = "\nWHERE $Z";
        if (isset($_POST["delete"])) {
            queries_redirect(
                $xe,
                'Item has been deleted.',
                $m->delete($a, $xg, !$Ei)
            );
        } else {
            $N = [];
            foreach ($p as $B => $o) {
                $X = process_input($o);
                if ($X !== false && $X !== null) {
                    $N[idf_escape($B)] = $X;
                }
            }
            if ($Ji) {
                if (!$N) {
                    redirect($xe);
                }
                queries_redirect(
                    $xe,
                    'Item has been updated.',
                    $m->update($a, $N, $xg, !$Ei)
                );
                if (is_ajax()) {
                    page_headers();
                    page_messages($n);
                    exit();
                }
            } else {
                $G = $m->insert($a, $N);
                $oe = $G ? last_id() : 0;
                queries_redirect(
                    $xe,
                    sprintf('Item%s has been inserted.', $oe ? " $oe" : ""),
                    $G
                );
            }
        }
    }
    $I = null;
    if ($_POST["save"]) {
        $I = (array) $_POST["fields"];
    } elseif ($Z) {
        $K = [];
        foreach ($p as $B => $o) {
            if (isset($o["privileges"]["select"])) {
                $Ga = convert_field($o);
                if ($_POST["clone"] && $o["auto_increment"]) {
                    $Ga = "''";
                }
                if ($x == "sql" && preg_match("~enum|set~", $o["type"])) {
                    $Ga = "1*" . idf_escape($B);
                }
                $K[] = ($Ga ? "$Ga AS " : "") . idf_escape($B);
            }
        }
        $I = [];
        if (!support("table")) {
            $K = ["*"];
        }
        if ($K) {
            $G = $m->select(
                $a,
                $K,
                [$Z],
                $K,
                [],
                isset($_GET["select"]) ? 2 : 1
            );
            if (!$G) {
                $n = error();
            } else {
                $I = $G->fetch_assoc();
                if (!$I) {
                    $I = false;
                }
            }
            if (isset($_GET["select"]) && (!$I || $G->fetch_assoc())) {
                $I = null;
            }
        }
    }
    if (!support("table") && !$p) {
        if (!$Z) {
            $G = $m->select($a, ["*"], $Z, ["*"]);
            $I = $G ? $G->fetch_assoc() : false;
            if (!$I) {
                $I = [$m->primary => ""];
            }
        }
        if ($I) {
            foreach ($I as $y => $X) {
                if (!$Z) {
                    $I[$y] = null;
                }
                $p[$y] = [
                    "field" => $y,
                    "null" => $y != $m->primary,
                    "auto_increment" => $y == $m->primary,
                ];
            }
        }
    }
    edit_form($a, $p, $I, $Ji);
} elseif (isset($_GET["create"])) {
    $a = $_GET["create"];
    $Sf = [];
    foreach (
        ['HASH', 'LINEAR HASH', 'KEY', 'LINEAR KEY', 'RANGE', 'LIST']
        as $y
    ) {
        $Sf[$y] = $y;
    }
    $Eg = referencable_primary($a);
    $ed = [];
    foreach ($Eg as $Ph => $o) {
        $ed[
            str_replace("`", "``", $Ph) .
                "`" .
                str_replace("`", "``", $o["field"])
        ] = $Ph;
    }
    $Ff = [];
    $R = [];
    if ($a != "") {
        $Ff = fields($a);
        $R = table_status($a);
        if (!$R) {
            $n = 'No tables.';
        }
    }
    $I = $_POST;
    $I["fields"] = (array) $I["fields"];
    if ($I["auto_increment_col"]) {
        $I["fields"][$I["auto_increment_col"]]["auto_increment"] = true;
    }
    if ($_POST) {
        set_adminer_settings([
            "comments" => $_POST["comments"],
            "defaults" => $_POST["defaults"],
        ]);
    }
    if ($_POST && !process_fields($I["fields"]) && !$n) {
        if ($_POST["drop"]) {
            queries_redirect(
                substr(ME, 0, -1),
                'Table has been dropped.',
                drop_tables([$a])
            );
        } else {
            $p = [];
            $Da = [];
            $Oi = false;
            $cd = [];
            $Ef = reset($Ff);
            $Aa = " FIRST";
            foreach ($I["fields"] as $y => $o) {
                $q = $ed[$o["type"]];
                $Ai = $q !== null ? $Eg[$q] : $o;
                if ($o["field"] != "") {
                    if (!$o["has_default"]) {
                        $o["default"] = null;
                    }
                    if ($y == $I["auto_increment_col"]) {
                        $o["auto_increment"] = true;
                    }
                    $rg = process_field($o, $Ai);
                    $Da[] = [$o["orig"], $rg, $Aa];
                    if ($rg != process_field($Ef, $Ef)) {
                        $p[] = [$o["orig"], $rg, $Aa];
                        if ($o["orig"] != "" || $Aa) {
                            $Oi = true;
                        }
                    }
                    if ($q !== null) {
                        $cd[idf_escape($o["field"])] =
                            ($a != "" && $x != "sqlite" ? "ADD" : " ") .
                            format_foreign_key([
                                'table' => $ed[$o["type"]],
                                'source' => [$o["field"]],
                                'target' => [$Ai["field"]],
                                'on_delete' => $o["on_delete"],
                            ]);
                    }
                    $Aa = " AFTER " . idf_escape($o["field"]);
                } elseif ($o["orig"] != "") {
                    $Oi = true;
                    $p[] = [$o["orig"]];
                }
                if ($o["orig"] != "") {
                    $Ef = next($Ff);
                    if (!$Ef) {
                        $Aa = "";
                    }
                }
            }
            $Uf = "";
            if ($Sf[$I["partition_by"]]) {
                $Vf = [];
                if (
                    $I["partition_by"] == 'RANGE' ||
                    $I["partition_by"] == 'LIST'
                ) {
                    foreach (array_filter($I["partition_names"]) as $y => $X) {
                        $Y = $I["partition_values"][$y];
                        $Vf[] =
                            "\n  PARTITION " .
                            idf_escape($X) .
                            " VALUES " .
                            ($I["partition_by"] == 'RANGE'
                                ? "LESS THAN"
                                : "IN") .
                            ($Y != "" ? " ($Y)" : " MAXVALUE");
                    }
                }
                $Uf .=
                    "\nPARTITION BY $I[partition_by]($I[partition])" .
                    ($Vf
                        ? " (" . implode(",", $Vf) . "\n)"
                        : ($I["partitions"]
                            ? " PARTITIONS " . +$I["partitions"]
                            : ""));
            } elseif (
                support("partitioning") &&
                preg_match("~partitioned~", $R["Create_options"])
            ) {
                $Uf .= "\nREMOVE PARTITIONING";
            }
            $Le = 'Table has been altered.';
            if ($a == "") {
                cookie("adminer_engine", $I["Engine"]);
                $Le = 'Table has been created.';
            }
            $B = trim($I["name"]);
            queries_redirect(
                ME . (support("table") ? "table=" : "select=") . urlencode($B),
                $Le,
                alter_table(
                    $a,
                    $B,
                    $x == "sqlite" && ($Oi || $cd) ? $Da : $p,
                    $cd,
                    $I["Comment"] != $R["Comment"] ? $I["Comment"] : null,
                    $I["Engine"] && $I["Engine"] != $R["Engine"]
                        ? $I["Engine"]
                        : "",
                    $I["Collation"] && $I["Collation"] != $R["Collation"]
                        ? $I["Collation"]
                        : "",
                    $I["Auto_increment"] != ""
                        ? number($I["Auto_increment"])
                        : "",
                    $Uf
                )
            );
        }
    }
    page_header(
        $a != "" ? 'Alter table' : 'Create table',
        $n,
        ["table" => $a],
        h($a)
    );
    if (!$_POST) {
        $I = [
            "Engine" => $_COOKIE["adminer_engine"],
            "fields" => [
                [
                    "field" => "",
                    "type" => isset($U["int"])
                        ? "int"
                        : (isset($U["integer"])
                            ? "integer"
                            : ""),
                    "on_update" => "",
                ],
            ],
            "partition_names" => [""],
        ];
        if ($a != "") {
            $I = $R;
            $I["name"] = $a;
            $I["fields"] = [];
            if (!$_GET["auto_increment"]) {
                $I["Auto_increment"] = "";
            }
            foreach ($Ff as $o) {
                $o["has_default"] = isset($o["default"]);
                $I["fields"][] = $o;
            }
            if (support("partitioning")) {
                $jd =
                    "FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = " .
                    q(DB) .
                    " AND TABLE_NAME = " .
                    q($a);
                $G = $g->query(
                    "SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $jd ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1"
                );
                list(
                    $I["partition_by"],
                    $I["partitions"],
                    $I["partition"],
                ) = $G->fetch_row();
                $Vf = get_key_vals(
                    "SELECT PARTITION_NAME, PARTITION_DESCRIPTION $jd AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION"
                );
                $Vf[""] = "";
                $I["partition_names"] = array_keys($Vf);
                $I["partition_values"] = array_values($Vf);
            }
        }
    }
    $pb = collations();
    $vc = engines();
    foreach ($vc as $uc) {
        if (!strcasecmp($uc, $I["Engine"])) {
            $I["Engine"] = $uc;
            break;
        }
    }
    echo '
<form action="" method="post" id="form">
<p>
';
    if (support("columns") || $a == "") {
        echo 'Table name: <input name="name" data-maxlength="64" value="',
            h($I["name"]),
            '" autocapitalize="off">
';
        if ($a == "" && !$_POST) {
            echo script("focus(qs('#form')['name']);");
        }
        echo $vc
        ? "<select name='Engine'>" .
            optionlist(["" => "(" . 'engine' . ")"] + $vc, $I["Engine"]) .
            "</select>" .
            on_help("getTarget(event).value", 1) .
            script("qsl('select').onchange = helpClose;")
        : "",
            ' ',
            $pb && !preg_match("~sqlite|mssql~", $x)
                ? html_select(
                    "Collation",
                    ["" => "(" . 'collation' . ")"] + $pb,
                    $I["Collation"]
                )
                : "",
            ' <input type="submit" value="Save">
';
    }
    echo '
';
    if (support("columns")) {
        echo '<div class="scrollable">
<table cellspacing="0" id="edit-fields" class="nowrap">
';
        edit_fields($I["fields"], $pb, "TABLE", $ed);
        echo '</table>
</div>
<p>
Auto Increment: <input type="number" name="Auto_increment" size="6" value="',
            h($I["Auto_increment"]),
            '">
',
            checkbox(
                "defaults",
                1,
                $_POST ? $_POST["defaults"] : adminer_setting("defaults"),
                'Default values',
                "columnShow(this.checked, 5)",
                "jsonly"
            ),
            support("comment")
                ? checkbox(
                        "comments",
                        1,
                        $_POST
                            ? $_POST["comments"]
                            : adminer_setting("comments"),
                        'Comment',
                        "editingCommentsClick(this, true);",
                        "jsonly"
                    ) .
                    ' <input name="Comment" value="' .
                    h($I["Comment"]) .
                    '" data-maxlength="' .
                    (min_version(5.5) ? 2048 : 60) .
                    '">'
                : '',
            '<p>
<input type="submit" value="Save">
';
    }
    echo '
';
    if ($a != "") {
        echo '<input type="submit" name="drop" value="Drop">',
            confirm(sprintf('Drop %s?', $a));
    }
    if (support("partitioning")) {
        $Tf = preg_match('~RANGE|LIST~', $I["partition_by"]);
        print_fieldset("partition", 'Partition by', $I["partition_by"]);
        echo '<p>
',
            "<select name='partition_by'>" .
                optionlist(["" => ""] + $Sf, $I["partition_by"]) .
                "</select>" .
                on_help(
                    "getTarget(event).value.replace(/./, 'PARTITION BY \$&')",
                    1
                ) .
                script("qsl('select').onchange = partitionByChange;"),
            '(<input name="partition" value="',
            h($I["partition"]),
            '">)
Partitions: <input type="number" name="partitions" class="size',
            $Tf || !$I["partition_by"] ? " hidden" : "",
            '" value="',
            h($I["partitions"]),
            '">
<table cellspacing="0" id="partition-table"',
            $Tf ? "" : " class='hidden'",
            '>
<thead><tr><th>Partition name<th>Values</thead>
';
        foreach ($I["partition_names"] as $y => $X) {
            echo '<tr>',
                '<td><input name="partition_names[]" value="' .
                    h($X) .
                    '" autocapitalize="off">',
                $y == count($I["partition_names"]) - 1
                    ? script("qsl('input').oninput = partitionNameChange;")
                    : '',
                '<td><input name="partition_values[]" value="' .
                    h($I["partition_values"][$y]) .
                    '">';
        }
        echo '</table>
</div></fieldset>
';
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
',
        script(
            "qs('#form')['defaults'].onclick();" .
                (support("comment")
                    ? " editingCommentsClick(qs('#form')['comments']);"
                    : "")
        );
} elseif (isset($_GET["indexes"])) {
    $a = $_GET["indexes"];
    $Jd = ["PRIMARY", "UNIQUE", "INDEX"];
    $R = table_status($a, true);
    if (
        preg_match(
            '~MyISAM|M?aria' .
                (min_version(5.6, '10.0.5') ? '|InnoDB' : '') .
                '~i',
            $R["Engine"]
        )
    ) {
        $Jd[] = "FULLTEXT";
    }
    if (
        preg_match(
            '~MyISAM|M?aria' .
                (min_version(5.7, '10.2.2') ? '|InnoDB' : '') .
                '~i',
            $R["Engine"]
        )
    ) {
        $Jd[] = "SPATIAL";
    }
    $w = indexes($a);
    $kg = [];
    if ($x == "mongo") {
        $kg = $w["_id_"];
        unset($Jd[0]);
        unset($w["_id_"]);
    }
    $I = $_POST;
    if ($_POST && !$n && !$_POST["add"] && !$_POST["drop_col"]) {
        $c = [];
        foreach ($I["indexes"] as $v) {
            $B = $v["name"];
            if (in_array($v["type"], $Jd)) {
                $f = [];
                $ue = [];
                $Yb = [];
                $N = [];
                ksort($v["columns"]);
                foreach ($v["columns"] as $y => $e) {
                    if ($e != "") {
                        $te = $v["lengths"][$y];
                        $Xb = $v["descs"][$y];
                        $N[] =
                            idf_escape($e) .
                            ($te ? "(" . +$te . ")" : "") .
                            ($Xb ? " DESC" : "");
                        $f[] = $e;
                        $ue[] = $te ? $te : null;
                        $Yb[] = $Xb;
                    }
                }
                if ($f) {
                    $Dc = $w[$B];
                    if ($Dc) {
                        ksort($Dc["columns"]);
                        ksort($Dc["lengths"]);
                        ksort($Dc["descs"]);
                        if (
                            $v["type"] == $Dc["type"] &&
                            array_values($Dc["columns"]) === $f &&
                            (!$Dc["lengths"] ||
                                array_values($Dc["lengths"]) === $ue) &&
                            array_values($Dc["descs"]) === $Yb
                        ) {
                            unset($w[$B]);
                            continue;
                        }
                    }
                    $c[] = [$v["type"], $B, $N];
                }
            }
        }
        foreach ($w as $B => $Dc) {
            $c[] = [$Dc["type"], $B, "DROP"];
        }
        if (!$c) {
            redirect(ME . "table=" . urlencode($a));
        }
        queries_redirect(
            ME . "table=" . urlencode($a),
            'Indexes have been altered.',
            alter_indexes($a, $c)
        );
    }
    page_header('Indexes', $n, ["table" => $a], h($a));
    $p = array_keys(fields($a));
    if ($_POST["add"]) {
        foreach ($I["indexes"] as $y => $v) {
            if ($v["columns"][count($v["columns"])] != "") {
                $I["indexes"][$y]["columns"][] = "";
            }
        }
        $v = end($I["indexes"]);
        if ($v["type"] || array_filter($v["columns"], 'strlen')) {
            $I["indexes"][] = ["columns" => [1 => ""]];
        }
    }
    if (!$I) {
        foreach ($w as $y => $v) {
            $w[$y]["name"] = $y;
            $w[$y]["columns"][] = "";
        }
        $w[] = ["columns" => [1 => ""]];
        $I["indexes"] = $w;
    }
    echo '
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
<thead><tr>
<th id="label-type">Index Type
<th><input type="submit" class="wayoff">Column (length)
<th id="label-name">Name
<th><noscript>',
        "<input type='image' class='icon' name='add[0]' src='" .
            h(
                preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.7.5"
            ) .
            "' alt='+' title='" .
            'Add next' .
            "'>",
        '</noscript>
</thead>
';
    if ($kg) {
        echo "<tr><td>PRIMARY<td>";
        foreach ($kg["columns"] as $y => $e) {
            echo select_input(" disabled", $p, $e),
                "<label><input disabled type='checkbox'>" .
                    'descending' .
                    "</label> ";
        }
        echo "<td><td>\n";
    }
    $ce = 1;
    foreach ($I["indexes"] as $v) {
        if (!$_POST["drop_col"] || $ce != key($_POST["drop_col"])) {
            echo "<tr><td>" .
            html_select(
                "indexes[$ce][type]",
                [-1 => ""] + $Jd,
                $v["type"],
                $ce == count($I["indexes"]) ? "indexesAddRow.call(this);" : 1,
                "label-type"
            ),
                "<td>";
            ksort($v["columns"]);
            $s = 1;
            foreach ($v["columns"] as $y => $e) {
                echo "<span>" .
                select_input(
                    " name='indexes[$ce][columns][$s]' title='" .
                        'Column' .
                        "'",
                    $p ? array_combine($p, $p) : $p,
                    $e,
                    "partial(" .
                        ($s == count($v["columns"])
                            ? "indexesAddColumn"
                            : "indexesChangeColumn") .
                        ", '" .
                        js_escape($x == "sql" ? "" : $_GET["indexes"] . "_") .
                        "')"
                ),
                    $x == "sql" || $x == "mssql"
                        ? "<input type='number' name='indexes[$ce][lengths][$s]' class='size' value='" .
                            h($v["lengths"][$y]) .
                            "' title='" .
                            'Length' .
                            "'>"
                        : "",
                    support("descidx")
                        ? checkbox(
                            "indexes[$ce][descs][$s]",
                            1,
                            $v["descs"][$y],
                            'descending'
                        )
                        : "",
                    " </span>";
                $s++;
            }
            echo "<td><input name='indexes[$ce][name]' value='" .
            h($v["name"]) .
            "' autocapitalize='off' aria-labelledby='label-name'>\n",
                "<td><input type='image' class='icon' name='drop_col[$ce]' src='" .
                    h(
                        preg_replace("~\\?.*~", "", ME) .
                            "?file=cross.gif&version=4.7.5"
                    ) .
                    "' alt='x' title='" .
                    'Remove' .
                    "'>" .
                    script(
                        "qsl('input').onclick = partial(editingRemoveRow, 'indexes\$1[type]');"
                    );
        }
        $ce++;
    }
    echo '</table>
</div>
<p>
<input type="submit" value="Save">
<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["database"])) {
    $I = $_POST;
    if ($_POST && !$n && !isset($_POST["add_x"])) {
        $B = trim($I["name"]);
        if ($_POST["drop"]) {
            $_GET["db"] = "";
            queries_redirect(
                remove_from_uri("db|database"),
                'Database has been dropped.',
                drop_databases([DB])
            );
        } elseif (DB !== $B) {
            if (DB != "") {
                $_GET["db"] = $B;
                queries_redirect(
                    preg_replace('~\bdb=[^&]*&~', '', ME) .
                        "db=" .
                        urlencode($B),
                    'Database has been renamed.',
                    rename_database($B, $I["collation"])
                );
            } else {
                $k = explode("\n", str_replace("\r", "", $B));
                $Jh = true;
                $ne = "";
                foreach ($k as $l) {
                    if (count($k) == 1 || $l != "") {
                        if (!create_database($l, $I["collation"])) {
                            $Jh = false;
                        }
                        $ne = $l;
                    }
                }
                restart_session();
                set_session("dbs", null);
                queries_redirect(
                    ME . "db=" . urlencode($ne),
                    'Database has been created.',
                    $Jh
                );
            }
        } else {
            if (!$I["collation"]) {
                redirect(substr(ME, 0, -1));
            }
            query_redirect(
                "ALTER DATABASE " .
                    idf_escape($B) .
                    (preg_match('~^[a-z0-9_]+$~i', $I["collation"])
                        ? " COLLATE $I[collation]"
                        : ""),
                substr(ME, 0, -1),
                'Database has been altered.'
            );
        }
    }
    page_header(DB != "" ? 'Alter database' : 'Create database', $n, [], h(DB));
    $pb = collations();
    $B = DB;
    if ($_POST) {
        $B = $I["name"];
    } elseif (DB != "") {
        $I["collation"] = db_collation(DB, $pb);
    } elseif ($x == "sql") {
        foreach (get_vals("SHOW GRANTS") as $ld) {
            if (
                preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~', $ld, $A) &&
                $A[1]
            ) {
                $B = stripcslashes(idf_unescape("`$A[2]`"));
                break;
            }
        }
    }
    echo '
<form action="" method="post">
<p>
',
        ($_POST["add_x"] || strpos($B, "\n")
            ? '<textarea id="name" name="name" rows="10" cols="40">' .
                h($B) .
                '</textarea><br>'
            : '<input name="name" id="name" value="' .
                h($B) .
                '" data-maxlength="64" autocapitalize="off">') .
            "\n" .
            ($pb
                ? html_select(
                        "collation",
                        ["" => "(" . 'collation' . ")"] + $pb,
                        $I["collation"]
                    ) .
                    doc_link([
                        'sql' => "charset-charsets.html",
                        'mariadb' => "supported-character-sets-and-collations/",
                        'mssql' => "ms187963.aspx",
                    ])
                : ""),
        script("focus(qs('#name'));"),
        '<input type="submit" value="Save">
';
    if (DB != "") {
        echo "<input type='submit' name='drop' value='" .
            'Drop' .
            "'>" .
            confirm(sprintf('Drop %s?', DB)) .
            "\n";
    } elseif (!$_POST["add_x"] && $_GET["db"] == "") {
        echo "<input type='image' class='icon' name='add' src='" .
            h(
                preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.7.5"
            ) .
            "' alt='+' title='" .
            'Add next' .
            "'>\n";
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["scheme"])) {
    $I = $_POST;
    if ($_POST && !$n) {
        $_ = preg_replace('~ns=[^&]*&~', '', ME) . "ns=";
        if ($_POST["drop"]) {
            query_redirect(
                "DROP SCHEMA " . idf_escape($_GET["ns"]),
                $_,
                'Schema has been dropped.'
            );
        } else {
            $B = trim($I["name"]);
            $_ .= urlencode($B);
            if ($_GET["ns"] == "") {
                query_redirect(
                    "CREATE SCHEMA " . idf_escape($B),
                    $_,
                    'Schema has been created.'
                );
            } elseif ($_GET["ns"] != $B) {
                query_redirect(
                    "ALTER SCHEMA " .
                        idf_escape($_GET["ns"]) .
                        " RENAME TO " .
                        idf_escape($B),
                    $_,
                    'Schema has been altered.'
                );
            } else {
                redirect($_);
            }
        }
    }
    page_header($_GET["ns"] != "" ? 'Alter schema' : 'Create schema', $n);
    if (!$I) {
        $I["name"] = $_GET["ns"];
    }
    echo '
<form action="" method="post">
<p><input name="name" id="name" value="',
        h($I["name"]),
        '" autocapitalize="off">
',
        script("focus(qs('#name'));"),
        '<input type="submit" value="Save">
';
    if ($_GET["ns"] != "") {
        echo "<input type='submit' name='drop' value='" .
            'Drop' .
            "'>" .
            confirm(sprintf('Drop %s?', $_GET["ns"])) .
            "\n";
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["call"])) {
    $da = $_GET["name"] ? $_GET["name"] : $_GET["call"];
    page_header('Call' . ": " . h($da), $n);
    $Ug = routine(
        $_GET["call"],
        isset($_GET["callf"]) ? "FUNCTION" : "PROCEDURE"
    );
    $Hd = [];
    $Jf = [];
    foreach ($Ug["fields"] as $s => $o) {
        if (substr($o["inout"], -3) == "OUT") {
            $Jf[$s] =
                "@" .
                idf_escape($o["field"]) .
                " AS " .
                idf_escape($o["field"]);
        }
        if (!$o["inout"] || substr($o["inout"], 0, 2) == "IN") {
            $Hd[] = $s;
        }
    }
    if (!$n && $_POST) {
        $ab = [];
        foreach ($Ug["fields"] as $y => $o) {
            if (in_array($y, $Hd)) {
                $X = process_input($o);
                if ($X === false) {
                    $X = "''";
                }
                if (isset($Jf[$y])) {
                    $g->query("SET @" . idf_escape($o["field"]) . " = $X");
                }
            }
            $ab[] = isset($Jf[$y]) ? "@" . idf_escape($o["field"]) : $X;
        }
        $F =
            (isset($_GET["callf"]) ? "SELECT" : "CALL") .
            " " .
            table($da) .
            "(" .
            implode(", ", $ab) .
            ")";
        $Dh = microtime(true);
        $G = $g->multi_query($F);
        $za = $g->affected_rows;
        echo $b->selectQuery($F, $Dh, !$G);
        if (!$G) {
            echo "<p class='error'>" . error() . "\n";
        } else {
            $h = connect();
            if (is_object($h)) {
                $h->select_db(DB);
            }
            do {
                $G = $g->store_result();
                if (is_object($G)) {
                    select($G, $h);
                } else {
                    echo "<p class='message'>" .
                        lang(
                            [
                                'Routine has been called, %d row affected.',
                                'Routine has been called, %d rows affected.',
                            ],
                            $za
                        ) .
                        "\n";
                }
            } while ($g->next_result());
            if ($Jf) {
                select($g->query("SELECT " . implode(", ", $Jf)));
            }
        }
    }
    echo '
<form action="" method="post">
';
    if ($Hd) {
        echo "<table cellspacing='0' class='layout'>\n";
        foreach ($Hd as $y) {
            $o = $Ug["fields"][$y];
            $B = $o["field"];
            echo "<tr><th>" . $b->fieldName($o);
            $Y = $_POST["fields"][$B];
            if ($Y != "") {
                if ($o["type"] == "enum") {
                    $Y = +$Y;
                }
                if ($o["type"] == "set") {
                    $Y = array_sum($Y);
                }
            }
            input($o, $Y, (string) $_POST["function"][$B]);
            echo "\n";
        }
        echo "</table>\n";
    }
    echo '<p>
<input type="submit" value="Call">
<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["foreign"])) {
    $a = $_GET["foreign"];
    $B = $_GET["name"];
    $I = $_POST;
    if (
        $_POST &&
        !$n &&
        !$_POST["add"] &&
        !$_POST["change"] &&
        !$_POST["change-js"]
    ) {
        $Le = $_POST["drop"]
            ? 'Foreign key has been dropped.'
            : ($B != ""
                ? 'Foreign key has been altered.'
                : 'Foreign key has been created.');
        $xe = ME . "table=" . urlencode($a);
        if (!$_POST["drop"]) {
            $I["source"] = array_filter($I["source"], 'strlen');
            ksort($I["source"]);
            $Xh = [];
            foreach ($I["source"] as $y => $X) {
                $Xh[$y] = $I["target"][$y];
            }
            $I["target"] = $Xh;
        }
        if ($x == "sqlite") {
            queries_redirect(
                $xe,
                $Le,
                recreate_table(
                    $a,
                    $a,
                    [],
                    [],
                    [
                        " $B" => $_POST["drop"]
                            ? ""
                            : " " . format_foreign_key($I),
                    ]
                )
            );
        } else {
            $c = "ALTER TABLE " . table($a);
            $fc =
                "\nDROP " .
                ($x == "sql" ? "FOREIGN KEY " : "CONSTRAINT ") .
                idf_escape($B);
            if ($_POST["drop"]) {
                query_redirect($c . $fc, $xe, $Le);
            } else {
                query_redirect(
                    $c .
                        ($B != "" ? "$fc," : "") .
                        "\nADD" .
                        format_foreign_key($I),
                    $xe,
                    $Le
                );
                $n =
                    'Source and target columns must have the same data type, there must be an index on the target columns and referenced data must exist.' .
                    "<br>$n";
            }
        }
    }
    page_header('Foreign key', $n, ["table" => $a], h($a));
    if ($_POST) {
        ksort($I["source"]);
        if ($_POST["add"]) {
            $I["source"][] = "";
        } elseif ($_POST["change"] || $_POST["change-js"]) {
            $I["target"] = [];
        }
    } elseif ($B != "") {
        $ed = foreign_keys($a);
        $I = $ed[$B];
        $I["source"][] = "";
    } else {
        $I["table"] = $a;
        $I["source"] = [""];
    }
    echo '
<form action="" method="post">
';
    $wh = array_keys(fields($a));
    if ($I["db"] != "") {
        $g->select_db($I["db"]);
    }
    if ($I["ns"] != "") {
        set_schema($I["ns"]);
    }
    $Dg = array_keys(array_filter(table_status('', true), 'fk_support'));
    $Xh =
        $a === $I["table"]
            ? $wh
            : array_keys(
                fields(in_array($I["table"], $Dg) ? $I["table"] : reset($Dg))
            );
    $rf = "this.form['change-js'].value = '1'; this.form.submit();";
    echo "<p>" .
        'Target table' .
        ": " .
        html_select("table", $Dg, $I["table"], $rf) .
        "\n";
    if ($x == "pgsql") {
        echo 'Schema' .
            ": " .
            html_select(
                "ns",
                $b->schemas(),
                $I["ns"] != "" ? $I["ns"] : $_GET["ns"],
                $rf
            );
    } elseif ($x != "sqlite") {
        $Rb = [];
        foreach ($b->databases() as $l) {
            if (!information_schema($l)) {
                $Rb[] = $l;
            }
        }
        echo 'DB' .
            ": " .
            html_select(
                "db",
                $Rb,
                $I["db"] != "" ? $I["db"] : $_GET["db"],
                $rf
            );
    }
    echo '<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="Change"></noscript>
<table cellspacing="0">
<thead><tr><th id="label-source">Source<th id="label-target">Target</thead>
';
    $ce = 0;
    foreach ($I["source"] as $y => $X) {
        echo "<tr>",
            "<td>" .
                html_select(
                    "source[" . +$y . "]",
                    [-1 => ""] + $wh,
                    $X,
                    $ce == count($I["source"]) - 1
                        ? "foreignAddRow.call(this);"
                        : 1,
                    "label-source"
                ),
            "<td>" .
                html_select(
                    "target[" . +$y . "]",
                    $Xh,
                    $I["target"][$y],
                    1,
                    "label-target"
                );
        $ce++;
    }
    echo '</table>
<p>
ON DELETE: ',
        html_select(
            "on_delete",
            [-1 => ""] + explode("|", $qf),
            $I["on_delete"]
        ),
        ' ON UPDATE: ',
        html_select(
            "on_update",
            [-1 => ""] + explode("|", $qf),
            $I["on_update"]
        ),
        doc_link([
            'sql' => "innodb-foreign-key-constraints.html",
            'mariadb' => "foreign-keys/",
            'pgsql' => "sql-createtable.html#SQL-CREATETABLE-REFERENCES",
            'mssql' => "ms174979.aspx",
            'oracle' =>
                "https://docs.oracle.com/cd/B19306_01/server.102/b14200/clauses002.htm#sthref2903",
        ]),
        '<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add column"></noscript>
';
    if ($B != "") {
        echo '<input type="submit" name="drop" value="Drop">',
            confirm(sprintf('Drop %s?', $B));
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["view"])) {
    $a = $_GET["view"];
    $I = $_POST;
    $Gf = "VIEW";
    if ($x == "pgsql" && $a != "") {
        $O = table_status($a);
        $Gf = strtoupper($O["Engine"]);
    }
    if ($_POST && !$n) {
        $B = trim($I["name"]);
        $Ga = " AS\n$I[select]";
        $xe = ME . "table=" . urlencode($B);
        $Le = 'View has been altered.';
        $T = $_POST["materialized"] ? "MATERIALIZED VIEW" : "VIEW";
        if (
            !$_POST["drop"] &&
            $a == $B &&
            $x != "sqlite" &&
            $T == "VIEW" &&
            $Gf == "VIEW"
        ) {
            query_redirect(
                ($x == "mssql" ? "ALTER" : "CREATE OR REPLACE") .
                    " VIEW " .
                    table($B) .
                    $Ga,
                $xe,
                $Le
            );
        } else {
            $Zh = $B . "_adminer_" . uniqid();
            drop_create(
                "DROP $Gf " . table($a),
                "CREATE $T " . table($B) . $Ga,
                "DROP $T " . table($B),
                "CREATE $T " . table($Zh) . $Ga,
                "DROP $T " . table($Zh),
                $_POST["drop"] ? substr(ME, 0, -1) : $xe,
                'View has been dropped.',
                $Le,
                'View has been created.',
                $a,
                $B
            );
        }
    }
    if (!$_POST && $a != "") {
        $I = view($a);
        $I["name"] = $a;
        $I["materialized"] = $Gf != "VIEW";
        if (!$n) {
            $n = error();
        }
    }
    page_header(
        $a != "" ? 'Alter view' : 'Create view',
        $n,
        ["table" => $a],
        h($a)
    );
    echo '
<form action="" method="post">
<p>Name: <input name="name" value="',
        h($I["name"]),
        '" data-maxlength="64" autocapitalize="off">
',
        support("materializedview")
            ? " " .
                checkbox(
                    "materialized",
                    1,
                    $I["materialized"],
                    'Materialized view'
                )
            : "",
        '<p>';
    textarea("select", $I["select"]);
    echo '<p>
<input type="submit" value="Save">
';
    if ($a != "") {
        echo '<input type="submit" name="drop" value="Drop">',
            confirm(sprintf('Drop %s?', $a));
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["event"])) {
    $aa = $_GET["event"];
    $Ud = [
        "YEAR",
        "QUARTER",
        "MONTH",
        "DAY",
        "HOUR",
        "MINUTE",
        "WEEK",
        "SECOND",
        "YEAR_MONTH",
        "DAY_HOUR",
        "DAY_MINUTE",
        "DAY_SECOND",
        "HOUR_MINUTE",
        "HOUR_SECOND",
        "MINUTE_SECOND",
    ];
    $Fh = [
        "ENABLED" => "ENABLE",
        "DISABLED" => "DISABLE",
        "SLAVESIDE_DISABLED" => "DISABLE ON SLAVE",
    ];
    $I = $_POST;
    if ($_POST && !$n) {
        if ($_POST["drop"]) {
            query_redirect(
                "DROP EVENT " . idf_escape($aa),
                substr(ME, 0, -1),
                'Event has been dropped.'
            );
        } elseif (
            in_array($I["INTERVAL_FIELD"], $Ud) &&
            isset($Fh[$I["STATUS"]])
        ) {
            $Zg =
                "\nON SCHEDULE " .
                ($I["INTERVAL_VALUE"]
                    ? "EVERY " .
                        q($I["INTERVAL_VALUE"]) .
                        " $I[INTERVAL_FIELD]" .
                        ($I["STARTS"] ? " STARTS " . q($I["STARTS"]) : "") .
                        ($I["ENDS"] ? " ENDS " . q($I["ENDS"]) : "")
                    : "AT " . q($I["STARTS"])) .
                " ON COMPLETION" .
                ($I["ON_COMPLETION"] ? "" : " NOT") .
                " PRESERVE";
            queries_redirect(
                substr(ME, 0, -1),
                $aa != ""
                    ? 'Event has been altered.'
                    : 'Event has been created.',
                queries(
                    ($aa != ""
                        ? "ALTER EVENT " .
                            idf_escape($aa) .
                            $Zg .
                            ($aa != $I["EVENT_NAME"]
                                ? "\nRENAME TO " . idf_escape($I["EVENT_NAME"])
                                : "")
                        : "CREATE EVENT " .
                            idf_escape($I["EVENT_NAME"]) .
                            $Zg) .
                        "\n" .
                        $Fh[$I["STATUS"]] .
                        " COMMENT " .
                        q($I["EVENT_COMMENT"]) .
                        rtrim(" DO\n$I[EVENT_DEFINITION]", ";") .
                        ";"
                )
            );
        }
    }
    page_header($aa != "" ? 'Alter event' . ": " . h($aa) : 'Create event', $n);
    if (!$I && $aa != "") {
        $J = get_rows(
            "SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = " .
                q(DB) .
                " AND EVENT_NAME = " .
                q($aa)
        );
        $I = reset($J);
    }
    echo '
<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>Name<td><input name="EVENT_NAME" value="',
        h($I["EVENT_NAME"]),
        '" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">Start<td><input name="STARTS" value="',
        h("$I[EXECUTE_AT]$I[STARTS]"),
        '">
<tr><th title="datetime">End<td><input name="ENDS" value="',
        h($I["ENDS"]),
        '">
<tr><th>Every<td><input type="number" name="INTERVAL_VALUE" value="',
        h($I["INTERVAL_VALUE"]),
        '" class="size"> ',
        html_select("INTERVAL_FIELD", $Ud, $I["INTERVAL_FIELD"]),
        '<tr><th>Status<td>',
        html_select("STATUS", $Fh, $I["STATUS"]),
        '<tr><th>Comment<td><input name="EVENT_COMMENT" value="',
        h($I["EVENT_COMMENT"]),
        '" data-maxlength="64">
<tr><th><td>',
        checkbox(
            "ON_COMPLETION",
            "PRESERVE",
            $I["ON_COMPLETION"] == "PRESERVE",
            'On completion preserve'
        ),
        '</table>
<p>';
    textarea("EVENT_DEFINITION", $I["EVENT_DEFINITION"]);
    echo '<p>
<input type="submit" value="Save">
';
    if ($aa != "") {
        echo '<input type="submit" name="drop" value="Drop">',
            confirm(sprintf('Drop %s?', $aa));
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["procedure"])) {
    $da = $_GET["name"] ? $_GET["name"] : $_GET["procedure"];
    $Ug = isset($_GET["function"]) ? "FUNCTION" : "PROCEDURE";
    $I = $_POST;
    $I["fields"] = (array) $I["fields"];
    if ($_POST && !process_fields($I["fields"]) && !$n) {
        $Df = routine($_GET["procedure"], $Ug);
        $Zh = "$I[name]_adminer_" . uniqid();
        drop_create(
            "DROP $Ug " . routine_id($da, $Df),
            create_routine($Ug, $I),
            "DROP $Ug " . routine_id($I["name"], $I),
            create_routine($Ug, ["name" => $Zh] + $I),
            "DROP $Ug " . routine_id($Zh, $I),
            substr(ME, 0, -1),
            'Routine has been dropped.',
            'Routine has been altered.',
            'Routine has been created.',
            $da,
            $I["name"]
        );
    }
    page_header(
        $da != ""
            ? (isset($_GET["function"])
                    ? 'Alter function'
                    : 'Alter procedure') .
                ": " .
                h($da)
            : (isset($_GET["function"])
                ? 'Create function'
                : 'Create procedure'),
        $n
    );
    if (!$_POST && $da != "") {
        $I = routine($_GET["procedure"], $Ug);
        $I["name"] = $da;
    }
    $pb = get_vals("SHOW CHARACTER SET");
    sort($pb);
    $Vg = routine_languages();
    echo '
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',
        h($I["name"]),
        '" data-maxlength="64" autocapitalize="off">
',
        $Vg
            ? 'Language' .
                ": " .
                html_select("language", $Vg, $I["language"]) .
                "\n"
            : "",
        '<input type="submit" value="Save">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
';
    edit_fields($I["fields"], $pb, $Ug);
    if (isset($_GET["function"])) {
        echo "<tr><td>" . 'Return type';
        edit_type(
            "returns",
            $I["returns"],
            $pb,
            [],
            $x == "pgsql" ? ["void", "trigger"] : []
        );
    }
    echo '</table>
</div>
<p>';
    textarea("definition", $I["definition"]);
    echo '<p>
<input type="submit" value="Save">
';
    if ($da != "") {
        echo '<input type="submit" name="drop" value="Drop">',
            confirm(sprintf('Drop %s?', $da));
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["sequence"])) {
    $fa = $_GET["sequence"];
    $I = $_POST;
    if ($_POST && !$n) {
        $_ = substr(ME, 0, -1);
        $B = trim($I["name"]);
        if ($_POST["drop"]) {
            query_redirect(
                "DROP SEQUENCE " . idf_escape($fa),
                $_,
                'Sequence has been dropped.'
            );
        } elseif ($fa == "") {
            query_redirect(
                "CREATE SEQUENCE " . idf_escape($B),
                $_,
                'Sequence has been created.'
            );
        } elseif ($fa != $B) {
            query_redirect(
                "ALTER SEQUENCE " .
                    idf_escape($fa) .
                    " RENAME TO " .
                    idf_escape($B),
                $_,
                'Sequence has been altered.'
            );
        } else {
            redirect($_);
        }
    }
    page_header(
        $fa != "" ? 'Alter sequence' . ": " . h($fa) : 'Create sequence',
        $n
    );
    if (!$I) {
        $I["name"] = $fa;
    }
    echo '
<form action="" method="post">
<p><input name="name" value="',
        h($I["name"]),
        '" autocapitalize="off">
<input type="submit" value="Save">
';
    if ($fa != "") {
        echo "<input type='submit' name='drop' value='" .
            'Drop' .
            "'>" .
            confirm(sprintf('Drop %s?', $fa)) .
            "\n";
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["type"])) {
    $ga = $_GET["type"];
    $I = $_POST;
    if ($_POST && !$n) {
        $_ = substr(ME, 0, -1);
        if ($_POST["drop"]) {
            query_redirect(
                "DROP TYPE " . idf_escape($ga),
                $_,
                'Type has been dropped.'
            );
        } else {
            query_redirect(
                "CREATE TYPE " . idf_escape(trim($I["name"])) . " $I[as]",
                $_,
                'Type has been created.'
            );
        }
    }
    page_header($ga != "" ? 'Alter type' . ": " . h($ga) : 'Create type', $n);
    if (!$I) {
        $I["as"] = "AS ";
    }
    echo '
<form action="" method="post">
<p>
';
    if ($ga != "") {
        echo "<input type='submit' name='drop' value='" .
            'Drop' .
            "'>" .
            confirm(sprintf('Drop %s?', $ga)) .
            "\n";
    } else {
        echo "<input name='name' value='" .
            h($I['name']) .
            "' autocapitalize='off'>\n";
        textarea("as", $I["as"]);
        echo "<p><input type='submit' value='" . 'Save' . "'>\n";
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["trigger"])) {
    $a = $_GET["trigger"];
    $B = $_GET["name"];
    $zi = trigger_options();
    $I = (array) trigger($B) + ["Trigger" => $a . "_bi"];
    if ($_POST) {
        if (
            !$n &&
            in_array($_POST["Timing"], $zi["Timing"]) &&
            in_array($_POST["Event"], $zi["Event"]) &&
            in_array($_POST["Type"], $zi["Type"])
        ) {
            $pf = " ON " . table($a);
            $fc = "DROP TRIGGER " . idf_escape($B) . ($x == "pgsql" ? $pf : "");
            $xe = ME . "table=" . urlencode($a);
            if ($_POST["drop"]) {
                query_redirect($fc, $xe, 'Trigger has been dropped.');
            } else {
                if ($B != "") {
                    queries($fc);
                }
                queries_redirect(
                    $xe,
                    $B != ""
                        ? 'Trigger has been altered.'
                        : 'Trigger has been created.',
                    queries(create_trigger($pf, $_POST))
                );
                if ($B != "") {
                    queries(
                        create_trigger($pf, $I + ["Type" => reset($zi["Type"])])
                    );
                }
            }
        }
        $I = $_POST;
    }
    page_header(
        $B != "" ? 'Alter trigger' . ": " . h($B) : 'Create trigger',
        $n,
        ["table" => $a]
    );
    echo '
<form action="" method="post" id="form">
<table cellspacing="0" class="layout">
<tr><th>Time<td>',
        html_select(
            "Timing",
            $zi["Timing"],
            $I["Timing"],
            "triggerChange(/^" .
                preg_quote($a, "/") .
                "_[ba][iud]$/, '" .
                js_escape($a) .
                "', this.form);"
        ),
        '<tr><th>Event<td>',
        html_select(
            "Event",
            $zi["Event"],
            $I["Event"],
            "this.form['Timing'].onchange();"
        ),
        in_array("UPDATE OF", $zi["Event"])
            ? " <input name='Of' value='" . h($I["Of"]) . "' class='hidden'>"
            : "",
        '<tr><th>Type<td>',
        html_select("Type", $zi["Type"], $I["Type"]),
        '</table>
<p>Name: <input name="Trigger" value="',
        h($I["Trigger"]),
        '" data-maxlength="64" autocapitalize="off">
',
        script("qs('#form')['Timing'].onchange();"),
        '<p>';
    textarea("Statement", $I["Statement"]);
    echo '<p>
<input type="submit" value="Save">
';
    if ($B != "") {
        echo '<input type="submit" name="drop" value="Drop">',
            confirm(sprintf('Drop %s?', $B));
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["user"])) {
    $ha = $_GET["user"];
    $pg = ["" => ["All privileges" => ""]];
    foreach (get_rows("SHOW PRIVILEGES") as $I) {
        foreach (
            explode(",", $I["Privilege"] == "Grant option" ? "" : $I["Context"])
            as $Bb
        ) {
            $pg[$Bb][$I["Privilege"]] = $I["Comment"];
        }
    }
    $pg["Server Admin"] += $pg["File access on server"];
    $pg["Databases"]["Create routine"] = $pg["Procedures"]["Create routine"];
    unset($pg["Procedures"]["Create routine"]);
    $pg["Columns"] = [];
    foreach (["Select", "Insert", "Update", "References"] as $X) {
        $pg["Columns"][$X] = $pg["Tables"][$X];
    }
    unset($pg["Server Admin"]["Usage"]);
    foreach ($pg["Tables"] as $y => $X) {
        unset($pg["Databases"][$y]);
    }
    $Ye = [];
    if ($_POST) {
        foreach ($_POST["objects"] as $y => $X) {
            $Ye[$X] = (array) $Ye[$X] + (array) $_POST["grants"][$y];
        }
    }
    $md = [];
    $nf = "";
    if (
        isset($_GET["host"]) &&
        ($G = $g->query("SHOW GRANTS FOR " . q($ha) . "@" . q($_GET["host"])))
    ) {
        while ($I = $G->fetch_row()) {
            if (
                preg_match('~GRANT (.*) ON (.*) TO ~', $I[0], $A) &&
                preg_match_all(
                    '~ *([^(,]*[^ ,(])( *\([^)]+\))?~',
                    $A[1],
                    $De,
                    PREG_SET_ORDER
                )
            ) {
                foreach ($De as $X) {
                    if ($X[1] != "USAGE") {
                        $md["$A[2]$X[2]"][$X[1]] = true;
                    }
                    if (preg_match('~ WITH GRANT OPTION~', $I[0])) {
                        $md["$A[2]$X[2]"]["GRANT OPTION"] = true;
                    }
                }
            }
            if (preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~", $I[0], $A)) {
                $nf = $A[1];
            }
        }
    }
    if ($_POST && !$n) {
        $of = isset($_GET["host"]) ? q($ha) . "@" . q($_GET["host"]) : "''";
        if ($_POST["drop"]) {
            query_redirect(
                "DROP USER $of",
                ME . "privileges=",
                'User has been dropped.'
            );
        } else {
            $af = q($_POST["user"]) . "@" . q($_POST["host"]);
            $Xf = $_POST["pass"];
            if ($Xf != '' && !$_POST["hashed"] && !min_version(8)) {
                $Xf = $g->result("SELECT PASSWORD(" . q($Xf) . ")");
                $n = !$Xf;
            }
            $Gb = false;
            if (!$n) {
                if ($of != $af) {
                    $Gb = queries(
                        (min_version(5)
                            ? "CREATE USER"
                            : "GRANT USAGE ON *.* TO") .
                            " $af IDENTIFIED BY " .
                            (min_version(8) ? "" : "PASSWORD ") .
                            q($Xf)
                    );
                    $n = !$Gb;
                } elseif ($Xf != $nf) {
                    queries("SET PASSWORD FOR $af = " . q($Xf));
                }
            }
            if (!$n) {
                $Rg = [];
                foreach ($Ye as $if => $ld) {
                    if (isset($_GET["grant"])) {
                        $ld = array_filter($ld);
                    }
                    $ld = array_keys($ld);
                    if (isset($_GET["grant"])) {
                        $Rg = array_diff(
                            array_keys(array_filter($Ye[$if], 'strlen')),
                            $ld
                        );
                    } elseif ($of == $af) {
                        $lf = array_keys((array) $md[$if]);
                        $Rg = array_diff($lf, $ld);
                        $ld = array_diff($ld, $lf);
                        unset($md[$if]);
                    }
                    if (
                        preg_match('~^(.+)\s*(\(.*\))?$~U', $if, $A) &&
                        (!grant("REVOKE", $Rg, $A[2], " ON $A[1] FROM $af") ||
                            !grant("GRANT", $ld, $A[2], " ON $A[1] TO $af"))
                    ) {
                        $n = true;
                        break;
                    }
                }
            }
            if (!$n && isset($_GET["host"])) {
                if ($of != $af) {
                    queries("DROP USER $of");
                } elseif (!isset($_GET["grant"])) {
                    foreach ($md as $if => $Rg) {
                        if (preg_match('~^(.+)(\(.*\))?$~U', $if, $A)) {
                            grant(
                                "REVOKE",
                                array_keys($Rg),
                                $A[2],
                                " ON $A[1] FROM $af"
                            );
                        }
                    }
                }
            }
            queries_redirect(
                ME . "privileges=",
                isset($_GET["host"])
                    ? 'User has been altered.'
                    : 'User has been created.',
                !$n
            );
            if ($Gb) {
                $g->query("DROP USER $af");
            }
        }
    }
    page_header(
        isset($_GET["host"])
            ? 'Username' . ": " . h("$ha@$_GET[host]")
            : 'Create user',
        $n,
        ["privileges" => ['', 'Privileges']]
    );
    if ($_POST) {
        $I = $_POST;
        $md = $Ye;
    } else {
        $I = $_GET + [
            "host" => $g->result(
                "SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"
            ),
        ];
        $I["pass"] = $nf;
        if ($nf != "") {
            $I["hashed"] = true;
        }
        $md[
            (DB == "" || $md ? "" : idf_escape(addcslashes(DB, "%_\\"))) . ".*"
        ] = [];
    }
    echo '<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>Server<td><input name="host" data-maxlength="60" value="',
        h($I["host"]),
        '" autocapitalize="off">
<tr><th>Username<td><input name="user" data-maxlength="80" value="',
        h($I["user"]),
        '" autocapitalize="off">
<tr><th>Password<td><input name="pass" id="pass" value="',
        h($I["pass"]),
        '" autocomplete="new-password">
';
    if (!$I["hashed"]) {
        echo script("typePassword(qs('#pass'));");
    }
    echo min_version(8)
    ? ""
    : checkbox(
        "hashed",
        1,
        $I["hashed"],
        'Hashed',
        "typePassword(this.form['pass'], this.checked);"
    ),
        '</table>

';
    echo "<table cellspacing='0'>\n",
        "<thead><tr><th colspan='2'>" .
            'Privileges' .
            doc_link(['sql' => "grant.html#priv_level"]);
    $s = 0;
    foreach ($md as $if => $ld) {
        echo '<th>' .
            ($if != "*.*"
                ? "<input name='objects[$s]' value='" .
                    h($if) .
                    "' size='10' autocapitalize='off'>"
                : "<input type='hidden' name='objects[$s]' value='*.*' size='10'>*.*");
        $s++;
    }
    echo "</thead>\n";
    foreach (
        [
            "" => "",
            "Server Admin" => 'Server',
            "Databases" => 'Database',
            "Tables" => 'Table',
            "Columns" => 'Column',
            "Procedures" => 'Routine',
        ]
        as $Bb => $Xb
    ) {
        foreach ((array) $pg[$Bb] as $og => $ub) {
            echo "<tr" .
                odd() .
                "><td" .
                ($Xb ? ">$Xb<td" : " colspan='2'") .
                ' lang="en" title="' .
                h($ub) .
                '">' .
                h($og);
            $s = 0;
            foreach ($md as $if => $ld) {
                $B = "'grants[$s][" . h(strtoupper($og)) . "]'";
                $Y = $ld[strtoupper($og)];
                if (
                    $Bb == "Server Admin" &&
                    $if != (isset($md["*.*"]) ? "*.*" : ".*")
                ) {
                    echo "<td>";
                } elseif (isset($_GET["grant"])) {
                    echo "<td><select name=$B><option><option value='1'" .
                        ($Y ? " selected" : "") .
                        ">" .
                        'Grant' .
                        "<option value='0'" .
                        ($Y == "0" ? " selected" : "") .
                        ">" .
                        'Revoke' .
                        "</select>";
                } else {
                    echo "<td align='center'><label class='block'>",
                        "<input type='checkbox' name=$B value='1'" .
                            ($Y ? " checked" : "") .
                            ($og == "All privileges"
                                ? " id='grants-$s-all'>"
                                : ">" .
                                    ($og == "Grant option"
                                        ? ""
                                        : script(
                                            "qsl('input').onclick = function () { if (this.checked) formUncheck('grants-$s-all'); };"
                                        ))),
                        "</label>";
                }
                $s++;
            }
        }
    }
    echo "</table>\n",
        '<p>
<input type="submit" value="Save">
';
    if (isset($_GET["host"])) {
        echo '<input type="submit" name="drop" value="Drop">',
            confirm(sprintf('Drop %s?', "$ha@$_GET[host]"));
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
';
} elseif (isset($_GET["processlist"])) {
    if (support("kill") && $_POST && !$n) {
        $je = 0;
        foreach ((array) $_POST["kill"] as $X) {
            if (kill_process($X)) {
                $je++;
            }
        }
        queries_redirect(
            ME . "processlist=",
            lang(
                [
                    '%d process has been killed.',
                    '%d processes have been killed.',
                ],
                $je
            ),
            $je || !$_POST["kill"]
        );
    }
    page_header('Process list', $n);
    echo '
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap checkable">
',
        script(
            "mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"
        );
    $s = -1;
    foreach (process_list() as $s => $I) {
        if (!$s) {
            echo "<thead><tr lang='en'>" . (support("kill") ? "<th>" : "");
            foreach ($I as $y => $X) {
                echo "<th>$y" .
                    doc_link([
                        'sql' =>
                            "show-processlist.html#processlist_" .
                            strtolower($y),
                        'pgsql' =>
                            "monitoring-stats.html#PG-STAT-ACTIVITY-VIEW",
                        'oracle' => "REFRN30223",
                    ]);
            }
            echo "</thead>\n";
        }
        echo "<tr" .
            odd() .
            ">" .
            (support("kill")
                ? "<td>" . checkbox("kill[]", $I[$x == "sql" ? "Id" : "pid"], 0)
                : "");
        foreach ($I as $y => $X) {
            echo "<td>" .
                (($x == "sql" &&
                    $y == "Info" &&
                    preg_match("~Query|Killed~", $I["Command"]) &&
                    $X != "") ||
                ($x == "pgsql" && $y == "current_query" && $X != "<IDLE>") ||
                ($x == "oracle" && $y == "sql_text" && $X != "")
                    ? "<code class='jush-$x'>" .
                        shorten_utf8($X, 100, "</code>") .
                        ' <a href="' .
                        h(
                            ME .
                                ($I["db"] != ""
                                    ? "db=" . urlencode($I["db"]) . "&"
                                    : "") .
                                "sql=" .
                                urlencode($X)
                        ) .
                        '">' .
                        'Clone' .
                        '</a>'
                    : h($X));
        }
        echo "\n";
    }
    echo '</table>
</div>
<p>
';
    if (support("kill")) {
        echo $s + 1 . "/" . sprintf('%d in total', max_connections()),
            "<p><input type='submit' value='" . 'Kill' . "'>\n";
    }
    echo '<input type="hidden" name="token" value="',
        $oi,
        '">
</form>
',
        script("tableCheck();");
} elseif (isset($_GET["select"])) {
    $a = $_GET["select"];
    $R = table_status1($a);
    $w = indexes($a);
    $p = fields($a);
    $ed = column_foreign_keys($a);
    $kf = $R["Oid"];
    parse_str($_COOKIE["adminer_import"], $ya);
    $Sg = [];
    $f = [];
    $di = null;
    foreach ($p as $y => $o) {
        $B = $b->fieldName($o);
        if (isset($o["privileges"]["select"]) && $B != "") {
            $f[$y] = html_entity_decode(strip_tags($B), ENT_QUOTES);
            if (is_shortable($o)) {
                $di = $b->selectLengthProcess();
            }
        }
        $Sg += $o["privileges"];
    }
    list($K, $nd) = $b->selectColumnsProcess($f, $w);
    $Yd = count($nd) < count($K);
    $Z = $b->selectSearchProcess($p, $w);
    $_f = $b->selectOrderProcess($p, $w);
    $z = $b->selectLimitProcess();
    if ($_GET["val"] && is_ajax()) {
        header("Content-Type: text/plain; charset=utf-8");
        foreach ($_GET["val"] as $Fi => $I) {
            $Ga = convert_field($p[key($I)]);
            $K = [$Ga ? $Ga : idf_escape(key($I))];
            $Z[] = where_check($Fi, $p);
            $H = $m->select($a, $K, $Z, $K);
            if ($H) {
                echo reset($H->fetch_row());
            }
        }
        exit();
    }
    $kg = $Hi = null;
    foreach ($w as $v) {
        if ($v["type"] == "PRIMARY") {
            $kg = array_flip($v["columns"]);
            $Hi = $K ? $kg : [];
            foreach ($Hi as $y => $X) {
                if (in_array(idf_escape($y), $K)) {
                    unset($Hi[$y]);
                }
            }
            break;
        }
    }
    if ($kf && !$kg) {
        $kg = $Hi = [$kf => 0];
        $w[] = ["type" => "PRIMARY", "columns" => [$kf]];
    }
    if ($_POST && !$n) {
        $jj = $Z;
        if (!$_POST["all"] && is_array($_POST["check"])) {
            $gb = [];
            foreach ($_POST["check"] as $db) {
                $gb[] = where_check($db, $p);
            }
            $jj[] = "((" . implode(") OR (", $gb) . "))";
        }
        $jj = $jj ? "\nWHERE " . implode(" AND ", $jj) : "";
        if ($_POST["export"]) {
            cookie(
                "adminer_import",
                "output=" .
                    urlencode($_POST["output"]) .
                    "&format=" .
                    urlencode($_POST["format"])
            );
            dump_headers($a);
            $b->dumpTable($a, "");
            $jd =
                ($K ? implode(", ", $K) : "*") .
                convert_fields($f, $p, $K) .
                "\nFROM " .
                table($a);
            $pd =
                ($nd && $Yd ? "\nGROUP BY " . implode(", ", $nd) : "") .
                ($_f ? "\nORDER BY " . implode(", ", $_f) : "");
            if (!is_array($_POST["check"]) || $kg) {
                $F = "SELECT $jd$jj$pd";
            } else {
                $Di = [];
                foreach ($_POST["check"] as $X) {
                    $Di[] =
                        "(SELECT" .
                        limit(
                            $jd,
                            "\nWHERE " .
                                ($Z ? implode(" AND ", $Z) . " AND " : "") .
                                where_check($X, $p) .
                                $pd,
                            1
                        ) .
                        ")";
                }
                $F = implode(" UNION ALL ", $Di);
            }
            $b->dumpData($a, "table", $F);
            exit();
        }
        if (!$b->selectEmailProcess($Z, $ed)) {
            if ($_POST["save"] || $_POST["delete"]) {
                $G = true;
                $za = 0;
                $N = [];
                if (!$_POST["delete"]) {
                    foreach ($f as $B => $X) {
                        $X = process_input($p[$B]);
                        if ($X !== null && ($_POST["clone"] || $X !== false)) {
                            $N[idf_escape($B)] =
                                $X !== false ? $X : idf_escape($B);
                        }
                    }
                }
                if ($_POST["delete"] || $N) {
                    if ($_POST["clone"]) {
                        $F =
                            "INTO " .
                            table($a) .
                            " (" .
                            implode(", ", array_keys($N)) .
                            ")\nSELECT " .
                            implode(", ", $N) .
                            "\nFROM " .
                            table($a);
                    }
                    if (
                        $_POST["all"] ||
                        ($kg && is_array($_POST["check"])) ||
                        $Yd
                    ) {
                        $G = $_POST["delete"]
                            ? $m->delete($a, $jj)
                            : ($_POST["clone"]
                                ? queries("INSERT $F$jj")
                                : $m->update($a, $N, $jj));
                        $za = $g->affected_rows;
                    } else {
                        foreach ((array) $_POST["check"] as $X) {
                            $fj =
                                "\nWHERE " .
                                ($Z ? implode(" AND ", $Z) . " AND " : "") .
                                where_check($X, $p);
                            $G = $_POST["delete"]
                                ? $m->delete($a, $fj, 1)
                                : ($_POST["clone"]
                                    ? queries("INSERT" . limit1($a, $F, $fj))
                                    : $m->update($a, $N, $fj, 1));
                            if (!$G) {
                                break;
                            }
                            $za += $g->affected_rows;
                        }
                    }
                }
                $Le = lang(
                    [
                        '%d item has been affected.',
                        '%d items have been affected.',
                    ],
                    $za
                );
                if ($_POST["clone"] && $G && $za == 1) {
                    $oe = last_id();
                    if ($oe) {
                        $Le = sprintf('Item%s has been inserted.', " $oe");
                    }
                }
                queries_redirect(
                    remove_from_uri(
                        $_POST["all"] && $_POST["delete"] ? "page" : ""
                    ),
                    $Le,
                    $G
                );
                if (!$_POST["delete"]) {
                    edit_form(
                        $a,
                        $p,
                        (array) $_POST["fields"],
                        !$_POST["clone"]
                    );
                    page_footer();
                    exit();
                }
            } elseif (!$_POST["import"]) {
                if (!$_POST["val"]) {
                    $n = 'Ctrl+click on a value to modify it.';
                } else {
                    $G = true;
                    $za = 0;
                    foreach ($_POST["val"] as $Fi => $I) {
                        $N = [];
                        foreach ($I as $y => $X) {
                            $y = bracket_escape($y, 1);
                            $N[idf_escape($y)] =
                                preg_match('~char|text~', $p[$y]["type"]) ||
                                $X != ""
                                    ? $b->processInput($p[$y], $X)
                                    : "NULL";
                        }
                        $G = $m->update(
                            $a,
                            $N,
                            " WHERE " .
                                ($Z ? implode(" AND ", $Z) . " AND " : "") .
                                where_check($Fi, $p),
                            !$Yd && !$kg,
                            " "
                        );
                        if (!$G) {
                            break;
                        }
                        $za += $g->affected_rows;
                    }
                    queries_redirect(
                        remove_from_uri(),
                        lang(
                            [
                                '%d item has been affected.',
                                '%d items have been affected.',
                            ],
                            $za
                        ),
                        $G
                    );
                }
            } elseif (!is_string($Tc = get_file("csv_file", true))) {
                $n = upload_error($Tc);
            } elseif (!preg_match('~~u', $Tc)) {
                $n = 'File must be in UTF-8 encoding.';
            } else {
                cookie(
                    "adminer_import",
                    "output=" .
                        urlencode($ya["output"]) .
                        "&format=" .
                        urlencode($_POST["separator"])
                );
                $G = true;
                $rb = array_keys($p);
                preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~', $Tc, $De);
                $za = count($De[0]);
                $m->begin();
                $L =
                    $_POST["separator"] == "csv"
                        ? ","
                        : ($_POST["separator"] == "tsv"
                            ? "\t"
                            : ";");
                $J = [];
                foreach ($De[0] as $y => $X) {
                    preg_match_all(
                        "~((?>\"[^\"]*\")+|[^$L]*)$L~",
                        $X . $L,
                        $Ee
                    );
                    if (!$y && !array_diff($Ee[1], $rb)) {
                        $rb = $Ee[1];
                        $za--;
                    } else {
                        $N = [];
                        foreach ($Ee[1] as $s => $nb) {
                            $N[idf_escape($rb[$s])] =
                                $nb == "" && $p[$rb[$s]]["null"]
                                    ? "NULL"
                                    : q(
                                        str_replace(
                                            '""',
                                            '"',
                                            preg_replace('~^"|"$~', '', $nb)
                                        )
                                    );
                        }
                        $J[] = $N;
                    }
                }
                $G = !$J || $m->insertUpdate($a, $J, $kg);
                if ($G) {
                    $G = $m->commit();
                }
                queries_redirect(
                    remove_from_uri("page"),
                    lang(
                        [
                            '%d row has been imported.',
                            '%d rows have been imported.',
                        ],
                        $za
                    ),
                    $G
                );
                $m->rollback();
            }
        }
    }
    $Ph = $b->tableName($R);
    if (is_ajax()) {
        page_headers();
        ob_start();
    } else {
        page_header('Select' . ": $Ph", $n);
    }
    $N = null;
    if (isset($Sg["insert"]) || !support("table")) {
        $N = "";
        foreach ((array) $_GET["where"] as $X) {
            if (
                $ed[$X["col"]] &&
                count($ed[$X["col"]]) == 1 &&
                ($X["op"] == "=" ||
                    (!$X["op"] && !preg_match('~[_%]~', $X["val"])))
            ) {
                $N .=
                    "&set" .
                    urlencode("[" . bracket_escape($X["col"]) . "]") .
                    "=" .
                    urlencode($X["val"]);
            }
        }
    }
    $b->selectLinks($R, $N);
    if (!$f && support("table")) {
        echo "<p class='error'>" .
            'Unable to select the table' .
            ($p ? "." : ": " . error()) .
            "\n";
    } else {
        echo "<form action='' id='form'>\n", "<div style='display: none;'>";
        hidden_fields_get();
        echo DB != ""
            ? '<input type="hidden" name="db" value="' .
                h(DB) .
                '">' .
                (isset($_GET["ns"])
                    ? '<input type="hidden" name="ns" value="' .
                        h($_GET["ns"]) .
                        '">'
                    : "")
            : "";
        echo '<input type="hidden" name="select" value="' . h($a) . '">',
            "</div>\n";
        $b->selectColumnsPrint($K, $f);
        $b->selectSearchPrint($Z, $f, $w);
        $b->selectOrderPrint($_f, $f, $w);
        $b->selectLimitPrint($z);
        $b->selectLengthPrint($di);
        $b->selectActionPrint($w);
        echo "</form>\n";
        $D = $_GET["page"];
        if ($D == "last") {
            $hd = $g->result(count_rows($a, $Z, $Yd, $nd));
            $D = floor(max(0, $hd - 1) / $z);
        }
        $eh = $K;
        $od = $nd;
        if (!$eh) {
            $eh[] = "*";
            $Cb = convert_fields($f, $p, $K);
            if ($Cb) {
                $eh[] = substr($Cb, 2);
            }
        }
        foreach ($K as $y => $X) {
            $o = $p[idf_unescape($X)];
            if ($o && ($Ga = convert_field($o))) {
                $eh[$y] = "$Ga AS $X";
            }
        }
        if (!$Yd && $Hi) {
            foreach ($Hi as $y => $X) {
                $eh[] = idf_escape($y);
                if ($od) {
                    $od[] = idf_escape($y);
                }
            }
        }
        $G = $m->select($a, $eh, $Z, $od, $_f, $z, $D, true);
        if (!$G) {
            echo "<p class='error'>" . error() . "\n";
        } else {
            if ($x == "mssql" && $D) {
                $G->seek($z * $D);
            }
            $sc = [];
            echo "<form action='' method='post' enctype='multipart/form-data'>\n";
            $J = [];
            while ($I = $G->fetch_assoc()) {
                if ($D && $x == "oracle") {
                    unset($I["RNUM"]);
                }
                $J[] = $I;
            }
            if (
                $_GET["page"] != "last" &&
                $z != "" &&
                $nd &&
                $Yd &&
                $x == "sql"
            ) {
                $hd = $g->result(" SELECT FOUND_ROWS()");
            }
            if (!$J) {
                echo "<p class='message'>" . 'No rows.' . "\n";
            } else {
                $Qa = $b->backwardKeys($a, $Ph);
                echo "<div class='scrollable'>",
                    "<table id='table' cellspacing='0' class='nowrap checkable'>",
                    script(
                        "mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"
                    ),
                    "<thead><tr>" .
                        (!$nd && $K
                            ? ""
                            : "<td><input type='checkbox' id='all-page' class='jsonly'>" .
                                script(
                                    "qs('#all-page').onclick = partial(formCheck, /check/);",
                                    ""
                                ) .
                                " <a href='" .
                                h(
                                    $_GET["modify"]
                                        ? remove_from_uri("modify")
                                        : $_SERVER["REQUEST_URI"] . "&modify=1"
                                ) .
                                "'>" .
                                'Modify' .
                                "</a>");
                $Xe = [];
                $kd = [];
                reset($K);
                $zg = 1;
                foreach ($J[0] as $y => $X) {
                    if (!isset($Hi[$y])) {
                        $X = $_GET["columns"][key($K)];
                        $o = $p[$K ? ($X ? $X["col"] : current($K)) : $y];
                        $B = $o
                            ? $b->fieldName($o, $zg)
                            : ($X["fun"]
                                ? "*"
                                : $y);
                        if ($B != "") {
                            $zg++;
                            $Xe[$y] = $B;
                            $e = idf_escape($y);
                            $Bd =
                                remove_from_uri('(order|desc)[^=]*|page') .
                                '&order%5B0%5D=' .
                                urlencode($y);
                            $Xb = "&desc%5B0%5D=1";
                            echo "<th>" .
                            script(
                                "mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});",
                                ""
                            ),
                                '<a href="' .
                                    h(
                                        $Bd .
                                            ($_f[0] == $e ||
                                            $_f[0] == $y ||
                                            (!$_f && $Yd && $nd[0] == $e)
                                                ? $Xb
                                                : '')
                                    ) .
                                    '">';
                            echo apply_sql_function($X["fun"], $B) . "</a>";
                            echo "<span class='column hidden'>",
                                "<a href='" .
                                    h($Bd . $Xb) .
                                    "' title='" .
                                    'descending' .
                                    "' class='text'> ↓</a>";
                            if (!$X["fun"]) {
                                echo '<a href="#fieldset-search" title="' .
                                'Search' .
                                '" class="text jsonly"> =</a>',
                                    script(
                                        "qsl('a').onclick = partial(selectSearch, '" .
                                            js_escape($y) .
                                            "');"
                                    );
                            }
                            echo "</span>";
                        }
                        $kd[$y] = $X["fun"];
                        next($K);
                    }
                }
                $ue = [];
                if ($_GET["modify"]) {
                    foreach ($J as $I) {
                        foreach ($I as $y => $X) {
                            $ue[$y] = max(
                                $ue[$y],
                                min(40, strlen(utf8_decode($X)))
                            );
                        }
                    }
                }
                echo ($Qa ? "<th>" . 'Relations' : "") . "</thead>\n";
                if (is_ajax()) {
                    if ($z % 2 == 1 && $D % 2 == 1) {
                        odd();
                    }
                    ob_end_clean();
                }
                foreach ($b->rowDescriptions($J, $ed) as $We => $I) {
                    $Ei = unique_array($J[$We], $w);
                    if (!$Ei) {
                        $Ei = [];
                        foreach ($J[$We] as $y => $X) {
                            if (
                                !preg_match(
                                    '~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~',
                                    $y
                                )
                            ) {
                                $Ei[$y] = $X;
                            }
                        }
                    }
                    $Fi = "";
                    foreach ($Ei as $y => $X) {
                        if (
                            ($x == "sql" || $x == "pgsql") &&
                            preg_match(
                                '~char|text|enum|set~',
                                $p[$y]["type"]
                            ) &&
                            strlen($X) > 64
                        ) {
                            $y = strpos($y, '(') ? $y : idf_escape($y);
                            $y =
                                "MD5(" .
                                ($x != 'sql' ||
                                preg_match("~^utf8~", $p[$y]["collation"])
                                    ? $y
                                    : "CONVERT($y USING " . charset($g) . ")") .
                                ")";
                            $X = md5($X);
                        }
                        $Fi .=
                            "&" .
                            ($X !== null
                                ? urlencode(
                                        "where[" . bracket_escape($y) . "]"
                                    ) .
                                    "=" .
                                    urlencode($X)
                                : "null%5B%5D=" . urlencode($y));
                    }
                    echo "<tr" .
                        odd() .
                        ">" .
                        (!$nd && $K
                            ? ""
                            : "<td>" .
                                checkbox(
                                    "check[]",
                                    substr($Fi, 1),
                                    in_array(
                                        substr($Fi, 1),
                                        (array) $_POST["check"]
                                    )
                                ) .
                                ($Yd || information_schema(DB)
                                    ? ""
                                    : " <a href='" .
                                        h(ME . "edit=" . urlencode($a) . $Fi) .
                                        "' class='edit'>" .
                                        'edit' .
                                        "</a>"));
                    foreach ($I as $y => $X) {
                        if (isset($Xe[$y])) {
                            $o = $p[$y];
                            $X = $m->value($X, $o);
                            if (
                                $X != "" &&
                                (!isset($sc[$y]) || $sc[$y] != "")
                            ) {
                                $sc[$y] = is_mail($X) ? $Xe[$y] : "";
                            }
                            $_ = "";
                            if (
                                preg_match(
                                    '~blob|bytea|raw|file~',
                                    $o["type"]
                                ) &&
                                $X != ""
                            ) {
                                $_ =
                                    ME .
                                    'download=' .
                                    urlencode($a) .
                                    '&field=' .
                                    urlencode($y) .
                                    $Fi;
                            }
                            if (!$_ && $X !== null) {
                                foreach ((array) $ed[$y] as $q) {
                                    if (
                                        count($ed[$y]) == 1 ||
                                        end($q["source"]) == $y
                                    ) {
                                        $_ = "";
                                        foreach ($q["source"] as $s => $wh) {
                                            $_ .= where_link(
                                                $s,
                                                $q["target"][$s],
                                                $J[$We][$wh]
                                            );
                                        }
                                        $_ =
                                            ($q["db"] != ""
                                                ? preg_replace(
                                                    '~([?&]db=)[^&]+~',
                                                    '\1' . urlencode($q["db"]),
                                                    ME
                                                )
                                                : ME) .
                                            'select=' .
                                            urlencode($q["table"]) .
                                            $_;
                                        if ($q["ns"]) {
                                            $_ = preg_replace(
                                                '~([?&]ns=)[^&]+~',
                                                '\1' . urlencode($q["ns"]),
                                                $_
                                            );
                                        }
                                        if (count($q["source"]) == 1) {
                                            break;
                                        }
                                    }
                                }
                            }
                            if ($y == "COUNT(*)") {
                                $_ = ME . "select=" . urlencode($a);
                                $s = 0;
                                foreach ((array) $_GET["where"] as $W) {
                                    if (!array_key_exists($W["col"], $Ei)) {
                                        $_ .= where_link(
                                            $s++,
                                            $W["col"],
                                            $W["val"],
                                            $W["op"]
                                        );
                                    }
                                }
                                foreach ($Ei as $de => $W) {
                                    $_ .= where_link($s++, $de, $W);
                                }
                            }
                            $X = select_value($X, $_, $o, $di);
                            $t = h("val[$Fi][" . bracket_escape($y) . "]");
                            $Y = $_POST["val"][$Fi][bracket_escape($y)];
                            $nc =
                                !is_array($I[$y]) &&
                                is_utf8($X) &&
                                $J[$We][$y] == $I[$y] &&
                                !$kd[$y];
                            $ci = preg_match('~text|lob~', $o["type"]);
                            echo "<td id='$t'";
                            if (($_GET["modify"] && $nc) || $Y !== null) {
                                $sd = h($Y !== null ? $Y : $I[$y]);
                                echo ">" .
                                    ($ci
                                        ? "<textarea name='$t' cols='30' rows='" .
                                            (substr_count($I[$y], "\n") + 1) .
                                            "'>$sd</textarea>"
                                        : "<input name='$t' value='$sd' size='$ue[$y]'>");
                            } else {
                                $ze = strpos($X, "<i>…</i>");
                                echo " data-text='" .
                                    ($ze ? 2 : ($ci ? 1 : 0)) .
                                    "'" .
                                    ($nc
                                        ? ""
                                        : " data-warning='" .
                                            h(
                                                'Use edit link to modify this value.'
                                            ) .
                                            "'") .
                                    ">$X</td>";
                            }
                        }
                    }
                    if ($Qa) {
                        echo "<td>";
                    }
                    $b->backwardKeysPrint($Qa, $J[$We]);
                    echo "</tr>\n";
                }
                if (is_ajax()) {
                    exit();
                }
                echo "</table>\n", "</div>\n";
            }
            if (!is_ajax()) {
                if ($J || $D) {
                    $Bc = true;
                    if ($_GET["page"] != "last") {
                        if ($z == "" || (count($J) < $z && ($J || !$D))) {
                            $hd = ($D ? $D * $z : 0) + count($J);
                        } elseif ($x != "sql" || !$Yd) {
                            $hd = $Yd ? false : found_rows($R, $Z);
                            if ($hd < max(1e4, 2 * ($D + 1) * $z)) {
                                $hd = reset(
                                    slow_query(count_rows($a, $Z, $Yd, $nd))
                                );
                            } else {
                                $Bc = false;
                            }
                        }
                    }
                    $Mf = $z != "" && ($hd === false || $hd > $z || $D);
                    if ($Mf) {
                        echo ($hd === false ? count($J) + 1 : $hd - $D * $z) >
                    $z
                        ? '<p><a href="' .
                            h(remove_from_uri("page") . "&page=" . ($D + 1)) .
                            '" class="loadmore">' .
                            'Load more data' .
                            '</a>' .
                            script(
                                "qsl('a').onclick = partial(selectLoadMore, " .
                                    +$z .
                                    ", '" .
                                    'Loading' .
                                    "…');",
                                ""
                            )
                        : '',
                            "\n";
                    }
                }
                echo "<div class='footer'><div>\n";
                if ($J || $D) {
                    if ($Mf) {
                        $Ge =
                            $hd === false
                                ? $D + (count($J) >= $z ? 2 : 1)
                                : floor(($hd - 1) / $z);
                        echo "<fieldset>";
                        if ($x != "simpledb") {
                            echo "<legend><a href='" .
                            h(remove_from_uri("page")) .
                            "'>" .
                            'Page' .
                            "</a></legend>",
                                script(
                                    "qsl('a').onclick = function () { pageClick(this.href, +prompt('" .
                                        'Page' .
                                        "', '" .
                                        ($D + 1) .
                                        "')); return false; };"
                                ),
                                pagination(0, $D) . ($D > 5 ? " …" : "");
                            for (
                                $s = max(1, $D - 4);
                                $s < min($Ge, $D + 5);
                                $s++
                            ) {
                                echo pagination($s, $D);
                            }
                            if ($Ge > 0) {
                                echo $D + 5 < $Ge ? " …" : "",
                                    $Bc && $hd !== false
                                        ? pagination($Ge, $D)
                                        : " <a href='" .
                                            h(
                                                remove_from_uri("page") .
                                                    "&page=last"
                                            ) .
                                            "' title='~$Ge'>" .
                                            'last' .
                                            "</a>";
                            }
                        } else {
                            echo "<legend>" . 'Page' . "</legend>",
                                pagination(0, $D) . ($D > 1 ? " …" : ""),
                                $D ? pagination($D, $D) : "",
                                $Ge > $D
                                    ? pagination($D + 1, $D) .
                                        ($Ge > $D + 1 ? " …" : "")
                                    : "";
                        }
                        echo "</fieldset>\n";
                    }
                    echo "<fieldset>",
                        "<legend>" . 'Whole result' . "</legend>";
                    $cc = ($Bc ? "" : "~ ") . $hd;
                    echo checkbox(
                    "all",
                    1,
                    0,
                    $hd !== false
                        ? ($Bc ? "" : "~ ") . lang(['%d row', '%d rows'], $hd)
                        : "",
                    "var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$cc' : checked); selectCount('selected2', this.checked || !checked ? '$cc' : checked);"
                ) . "\n",
                        "</fieldset>\n";
                    if ($b->selectCommandPrint()) {
                        echo '<fieldset',
                            $_GET["modify"] ? '' : ' class="jsonly"',
                            '><legend>Modify</legend><div>
<input type="submit" value="Save"',
                            $_GET["modify"]
                                ? ''
                                : ' title="' .
                                    'Ctrl+click on a value to modify it.' .
                                    '"',
                            '>
</div></fieldset>
<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete">',
                            confirm(),
                            '</div></fieldset>
';
                    }
                    $fd = $b->dumpFormat();
                    foreach ((array) $_GET["columns"] as $e) {
                        if ($e["fun"]) {
                            unset($fd['sql']);
                            break;
                        }
                    }
                    if ($fd) {
                        print_fieldset(
                            "export",
                            'Export' . " <span id='selected2'></span>"
                        );
                        $Kf = $b->dumpOutput();
                        echo $Kf
                        ? html_select("output", $Kf, $ya["output"]) . " "
                        : "",
                            html_select("format", $fd, $ya["format"]),
                            " <input type='submit' name='export' value='" .
                                'Export' .
                                "'>\n",
                            "</div></fieldset>\n";
                    }
                    $b->selectEmailPrint(array_filter($sc, 'strlen'), $f);
                }
                echo "</div></div>\n";
                if ($b->selectImportPrint()) {
                    echo "<div>",
                        "<a href='#import'>" . 'Import' . "</a>",
                        script(
                            "qsl('a').onclick = partial(toggle, 'import');",
                            ""
                        ),
                        "<span id='import' class='hidden'>: ",
                        "<input type='file' name='csv_file'> ",
                        html_select(
                            "separator",
                            ["csv" => "CSV,", "csv;" => "CSV;", "tsv" => "TSV"],
                            $ya["format"],
                            1
                        );
                    echo " <input type='submit' name='import' value='" .
                    'Import' .
                    "'>",
                        "</span>",
                        "</div>";
                }
                echo "<input type='hidden' name='token' value='$oi'>\n",
                    "</form>\n",
                    !$nd && $K ? "" : script("tableCheck();");
            }
        }
    }
    if (is_ajax()) {
        ob_end_clean();
        exit();
    }
} elseif (isset($_GET["variables"])) {
    $O = isset($_GET["status"]);
    page_header($O ? 'Status' : 'Variables');
    $Wi = $O ? show_status() : show_variables();
    if (!$Wi) {
        echo "<p class='message'>" . 'No rows.' . "\n";
    } else {
        echo "<table cellspacing='0'>\n";
        foreach ($Wi as $y => $X) {
            echo "<tr>",
                "<th><code class='jush-" .
                    $x .
                    ($O ? "status" : "set") .
                    "'>" .
                    h($y) .
                    "</code>",
                "<td>" . h($X);
        }
        echo "</table>\n";
    }
} elseif (isset($_GET["script"])) {
    header("Content-Type: text/javascript; charset=utf-8");
    if ($_GET["script"] == "db") {
        $Mh = ["Data_length" => 0, "Index_length" => 0, "Data_free" => 0];
        foreach (table_status() as $B => $R) {
            json_row("Comment-$B", h($R["Comment"]));
            if (!is_view($R)) {
                foreach (["Engine", "Collation"] as $y) {
                    json_row("$y-$B", h($R[$y]));
                }
                foreach (
                    $Mh + ["Auto_increment" => 0, "Rows" => 0]
                    as $y => $X
                ) {
                    if ($R[$y] != "") {
                        $X = format_number($R[$y]);
                        json_row(
                            "$y-$B",
                            $y == "Rows" &&
                            $X &&
                            $R["Engine"] ==
                                ($zh == "pgsql" ? "table" : "InnoDB")
                                ? "~ $X"
                                : $X
                        );
                        if (isset($Mh[$y])) {
                            $Mh[$y] +=
                                $R["Engine"] != "InnoDB" || $y != "Data_free"
                                    ? $R[$y]
                                    : 0;
                        }
                    } elseif (array_key_exists($y, $R)) {
                        json_row("$y-$B");
                    }
                }
            }
        }
        foreach ($Mh as $y => $X) {
            json_row("sum-$y", format_number($X));
        }
        json_row("");
    } elseif ($_GET["script"] == "kill") {
        $g->query("KILL " . number($_POST["kill"]));
    } else {
        foreach (count_tables($b->databases()) as $l => $X) {
            json_row("tables-$l", $X);
            json_row("size-$l", db_size($l));
        }
        json_row("");
    }
    exit();
} else {
    $Vh = array_merge((array) $_POST["tables"], (array) $_POST["views"]);
    if ($Vh && !$n && !$_POST["search"]) {
        $G = true;
        $Le = "";
        if (
            $x == "sql" &&
            $_POST["tables"] &&
            count($_POST["tables"]) > 1 &&
            ($_POST["drop"] || $_POST["truncate"] || $_POST["copy"])
        ) {
            queries("SET foreign_key_checks = 0");
        }
        if ($_POST["truncate"]) {
            if ($_POST["tables"]) {
                $G = truncate_tables($_POST["tables"]);
            }
            $Le = 'Tables have been truncated.';
        } elseif ($_POST["move"]) {
            $G = move_tables(
                (array) $_POST["tables"],
                (array) $_POST["views"],
                $_POST["target"]
            );
            $Le = 'Tables have been moved.';
        } elseif ($_POST["copy"]) {
            $G = copy_tables(
                (array) $_POST["tables"],
                (array) $_POST["views"],
                $_POST["target"]
            );
            $Le = 'Tables have been copied.';
        } elseif ($_POST["drop"]) {
            if ($_POST["views"]) {
                $G = drop_views($_POST["views"]);
            }
            if ($G && $_POST["tables"]) {
                $G = drop_tables($_POST["tables"]);
            }
            $Le = 'Tables have been dropped.';
        } elseif ($x != "sql") {
            $G =
                $x == "sqlite"
                    ? queries("VACUUM")
                    : apply_queries(
                        "VACUUM" . ($_POST["optimize"] ? "" : " ANALYZE"),
                        $_POST["tables"]
                    );
            $Le = 'Tables have been optimized.';
        } elseif (!$_POST["tables"]) {
            $Le = 'No tables.';
        } elseif (
            $G = queries(
                ($_POST["optimize"]
                    ? "OPTIMIZE"
                    : ($_POST["check"]
                        ? "CHECK"
                        : ($_POST["repair"]
                            ? "REPAIR"
                            : "ANALYZE"))) .
                    " TABLE " .
                    implode(", ", array_map('idf_escape', $_POST["tables"]))
            )
        ) {
            while ($I = $G->fetch_assoc()) {
                $Le .=
                    "<b>" .
                    h($I["Table"]) .
                    "</b>: " .
                    h($I["Msg_text"]) .
                    "<br>";
            }
        }
        queries_redirect(substr(ME, 0, -1), $Le, $G);
    }
    page_header(
        $_GET["ns"] == ""
            ? 'Database' . ": " . h(DB)
            : 'Schema' . ": " . h($_GET["ns"]),
        $n,
        true
    );
    if ($b->homepage()) {
        if ($_GET["ns"] !== "") {
            echo "<h3 id='tables-views'>" . 'Tables and views' . "</h3>\n";
            $Uh = tables_list();
            if (!$Uh) {
                echo "<p class='message'>" . 'No tables.' . "\n";
            } else {
                echo "<form action='' method='post'>\n";
                if (support("table")) {
                    echo "<fieldset><legend>" .
                    'Search data in tables' .
                    " <span id='selected2'></span></legend><div>",
                        "<input type='search' name='query' value='" .
                            h($_POST["query"]) .
                            "'>",
                        script(
                            "qsl('input').onkeydown = partialArg(bodyKeydown, 'search');",
                            ""
                        ),
                        " <input type='submit' name='search' value='" .
                            'Search' .
                            "'>\n",
                        "</div></fieldset>\n";
                    if ($_POST["search"] && $_POST["query"] != "") {
                        $_GET["where"][0]["op"] = "LIKE %%";
                        search_tables();
                    }
                }
                echo "<div class='scrollable'>\n",
                    "<table cellspacing='0' class='nowrap checkable'>\n",
                    script(
                        "mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"
                    ),
                    '<thead><tr class="wrap">',
                    '<td><input id="check-all" type="checkbox" class="jsonly">' .
                        script(
                            "qs('#check-all').onclick = partial(formCheck, /^(tables|views)\[/);",
                            ""
                        ),
                    '<th>' . 'Table',
                    '<td>' .
                        'Engine' .
                        doc_link(['sql' => 'storage-engines.html']),
                    '<td>' .
                        'Collation' .
                        doc_link([
                            'sql' => 'charset-charsets.html',
                            'mariadb' =>
                                'supported-character-sets-and-collations/',
                        ]),
                    '<td>' .
                        'Data Length' .
                        doc_link([
                            'sql' => 'show-table-status.html',
                            'pgsql' =>
                                'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT',
                            'oracle' => 'REFRN20286',
                        ]),
                    '<td>' .
                        'Index Length' .
                        doc_link([
                            'sql' => 'show-table-status.html',
                            'pgsql' =>
                                'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT',
                        ]),
                    '<td>' .
                        'Data Free' .
                        doc_link(['sql' => 'show-table-status.html']),
                    '<td>' .
                        'Auto Increment' .
                        doc_link([
                            'sql' => 'example-auto-increment.html',
                            'mariadb' => 'auto_increment/',
                        ]),
                    '<td>' .
                        'Rows' .
                        doc_link([
                            'sql' => 'show-table-status.html',
                            'pgsql' => 'catalog-pg-class.html#CATALOG-PG-CLASS',
                            'oracle' => 'REFRN20286',
                        ]),
                    support("comment")
                        ? '<td>' .
                            'Comment' .
                            doc_link([
                                'sql' => 'show-table-status.html',
                                'pgsql' =>
                                    'functions-info.html#FUNCTIONS-INFO-COMMENT-TABLE',
                            ])
                        : '',
                    "</thead>\n";
                $S = 0;
                foreach ($Uh as $B => $T) {
                    $Zi = $T !== null && !preg_match('~table~i', $T);
                    $t = h("Table-" . $B);
                    echo '<tr' .
                    odd() .
                    '><td>' .
                    checkbox(
                        $Zi ? "views[]" : "tables[]",
                        $B,
                        in_array($B, $Vh, true),
                        "",
                        "",
                        "",
                        $t
                    ),
                        '<th>' .
                            (support("table") || support("indexes")
                                ? "<a href='" .
                                    h(ME) .
                                    "table=" .
                                    urlencode($B) .
                                    "' title='" .
                                    'Show structure' .
                                    "' id='$t'>" .
                                    h($B) .
                                    '</a>'
                                : h($B));
                    if ($Zi) {
                        echo '<td colspan="6"><a href="' .
                        h(ME) .
                        "view=" .
                        urlencode($B) .
                        '" title="' .
                        'Alter view' .
                        '">' .
                        (preg_match('~materialized~i', $T)
                            ? 'Materialized view'
                            : 'View') .
                        '</a>',
                            '<td align="right"><a href="' .
                                h(ME) .
                                "select=" .
                                urlencode($B) .
                                '" title="' .
                                'Select data' .
                                '">?</a>';
                    } else {
                        foreach (
                            [
                                "Engine" => [],
                                "Collation" => [],
                                "Data_length" => ["create", 'Alter table'],
                                "Index_length" => ["indexes", 'Alter indexes'],
                                "Data_free" => ["edit", 'New item'],
                                "Auto_increment" => [
                                    "auto_increment=1&create",
                                    'Alter table',
                                ],
                                "Rows" => ["select", 'Select data'],
                            ]
                            as $y => $_
                        ) {
                            $t = " id='$y-" . h($B) . "'";
                            echo $_
                                ? "<td align='right'>" .
                                    (support("table") ||
                                    $y == "Rows" ||
                                    (support("indexes") && $y != "Data_length")
                                        ? "<a href='" .
                                            h(ME . "$_[0]=") .
                                            urlencode($B) .
                                            "'$t title='$_[1]'>?</a>"
                                        : "<span$t>?</span>")
                                : "<td id='$y-" . h($B) . "'>";
                        }
                        $S++;
                    }
                    echo support("comment")
                        ? "<td id='Comment-" . h($B) . "'>"
                        : "";
                }
                echo "<tr><td><th>" . sprintf('%d in total', count($Uh)),
                    "<td>" .
                        h(
                            $x == "sql"
                                ? $g->result("SELECT @@storage_engine")
                                : ""
                        ),
                    "<td>" . h(db_collation(DB, collations()));
                foreach (["Data_length", "Index_length", "Data_free"] as $y) {
                    echo "<td align='right' id='sum-$y'>";
                }
                echo "</table>\n", "</div>\n";
                if (!information_schema(DB)) {
                    echo "<div class='footer'><div>\n";
                    $Ti =
                        "<input type='submit' value='" .
                        'Vacuum' .
                        "'> " .
                        on_help("'VACUUM'");
                    $wf =
                        "<input type='submit' name='optimize' value='" .
                        'Optimize' .
                        "'> " .
                        on_help(
                            $x == "sql"
                                ? "'OPTIMIZE TABLE'"
                                : "'VACUUM OPTIMIZE'"
                        );
                    echo "<fieldset><legend>" .
                        'Selected' .
                        " <span id='selected'></span></legend><div>" .
                        ($x == "sqlite"
                            ? $Ti
                            : ($x == "pgsql"
                                ? $Ti . $wf
                                : ($x == "sql"
                                    ? "<input type='submit' value='" .
                                        'Analyze' .
                                        "'> " .
                                        on_help("'ANALYZE TABLE'") .
                                        $wf .
                                        "<input type='submit' name='check' value='" .
                                        'Check' .
                                        "'> " .
                                        on_help("'CHECK TABLE'") .
                                        "<input type='submit' name='repair' value='" .
                                        'Repair' .
                                        "'> " .
                                        on_help("'REPAIR TABLE'")
                                    : ""))) .
                        "<input type='submit' name='truncate' value='" .
                        'Truncate' .
                        "'> " .
                        on_help(
                            $x == "sqlite"
                                ? "'DELETE'"
                                : "'TRUNCATE" .
                                    ($x == "pgsql" ? "'" : " TABLE'")
                        ) .
                        confirm() .
                        "<input type='submit' name='drop' value='" .
                        'Drop' .
                        "'>" .
                        on_help("'DROP TABLE'") .
                        confirm() .
                        "\n";
                    $k = support("scheme") ? $b->schemas() : $b->databases();
                    if (count($k) != 1 && $x != "sqlite") {
                        $l = isset($_POST["target"])
                            ? $_POST["target"]
                            : (support("scheme")
                                ? $_GET["ns"]
                                : DB);
                        echo "<p>" . 'Move to other database' . ": ",
                            $k
                                ? html_select("target", $k, $l)
                                : '<input name="target" value="' .
                                    h($l) .
                                    '" autocapitalize="off">',
                            " <input type='submit' name='move' value='" .
                                'Move' .
                                "'>",
                            support("copy")
                                ? " <input type='submit' name='copy' value='" .
                                    'Copy' .
                                    "'> " .
                                    checkbox(
                                        "overwrite",
                                        1,
                                        $_POST["overwrite"],
                                        'overwrite'
                                    )
                                : "",
                            "\n";
                    }
                    echo "<input type='hidden' name='all' value=''>";
                    echo script(
                    "qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^(tables|views)\[/));" .
                        (support("table")
                            ? " selectCount('selected2', formChecked(this, /^tables\[/) || $S);"
                            : "") .
                        " }"
                ),
                        "<input type='hidden' name='token' value='$oi'>\n",
                        "</div></fieldset>\n",
                        "</div></div>\n";
                }
                echo "</form>\n", script("tableCheck();");
            }
            echo '<p class="links"><a href="' .
            h(ME) .
            'create=">' .
            'Create table' .
            "</a>\n",
                support("view")
                    ? '<a href="' . h(ME) . 'view=">' . 'Create view' . "</a>\n"
                    : "";
            if (support("routine")) {
                echo "<h3 id='routines'>" . 'Routines' . "</h3>\n";
                $Wg = routines();
                if ($Wg) {
                    echo "<table cellspacing='0'>\n",
                        '<thead><tr><th>' .
                            'Name' .
                            '<td>' .
                            'Type' .
                            '<td>' .
                            'Return type' .
                            "<td></thead>\n";
                    odd('');
                    foreach ($Wg as $I) {
                        $B =
                            $I["SPECIFIC_NAME"] == $I["ROUTINE_NAME"]
                                ? ""
                                : "&name=" . urlencode($I["ROUTINE_NAME"]);
                        echo '<tr' . odd() . '>',
                            '<th><a href="' .
                                h(
                                    ME .
                                        ($I["ROUTINE_TYPE"] != "PROCEDURE"
                                            ? 'callf='
                                            : 'call=') .
                                        urlencode($I["SPECIFIC_NAME"]) .
                                        $B
                                ) .
                                '">' .
                                h($I["ROUTINE_NAME"]) .
                                '</a>',
                            '<td>' . h($I["ROUTINE_TYPE"]),
                            '<td>' . h($I["DTD_IDENTIFIER"]),
                            '<td><a href="' .
                                h(
                                    ME .
                                        ($I["ROUTINE_TYPE"] != "PROCEDURE"
                                            ? 'function='
                                            : 'procedure=') .
                                        urlencode($I["SPECIFIC_NAME"]) .
                                        $B
                                ) .
                                '">' .
                                'Alter' .
                                "</a>";
                    }
                    echo "</table>\n";
                }
                echo '<p class="links">' .
                    (support("procedure")
                        ? '<a href="' .
                            h(ME) .
                            'procedure=">' .
                            'Create procedure' .
                            '</a>'
                        : '') .
                    '<a href="' .
                    h(ME) .
                    'function=">' .
                    'Create function' .
                    "</a>\n";
            }
            if (support("sequence")) {
                echo "<h3 id='sequences'>" . 'Sequences' . "</h3>\n";
                $kh = get_vals(
                    "SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = current_schema() ORDER BY sequence_name"
                );
                if ($kh) {
                    echo "<table cellspacing='0'>\n",
                        "<thead><tr><th>" . 'Name' . "</thead>\n";
                    odd('');
                    foreach ($kh as $X) {
                        echo "<tr" .
                            odd() .
                            "><th><a href='" .
                            h(ME) .
                            "sequence=" .
                            urlencode($X) .
                            "'>" .
                            h($X) .
                            "</a>\n";
                    }
                    echo "</table>\n";
                }
                echo "<p class='links'><a href='" .
                    h(ME) .
                    "sequence='>" .
                    'Create sequence' .
                    "</a>\n";
            }
            if (support("type")) {
                echo "<h3 id='user-types'>" . 'User types' . "</h3>\n";
                $Ri = types();
                if ($Ri) {
                    echo "<table cellspacing='0'>\n",
                        "<thead><tr><th>" . 'Name' . "</thead>\n";
                    odd('');
                    foreach ($Ri as $X) {
                        echo "<tr" .
                            odd() .
                            "><th><a href='" .
                            h(ME) .
                            "type=" .
                            urlencode($X) .
                            "'>" .
                            h($X) .
                            "</a>\n";
                    }
                    echo "</table>\n";
                }
                echo "<p class='links'><a href='" .
                    h(ME) .
                    "type='>" .
                    'Create type' .
                    "</a>\n";
            }
            if (support("event")) {
                echo "<h3 id='events'>" . 'Events' . "</h3>\n";
                $J = get_rows("SHOW EVENTS");
                if ($J) {
                    echo "<table cellspacing='0'>\n",
                        "<thead><tr><th>" .
                            'Name' .
                            "<td>" .
                            'Schedule' .
                            "<td>" .
                            'Start' .
                            "<td>" .
                            'End' .
                            "<td></thead>\n";
                    foreach ($J as $I) {
                        echo "<tr>",
                            "<th>" . h($I["Name"]),
                            "<td>" .
                                ($I["Execute at"]
                                    ? 'At given time' .
                                        "<td>" .
                                        $I["Execute at"]
                                    : 'Every' .
                                        " " .
                                        $I["Interval value"] .
                                        " " .
                                        $I["Interval field"] .
                                        "<td>$I[Starts]"),
                            "<td>$I[Ends]",
                            '<td><a href="' .
                                h(ME) .
                                'event=' .
                                urlencode($I["Name"]) .
                                '">' .
                                'Alter' .
                                '</a>';
                    }
                    echo "</table>\n";
                    $_c = $g->result("SELECT @@event_scheduler");
                    if ($_c && $_c != "ON") {
                        echo "<p class='error'><code class='jush-sqlset'>event_scheduler</code>: " .
                            h($_c) .
                            "\n";
                    }
                }
                echo '<p class="links"><a href="' .
                    h(ME) .
                    'event=">' .
                    'Create event' .
                    "</a>\n";
            }
            if ($Uh) {
                echo script("ajaxSetHtml('" . js_escape(ME) . "script=db');");
            }
        }
    }
}
page_footer();
