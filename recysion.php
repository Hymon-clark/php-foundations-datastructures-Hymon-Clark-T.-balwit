<?php

$jsonData = file_get_contents("books.json");
$library = json_decode($jsonData, true);

function printLibrary(array $sections, int $level = 0): void {
    foreach ($sections as $category => $content) {

        if (isset($content[0]) && is_array($content[0])) {
            echo str_repeat(' ', $level) . strtoupper($category) . PHP_EOL;
            foreach ($content as $book) {
                echo str_repeat(' ', $level + 2) . "- {$book['title']} by {$book['author']} ({$book['year']})" . PHP_EOL;
            }
        }

        elseif (is_array($content)) {
            echo str_repeat(' ', $level) . strtoupper($category) . PHP_EOL;
            printLibrary($content, $level + 2);
        }

        else {
            echo str_repeat(' ', $level) . $content . PHP_EOL;
        }
    }
}

if (php_sapi_name() === 'cli' && realpath($argv[0]) === __FILE__) {
    echo "Library Sections:\n";
    printLibrary($library);
}

if (php_sapi_name() !== 'cli') {
    echo "<pre>Library Sections:\n";
    printLibrary($library);
    echo "</pre>";
}
?>
