<?php
$pageToBeDeleted = $_GET['page'];
$fp = fopen("delete.txt", "w");
fwrite($fp, $pageToBeDeleted); 
fwrite($fp, "\n"); 
fclose($fp);  
header("Location: ./index.php?request=" . $pageToBeDeleted);
?>