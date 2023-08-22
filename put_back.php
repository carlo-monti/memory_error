<?php
$pageToBePutBack = $_GET['page'];
$fp = fopen("urn.txt", "a");
fwrite($fp, $pageToBePutBack); 
fwrite($fp, "\n"); 
fclose($fp);  
header("Location: ./index.php?request=" . $pageToBePutBack);
?>