<?php

$jsonData = file_get_contents("books.json");
$library = json_decode($jsonData, true);

$books = [];
foreach ($library as $category => $bookList) {
    foreach ($bookList as $book) {
        $books[$book['title']] = [
            "author" => $book['author'],
            "year" => $book['year'],
            "genre" => ucfirst(str_replace("_books", "", $category))
        ];
    }
}

function findBook(string $title, array $books): void {
    foreach ($books as $bookTitle => $details) {
        if (strcasecmp($title, $bookTitle) === 0) {
            echo "Title: $bookTitle" . PHP_EOL;
            echo "Author: {$details['author']}" . PHP_EOL;
            echo "Year: {$details['year']}" . PHP_EOL;
            echo "Genre: {$details['genre']}" . PHP_EOL;
            return;
        }
    }
    echo "Book not found: $title" . PHP_EOL;
}

if (php_sapi_name() === 'cli' && realpath($argv[0]) === __FILE__) {
    findBook('The Hobbit', $books);
}

if (php_sapi_name() !== 'cli') {
    echo "<pre>";
    findBook('The Hobbit', $books);
    echo "</pre>";
}
?>
