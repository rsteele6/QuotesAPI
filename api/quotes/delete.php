<?php   
    include_once '../../config/Database.php';
    include_once '../../model/Quote.php';

    function deleteQuote()
    {
        $database = new Database();
        $db = $database->connect();
    
        $quote = new Quote($db);

        // Get posted data
        $postedData = json_decode(file_get_contents("php://input"));

        // Bind values
        $quote->id = $postedData->id;

        if ($quote->getSingleQuote())
        {
            $quote->delete();
        }
        else
        {
            echo json_encode
            (
                array('message' => "No Quotes Found")
            );
        }
    }
?>