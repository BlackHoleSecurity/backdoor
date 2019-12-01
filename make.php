#!/usr/bin/env php
<?php
try {
    $phar = new Phar('ini.phar');
    $input = readline('count of files to compress : ');
    $file = array();
    for ($x = 0; $x < $input; $x++) {
        $file[$x] = trim(readline('filename : '));
        $phar[basename($file[$x])] = file_get_contents($file[$x]);
    }
    $phar->compressFiles(Phar::BZ2);
    $phar->setSignatureAlgorithm(Phar::SHA1, sha1('Cvar1984'));
    $phar->setStub('<?php
Phar::webPhar();
__HALT_COMPILER(); ?>');
} catch (Exception $e) {
    echo $e->getMessage();
}

