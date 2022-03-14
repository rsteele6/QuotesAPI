<?php   
    include_once '../../config/Database.php';
    include_once '../../model/Author.php';

    function createAuthor()
    {
        $database = new Database();
        $db = $database->connect();
    
        $author = new Author($db);

        // Get posted data
        $postedData = json_decode(file_get_contents("php://input"));

        // Bind values
        $author->author = $postedData->author;

        $author->addAuthor();
    }
?>