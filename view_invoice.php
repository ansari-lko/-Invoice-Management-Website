<?php
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT invoices.*, clients.name AS client_name, clients.email, clients.address 
        FROM invoices 
        JOIN clients ON invoices.client_id = clients.id 
        WHERE invoices.id='$id'";
$invoice = $conn->query($sql)->fetch_assoc();

$items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id='$id'");
?>
<h2>Invoice <?= $invoice['invoice_number']; ?></h2>
<p><b>Client:</b> <?= $invoice['client_name']; ?> (<?= $invoice['email']; ?>)</p>
<p><b>Address:</b> <?= $invoice['address']; ?></p>
<p><b>Issue Date:</b> <?= $invoice['issue_date']; ?> | <b>Due Date:</b> <?= $invoice['due_date']; ?></p>

<table border="1" cellpadding="10">
  <tr>
    <th>Description</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
  </tr>
  <?php while($item = $items->fetch_assoc()) { ?>
  <tr>
    <td><?= $item['description']; ?></td>
    <td><?= $item['quantity']; ?></td>
    <td>$<?= number_format($item['price'],2); ?></td>
    <td>$<?= number_format($item['quantity'] * $item['price'],2); ?></td>
  </tr>
  <?php } ?>
</table>
<h3>Total: $<?= number_format($invoice['total'],2); ?></h3>
