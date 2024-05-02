<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- You can link your CSS file here -->
    <title>Refund Requests</title>
    <style>
        /* CSS styles for the layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        header {
            background-color: #0077b6; /* Dark blue color */
            color: #fff;
            padding: 20px 0; /* Increased padding */
            text-align: center;
            border-bottom: 0px solid #fff; /* Border at the bottom */
        }

        header nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        header nav ul li {
            display: inline;
            margin-right: 20px;
        }

        header nav ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
        }

        header nav ul li a:hover {
            background-color: #005691; /* Slightly darker on hover */
        }

        .container {
            max-width: 1200px; /* Increased container width */
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .button-container {
            text-align: center;
        }

        .button-container button {
            padding: 5px 10px; /* Adjusted padding */
            margin: 0 2px; /* Adjusted margin */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px; /* Reduced font size */
        }

        .button-container button.approve {
            background-color: #28a745; /* Green color */
            color: #fff;
        }

        .button-container button.decline {
            background-color: #dc3545; /* Red color */
            color: #fff;
        }

        .button-container button:hover {
            opacity: 0.8;
        }

        /* Left and right background */
        .left-background,
        .right-background {
            content: "";
            display: block;
            position: fixed;
            top: 0;
            bottom: 0;
            width: 10%; /* Adjust as needed */
            background-color: #0077b6; /* Dark blue color */
            z-index: -1;
        }

        .left-background {
            left: 0;
        }

        .right-background {
            right: 0;
        }

        /* Highlight refund status */
        .pending {
            background-color: #ffdddd; /* Light red color */
        }

        .processing {
            background-color: #ffffcc; /* Light yellow color */
        }

        .approved {
            background-color: #ddffdd; /* Light green color */
        }

        .declined {
            background-color: #ffdddd; /* Light red color */
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="business-homepage.php">Back</a></li>
                <li><a href="business-logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="left-background"></div> 
    <div class="right-background"></div> 

    <div class="container">
        <h1>Refund Requests</h1>

        <table>
            <thead>
                <tr>
                    <th>Refund ID</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $db = new PDO("sqlite:C:/xampp/htdocs/Currency-Transfer-Application/Currency_Database.db");
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Query to fetch refund requests
                $sql = "SELECT * FROM refunds";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $refunds = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Display refund requests
                foreach ($refunds as $refund) {
                    echo "<tr class='{$refund['status']}'>"; // Add class based on status
                    echo "<td>{$refund['refund_id']}</td>";
                    echo "<td>{$refund['status']}</td>";
                    echo "<td>{$refund['date']}</td>";
                    echo "<td>{$refund['amount']}</td>";
                    echo "<td>{$refund['reason']}</td>";
                    echo "<td class='button-container'>";
                    
                    // Display action buttons based on refund status
                    if ($refund['status'] == 'Pending') {
                        echo "<form action='' method='post'>";
                        echo "<input type='hidden' name='refund_id' value='{$refund['refund_id']}'>";
                        echo "<input type='hidden' name='action' value='approve'>";
                        echo "<button class='approve' type='submit'>Approve</button>";
                        echo "</form>";
                        echo "<form action='' method='post'>";
                        echo "<input type='hidden' name='refund_id' value='{$refund['refund_id']}'>";
                        echo "<input type='hidden' name='action' value='decline'>";
                        echo "<button class='decline' type='submit'>Decline</button>";
                        echo "</form>";
                    } elseif ($refund['status'] == 'approved' || $refund['status'] == 'declined') {
                        echo "<span>Processed</span>"; // Show 'Processed' for already processed requests
                    } else {
                        echo "<span>Processed</span>"; // Show 'No Action' for other statuses
                    }
                    
                    echo "</td>";
                    echo "</tr>";
                }

                // Process refund requests
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $refund_id = $_POST['refund_id'];
                    $action = $_POST['action'];
                    $status = '';
                    
                    if ($action == 'approve') {
                        $status = 'Approved';
                    } elseif ($action == 'decline') {
                        $status = 'Declined';
                    }
                    
                    // Update status in the database
                    $update_sql = "UPDATE refunds SET status = :status WHERE refund_id = :refund_id";
                    $update_stmt = $db->prepare($update_sql);
                    $update_stmt->bindParam(':status', $status);
                    $update_stmt->bindParam(':refund_id', $refund_id);
                    $update_stmt->execute();
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
