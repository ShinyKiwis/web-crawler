<?php 
// Crawler for url: https://books.toscrape.com/
// Get all images
$http_client = new \GuzzeleHttp\Client();
$response = $http_client->get("https://books.toscrape.com/");

function get_data() {
    return "HELLO";
}
?>