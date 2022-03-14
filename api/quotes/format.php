<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

include_once '../../config/Database.php';

function formatQuote($quote, $id)
{
    $quote->id = $id;
    $quote->getSingleQuote();


    if($quote->quote)
    {
        // create array
        $quoteItem = array
        (
            'id' => $id,
            'quote' => $quote->quote,
            'authorId' => $quote->authorId,
            'categoryId' => $quote->categoryId
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