<?php
    class Author 
    {
        private $conn;
        private $table = 'authors';

        public $id;
        public $author;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function getAuthors()
        {
            $query = 
            '
                SELECT
                    id,
                    author
                FROM
                    ' . $this->table . '
                ORDER BY
                    id ASC
            ';

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        public function getSingleAuthor()
        {
            $query = 
            '
            SELECT 
                id,
                author
            FROM 
                ' . $this->table . '
            WHERE
                id = ?
            ';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                 
            // conditional test
            if ($row && $row['id'])
            {
                $this->id = $row['id'];
                $this->author = $row['author'];

                return true;
            }
            else
            {
                return false;
            }
        }
        public function addAuthor()
        {
            $query = 
            '
                INSERT INTO ' . $this->table . '
                SET
                    author = :author
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));

            // Bind data
            $stmt->bindParam(':author', $this->author);

            // Execute Query
            if(!empty($this->author))
            {
                $stmt->execute();
                $id = $this->conn->lastInsertId();

                echo json_encode
                (
                    array
                    (
                        'id' => $id,
                        'author' => $this->author
                    )
                );
                return true;
            }
            else
            {
                echo json_encode
                (
                    array ('message' => 'AuthorId is empty')
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
                    author = :author
                WHERE
                    id = :id
            ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':author', $this->author);
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
                    array ('message' => 'AuthorId is empty')
                );
                return false;
            }
        }
    }
?>