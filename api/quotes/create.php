<?php   

    include_once '../../config/Database.php';
    include_once '../../model/Quote.php';
    include_once '../quotes/format.php';

    function createQuote()
    {   
        $database = new Database();
        $db = $database->connect();
    
        $quote = new Quote($db);

        // Get posted data
        $postedData = json_decode(file_get_contents("php://input"));

        // Bind values

        $quote->quote = $postedData->quote;
        $quote->authorId = $postedData->authorId;
        $quote->categoryId = $postedData->categoryId;

        $id = $quote->addQuote();

        formatQuote($quote, $id);  
    }
?>