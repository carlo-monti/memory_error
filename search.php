<?php
$listOfFiles = scandir('./archive');
$keyword = strtolower($_GET['keyword']);
$searchInText = $_GET['searchInText'];
?>

<!DOCTYPE html>
<html lang="en">
<title>
Search
</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif; background-color: rgb(16,16,16); color: lightgray}
h1{color: whitesmoke};
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}
.w3-twothird {font-size:20px; font-family: "Montserrat", sans-serif;}
</style>
<body id="body" style="color: lightgray; background-color: rgb(16,16,16);">

<?php include('navbar.php');?>

<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
        <form action="search.php" method="GET"><br>
          <input type="text" id="keyword" name="keyword" value="<?php echo $keyword;?>"><br>
          <input type="checkbox" id="searchInText" name="searchInText" value="true">
            <label for="searchInText">Search in text</label><br>
          <input type="submit" value="Search">
        </form>
       
<?php 

    if(strlen($keyword) > 1){
        echo "<hr>";
        echo "<b>	&#9654; In title</b><br>";
        $countResults = 0;
        foreach($listOfFiles as $i){
            if(stripos($i,$keyword) > -1){
                echo '<div style="padding-left:20px"><a href="./index.php?request=' . $i . '">' . $i . '</a></div>';
                $countResults++;
            }else{
            }
        }
        if($countResults == 0){
            echo "<div style='padding-left:20px'>No results</div>";
        }
        if($searchInText){
            echo "<br><b> 	&#9654; In text</b><br>";
            foreach($listOfFiles as $i){
                $contents = file_get_contents("./archive/" . $i);
                if(stripos($contents,$keyword) > -1){
                    echo '<div style="padding-left:20px"><a href="./index.php?request=' . $i . '">' . $i . '</a></div>';
                }
            }
        }
    }
?>
        
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity">  
</footer>

<script>
    var color = 0;
// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
  var x = document.getElementById("navDemo");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}

function black() {
    var a = document.getElementById("body");
    if(color == 0){
        a.style.color = "black";
        a.style.backgroundColor = "whitesmoke";
        var b = document.getElementsByTagName("h1");
        b[0].style.backgroundColor = "whitesmoke";
        b[0].style.color = "black";
        color++;
    }else if(color == 1){
        a.style.color = "whitesmoke";
        a.style.backgroundColor = "rgb(16,16,16)";
        var b = document.getElementsByTagName("h1");
        b[0].style.backgroundColor = "rgb(16,16,16)";
        b[0].style.color = "whitesmoke";
        color++;
    }else{
        a.style.color = "lightgray";
        a.style.backgroundColor = "rgb(16,16,16)";
        var b = document.getElementsByTagName("h1");
        b[0].style.backgroundColor = "rgb(16,16,16)";
        b[0].style.color = "whitesmoke";
        color = 0;
    }
}
</script>

</body>
</html>