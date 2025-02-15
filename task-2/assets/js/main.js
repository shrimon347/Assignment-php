$(document).ready(function () {
  let currentCarId = null;

  // Initialize form validation
  $("#carForm").validate({
    rules: {
      brand: {
        required: true,
        minlength: 2,
      },
      model: {
        required: true,
        minlength: 2,
      },
      year: {
        required: true,
        digits: true,
        min: 1900,
        max: new Date().getFullYear(),
      },
      price: {
        required: true,
        number: true,
        min: 0,
      },
    },
    messages: {
      brand: {
        required: "Please enter the car brand.",
        minlength: "Brand must be at least 2 characters long.",
      },
      model: {
        required: "Please enter the car model.",
        minlength: "Model must be at least 2 characters long.",
      },
      year: {
        required: "Please enter the car year.",
        digits: "Year must be a numeric value.",
        min: "Year must be greater than or equal to 1900.",
        max: `Year must be less than or equal to ${new Date().getFullYear()}.`,
      },
      price: {
        required: "Please enter the car price.",
        number: "Price must be a numeric value.",
        min: "Price must be greater than or equal to 0.",
      },
    },
    errorPlacement: function (error, element) {
      error.insertAfter(element);
    },
    submitHandler: function (form) {
      // Handle form submission if validation passes
      let formData = $(form).serialize();
      let action = currentCarId ? "update" : "insert";
      formData += `&action=${action}&car_id=${currentCarId || ""}`;

      $.post("api.php", formData, function (response) {
        if (response.includes("successfully")) {
          toastr.success("Car saved successfully!");
          $("#carForm")[0].reset(); // Reset the form
          currentCarId = null; // Clear the current car ID
          $("#carForm button[type='submit']").text("Save Car"); // Change button text back to "Save Car"
          loadCars();
        } else {
          toastr.error(response);
        }
      }).fail(function () {
        toastr.error("An error occurred while saving the car.");
      });
    },
  });

  // Load all cars on page load
  function loadCars() {
    $.ajax({
      url: "api.php",
      method: "GET",
      success: function (response) {
        $("#carTable").html(response);
      },
      error: function () {
        toastr.error("Failed to load cars.");
      },
    });
  }

  // Populate the form when the Edit button is clicked
  $(document).on("click", ".edit-btn", function () {
    let id = $(this).data("id");

    $.get("api.php", { action: "getCarById", car_id: id }, function (response) {
      try {
        let data = JSON.parse(response);

        if (data.error) {
          toastr.error(data.error);
          return;
        }

        // Populate form fields with car details
        $("#car_id").val(data.id);
        $("#brand").val(data.brand);
        $("#model").val(data.model);
        $("#year").val(data.year);
        $("#price").val(data.price);

        currentCarId = data.id; // Store the current car ID for updates
        $("#carForm button[type='submit']").text("Update Car");
        toastr.info("Car details loaded for editing.");
      } catch (error) {
        console.error("Error parsing JSON:", error);
        toastr.error("Invalid response from server.");
      }
    }).fail(function () {
      toastr.error("Failed to fetch car details.");
    });
  });

  // Handle Delete button click with confirmation
  $(document).on("click", ".delete-btn", function () {
    let id = $(this).data("id");

    if (confirm("Are you sure you want to delete this car?")) {
      $.post("api.php", { action: "delete", car_id: id }, function (response) {
        if (response.includes("successfully")) {
          toastr.success("Car deleted successfully!");
        } else {
          toastr.error(response);
        }
        loadCars();
      }).fail(function () {
        toastr.error("An error occurred while deleting the car.");
      });
    }
  });

  // Handle Search button click
  $("#searchBtn").click(function () {
    let keyword = $("#search").val().trim();
    if (!keyword) {
      toastr.warning("Please enter a search keyword.");
      return;
    }

    $.get("api.php", { search: keyword }, function (response) {
      $("#carTable").html(response); // Insert the HTML table into the DOM
      toastr.info("Search results loaded.");
    }).fail(function () {
      toastr.error("An error occurred while searching for cars.");
    });
  });

  // Handle Filter by Brand dropdown change
  $("#filterBrand").change(function () {
    let brand = $(this).val();

    $.get("api.php", { filter: 1, brand: brand }, function (response) {
      $("#carTable").html(response);
      toastr.info(`Filtered cars by brand: ${brand || "All"}`);
    }).fail(function () {
      toastr.error("An error occurred while filtering cars.");
    });
  });

  // Load initial data
  loadCars();
});
