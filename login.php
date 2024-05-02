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
        $stmt_user = $db->prepare("SELECT * FROM User WHERE email = :username AND password = :password AND valid = 1");
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

        // Display error message for invalid credentials or inactive accounts
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

// Check if the form is submitted for business manager login
if (isset($_POST["business_manager_login"])) {
    // Retrieve business manager input from the form
    $business_manager_id = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the SQLite database
    $db = new SQLite3($path);

    // Check if the connection is successful
    if (!$db) {
        echo "<p style='color: red;'>Error: Unable to open database.</p>";
    } else {
        // Prepare the SELECT statement for business managers
        $stmt_manager = $db->prepare("SELECT * FROM Business_Manager WHERE business_manager_id = :business_manager_id AND password = :password AND active = 1");
        $stmt_manager->bindParam(':business_manager_id', $business_manager_id);
        $stmt_manager->bindParam(':password', $password);
        $result_manager = $stmt_manager->execute();

        // Check if the query returned any rows for business managers
        if ($result_manager && $result_manager->fetchArray()) {
            $stmt_manager->close();
            $db->close();
            // Redirect to business-homepage.php
            header("Location: business-homepage.php");
            exit();
        }

        // Close the statement and the database connection
        $stmt_manager->close();
        $db->close();

        // Display error message for invalid credentials
        echo "<p style='color: red;'>Invalid business manager ID or password for business manager login. Please try again.</p>";
    }
}
?>

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
            <input type="submit" value="Business Manager Login" name="business_manager_login">
        </form>

        <?php
        // Display any error messages or additional information here if needed
        ?>
    </div>
</body>
</html>
