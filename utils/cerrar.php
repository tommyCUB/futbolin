<?php
session_start();
$_SESSION['user']="";
session_destroy();
?>
<script>
 window.location.replace("../index.php"); 	
</script>

 