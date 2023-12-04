<html>

<head>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .container {
            /* width: 80%; */
            /* max-width: 1200px; */
            margin: 0 auto;
        }

        .logo {
            display: block;
            margin: 20px auto;
            width: 100px;
            height: 100px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #949494;
            /* height: ; */
            /* Adjust the height based on your design */
        }

        .search-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .search-box {
            display: flex;
            align-items: center;
            border: 1px solid #dfe1e5;
            border-radius: 24px;
            margin: 20px auto;
            padding: 10px;
            width: 600px;
        }

        .search-input {
            /* flex: 1; */
            background-color: white;
            padding: 20px;
            border-radius: 20px;
            width: 300px;
            height: 50px;
            border: none;
            outline: none;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .search-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .search-button {
            border: none;
            background-color: #3385FC;
            border-radius: 24px;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
        }

        .search-button:hover {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .result {
            margin: 20px 0;
            margin-left: 20px;
        }

        .result-title {
            color: #1a0dab;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
        }

        .result-title:hover {
            text-decoration: underline;
        }

        .result-url {
            color: #006621;
            font-size: 14px;
        }

        .result-snippet {
            color: #545454;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="https://th.bing.com/th/id/R.aec641e622b05fab68b525dc57de2cc1?rik=G78pmKNs1erl5Q&pid=ImgRaw&r=0" alt="Google logo" class="logo">

        <form action="" method="GET" class="search-form">
            <label for="searchInput" class="visually-hidden">Search:</label>
            <input type="text" id="searchInput" name="searchString" class="search-input" placeholder="Write the String to be Searched">

            <button type="submit" class="search-button">
                <img src="https://th.bing.com/th/id/R.aec641e622b05fab68b525dc57de2cc1?rik=G78pmKNs1erl5Q&pid=ImgRaw&r=0" alt="Search icon" class="search-icon">
                Search
            </button>
        </form>
    </div>

    <div class="results">
        <?php
        // Form Handling
        if (isset($_GET['searchString'])) {
            $searchQuery = $_GET['searchString'];

            // Calling search function to search the string in all the files and return the array
            include 'search.php';
            $searchResults = searchText($searchQuery);

            if (!$searchResults) {
                echo '<div class="result">No Result Found</div>';
                return;
            }
            foreach ($searchResults as $result) {
                echo '<div class="result">';
                echo '<a href="' . $result['url'] . '" class="result-title">' . $result['title'] . '</a>';
                echo '<div class="result-url">' . $result['url'] . '</div>';
                echo '<div class="result-snippet">' . $result['contextLines'] . '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>