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
            <input type="text" id="username" name="username" placeholder="Enter your Email or Employee ID">
            <input type="password" id="password" name="password" placeholder="Enter your Password">
            <input type="submit" value="User Login" name="user_login">
            <input type="submit" value="Admin Login" name="admin_login">
        </form>

        <?php
        // Path validation
        $path = 'C:\xampp\htdocs\Currency-Transfer-Application\Currency_Database.db';
        $realPath = realpath($path);

        if ($realPath === false) {
            die("The path '$path' does not exist.");
        }

        // Check if the form is submitted for user login
        if (isset($_POST["user_login"])) {
            // Retrieve user input from the form
            $username = $_POST["username"];
            $password = $_POST["password"];

            // Connect to the SQLite database
            $db = new SQLite3($path);

            // Check if the connection is successful
            if (!$db) {
                echo "<p style='color: red;'>Error: Unable to open database.</p>";
            } else {
                // Prepare the SELECT statement for users
                $stmt_user = $db->prepare("SELECT * FROM User WHERE email = :username AND password = :password");
                $stmt_user->bindParam(':username', $username);
                $stmt_user->bindParam(':password', $password);
                $result_user = $stmt_user->execute();

                // Check if the query returned any rows for users
                if ($result_user && $result_user->fetchArray()) {
                    $stmt_user->close();
                    $db->close();
                    // Redirect to user-homepage.php
                    header("Location: user-homepage.php");
                    exit();
                }

                // Close the statement and the database connection
                $stmt_user->close();
                $db->close();

                // Display error message for invalid credentials
                echo "<p style='color: red;'>Invalid username or password for user login. Please try again.</p>";
            }
        }

        // Check if the form is submitted for admin login
        if (isset($_POST["admin_login"])) {
            // Retrieve admin input from the form
            $admin_id = $_POST["username"];
            $password = $_POST["password"];

            // Connect to the SQLite database
            $db = new SQLite3($path);

            // Check if the connection is successful
            if (!$db) {
                echo "<p style='color: red;'>Error: Unable to open database.</p>";
            } else {
                // Prepare the SELECT statement for admins
                $stmt_admin = $db->prepare("SELECT * FROM Administrator WHERE administrator_id = :admin_id AND password = :password AND active = 1");
                $stmt_admin->bindParam(':admin_id', $admin_id);
                $stmt_admin->bindParam(':password', $password);
                $result_admin = $stmt_admin->execute();

                // Check if the query returned any rows for admins
                if ($result_admin && $result_admin->fetchArray()) {
                    $stmt_admin->close();
                    $db->close();
                    // Redirect to admin-homepage.php or wherever admins are directed
                    header("Location: admin-homepage.php");
                    exit();
                }

                // Close the statement and the database connection
                $stmt_admin->close();
                $db->close();

                // Display error message for invalid credentials
                echo "<p style='color: red;'>Invalid admin ID or password for admin login. Please try again.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
