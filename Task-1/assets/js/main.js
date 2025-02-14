document
  .getElementById("registrationForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the form from submitting without reload

    // Clear previous messages
    document.getElementById("message").innerHTML = "";

    // Get form values
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();

    // Validate name (only letters and spaces allowed)
    const namePattern = /^[a-zA-Z\s]+$/;
    if (!namePattern.test(name)) {
      document.getElementById("message").innerHTML =
        '<div class="alert alert-danger">Name must contain only letters and spaces.</div>';
      // Hide the message after 3 seconds
      setTimeout(() => {
        document.getElementById("message").innerHTML = "";
      }, 2000);
      return;
    }

    // Validate email format
    function isValidEmailWithTLD(email) {
      const knownTLDs = [
        "com",
        "net",
        "org",
        "edu",
        "gov",
        "io",
        "dev",
        "co",
        "xyz",
      ];
      const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      // Check if the email matches the general email pattern
      if (!emailPattern.test(email)) {
        document.getElementById("message").innerHTML =
          '<div class="alert alert-danger">Invalid email format.</div>';
        // Hide the message after 3 seconds
        setTimeout(() => {
          document.getElementById("message").innerHTML = "";
        }, 3000);
        return false;
      }

      const tld = email.split(".").pop().toLowerCase();

      if (!knownTLDs.includes(tld)) {
        document.getElementById("message").innerHTML =
          '<div class="alert alert-danger">Invalid Email Format Domain. Use .com, .net, .org etc.</div>';
        // Hide the message after 3 seconds
        setTimeout(() => {
          document.getElementById("message").innerHTML = "";
        }, 3000);
        return false;
      }

      return true; // Email is valid
    }

    // Validate the email using the function
    if (!isValidEmailWithTLD(email)) {
      return;
    }

    // Get form data
    const formData = new FormData(this);

    // Send AJAX request
    fetch("process.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text()) // Parse the response as text
      .then((data) => {
        // Display the response message in the #message div
        document.getElementById("message").innerHTML = data;
        if (data.includes("Registration successful")) {
          // Reset the form fields
          document.getElementById("registrationForm").reset();
        }
        // Hide the message after 3 seconds
        setTimeout(() => {
          document.getElementById("message").innerHTML = "";
        }, 3000);
      })
      .catch((error) => {
        console.error("Error:", error);
        document.getElementById("message").innerHTML =
          '<div class="alert alert-danger">An error occurred. Please try again.</div>';
        // Hide the message after 3 seconds
        setTimeout(() => {
          document.getElementById("message").innerHTML = "";
        }, 3000);
      });
  });
