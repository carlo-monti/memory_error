<?php
$MI = 0;
$MAX = 1000000;
$pageRequested = $_GET['request'];
$requestedLength = $_GET['length_of_text'];
if(isset($pageRequested)){
    $selected_page = $pageRequested;
    $selected_page_to_show = file_get_contents("./archive/" . $pageRequested); #to remove \n at the end
}else{
    if(isset($requestedLength)){
        switch($requestedLength){
            case 'short':
                $urn_name = 'urn-short.txt';
                $MIN = 0;
                $MAX = 1000;
                break;
            case 'medium':
                $urn_name = 'urn-medium.txt';
                $MIN = 1000;
                $MAX = 10000;
                break;
            case 'long':
                $urn_name = 'urn-long.txt';
                $MIN = 10000;
                $MAX = 1000000;
                break;
        }
    }else{
        $urn_name = 'urn-medium.txt';
        $MIN = 1000;
        $MAX = 10000;
    }
    #count lines in the file
    $lines = count(file("./" . $urn_name));
    if($lines == 0){
        #create list of files
        $list_of_files = scandir("./archive");
        $index_of_img = array_search('img',$list_of_files);
        unset($list_of_files[$index_of_revision]);
        $number_of_files = count($list_of_files);
        $fp = fopen($urn_name, 'a');//opens file in append mode  
        for($i = 0; $i<$number_of_files; $i++){
            if($list_of_files[$i] == "." || $list_of_files[$i] == ".." || $list_of_files[$i] == "img"){
                continue;
                }
            if(filesize("archive/" . $list_of_files[$i]) <= $MAX and filesize("./archive/" . $list_of_files[$i]) > $MIN){
                fwrite($fp, $list_of_files[$i] . "\n");
            }
        }
        fclose($fp);  
        #recount lines
        $lines = count(file("./" . $urn_name));
    }
    
    $selected_page_number = rand(0,$lines - 1);
    $readedLines = file($urn_name);
    $selected_page = $readedLines[$selected_page_number];
    $selected_page_to_show = file_get_contents("./archive/" . substr($selected_page,0,-1)); #to remove \n at the end
    
    #delete selected line from the urn file
    $contentOfUrn = file_get_contents("./" . $urn_name);
    $contentOfUrn = str_replace($selected_page, '', $contentOfUrn);
    file_put_contents("./" . $urn_name, $contentOfUrn);
}

#get title
$start_of_title = strpos($selected_page_to_show,"<title>") + 7;
$end_of_title = strpos($selected_page_to_show,"</title>");
$title = substr($selected_page_to_show, $start_of_title, $end_of_title - $start_of_title);
#get subtitle
if(strpos($selected_page_to_show,"<h1>")===false){
    $subtitle = false;
}else{
    $start_of_subtitle = strpos($selected_page_to_show,"<h1>") + 4;
    $end_of_subtitle = strpos($selected_page_to_show,"</h1>");
    $subtitle = substr($selected_page_to_show, $start_of_subtitle, $end_of_subtitle - $start_of_subtitle);
}
#get body
$start_of_body = strpos($selected_page_to_show,"<body>") + 6;
$end_of_body = strpos($selected_page_to_show,"</body>");
$body = substr($selected_page_to_show, $start_of_body, $end_of_body - $start_of_body);
?>

<!DOCTYPE html>
<html lang="it">
<title>   
<?php echo $title; ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor.fa-coffee {font-size:200px}
</style>
<body id="body">
<?php include('navbar.php');?>

<!-- Header -->
<header class="w3-container w3-red w3-center" style="padding:128px 16px">
  <h1 class="w3-margin">
    <span style="text-transform:uppercase">
        <?php echo $title;?>
    </span>
    <?php 
        if($subtitle){
            //echo '<p class="w3-xlarge">' . $subtitle . '</p>';
        }
    ?>
  </h1>
</header>

<?php
    $many_p = explode("</p>",$body);
    $color = 0;

    foreach($many_p as $single_p){
        $has_h1 = strpos($single_p,"<h1");
        if($has_h1 === false){
        }else{
            $end_h1 = strpos($single_p,"</h1>");
            $single_p = substr($single_p, $end_h1 + 5);
        }
        $is_first = true;
        if(strlen($single_p) < 6){
            continue;
        }
        if(!$is_first){
            echo '</p></div></div></div>';
        }else{
            $is_first = false;
        }
        if($color % 3 == 0){
            echo '<div id="color_0" class="w3-row-padding w3-padding-64 w3-container"><div class="w3-content"><div class="w3-twothird">' . $single_p;
        }else if($color % 3 == 1){
            echo '<div id="color_1" class="w3-row-padding w3-light-grey w3-padding-64 w3-container"><div class="w3-content"><div class="w3-twothird">' . $single_p;
        }else{
            echo '<div id="color_2" class="w3-container w3-black w3-opacity w3-padding-64"><div class="w3-content"><div class="w3-twothird">' . $single_p;
        }
        $color++;
        echo '</div></div></div>';
        }
?>

<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity">  
<details>
    <div id="selection"></div>
    <summary>Edit</summary>
    <button type="button" onclick="increase_font_size()">Size +</button>
    <button type="button" onclick="decrease_font_size()">Size -</button>
    <button type="button" onclick="location.href='./revision_request.php?page=<?php echo substr($selected_page,0,-1); ?>'">Revision</button>
    <button type="button" onclick="location.href='./put_back.php?page=<?php echo substr($selected_page,0,-1); ?>'">Put back</button>
    <button type="button" onclick="location.href='./delete.php?page=<?php echo $selected_page; ?>'">Delete</button>
    <br>
    <p><?php echo $selected_page; ?></p>
    <form action="./edit_html.php" name="confirmationForm" method="post">
        <input type="hidden" id="page" name="page" value="<?php echo $selected_page ?>">
        <textarea id="plain_html" class="text" cols="86" rows ="20" name="plain_html" accept-charset="utf-8">
            <?php
                $plain_html = str_replace("<p>","\n\n<p>",$selected_page_to_show);
                echo $plain_html;
            ?>
        </textarea>
        <br>
        <button type="submit" value="Email" class="submitButton">Change</button>
    </form>
</details> 
</footer>

<script>

    // Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
  var x = document.getElementById("navDemo");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}

var color = 0;

function black() {
    var a = document.getElementById("body");
    if(color == 0){
        a.style.color = "black";
        a.style.backgroundColor = "whitesmoke";
        var b = document.getElementsByTagName("h1");
        b[0].style.backgroundColor = "whitesmoke";
        b[0].style.color = "black";
        color = 1;
    }else{
        a.style.color = "whitesmoke";
        a.style.backgroundColor = "rgb(16,16,16)";
        var b = document.getElementsByTagName("h1");
        b[0].style.backgroundColor = "rgb(16,16,16)";
        b[0].style.color = "whitesmoke";
        color = 0;
    }
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

font_sizes = [];
font_sizes.push("xx-small","x-small","small","medium","large","x-large","xx-large","xxx-large");
font_size = 3;
font_size = getCookie("font_size");
if(font_size == null || font_size === undefined){
    font_size = 3;
    document.cookie = "font_size=" + font_size.toString();
}else{
    font_size = parseInt(font_size);
}
document.getElementById("body").style.fontSize = font_sizes[font_size];

function increase_font_size() {
    font_size++;
    if(font_size>7){
        font_size = 7;
    }
    document.getElementById("body").style.fontSize = font_sizes[font_size];
    document.cookie = "font_size=" + font_size.toString();
}

function decrease_font_size() {
    font_size--;
    if(font_size<0){
        font_size = 0;
    }
    document.getElementById("body").style.fontSize = font_sizes[font_size];
    document.cookie = "font_size=" + font_size.toString();
}

document.getElementById("plain_html").style.width = (window.screen.width - 30) + "px";

</script>

</body>
</html>