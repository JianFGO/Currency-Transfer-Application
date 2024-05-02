<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- You can link your CSS file here -->
    <title>User Suspension</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr.invalid {
            background-color: #ffcccc; /* Light red */
        }

        tr.valid {
            background-color: #ffffff; /* White */
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container button {
            padding: 10px 20px;
            background-color: #007bff; /* Blue */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-container button:hover {
            background-color: #0056b3; /* Darker blue on hover */
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
        <h1>User Suspension</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Surname</th>
                        <th>Address</th>
                        <th>Tele Num</th>
                        <th>Active</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Establish database connection
                    $dbPath = 'C:\xampp\htdocs\Currency-Transfer-Application\Currency_Database.db';
                    try {
                        $db = new PDO('sqlite:' . $dbPath);
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Retrieve users from the User table
                        $stmt = $db->query("SELECT user_id, first_name, last_name, address, phone_number, valid FROM User");
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($users as $user) {
                            $validText = $user['valid'] == 0 ? 'Suspended' : 'Yes';
                            $validClass = $user['valid'] == 0 ? 'invalid' : 'valid';
                            echo "<tr class='$validClass'>";
                            echo "<td>{$user['user_id']}</td>";
                            echo "<td>{$user['first_name']}</td>";
                            echo "<td>{$user['last_name']}</td>";
                            echo "<td>{$user['address']}</td>";
                            echo "<td>{$user['phone_number']}</td>";
                            echo "<td>{$validText}</td>";
                            echo "<td><input type='checkbox' name='selected_users[]' value='{$user['user_id']}'></td>";
                            echo "</tr>";
                        }

                    } catch (PDOException $e) {
                        echo "<tr><td colspan='7'>Database connection failed: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="button-container">
                <button type="submit" name="update_valid">Update Validity</button>
            </div>
        </form>

        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_valid'])) {
            // Check if users are selected
            if (isset($_POST['selected_users'])) {
                $selectedUsers = $_POST['selected_users'];
                // Update the validity for selected users
                foreach ($selectedUsers as $userId) {
                    // Toggle validity between 0 and 1
                    $validity = $db->query("SELECT valid FROM User WHERE user_id = $userId")->fetchColumn();
                    $newValidity = $validity == 0 ? 1 : 0;
                    $db->exec("UPDATE User SET valid = $newValidity WHERE user_id = $userId");
                }
                // Refresh the page
                header("Location: admin-suspension.php");
                exit();
            } else {
                echo "<p>Please select users to update.</p>";
            }
        }
        ?>
    </div>
</body>
</html
