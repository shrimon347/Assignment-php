<?php
require_once 'Database.php';

class Car extends Database {
    // Insert a new car
    public function insert($brand, $model, $year, $price) {
        $query = "INSERT INTO cars (brand, model, year, price) VALUES (:brand, :model, :year, :price)";
        $stmt = $this->conn->prepare($query);
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
    public function getAll() {
        $query = "SELECT * FROM cars";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update car details (supports partial updates)
    public function update($id, $brand = null, $model = null, $year = null, $price = null) {
        // Fetch the existing car data
        $existingCar = $this->getCarById($id);
        if (!$existingCar) {
            return false; // Car not found
        }

        // Use existing values if new values are not provided
        $brand = $brand ?? $existingCar['brand'];
        $model = $model ?? $existingCar['model'];
        $year = $year ?? $existingCar['year'];
        $price = $price ?? $existingCar['price'];

        // Construct the update query
        $query = "UPDATE cars SET brand = :brand, model = :model, year = :year, price = :price WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a car
    public function delete($id) {
        $query = "DELETE FROM cars WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Search cars by brand or model
    public function search($keyword) {
        $query = "SELECT * FROM cars WHERE brand LIKE :keyword OR model LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $keyword = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Filter cars by criteria
    public function filter($filters) {
        $conditions = [];
        $params = [];

        if (!empty($filters['brand'])) {
            $conditions[] = "brand = :brand";
            $params[':brand'] = $filters['brand'];
        }
        // if (!empty($filters['model'])) {
        //     $conditions[] = "model = :model";
        //     $params[':model'] = $filters['model'];
        // }
        // if (!empty($filters['min_year']) && !empty($filters['max_year'])) {
        //     $conditions[] = "year BETWEEN :min_year AND :max_year";
        //     $params[':min_year'] = $filters['min_year'];
        //     $params[':max_year'] = $filters['max_year'];
        // }
        // if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
        //     $conditions[] = "price BETWEEN :min_price AND :max_price";
        //     $params[':min_price'] = $filters['min_price'];
        //     $params[':max_price'] = $filters['max_price'];
        // }

        // Build the query
        $query = "SELECT * FROM cars";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Helper method to fetch a car by ID
    public function getCarById($id) {
        $query = "SELECT * FROM cars WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>