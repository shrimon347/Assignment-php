<?php
require_once 'Car.php';

$car = new Car();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'insert' || $action === 'update') {
        $id = $_POST['car_id'] ?? null;
        $brand = $_POST['brand'] ?? null;
        $model = $_POST['model'] ?? null;
        $year = $_POST['year'] ?? null;
        $price = $_POST['price'] ?? null;

        // Perform insert or update
        if ($action === 'insert') {
            $result = $car->insert($brand, $model, $year, $price);
        } elseif ($action === 'update') {
            $result = $car->update($id, $brand, $model, $year, $price);
        }

        echo $result ? "Car saved successfully" : "Operation failed";
        exit;
    } elseif ($action === 'delete') {
        $id = $_POST['car_id'] ?? '';
        if (empty($id)) {
            echo "Car ID is required for deletion";
            exit;
        }
        $result = $car->delete($id);
        echo $result ? "Car deleted successfully" : "Deletion failed";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getCarById') {
        $id = $_GET['car_id'] ?? '';
        if (empty($id)) {
            echo json_encode(["error" => "Car ID is required"]);
            exit;
        }
    
        $carDetails = $car->getCarById($id);
        
        if ($carDetails) {
            echo json_encode($carDetails);
        } else {
            echo json_encode(["error" => "Car not found"]);
        }
    
        exit;
    }

    if (isset($_GET['search'])) {
        $keyword = $_GET['search'];
        $cars = $car->search($keyword);
        echo generateHtmlTable($cars);
        exit;
    }

    if (isset($_GET['filter'])) {
        $filters = [
            'brand' => $_GET['brand'] ?? ''
        ];
        $cars = $car->filter($filters);
        echo generateHtmlTable($cars);
        exit;
    }

    // Default: Fetch all cars
    $cars = $car->getAll();
    echo generateHtmlTable($cars);
}

// Helper function to generate an HTML table with Edit and Delete buttons
function generateHtmlTable($cars) {
    if (empty($cars)) {
        return "No cars found";
    }

    $html = "<table class='table table-bordered'><thead><tr><th>ID</th><th>Brand</th><th>Model</th><th>Year</th><th>Price</th><th>Actions</th></tr></thead><tbody>";
    foreach ($cars as $car) {
        $html .= "<tr>
            <td>{$car['id']}</td>
            <td>{$car['brand']}</td>
            <td>{$car['model']}</td>
            <td>{$car['year']}</td>
            <td>$ {$car['price']}</td>
            <td>
                <button class='btn btn-warning btn-sm edit-btn' data-id='{$car['id']}'>Edit</button>
                <button class='btn btn-danger btn-sm delete-btn' data-id='{$car['id']}'>Delete</button>
            </td>
        </tr>";
    }
    $html .= "</tbody></table>";
    return $html;
}
?>
