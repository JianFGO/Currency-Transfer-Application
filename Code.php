// Assuming $sourceAccountId, $destinationAccountId, $transferAmount, and $exchangeRate are obtained from user input or elsewhere

try {
    $db->beginTransaction();

    // Deduct funds from the source account
    $stmt = $db->prepare("UPDATE Currency SET balance = balance - :transferAmount WHERE currency_id = :sourceAccountId");
    $stmt->bindParam(':transferAmount', $transferAmount);
    $stmt->bindParam(':sourceAccountId', $sourceAccountId);
    $stmt->execute();

    // Add funds to the destination account
    $convertedAmount = $transferAmount * $exchangeRate;
    $stmt = $db->prepare("UPDATE Currency SET balance = balance + :convertedAmount WHERE currency_id = :destinationAccountId");
    $stmt->bindParam(':convertedAmount', $convertedAmount);
    $stmt->bindParam(':destinationAccountId', $destinationAccountId);
    $stmt->execute();

    // Log the transaction
    $stmt = $db->prepare("INSERT INTO TransactionLog (source_account_id, destination_account_id, transfer_amount, exchange_rate) VALUES (:sourceAccountId, :destinationAccountId, :transferAmount, :exchangeRate)");
    $stmt->bindParam(':sourceAccountId', $sourceAccountId);
    $stmt->bindParam(':destinationAccountId', $destinationAccountId);
    $stmt->bindParam(':transferAmount', $transferAmount);
    $stmt->bindParam(':exchangeRate', $exchangeRate);
    $stmt->execute();

    $db->commit();
    echo "Funds transferred successfully!";
} catch (Exception $e) {
    $db->rollBack();
    echo "Error: " . $e->getMessage();
}
