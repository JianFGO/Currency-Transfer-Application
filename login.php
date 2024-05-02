<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- You can link your CSS file here -->
    <title>Login</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="create-account.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="left-background"></div> 
    <div class="right-background"></div> 
    
    <div class="container">
        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="email" id="email" name="email" placeholder="Enter your Email">
            <input type="password" id="password" name="password" placeholder="Enter your Password">
            <input type="submit" value="Login">
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
            $email = $_POST["email"];
            $password = $_POST["password"];

            // Connect to the SQLite database
            $db = new SQLite3($path);

            // Check if the connection is successful
            if (!$db) {
                echo "<p style='color: red;'>Error: Unable to open database.</p>";
            } else {
                // Prepare the SELECT statement
                $stmt = $db->prepare("SELECT * FROM User WHERE email = :email AND password = :password");

                // Check if the statement was prepared successfully
                if (!$stmt) {
                    echo "<p style='color: red;'>Error: Unable to prepare statement.</p>";
                } else {
                    // Bind parameters
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);

                    // Execute the statement
                    $result = $stmt->execute();

                    // Check if the query returned any rows
                    if ($result && $result->fetchArray()) {
                        // Close the statement and the database connection
                        $stmt->close();
                        $db->close();
                        // Redirect to user-homepage.php
                        header("Location: user-homepage.php");
                        exit();
                    } else {
                        echo "<p style='color: red;'>Invalid email or password. Please try again.</p>";
                    }

                    // Close the statement and the database connection
                    $stmt->close();
                    $db->close();
                }
            }
        }
        ?>
    </div>
</body>
</html>
