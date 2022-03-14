<?php

include_once '../../config/Database.php';
include_once '../../model/Author.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') 
{
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
}

// Display all authors
if ($method === 'GET')
{
    // check if id param is passed. If not, display all results
    if(isset($_GET['id']))
    {
        // display specific result
        require '../authors/read_single.php';
        displaySingle();
    }
    else
    {
        // display all
        require '../authors/read.php';
        displayAll();
    }
    
}
if ($method === 'POST')
{
    // Get request body
    $requestBody = json_decode(file_get_contents('php://input'));
    
    // Check if author is included in request body
    if (isset($requestBody->author))
    {
        require '../authors/create.php';
        createAuthor();
    }
    else
    {
        echo json_encode
        (
            array('message' => "Missing Required Parameters")
        );
    }
}
if ($method === 'PUT')
{
    $requestBody = json_decode(file_get_contents('php://input'));

    if(isset($requestBody->id) && isset($requestBody->author))
    {
        require '../authors/update.php';
        require '../authors/validate_data.php';

        $database = new Database();
        $db = $database->connect();

        $author = new Author($db);

        // Bind Values
        $author->id = $requestBody->id;
        $author->author = $requestBody->author;

        if(authorIdValid($author) && authorValid($author))
        {
            updateAuthor();
        }
        else if (!authorIdValid($author))
        {
            echo json_encode
            (
                array('message' => "authorId Not Found")
            );
        }
        else if (!authorValid($author))
        {
            echo json_encode
            (
                array('message' => "Author is blank")
            );
        } 
    }

    else
    {
        if(!isset($requestBody->id))
        {
            echo json_encode
            (
                array('message' => "authorId Not Found")
            );
        }
        if (!isset($requestBody->author))
        {
            echo json_encode
            (
                array('message' => "Missing Required Parameters")
            );
        }
    }
}

if ($method === 'DELETE')
{
    $requestBody = json_decode(file_get_contents('php://input'));

    if(isset($requestBody->id))
    {
        require '../authors/delete.php';
        deleteAuthor();
    }
    else
    {
        echo json_encode
        (
            array('message' => "Missing Required Parameters")
        );
    }
}

?>


