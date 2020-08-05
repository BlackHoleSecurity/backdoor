<?php
if (isset($_POST['submit'])) {
  $filename = $_POST['filename'];
  foreach ($filename as $value) {
    $handle = fopen($value, "w");
    if (fwrite($handle, $_POST['data'])) {
      print("success");
    } else {
      print("failed");
    }
}
}
?>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript">
var max_fields = 10;
      var x = 1;
      $(document).on('click', '#add_input', function(e){
        if(x < max_fields){
          x++;
          $('#output').append('<div id=\"out\"><input type=\"text\" name=\"filename[]\"><a href="#" class=\"remove\">remove</a></div></div></div>');
        }
        $('#output').on("click",".remove", function(e){
          e.preventDefault(); $(this).parent('#out').remove(); x--;
          repeat();
        })
      });
</script>
<form method="post">
  <input type="text" name="filename[]">
  <a id="add_input">add</a>
  <div id="output"></div>
  <textarea name="data"></textarea>
  <input type="submit" name="submit">
</form>
