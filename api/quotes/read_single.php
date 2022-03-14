<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

include_once '../../config/Database.php';
include_once '../../model/Category.php';

function displaySingle()
{
    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);

    // Get ID
    $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get quote
    $quote->getSingleQuote();


    if($quote->quote)
    {
        // create array
        $quoteItem = array
        (
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->authorId,
            'category' => $quote->categoryId
        );
        print_r(json_encode($quoteItem));
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