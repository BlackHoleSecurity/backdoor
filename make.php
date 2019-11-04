#!/usr/bin/env php
<?php
$file=readline("input filename : ");
try {
    $phar = new Phar('main.phar');
    $phar['index.php'] = file_get_contents($file);
    $phar->compressFiles(Phar::BZ2);
    $phar->setSignatureAlgorithm(Phar::SHA1, sha1('Cvar1984'));
    $phar->setStub('<?php
Phar::webPhar();
__HALT_COMPILER(); ?>');
} catch (Exception $e) {
    // handle error here
}

