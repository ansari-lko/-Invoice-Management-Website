<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $invoice_number = "INV-" . time();
    $issue_date = $_POST['issue_date'];
    $due_date   = $_POST['due_date'];
    $status     = 'pending';
    $total      = 0;

    $sql = "INSERT INTO invoices (client_id, invoice_number, issue_date, due_date, status, total) 
            VALUES ('$client_id','$invoice_number','$issue_date','$due_date','$status','$total')";
    if ($conn->query($sql)) {
        $invoice_id = $conn->insert_id;

        foreach ($_POST['items'] as $item) {
            $desc = $item['description'];
            $qty  = $item['quantity'];
            $price = $item['price'];
            $line_total = $qty * $price;
            $total += $line_total;

            $conn->query("INSERT INTO invoice_items (invoice_id, description, quantity, price) 
                          VALUES ('$invoice_id','$desc','$qty','$price')");
        }

        $conn->query("UPDATE invoices SET total='$total' WHERE id='$invoice_id'");

        echo "Invoice created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<form method="POST">
  <input type="number" name="client_id" placeholder="Client ID" required><br>
  <input type="date" name="issue_date" required><br>
  <input type="date" name="due_date" required><br>

  <h3>Items</h3>
  <div>
    <input type="text" name="items[0][description]" placeholder="Description" required>
    <input type="number" name="items[0][quantity]" placeholder="Qty" required>
    <input type="number" step="0.01" name="items[0][price]" placeholder="Price" required>
  </div>
  <button type="submit">Create Invoice</button>
</form>
