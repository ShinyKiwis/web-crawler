
<?php 

require 'vendor/autoload.php';
require_once 'nav-img-crawler.php';
require_once 'unsplash-crawler.php';
require_once 'anna-crawler.php';
require_once 'pdfdrive-crawler.php';

$output = array (
    'message' => 'ERROR',
);

if(isset($_POST['action'])){
    if($_POST['action'] == '1'){
        $url = $_POST['url'];
        $type = $_POST['type'];
        $output = array(
            'url' => $url,
            'type' => $type,
        );
        $unsplash_url = "/https:\/\/unsplash.com\/s\/photos\//i";
        $anna_url = "/https:\/\/annas-archive.org\/search\?/i";
        $output['status'] = preg_match($anna_url, $url);
        // Start crawling here
        if($url == "https://books.toscrape.com/") {
            $output['data'] = get_data();
        }else if (preg_match($unsplash_url, $url)) {
            ob_start();
            $output['data'] = getUnsplashData(preg_replace($unsplash_url, "", $url));
            ob_end_clean();
        }else if(preg_match($anna_url, $url)) {
            // $output['status-keyword'] =preg_replace($anna_url,"",$url);
            $output['data'] = getAnnaData(preg_replace($anna_url,"",$url),$type);
        }else {
            // Pdfdrive here
            // Write a function and return array of urls
            $output['data'] = get_pdf();
        }
    } else if($_POST['action'] == '2'){
        $output = array (
            'message' => 'Stopped Crawling'
        );
    }
}
echo json_encode($output);
?>