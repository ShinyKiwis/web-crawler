<?php 
$rand = "This is a random string"
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
    <div class="container_action">
      <input type="text" placeholder="Enter a url you want to crawl" />
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
    <div class="container_result">
      <h2>Result: </h2>
      <div class="result_box">

      </div>
      <div class="result_buttons">
        <button>Download as zip</button>
      </div>
    </div>
  </div>
</body>
</html>