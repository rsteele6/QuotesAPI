<?php   

    include_once '../../config/Database.php';
    include_once '../../model/Category.php';

    function updateCategory()
    {
        $database = new Database();
        $db = $database->connect();
    
        $category = new Category($db);

        // Get posted data
        $postedData = json_decode(file_get_contents("php://input"));

        // Set ID to update
        $category->id = $postedData->id;

        $category->category = $postedData->category;

        if($category->update())
        {
            echo json_encode
            (
                array
                (
                    'id' => $category->id,
                    'category' => $category->category
                )
            );
        }
        else
        {
            echo json_encode
            (
                array('message' => 'Category Not Updated')
            );
        }
    }
?>
