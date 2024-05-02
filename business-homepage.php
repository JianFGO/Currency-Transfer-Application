<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- You can link your CSS file here -->
    <title>Business Homepage</title>
    <style>
        /* CSS styles for the layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        header {
            background-color: #0077b6;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 0px solid #fff;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
        }
        nav ul li a:hover {
            background-color: #005691;
        }
        .container {
            max-width: 1200px; /* Increased max-width */
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Enable horizontal scroll if needed */
        }
        table {
            width: 120%; /* Increased table width */
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px; /* Increased padding for cells */
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .left-background,
        .right-background {
            content: "";
            display: block;
            position: fixed;
            top: 0;
            bottom: 0;
            width: 10%;
            background-color: #0077b6;
            z-index: -1;
        }
        .left-background {
            left: 0;
        }
        .right-background {
            right: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Business Homepage</h1>
        <nav>
            <ul>
                <li><a href="business-homepage.php">Transaction Overview</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="left-background"></div> 
    <div class="right-background"></div> 
    
    <div class="container">
        <h2>Transaction Overview</h2>
        <?php
        // Database connection
        $db = new PDO("sqlite:C:/xampp/htdocs/Currency-Transfer-Application/Currency_Database.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to fetch transactions
        $sql = "SELECT * FROM Transactions";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if transactions exist
        if ($transactions) {
            // Display transactions in a table
            echo "<table>";
            echo "<thead><tr><th>Transaction ID</th><th>Sender Account Number</th><th>Sender Sort Code</th><th>Sender Branch Code</th><th>Amount</th><th>Receiver Account Number</th><th>Transaction Date</th><th>Receiver Sort Code</th><th>Receiver Branch Code</th></tr></thead>";
            echo "<tbody>";
            foreach ($transactions as $transaction) {
                echo "<tr>";
                echo "<td>{$transaction['transaction_id']}</td>";
                echo "<td>{$transaction['sender_account_number']}</td>";
                echo "<td>{$transaction['sender_sort_code']}</td>";
                echo "<td>{$transaction['sender_branch_code']}</td>";
                echo "<td>";
                // Display amount with currency symbol based on currency ID
                switch ($transaction['currency_id']) {
                    case 1:
                        echo "€ {$transaction['amount']}";
                        break;
                    case 2:
                        echo "£ {$transaction['amount']}";
                        break;
                    case 3:
                        echo "$ {$transaction['amount']}";
                        break;
                    case 4:
                        echo "¥ {$transaction['amount']}";
                        break;
                    default:
                        echo "{$transaction['amount']}";
                }
                echo "</td>";
                echo "<td>{$transaction['receiver_account_number']}</td>";
                echo "<td>{$transaction['transaction_date']}</td>";
                echo "<td>{$transaction['receiver_sort_code']}</td>";
                echo "<td>{$transaction['receiver_branch_code']}</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No transactions found.</p>";
        }
        ?>
    </div>
</body>
</html>
