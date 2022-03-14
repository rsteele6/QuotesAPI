<?php
    class Quote 
    {
        private $conn;
        private $table = 'quotes';

        public $id;
        public $quote;
        // Foreign keys
        public $authorId;
        public $categoryId;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function getQuotes()
        {
            $query = 
            '
                SELECT
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
                FROM 
                    quotes
                    
                JOIN 
                    authors
                ON 
                    quotes.authorId = authors.id
                JOIN 
                    categories
                ON
                    quotes.categoryId = categories.id
            ';

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        public function getSingleQuote()
        {
            $query = 
            '
                SELECT
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
                FROM 
                    quotes   
                JOIN 
                    authors
                ON 
                    quotes.authorId = authors.id
                JOIN 
                    categories
                ON
                    quotes.categoryId = categories.id
                WHERE
                    quotes.id = ?
            ';
            
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                 
            if ($row && $row['id'])
            {
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->authorId = $row['author'];
                $this->categoryId = $row['category'];
                
                return true;
            }
            else
            {
                return false;
            }
        }

        public function getQuotesByCategory()
        {
            $query = 
            '
                SELECT
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
                FROM 
                    quotes   
                JOIN 
                    authors
                ON 
                    quotes.authorId = authors.id
                JOIN 
                    categories
                ON
                    quotes.categoryId = categories.id
                WHERE
                    quotes.categoryId = ?
            ';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->categoryId);

            $stmt->execute();

            return $stmt;
        }

        public function getQuotesByAuthor()
        {
            $query = 
            '
                SELECT
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
                FROM 
                    quotes   
                JOIN 
                    authors
                ON 
                    quotes.authorId = authors.id
                JOIN 
                    categories
                ON
                    quotes.categoryId = categories.id
                WHERE
                    quotes.authorId = ?
            ';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->authorId);

            $stmt->execute();

            return $stmt;
        }

        public function getQuoteByAuthorAndCategory()
        {
            $query = 
            '
                SELECT
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
                FROM 
                    quotes   
                JOIN 
                    authors
                ON 
                    quotes.authorId = authors.id
                JOIN 
                    categories
                ON
                    quotes.categoryId = categories.id
                WHERE
                    quotes.authorId = ?
                AND
                    quotes.categoryId = ?
            ';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->authorId);
            $stmt->bindParam(2, $this->categoryId);

            $stmt->execute();

            return $stmt;
        }
        
        public function addQuote()
        {
            $query = 
                '
                    INSERT INTO ' . $this->table . '
                    SET
                        quote = :quote,
                        authorId = :authorId,
                        categoryId = :categoryId 
                ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->authorId = htmlspecialchars(strip_tags($this->authorId));
            $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':authorId', $this->authorId);
            $stmt->bindParam(':categoryId', $this->categoryId);
    
            if(!empty($this->quote))
            {
                $stmt->execute();
                $id = $this->conn->lastInsertId();

                return $id;
            }
            else
            {
                echo json_encode
                (
                    array ('message' => 'AuthorId is empty')
                );
            }
        }
        public function update()
        {
                $query = 
                '
                    UPDATE quotes
                    SET
                        quotes.quote = :quote,
                        quotes.authorId = :authorId,
                        quotes.categoryId = :categoryId
                    WHERE
                        quotes.id = :id
                ';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->authorId = htmlspecialchars(strip_tags($this->authorId));
            $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':authorId', $this->authorId);
            $stmt->bindParam(':categoryId', $this->categoryId);

            if($stmt->execute())
            {
                return true;
            }
            else
            {
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
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
                    array ('message' => 'No Quotes Found')
                );
                return false;
            }
        }
    } 
?>