<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT invoices.id, invoices.invoice_number, invoices.issue_date, invoices.due_date, invoices.status, invoices.total, clients.name 
        FROM invoices 
        JOIN clients ON invoices.client_id = clients.id 
        ORDER BY invoices.id DESC";
$result = $conn->query($sql);
?>
<h2>Invoice Dashboard</h2>
<a href="create_invoice.php">➕ Create New Invoice</a>
<table border="1" cellpadding="10">
  <tr>
    <th>Invoice #</th>
    <th>Client</th>
    <th>Issue Date</th>
    <th>Due Date</th>
    <th>Status</th>
    <th>Total</th>
    <th>Actions</th>
  </tr>
<?php while($row = $result->fetch_assoc()) { ?>
  <tr>
    <td><?= $row['invoice_number']; ?></td>
    <td><?= $row['name']; ?></td>
    <td><?= $row['issue_date']; ?></td>
    <td><?= $row['due_date']; ?></td>
    <td><?= ucfirst($row['status']); ?></td>
    <td>$<?= number_format($row['total'],2); ?></td>
    <td>
      <a href="view_invoice.php?id=<?= $row['id']; ?>">👁 View</a> |
      <a href="pdf_invoice.php?id=<?= $row['id']; ?>">⬇ PDF</a>
    </td>
  </tr>
<?php } ?>
</table>
