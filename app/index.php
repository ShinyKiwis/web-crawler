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
    <form  method="POST" class="container_wrapper" id="input_form">
      <div class="container_input">
        <input name="url" id="url" type="text" placeholder="Enter a url you want to crawl" value=""/>
        <select name="type" id="type">
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
        <input type="submit" name="start" id="start" value="Start to crawl"/>
        <input type="submit" name="stop" id="stop" value="Stop crawling"/>
        <input type="hidden" name="action" id="action" value="" />
      </div>
    </form>
    <div class="container_result">
      <h2 id="message"></h2>
      <h2>Result: </h2>
      <div class="result_box">
      </div>
      <form action="index.php" class="result_buttons" method="POST" id="download_form">
        <input type="submit" name="download" value="Download as zip" />
        <input type="hidden" name="download" value="1" />
      </form>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script type="text/javascript" >
    // Handle action 
    // 1. start to crawl
    // 2. stop crawling
    // 3. download all
    $('#start').click(function (){
      $('#action').val('1');
    })
    $('#stop').click(function (){
      $('#action').val('2');
    })
    // Handle download 
    $('#download_form').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        type: 'post',
        url: 'download.php',
        data: $('#download_form').serialize(),
        success: function (response) {
          if(typeof response == 'object'){
            response_data = JSON.parse(response)
            alert(response_data)
          }else {
            console.log(response)
            const blob = new Blob([response], { type: "application/zip" });
            // Create a temporary link to the Blob
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'data.zip';
            
            // Add the link to the DOM and simulate a click
            document.body.appendChild(link);
            link.click();
            
            // Remove the link from the DOM
            document.body.removeChild(link);
          }
        },
        error: function () {

        }
      })
    })
    
    // Handle form submit
    $('#input_form').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        type:'post',
        url: 'server.php',
        data: $('#input_form').serialize(),
        success: function (response) {
          response_data = JSON.parse(response.trim())
          // This is for debugging
          // response_data = JSON.parse(JSON.stringify(response.trim()))
          // alert(response_data)
          if(response_data.hasOwnProperty('message')){
            message = response_data.message 
            document.getElementById('message').textContent = message
          }else{
            message = `Crawling ${response_data.type} files at link: ${response_data.url}`
            document.getElementById('message').textContent = message
            result_box = document.getElementsByClassName('result_box')[0]
            // Output for users
            response_data.data.forEach(data => {
              const link = document.createElement("a")
              link.textContent = data
              result_box.appendChild(link)
              result_box.appendChild(document.createElement("br"))
            }) 

            // Save data to download later
            $.ajax({
              type:'post',
              url: 'download.php',
              data: {download_list: response_data.data},
              success: function(response) {
                alert(response_data.data)
                response_data = JSON.parse(JSON.stringify(response))
                alert(`AFTER CRAWLED: ${response_data}`)
              },
              error: function (){
                alert('ERROR WHILE PROCESSING FOR DOWNLOAD!')
              }
            })
          }
        },
        error: function() {
          alert('ERROR!')
        }
      })
      // alert($('#input_form').serialize())
    })
  </script>
</body>
</html>