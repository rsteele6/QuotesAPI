<?php   

    include_once '../../config/Database.php';
    include_once '../../model/Author.php';

    function updateAuthor()
    {
        $database = new Database();
        $db = $database->connect();
    
        $author = new Author($db);

        // Get posted data
        $postedData = json_decode(file_get_contents("php://input"));

        // Set ID to update
        $author->id = $postedData->id;

        $author->author = $postedData->author;

        if($author->update())
        {
            echo json_encode
                (
                    array
                    (
                        'id' => $author->id,
                        'author' => $author->author
                    )
                );
        }
        else
        {
            echo json_encode
            (
                array('message' => 'Author Not Updated')
            );
        }
    }
?>
