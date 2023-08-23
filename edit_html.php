<?php
    $page = $_POST["page"];
    //$text = htmlentities($_POST["plain_html"]);
    $text = str_replace("\n","",$_POST["plain_html"]);
    //echo $text;
    $fp = fopen("./archive/".$page, "w") or die("Unable to open file!");
    $ret = fwrite($fp, $text);
    fclose($fp);
    header("Location: ./index.php?request=" . $page);
?>
