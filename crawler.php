<?php

set_time_limit(300);

// Function to crawl a website and extract html
function extractHtmlContent($url)
{
    $html = file_get_contents($url); // Get the HTML content of the page
    return $html;
}

// Function to extract urls from html
function extractUrls($html)
{
    // Use a regular expression to find all the links in the page
    preg_match_all('/<a\s+(?:[^>]*?\s+)?href="([^"]*)"/i', $html, $matches);

    $links = $matches[1]; // Extracted links
    // echo "The Urls on the page: $url : are<br>";
    // echo "<pre>";
    // print_r($links);
    // echo "</pre>";
    return $links;
}


// Function to start crawling from a given URL with filtering and content saving
function startCrawling($startUrl, $rootUrl, $depth)
{
    static $visited = array(); // To keep track of visited URLs

    if ($depth === 0 || in_array($startUrl, $visited))
    {
        return;
    }

    $visited[] = $startUrl;
    echo "Crawling: $startUrl<br>";
    $html = extractHtmlContent($startUrl);
    $links = extractUrls($html);

    foreach ($links as $link)
    {
        startCrawling($link, $startUrl, $depth - 1);
    }

    // echo "The Visited Urls are:<br>";
    // echo "<pre>";
    // print_r($visited);
    // echo "</pre>";
}

$startUrl = "https://www.forbes.com/";
// $rootUrl = "https://www.aljazeera.com/";
$depth = 2;

startCrawling($startUrl, $startUrl, $depth);
