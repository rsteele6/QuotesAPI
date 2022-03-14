<?php

include_once '../../config/Database.php';
include_once '../../model/Category.php';


function displayAll()
{
    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);
    $result = $category->getCategories();
    $numRows = $result->rowCount();

    if($numRows > 0)
    {
        $categoriesArr = array();
    
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
    
            $categoryItem = array
            (
                'id' => $id,
                'category' => $category
            );
    
            array_push($categoriesArr, $categoryItem);
        }
    
        echo json_encode($categoriesArr);
    }
    else
    {
        echo json_encode
        (
            array('message' => 'No categories found!')
        );
    }
}
?>