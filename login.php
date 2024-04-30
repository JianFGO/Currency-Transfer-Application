<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- You can link your CSS file here -->
    <title>Create Currency Account</title>
</head>
<body>
    <header>
        <!-- Your navigation bar goes here -->
    </header>

    <div class="container">
        <h1>Create Currency Account</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" id="firstname" name="firstname" placeholder="Enter your First Name">
            <input type="text" id="lastname" name="lastname" placeholder="Enter your Last Name">
            <input type="email" id="email" name="email" placeholder="Enter your Email">
            <input type="password" id="password" name="password" placeholder="Enter your Password">
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your Password">
            <input type="text" id="address" name="address" placeholder="Enter your Address">
            <input type="text" id="phone" name="phone" placeholder="Enter your Phone Number">
            <input type="submit" value="Create Account">
        </form>

        <?php
        // Path validation
        $path = 'C:\xampp\htdocs\Currency-Transfer-Application\Currency_Database.db';
        $realPath = realpath($path);

        if ($realPath === false) {
            die("The path '$path' does not exist.");
        }

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve user input from the form
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirmPassword = $_POST["confirmPassword"];
            $address = $_POST["address"];
            $phone = $_POST["phone"];

            // Check if form fields are not empty
            if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmPassword) || empty($address) || empty($phone)) {
                echo "<p style='color: red;'>Error: Please fill in all fields.</p>";
            } else {
                // Check if passwords match
                if ($password !== $confirmPassword) {
                    echo "<p style='color: red;'>Error: Passwords do not match.</p>";
                } else {
                    // Connect to the SQLite database
                    $db = new SQLite3($path);

                    // Check if the connection is successful
                    if (!$db) {
                        echo "<p style='color: red;'>Error: Unable to open database.</p>";
                    } else {
                        // Prepare the INSERT statement
                        $stmt = $db->prepare("INSERT INTO User (email, password, first_name, last_name, address, phone_number) VALUES (:email, :password, :firstname, :lastname, :address, :phone)");

                        // Check if the statement was prepared successfully
                        if (!$stmt) {
                            echo "<p style='color: red;'>Error: Unable to prepare statement.</p>";
                        } else {
                            // Bind parameters
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':password', $password);
                            $stmt->bindParam(':firstname', $firstname);
                            $stmt->bindParam(':lastname', $lastname);
                            $stmt->bindParam(':address', $address);
                            $stmt->bindParam(':phone', $phone);

                            // Execute the statement
                            $result = $stmt->execute();

                            // Check if the insertion was successful
                            if ($result) {
                                echo "<p>Your account has been successfully created.</p>";
                            } else {
                                echo "<p style='color: red;'>Error: Unable to create your account.</p>";
                            }

                            // Close the statement and the database connection
                            $stmt->close();
                            $db->close();
                        }
                    }
                }
            }
        }
        ?>
    </div>
</body>
</html>
