<?php

try {
    $output = trim(readline('output file.phar : '));
    $phar = new Phar($output);
    $input = readline('count of files to compress : ');
    $file = [];
    for ($x = 0; $x < $input; $x++) {
        $file[$x] = trim(readline('filename : '));
        $phar[basename($file[$x])] = file_get_contents($file[$x]);
    }
    $phar->compressFiles(Phar::BZ2);
    $phar->setSignatureAlgorithm(Phar::SHA1);
    $phar->setStub('���� JFIF <?php Phar::webPhar() ;__HALT_COMPILER(); ?>');
} catch (Exception $e) {
    echo $e->getMessage();
}
