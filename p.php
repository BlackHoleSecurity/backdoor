<?php
if(isset($_POST) && !empty($_FILES['image']['name'])){
   $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
   $filepath = ''; 
   $img = $_FILES['image']['name'];
   $tmp = $_FILES['image']['tmp_name'];

   list($txt, $ext) = explode(".", $img); // alternative $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
  
   $image_name = time().".".$ext;

   // Validate File extension
   //if(in_array($ext, $valid_extensions))
  $file_type = $_FILES['image']['type']; //returns the mimetype
    $allowed = array("image/jpeg", "image/gif", "image/png");
    if(in_array($file_type, $allowed)){ 
        $filepath=$filepath.$image_name;
        if(move_uploaded_file($tmp,$filepath)){
            echo "<img width='300px' src='".$filepath."'>";
        }
        else{
            echo "Failed to upload image on server";
        }
    }
    else{
        echo 'Please upload valid image';
    }
}
else{
    echo "Please select image to upload";
}
?>
