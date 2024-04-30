<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- You can link your CSS file here -->
    <title>User Homepage</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .button-container {
            margin-top: 20px;
        }

        .transfer-button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .transfer-button:hover {
            background-color: #0056b3;
        }

        /* Align table cells */
        .name-column {
            width: 40%;
        }

        .code-column {
            width: 20%;
        }

        .balance-column {
            width: 40%;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="create-account.php">Create Currency Account</a></li>
                <li><a href="delete-account.php">Delete Currency Account</a></li>
                <li><a href="transfer-funds.php">Transfer Funds</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="left-background"></div> 
    <div class="right-background"></div> 
    
    <div class="container">
        <h1>User Homepage</h1>

        <?php
        // Establish database connection
        $dbPath = 'C:\xampp\htdocs\Currency-Transfer-Application\Currency_Database.db';
        try {
            $db = new PDO('sqlite:' . $dbPath);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Fetch currency accounts from the database
            $sql = "SELECT * FROM Currency"; // Assuming the table name is "Currency"
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $accounts = $stmt->fetchAll();

            // Display currency accounts
            if ($accounts) {
                foreach ($accounts as $account) {
                    echo "<h2>{$account['name']}</h2>";
                    echo "<table>";
                    echo "<tr><th class='name-column'>Name</th><th class='code-column'>Code</th><th class='balance-column'>Balance</th></tr>";

                    // Fetch balances for the current currency account
                    $accountId = $account['currency_id'];
                    $balanceStmt = $db->prepare("SELECT balance FROM Currency_account WHERE currency_id = :accountId");
                    $balanceStmt->bindParam(':accountId', $accountId);
                    $balanceStmt->execute();
                    $balance = $balanceStmt->fetchColumn();

                    echo "<tr><td>{$account['name']}</td><td>{$account['code']}</td><td>{$balance}</td></tr>";
                    echo "</table>";
                    echo "<div class='button-container'>";
                    echo "<button class='transfer-button'>Transfer Funds</button>";
                    echo "</div>";
                }
            } else {
                echo "<p>No currency accounts found.</p>";
            }
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
        ?>
    </div>
</body>
</html>
