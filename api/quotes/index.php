<?php

include_once '../../config/Database.php';
include_once '../../model/Quote.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') 
{
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
}

if ($method === 'GET')
{
    // check if id param is passed
    if(isset($_GET['id']))
    {
        // display specific result
        require '../quotes/read_single.php';
        displaySingle();
    }
    
    // check if only the authorID param is passed
    else if(isset($_GET['authorId']))
    {
        // display specific result
        require '../quotes/read_quotes_by_author.php';
        displayQuotesByAuthor();
    }
    // check if only the categoryID param is passed
    else if(isset($_GET['categoryId']))
    {
        // display specific result
        require '../quotes/read_quotes_by_category.php';
        displayQuotesByCategory();
    }
    // check if both authorId and categoryId is passed
    else if(isset($_GET['authorId']) && isset($_GET['categoryId']))
    {
        // display specific result
        require '../quotes/read_quotes_by_author_and_category.php';
        displayQuotesByAuthorAndCategory();
    }
    else
    {
        // display all
        require '../quotes/read.php';
        displayAll();
    }  
}

if ($method === 'POST')
{
    // Get request body
    $requestBody = json_decode(file_get_contents('php://input'));
    
    // Check if quote, authorId and categoryId are included in request body
    if (isset($requestBody->quote) && isset($requestBody->authorId) && isset($requestBody->categoryId))
    {
        require '../quotes/create.php';
        require '../authors/validate_data.php';
        require '../categories/validate_data.php';
        require '../../model/Author.php';
        require '../../model/Category.php';

        // connect to db
        $database = new Database();
        $db = $database->connect();

        // instantiate models
        $author = new Author($db);
        $category = new Category($db);

        // Bind values
        $author->id = $requestBody->authorId;
        $category->id = $requestBody->categoryId;
        
        if(authorIdValid($author) && categoryIdValid($category))
        {
            createQuote();
        }
        else
        {
            if(!authorIdValid($author))
            {
                echo json_encode
                (
                    array('message' => "authorId Not Found")
                );
            }
            if(!categoryIdValid($category))
            {
                echo json_encode
                (
                    array('message' => "categoryId Not Found")
                );
            }
        }
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

    if (isset($requestBody->quote) && isset($requestBody->id) && isset($requestBody->authorId) && isset($requestBody->categoryId))
    {
        require '../quotes/update.php';
        require '../authors/validate_data.php';
        require '../categories/validate_data.php';
        require '../quotes/validate_data.php';
        require '../../model/Author.php';
        require '../../model/Category.php';

        $database = new Database();
        $db = $database->connect();

        $quote = new Quote($db);
        $author = new Author($db);
        $category = new Category($db);

        // Bind Values
        $quote->id = $requestBody->id;
        $quote->quote = $requestBody->quote;
        $quote->authorId = $requestBody->authorId;
        $quote->categoryId = $requestBody->categoryId;

        $author->id = $requestBody->authorId;
        $category->id = $requestBody->categoryId;

        if(categoryIdValid($category) && quoteIdValid($quote) && authorIdValid($author))
        {
            updateQuote();
        }
        else
        {
            if(!categoryIdValid($category))
            {
                echo json_encode
                (
                    array('message' => "categoryId Not Found")
                );
            }
            else if (!authorIdValid($author))
            {
                echo json_encode
                (
                    array('message' => "authorId Not Found")
                );
            }
            else if (!quoteIdValid($quote))
            {
                echo json_encode
                (
                    array('message' => "No Quotes Found")
                );
            }
        }
    }
    else
    {
        if(!isset($requestBody->id))
        {
            echo json_encode
            (
                array('message' => "No Quotes Found")
            );
        }
        if (!isset($requestBody->quote) || !isset($requestBody->authorId) || !isset($requestBody->categoryId))
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
        require '../quotes/delete.php';
        deleteQuote();
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
