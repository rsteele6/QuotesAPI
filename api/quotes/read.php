<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

include_once '../../config/Database.php';
include_once '../../model/Quote.php';


function displayAll()
{
    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);
    $result = $quote->getQuotes();
    $numRows = $result->rowCount();

    if($numRows > 0)
    {
        $quotesArr = array();
    
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
    
            $quotesItem = array

            (
                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
            );
    
            array_push($quotesArr, $quotesItem);
        }
        
        echo json_encode($quotesArr);
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