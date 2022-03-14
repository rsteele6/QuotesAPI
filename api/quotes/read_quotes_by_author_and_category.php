<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

include_once '../../config/Database.php';
include_once '../../model/Quote.php';

function displayQuotesByAuthorAndCategory()
{
    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    // Get authorId & category ID
    $quote->authorId = isset($_GET['authorId']) ? $_GET['authorId'] : die();
    $quote->categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : die();

    $result = $quote->getQuoteByAuthorAndCategory();

    $num = $result->rowCount();

    if ($num > 0)
    {
        // quote array
        $quote_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            $quote_item = array
            (
                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
            );

            array_push($quote_arr, $quote_item);
        }
        // Convert to JSON & output
        echo json_encode($quote_arr);
    }
    else
    {
        echo json_encode
        (
            array('message' => 'No Quotes Found')
        );
    }
}

?>






