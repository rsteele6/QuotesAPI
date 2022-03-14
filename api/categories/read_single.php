<?php

include_once '../../config/Database.php';
include_once '../../model/Category.php';

function displaySingle()
{
    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    // Get ID
    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get author
    $category->getSingleCategory();

    // conditional test

    if($category->category)
    {
        // create array
        $categoryItem = array
        (
            'id' => $category->id,
            'category' => $category->category
        );
        print_r(json_encode($categoryItem));
        return true;
    }
    else
    {
        echo json_encode
        (
            array('message' => 'categoryId Not Found')
        );
        return false;
    }
}
?>