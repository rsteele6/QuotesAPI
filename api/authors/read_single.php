<?php

include_once '../../config/Database.php';
include_once '../../model/Author.php';

function displaySingle()
{
    $database = new Database();
    $db = $database->connect();

    $author = new Author($db);

    // Get ID
    $author->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get author
    $author->getSingleAuthor();

    // conditional test

    if($author->author)
    {
        // create array
        $authorItem = array
        (
            'id' => $author->id,
            'author' => $author->author
        );
        print_r(json_encode($authorItem));
        return true;
    }
    else
    {
        echo json_encode
        (
            array('message' => 'authorId Not Found')
        );
        return false;
    }
}
?>