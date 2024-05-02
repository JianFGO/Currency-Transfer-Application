<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- You can link your CSS file here -->
    <title>Logout</title>
    <style>
        .container {
            text-align: center;
            margin-top: 100px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .button-container button,
        .button-container a {
            padding: 10px 20px;
            margin: 0 5px; /* Adjusted margin for both buttons */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            width: 100px; /* Uniform button width */
        }

        .button-container button:hover,
        .button-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin-homepage.php">Back</a></li>
            </ul>
        </nav>
    </header>

    <div class="left-background"></div> 
    <div class="right-background"></div> 
    
    <div class="container">
        <h1>Logout</h1>
        <p>Are you sure you want to log out?</p>
        <div class="button-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button type="submit" name="logout">Yes</button>
            </form>
            <a href="admin-homepage.php">No</a>
        </div>

        <?php
        // Check if the logout button is clicked
        if (isset($_POST["logout"])) {
            // Redirect to login.php
            header("Location: login.php");
            exit();
        }
        ?>
    </div>
</body>
</html>
