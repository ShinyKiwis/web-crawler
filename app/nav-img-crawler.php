<?php 
// Crawler for url: https://books.toscrape.com/
// Get all images

function get_data() {
    $http_client = new GuzzleHttp\Client();
    $response = $http_client->get("https://books.toscrape.com/");
    
    // Although the code below allows you to crawl all the pages
    // I will limited at only 5 pages for presentation purposes
    $depth = 5;
    $extracted_thumbnails = [];
    do{
        $html_string = (string)$response->getBody();
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($html_string);
        $xpath = new DOMXPath($doc);
        $next_link = $xpath->evaluate('//ul[@class="pager"]/li[@class="next"]/a/@href');
        if(!empty($next_link)){
            $depth -= 1;
            $next_page = str_contains($next_link[0]->textContent, 'catalogue') ? $next_link[0]->textContent : 'catalogue/' . $next_link[0]->textContent;
            $next_link = "https://books.toscrape.com/" . $next_page;
            $thumbnails = $xpath->evaluate('//img[@class="thumbnail"]/@src');
            // $extracted_thumbnails = $thumbnails;
            // break;
            foreach ($thumbnails as $thumbnail) {
                $extracted_thumbnails[] = "https://books.toscrape.com/" . str_replace("../", "", $thumbnail->textContent);
            }
        }        
        $response = $http_client->get($next_link);
    }while($depth !=  0);
    return $extracted_thumbnails;
}
?>