<?php

// Function to search a string in all the files
function searchText($searchString, $contextWords = 5)
{
    $searchResults = [];

    foreach (glob('scraped-files/*.txt') as $file) {
        if (!file_exists($file)) {
            continue; // Skip non-existent files
        }

        // Use file_get_contents for efficient file reading
        $content = file_get_contents($file);

        // Extract URL, Title, and Content using regex
        preg_match('/^URL: (.+)$/m', $content, $urlMatches);
        preg_match('/^TITLE: (.+)$/m', $content, $titleMatches);
        $title = isset($titleMatches[1]) ? trim($titleMatches[1]) : '';
        $url = isset($urlMatches[1]) ? trim($urlMatches[1]) : '';

        // Search for the string within the content
        $lines = explode("\n", $content);
        $occurrences = [];

        foreach ($lines as $lineNumber => $line) {
            // Use preg_match_all to capture words around the string
            if (preg_match_all("/(\b\w+\b.*?){$searchString}(.*?\b\w+\b)/i", $line, $matches)) {
                foreach ($matches[0] as $match) {
                    // Combine line number at the start of each line
                    $contextLines = explode("\n", $match);
                    $combinedLines = array_map(function ($contextLine) use ($lineNumber) {
                        return "[{$lineNumber}] {$contextLine}";
                    }, $contextLines);

                    $context = implode("\n", $combinedLines);

                    $occurrences[] = [
                        'lineNumber' => $lineNumber + 1,
                        'contextLines' => $context,
                        'contextLine' => $contextLines[0], // Include the first line of the context
                    ];
                }
            }
        }

        // Include file entry only once with all occurrences and context lines
        if (!empty($occurrences)) {
            $searchResults[] = [
                'url' => $url,
                'title' => $title,
                'file' => $file,
                'occurrences' => $occurrences,
                'contextLines' => $occurrences[0]['contextLines'], // Include the context lines at the top level
            ];
        }
    }
    return $searchResults;
}

?>