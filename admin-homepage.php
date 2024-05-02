<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Center the title */
        .title-container {
            text-align: center;
        }
        
        .table-container {
            text-align: center;
            margin: 20px auto; 
            width: 80%; 
        }
        
        table {
            border-collapse: collapse;
            width: 100%; 
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Highlighting based on 'result' column */
        .highlight-green {
            background-color: #9effa3; /* Light green */
        }

        .highlight-red {
            background-color: #ff9c9c; /* Light red */
        }

        .highlight-orange {
            background-color: #ffd966; /* Light orange */
        }

        /* Selection box and submit button styles */
        .submit-container {
            text-align: center;
            margin-top: 20px;
        }

        select {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            width: 80%;
            height: 150px; /* Increased height */
            padding: 8px;
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
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin-homepage.php">Home</a></li>
                <li><a href="admin-suspension.php">Users</a></li>
                <li><a href="admin-logout.php">Sign Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="left-background"></div> 
    <div class="right-background"></div> 

    <div class="title-container">
        <h1>Flagged Transactions</h1>
    </div>

    <div class="table-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table>
                <thead>
                    <tr>
                        <th>Flagged Transaction ID</th>
                        <th>Transaction ID</th>
                        <th>Reason</th>
                        <th>Date Flagged</th>
                        <th>Result</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connect to the SQLite database
                    $db = new PDO("sqlite:C:/xampp\htdocs\Currency-Transfer-Application\Currency_Database.db");
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Fetch flagged transactions from the database
                    $sql = "SELECT flagged_transaction_id, transaction_id, reason, date_flagged, result FROM flagged_transaction";
                    $stmt = $db->query($sql);

                    // Display flagged transactions in the table
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Highlight based on 'result' column
                        $highlightClass = '';
                        $resultText = '';
                        switch ($row['result']) {
                            case 1:
                                $highlightClass = 'highlight-green';
                                $resultText = 'resolved';
                                break;
                            case 0:
                                $highlightClass = 'highlight-red';
                                $resultText = 'not resolved';
                                break;
                            case 3:
                                $highlightClass = 'highlight-orange';
                                $resultText = 'Under Review';
                                break;
                            default:
                                $highlightClass = '';
                                $resultText = 'Unknown';
                                break;
                        }

                        echo "<tr class='$highlightClass'>";
                        echo "<td>{$row['flagged_transaction_id']}</td>";
                        echo "<td>{$row['transaction_id']}</td>";
                        echo "<td>{$row['reason']}</td>";
                        echo "<td>{$row['date_flagged']}</td>";
                        echo "<td>$resultText</td>";
                        echo "<td><input type='checkbox' name='selected_transactions[]' value='{$row['flagged_transaction_id']}'></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="submit-container">
                <select name="new_result">
                    <option value="1">Resolved</option>
                    <option value="0">Not Resolved</option>
                    <option value="3">Under Review</option>
                </select>
                <br>
                <textarea name="reason" placeholder="Reason for submission"></textarea>
                <br>
                <input type="submit" name="submit_changes" value="Submit Changes">
            </div>
        </form>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_changes'])) {
    if (isset($_POST['selected_transactions']) && !empty($_POST['new_result']) && !empty($_POST['reason'])) {
        $newResult = $_POST['new_result'];
        $reason = $_POST['reason'];
        $selectedTransactions = implode(',', $_POST['selected_transactions']);

        // Connect to the SQLite database
        $db = new PDO("sqlite:C:/xampp\htdocs\Currency-Transfer-Application\Currency_Database.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update the 'result' column and add 'reason' for selected transactions
        $sql = "UPDATE flagged_transaction SET result = :newResult, reason = :reason WHERE flagged_transaction_id IN ($selectedTransactions)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':newResult', $newResult, PDO::PARAM_INT);
        $stmt->bindParam(':reason', $reason, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo "<script>alert('Changes submitted successfully!');</script>";
            echo "<script>window.location.href = 'admin-homepage.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error submitting changes. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No transactions selected, new result, or reason provided.');</script>";
    }
}
?>
