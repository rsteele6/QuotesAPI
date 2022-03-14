<?php   
    include_once '../../config/Database.php';
    include_once '../../model/Category.php';

    function deleteCategory()
    {
        $database = new Database();
        $db = $database->connect();
    
        $category = new Category($db);

        // Get posted data
        $postedData = json_decode(file_get_contents("php://input"));

        // Bind values
        $category->id = $postedData->id;

        $category->delete();
    }
?>