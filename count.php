<?PHP


$folderCount = $fileCount = 0;

countStuff('.', $fileCount, $folderCount);

function countStuff($handle, &$fileCount, &$folderCount)
{
    if ($handle = opendir($handle)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if (is_dir($entry)) {
                    echo "Folder => " . $entry . "<br>";
                    countStuff($entry, $fileCount, $folderCount);
                    $folderCount++;
                } else {
                    echo "File   => " . $entry . "<br>";
                    $fileCount++;
                }
            }
        }
        closedir($handle);
    }
}
echo "<br>==============<br>";
echo "Total Folder Count : " . $folderCount . "<br>";
echo "Total File Count : " . $fileCount;
