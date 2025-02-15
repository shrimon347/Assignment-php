<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 d-flex justify-content-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">User Registration</h2>

        <!-- Display success or error messages -->
        <div id="message" class="mt-3"></div>

        <!-- Form with Box Shadow -->
        <form id="registrationForm" class="mt-4 shadow-lg p-4 rounded bg-white">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control form-control-sm" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form-control-sm" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
</div>

<script src="./assets/js/main.js"></script>
</body>
</html>
