<?php   
    include_once '../../config/Database.php';
    include_once '../../model/Author.php';

    function deleteAuthor()
    {
        $database = new Database();
        $db = $database->connect();
    
        $author = new Author($db);

        // Get posted data
        $postedData = json_decode(file_get_contents("php://input"));

        // Bind values
        $author->id = $postedData->id;

        $author->delete();
    }
?>