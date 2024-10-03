<?php

require 'DbConnection.php';

class CRUD
{
    private $conn;

    public function __construct()
    {
        $dbConnection = new DbConnection();
        $this->conn = $dbConnection->getConnection();
    }

    public function createTable()
    {
        try {
            // SQL query to create the products table
            $sqlQuery = "
            CREATE TABLE IF NOT EXISTS products (
                number INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                bottle_size VARCHAR(50),
                price DECIMAL(10, 2),
                price_gbp DECIMAL(10, 2),
                time_stamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                order_amount INT DEFAULT 0,
                PRIMARY KEY (number)
            )";

            // Executing the SQL query
            $this->conn->exec($sqlQuery);
            echo "Table 'products' created successfully.";
        } catch (PDOException $error) {
            // Handling errors during table creation
            echo "Error creating table: " . $error->getMessage();
        }
    }

    public function batchInsertUpdate($data, $priceIndex, $euroToGbp)
    {
        try {
            // Start a transaction
            $this->conn->beginTransaction();

            // Prepare SQL for batch insert/update
            $sqlQuery = "
            INSERT INTO products (number, name, bottle_size, price, price_gbp, time_stamp, order_amount) 
            VALUES (:number, :name, :bottle_size, :price, :price_gbp, :time_stamp, :order_amount)
            ON DUPLICATE KEY UPDATE 
                name = VALUES(name),
                bottle_size = VALUES(bottle_size),
                price = VALUES(price),
                price_gbp = VALUES(price_gbp),
                time_stamp = VALUES(time_stamp)
            ";

            $stmt = $this->conn->prepare($sqlQuery);
            $timestamp = date('Y-m-d H:i:s');

            // Loop through the data and bind values
            foreach ($data as $row) {
                // As format of the file is expected to be same always, harcoding the index 
                $priceEuro = $row[$priceIndex];         // Hinta
                $priceInGbp = $priceEuro * $euroToGbp;  // PriceGbp
                $number = $row[0];                      // Numero
                $name = $row[1];                        // Nimi
                $bottleSize = $row[3];                  // Pullokoko

                // Bind values to the SQL statement
                $stmt->bindValue(':number', $number);
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':bottle_size', $bottleSize);
                $stmt->bindValue(':price', $priceEuro);
                $stmt->bindValue(':price_gbp', $priceInGbp);
                $stmt->bindValue(':time_stamp', $timestamp);
                $stmt->bindValue(':order_amount', 0); // Default order amount to 0

                // Execute the statement for each row
                $stmt->execute();
            }

            // Commit the transaction
            $this->conn->commit();
            echo "Batch insert/update completed successfully.";
        } catch (PDOException $error) {
            // Rollback in case of an error
            $this->conn->rollBack();
            echo "Error: " . $error->getMessage();
        }
    }
}
