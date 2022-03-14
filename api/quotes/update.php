<?php   

    include_once '../../config/Database.php';
    include_once '../../model/Quote.php';

    function updateQuote()
    {
        $database = new Database();
        $db = $database->connect();
    
        $quote = new Quote($db);

        // Get posted data
        $postedData = json_decode(file_get_contents("php://input"));

        // Set ID to update
        $quote->id = $postedData->id;

        $quote->quote = $postedData->quote;
        $quote->authorId = $postedData->authorId;
        $quote->categoryId = $postedData->categoryId;

        if($quote->update())
        {
            $quote->getSingleQuote();

            if ($quote->quote)
            {
                $quoteItem = array
                (
                    'id' => $quote->id,
                    'quote' => $quote->quote,
                    'authorId' => $quote->authorId,
                    'categoryId' => $quote->categoryId
                );
                print_r(json_encode($quoteItem));
            }
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
