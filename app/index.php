<?php 
$url = '';
$message = '';
$is_crawling = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if (isset($_POST["start_crawl"])){
    $url = $_POST["url"];
    if($url != ''){
      $is_crawling = TRUE;
      $message = "Crawling at ". $url;
      // Call function crawl here 
    }
  }elseif (isset($_POST["stop_crawl"])){
    if($is_crawling) {
      $message = "Stopped crawling";
      $is_crawling = FALSE;
      // Call function to stop crawl here
    }
  }elseif (isset($_POST["download"])){
    // Call function to start download here
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="main.css"/>
  <title>Simple Web Crawler</title>
</head>
<body>
  <div class="container">
    <h1>Simple Web Crawler</h1>
    <form action="index.php" method="POST" class="container_wrapper" id="input_form">
      <div class="container_input">
        <input name="url" id="url" type="text" placeholder="Enter a url you want to crawl" value=""/>
        <select>
          <option>Choose file format</option>
          <option disabled>---Image Extensions---</option>
          <option>jpg</option>
          <option>png</option>
          <option>jpeg</option>
          <option disabled>---File Extensions---</option>
          <option>pdf</option>
          <option>mp3</option>
        </select>
      </div>
      <div class="container_operation">
        <input type="submit" name="start_crawl" value="Start to crawl"/>
        <input type="submit" name="stop_crawl" value="Stop crawling"/>
      </div>
    </form>
    <div class="container_result">
      <h2><?= $message?></h2>
      <h2>Result: </h2>
      <div class="result_box">

      </div>
      <form action="index.php" class="result_buttons" method="POST">
        <input type="submit" name="download" value="Download as zip" />
      </form>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script type="text/javascript" >
    $(document).ready(function() {
      $("form").on("submit", function(event) {
        // $.ajax({
        //   type: "POST",
        //   url: "index.php",
        //   data: $(this).serialize,
        //   succes: function {
        //     console.log("YAY")
        //   }
        // })
        event.preventDefault()
      })
    })

  </script>
</body>
</html>