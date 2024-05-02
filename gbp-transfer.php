<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Pound Transfer</title>
    <style>
        /* Additional styles for the Pound Transfer page */
        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }

        /* Form styles */
        form {
            margin-top: 20px;
        }

        select,
        input[type="text"] {
            width: calc(100% - 22px); /* Adjusting for input border */
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #0077b6; /* Dark blue color */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #005691; /* Slightly darker on hover */
        }

        /* Table styles */
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
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="user-homepage.php">Back</a></li>
            </ul>
        </nav>
    </header>

    <div class="left-background"></div> 
    <div class="right-background"></div> 

    <div class="container">
        <h1>Pound Transfer</h1>

        <!-- Display Pound account balance -->
        <?php
            // Establish database connection
            $dbPath = 'C:\xampp\htdocs\Currency-Transfer-Application\Currency_Database.db';
            try {
                $db = new PDO('sqlite:' . $dbPath);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Retrieve current Pound balance
                $poundStmt = $db->prepare("SELECT balance FROM Currency_account WHERE currency_id = :poundCurrencyId");
                $poundStmt->bindValue(':poundCurrencyId', 2); // Assuming Pound has currency_id 2
                $poundStmt->execute();
                $currentPoundBalance = $poundStmt->fetchColumn();

                echo "<p>Pound Account Balance: $currentPoundBalance</p>";

            } catch (PDOException $e) {
                echo "<p class='error-message'>Database connection failed: " . $e->getMessage() . "</p>";
            }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="destination_currency">Destination Currency:</label>
            <select name="destination_currency" id="destination_currency">
                <option value="Euro">Euro</option>
                <option value="US Dollar">US Dollar</option>
                <option value="Japanese Yen">Japanese Yen</option>
            </select>
            <br><br>
            <label for="transfer_amount">Transfer Amount:</label>
            <input type="text" id="transfer_amount" name="transfer_amount">
            <br><br>
            <input type="submit" value="Transfer Funds" class="transfer-button">
        </form>

        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Establish database connection
            $dbPath = 'C:\xampp\htdocs\Currency-Transfer-Application\Currency_Database.db';
            try {
                $db = new PDO('sqlite:' . $dbPath);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Retrieve form data
                $destinationCurrency = isset($_POST['destination_currency']) ? $_POST['destination_currency'] : null;
                $transferAmount = isset($_POST['transfer_amount']) ? $_POST['transfer_amount'] : null;

                // Check if the destination currency and transfer amount are selected
                if ($destinationCurrency === null || $transferAmount === null) {
                    echo "<p class='error-message'>Please select a destination currency and enter a transfer amount.</p>";
                    exit;
                }

                // Define conversion rates
                $conversionRates = [
                    'Euro' => 1.17, // 1 Pound = 1.17 Euro
                    'US Dollar' => 1.45, // 1.45 USD for 1 Pound
                    'Japanese Yen' => 225.84 // 225.84 JPY for 1 Pound
                ];

                // Check if the destination currency is valid
                if (!array_key_exists($destinationCurrency, $conversionRates)) {
                    echo "<p class='error-message'>Invalid destination currency.</p>";
                    exit;
                }

                // Retrieve current Pound balance
                $poundStmt = $db->prepare("SELECT balance FROM Currency_account WHERE currency_id = :poundCurrencyId");
                $poundStmt->bindValue(':poundCurrencyId', 2); // Assuming Pound has currency_id 2
                $poundStmt->execute();
                $currentPoundBalance = $poundStmt->fetchColumn();

                // Calculate new balances
                $conversionRate = $conversionRates[$destinationCurrency];
                $newPoundBalance = $currentPoundBalance - $transferAmount;
                $newDestinationBalance = $transferAmount * $conversionRate;

                // Update balances in the database
                $updatePoundBalanceStmt = $db->prepare("UPDATE Currency_account SET balance = :newPoundBalance WHERE currency_id = :poundCurrencyId");
                $updatePoundBalanceStmt->bindValue(':newPoundBalance', $newPoundBalance);
                $updatePoundBalanceStmt->bindValue(':poundCurrencyId', 2); // Assuming Pound has currency_id 2
                $updatePoundBalanceStmt->execute();

                // Determine the destination currency ID
                $destinationCurrencyId = null;
                switch ($destinationCurrency) {
                    case 'Euro':
                        $destinationCurrencyId = 1;
                        break;
                    case 'US Dollar':
                        $destinationCurrencyId = 3; // Assuming USD has currency_id 3
                        break;
                    case 'Japanese Yen':
                        $destinationCurrencyId = 4; // Assuming JPY has currency_id 4
                        break;
                }

                // Update destination balance
                if ($destinationCurrencyId !== null) {
                    $updateDestinationBalanceStmt = $db->prepare("UPDATE Currency_account SET balance = balance + :newDestinationBalance WHERE currency_id = :destinationCurrencyId");
                    $updateDestinationBalanceStmt->bindValue(':newDestinationBalance', $newDestinationBalance);
                    $updateDestinationBalanceStmt->bindValue(':destinationCurrencyId', $destinationCurrencyId);
                    $updateDestinationBalanceStmt->execute();
                }

                // Display success message
                echo "<p class='success-message'>Funds transferred successfully.</p>";
            } catch (PDOException $e) {
                echo "<p class='error-message'>Database connection failed: " . $e->getMessage() . "</p>";
            }
        }
        ?>
    </div>
</body>
</html>
