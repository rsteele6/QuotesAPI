<?php

include_once '../../config/Database.php';
include_once '../../model/Author.php';


function displayAll()
{
    $database = new Database();
    $db = $database->connect();

    $author = new Author($db);
    $result = $author->getAuthors();
    $numRows = $result->rowCount();

    if($numRows > 0)
    {
        $authorsArr = array();
    
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
    
            $authorItem = array
            (
                'id' => $id,
                'author' => $author
            );
            array_push($authorsArr, $authorItem);
        }
    
        echo json_encode($authorsArr);
    }
    else
    {
        echo json_encode
        (
            array('message' => 'No authors found!')
        );
    }
}
?>