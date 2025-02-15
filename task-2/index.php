<?php
require_once 'Car.php';

$car = new Car();
$cars = $car->getAll(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
   
        label.error {
            color: red;
            font-size: 14px;
            margin-left: 10px;
        }


        input.error {
            border: 1px solid red;
        }
    </style>

</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Car Management System</h1>

        <!-- Add/Edit Car Form -->
        <form id="carForm" class="row g-3 mb-4">
            <input type="hidden" id="car_id" name="car_id">
            <div class="col-md-3">
                <input type="text" id="brand" name="brand" class="form-control" placeholder="Brand" required>
            </div>
            <div class="col-md-3">
                <input type="text" id="model" name="model" class="form-control" placeholder="Model" required>
            </div>
            <div class="col-md-2">
                <input type="number" id="year" name="year" class="form-control" placeholder="Year" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" id="price" name="price" class="form-control" placeholder="Price" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Add a Car</button>
            </div>
        </form>

        <!-- Search and Filter Section -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" id="search" class="form-control" placeholder="Search by Brand or Model">
            </div>
            <div class="col-md-2">
                <button id="searchBtn" class="btn btn-primary w-100">Search</button>
            </div>
            <div class="col-md-4">
                <select id="filterBrand" class="form-select" placeholder="Filter by Brand">
                    <option value="">Filter by Brand</option>
                    <?php
                    // Dynamically populate filter options with unique brands
                    $brands = array_unique(array_column($cars, 'brand'));
                    foreach ($brands as $brand) {
                        echo "<option value='$brand'>$brand</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Car Table -->
        <table id="carTable" class="table table-bordered table-striped">
            
        </table>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="./assets/js/main.js"></script>
</body>
</html>