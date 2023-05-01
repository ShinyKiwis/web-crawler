
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
        );
        // Start crawling here
    } else if($_POST['action'] == '2'){
        $output = array (
            'message' => 'Stopped Crawling'
        );
    }
}
echo json_encode($output);
?>