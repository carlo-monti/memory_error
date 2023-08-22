<?php
$pageToBeRevisioned = $_GET['page'];
$fp = fopen("revisions_requested.txt", "a");
fwrite($fp, $pageToBeRevisioned . "\n"); 
fclose($fp);  
header("Location: ./index.php?request=" . $pageToBeRevisioned);
?>