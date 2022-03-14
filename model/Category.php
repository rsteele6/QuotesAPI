<?php
    class Category 
    {
        private $conn;
        private $table = 'categories';

        public $id;
        public $category;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function getCategories()
        {
            $query = 
            '
                SELECT
                    id,
                    category
                FROM
                    ' . $this->table . '
                ORDER BY
                    id ASC
            ';

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        public function getSingleCategory()
        {
            $query = 
            '
            SELECT 
                id,
                category
            FROM 
                ' . $this->table . '
            WHERE
                id = ?
            ';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                 
            if ($row && $row['id'])
            {
                $this->id = $row['id'];
                $this->category = $row['category'];
                return true;
            }
            else
            {
                return false;
            }
        }

        public function addCategory()
        {
            $query = 
            '
                INSERT INTO ' . $this->table . '
                SET
                    category = :category
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':category', $this->category);

            // Execute Query

            if(!empty($this->category))
            {
                $stmt->execute();
                $id = $this->conn->lastInsertId();
                echo json_encode
                (
                    array
                    (
                        'id' => $id,
                        'category' => $this->category
                    )
                );

                return true;
            }
            else
            {
                echo json_encode
                (
                    array ('message' => 'CategoryId is empty')
                );
                
                return false;
            }
        }

        public function update()
        {
            $query = 
            '
                UPDATE ' . $this->table . '
                SET
                    category = :category
                WHERE
                    id = :id
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);

            if($stmt->execute())
            {
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function delete()
        {
            $query = 
            '
                DELETE FROM ' . $this->table . '
                WHERE
                    id = :id
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute Query
            if($stmt->execute())
            {
                echo json_encode
                (
                    array
                    (
                        'id' => $this->id
                    )
                );
                return true;
            }
            else
            {
                echo json_encode
                (
                    array ('message' => 'CategoryId is empty')
                );
                return false;
            }
    }   }
?>