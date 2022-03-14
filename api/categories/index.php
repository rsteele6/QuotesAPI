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
        require '../categories/read_single.php';
        displaySingle();
    }
    else
    {
        // display all
        require '../categories/read.php';
        displayAll();
    }
}

if ($method === 'POST')
{
    // Get request body
    $requestBody = json_decode(file_get_contents('php://input'));
    
    // Check if category is included in request body

    if (isset($requestBody->category))
    {
        require '../categories/create.php';
        createCategory();
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

    if(isset($requestBody->id) && isset($requestBody->category))
    {
        require '../categories/update.php';
        require '../categories/validate_data.php';

        $database = new Database();
        $db = $database->connect();

        $category = new Category($db);

        // Bind Values
        $category->id = $requestBody->id;
        $category->category = $requestBody->category;

        if(categoryIdValid($category) && categoryValid($category))
        {
            updateCategory();
        }
        else if (!categoryIdValid($category))
        {
            echo json_encode
            (
                array('message' => "categoryId Not Found")
            );
        }
        else if (!categoryValid($category))
        {
            echo json_encode
            (
                array('message' => "Category is blank")
            );
        } 
    }

    else
    {
        if(!isset($requestBody->id))
        {
            echo json_encode
            (
                array('message' => "categoryId Not Found")
            );
        }
        if (!isset($requestBody->category))
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
        require '../categories/delete.php';
        deleteCategory();
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