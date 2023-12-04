# **Web Crawler and Search Project**
This project consists of a web crawler and a search functionality implemented in PHP. The web crawler (crawl.php) is designed to crawl a website, extract HTML content, and save the content of each page to text files. The search functionality (index.php and search.php) allows users to search for a specific string within the crawled pages.

## Getting Started
To use the web crawler and search functionality, follow these steps:

### Prerequisites
Ensure you have PHP installed on your server.
The project assumes a web server environment.
### Installation
Clone the repository to your local machine or server:
git clone https://github.com/your-username/your-repository.git

Navigate to the project directory:
cd your-repository

Set up your web server to serve the files.

## Usage
### Crawling a Website:

Open crawl.php and set the **$startUrl** variable to the website you want to crawl (e.g., "https://example.com").

Adjust the **$depth** variable to specify the crawling depth.

Run the script:
php crawl.php

The web crawler will start from the specified URL, fetch HTML content, and recursively crawl linked pages up to the specified depth. It extracts page titles and all text data, saving each page's content to a text file in the "**scraped-files**" directory.
### File Storage Format
The content of each crawled page is stored in a text file within the "scraped-files" directory. The filenames are formatted to provide meaningful and unique identification. The format of each filename is structured as follows:

[FORMATTED_URL].txt

**FORMATTED_URL:** Represents the URL of the crawled page with special characters replaced by underscores.

For example, if the URL is **https://example.com/page1**, the filename would be **https__example_com_page1.txt**.

This naming convention ensures that each filename is unique and easily correlates to the crawled page's URL.

The format of each text file is structured as follows:

URL: [URL_OF_THE_CRAWLED_PAGE]

TITLE: [PAGE_TITLE]

[PAGE_CONTENT]

URL: Represents the URL of the crawled page.

TITLE: Represents the title of the page.

PAGE_CONTENT: Contains the entire text content of the page.

For example:

URL: https://example.com/page1

TITLE: Example Page 1

This is the content of Example Page 1.

It can span multiple lines and include various text data.
This format allows for easy retrieval of URL, title, and content information when searching for specific strings using the search functionality.


### Searching for a String

Open index.php and ensure it includes the correct path to search.php.

Access index.php through your web browser.

Enter a search string and click the "Search" button.

The search functionality utilizes the searchText function in search.php. This function searches for the entered string in all text files within the "scraped-files" directory. It extracts relevant information such as URLs, titles, line numbers, and context lines for each occurrence.

The search results are displayed on the web page, showing titles, URLs, and context snippets.

## File Descriptions
### index.php
Front-end web page with a search form.

Sends a GET request to itself with the entered search string.

Includes the following logic:

#### Form Handling: 
Processes the search form submission and calls the searchText function from search.php.

#### Displaying Search Results: 
Displays search results including titles, URLs, and context snippets.

### search.php

Back-end logic for searching a string in all text files within the "**scraped-files**" directory.

Defines the following function:

#### searchText($searchString, $contextWords = 5):
Searches for occurrences of a given $searchString in all text files within the "**scraped-files**" directory. It returns an array of search results containing URLs, titles, line numbers, and context lines.

The function utilizes regular expressions to find occurrences of the search string and capture context lines. The context around the search string is determined by the number of words specified in $contextWords.

### crawl.php

Web crawler script that extracts HTML content from a specified website and saves it to text files.

Defines the following functions:
#### extractHtmlContent($url):

* Fetches the HTML content of a given URL using file_get_contents.

#### extractUrls($html): 
* Uses a regular expression to extract all the URLs from the HTML content.

#### makeUrlFromRelativeUrl($startUrl, $rootUrl): 
* Converts relative URLs to absolute URLs based on the root URL.

#### isSameDomain($url, $rootUrl): 
* Checks if two URLs belong to the same domain.

#### getDisallowedUrls($baseUrl): 
* Fetches disallowed URLs from the robots.txt file of a given base URL.

#### isUrlDisallowed($url, $disallowedUrls): 
* Checks if a given URL is in the array of disallowed URLs.

#### parseHtml($htmlContent): 
* Uses DOMDocument and XPath to parse HTML content, extracting page title and all text data.

#### savePageContent($url, $title, $textContent): 
* Saves the content of a web page to a file with a formatted filename.

#### startCrawling($startUrl, $rootUrl, $depth, $disallowedUrls): 
* Initiates the crawling process from a given URL with filtering and content saving.

The script starts crawling from the specified $startUrl ("https://example.com/") and saves the content of each page to a text file in the "**scraped-files**" directory.

#### Execution Flow:
In crawl.php, first, the script configures settings, initializes variables, and retrieves disallowed URLs from robots.txt. The startCrawling function is then called with the initial URL, root URL, crawling depth, and disallowed URLs. Inside startCrawling, relative URLs are handled, and conditions are checked to control the crawling process. It fetches HTML content using extractHtmlContent, extracts URLs with extractUrls, and parses the HTML using parseHtml. The page content, including title and text, is saved to a text file via savePageContent. The function recursively explores linked pages, iterating through extracted URLs and calling itself for each link. 
