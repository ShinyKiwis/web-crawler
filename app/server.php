
<?php 
require 'vendor/autoload.php';
require_once 'nav-img-crawler.php';

$output = array (
    'message' => 'ERROR'
);

if(isset($_POST['action'])){
    if($_POST['action'] == '1'){
        $url = $_POST['url'];
        $type = $_POST['type'];
        $output = array(
            'url' => $url,
            'type' => $type,
            'status' => $url == "https://books.toscrape.com/"
        );
        // Start crawling here
        if($url == "https://books.toscrape.com/") {
            $output['data'] = get_data();
        }else if ($url == ""){
            $output['data'] = getUnsplashData();
        }
    } else if($_POST['action'] == '2'){
        $output = array (
            'message' => 'Stopped Crawling'
        );
    }
}
echo json_encode($output);
?>