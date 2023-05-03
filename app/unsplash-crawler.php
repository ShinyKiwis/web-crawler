<?php

function getUnsplashData($queryString) {
    $requestData = [
        'debug' => true,
        'query' => [
            'query' => $queryString,
            'per_page' => 20,
            'page' => 1
        ],
    ];
    $extracted_images = [];

    $http_client = new GuzzleHttp\Client();
    $response = $http_client->get("https://unsplash.com/napi/search/photos", $requestData);
    $content = $response->getBody()->getContents();
    $json = json_decode($content, true);
    $images = $json['results'];
    $page_count = $json['total_pages'];
    foreach($images as $image) {
        $extracted_images[] = $image['urls']['full'];
    }

    for ($i = 2; $i <= $page_count; $i++) {
        $requestData = [
            'debug' => true,
            'query' => [
                'query' => $queryString,
                'per_page' => 20,
                'page' => $i
            ],
        ];
        $response = $http_client->get("https://unsplash.com/napi/search/photos", $requestData);
        $content = $response->getBody()->getContents();
        $json = json_decode($content, true);
        $images = $json['results'];
        foreach ($images as $image) {
            $extracted_images[] = $image['urls']['full'];
        }
    }

    foreach ($extracted_images as $extracted) {
        echo $extracted;
        echo "\n";
    }

    return $extracted_images;
}
?>