<?php
require_once 'Database.php';

class Car {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Insert a new car
    public function insertCar($brand, $model, $year, $price) {
        $query = "INSERT INTO cars (brand, model, year, price) VALUES (:brand, :model, :year, :price)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':price', $price);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Retrieve all cars
    public function getAllCars() {
        $query = "SELECT * FROM cars";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update car details (supports both PUT and PATCH)
    public function updateCar($id, $data) {
        $query = "UPDATE cars SET ";
        $updates = [];
        $params = [];

        // Check each field and add it to the query if it exists
        if (isset($data['brand'])) {
            $updates[] = "brand = :brand";
            $params[':brand'] = $data['brand'];
        }
        if (isset($data['model'])) {
            $updates[] = "model = :model";
            $params[':model'] = $data['model'];
        }
        if (isset($data['year'])) {
            $updates[] = "year = :year";
            $params[':year'] = $data['year'];
        }
        if (isset($data['price'])) {
            $updates[] = "price = :price";
            $params[':price'] = $data['price'];
        }

        // If no fields are provided, return false (nothing to update)
        if (empty($updates)) {
            return false;
        }

        // Complete the query
        $query .= implode(", ", $updates);
        $query .= " WHERE id = :id";
        $params[':id'] = $id;

        // Prepare and execute the query
        $stmt = $this->db->prepare($query);

        // Bind parameters dynamically
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        // Execute the query and return the result
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a car
    public function deleteCar($id) {
        $query = "DELETE FROM cars WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>