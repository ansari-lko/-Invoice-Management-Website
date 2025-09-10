composer require dompdf/dompdf
Install dompdf (best library for PHP PDF export).

<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

include 'db.php';

$id = $_GET['id'];
$sql = "SELECT invoices.*, clients.name AS client_name, clients.email, clients.address 
        FROM invoices 
        JOIN clients ON invoices.client_id = clients.id 
        WHERE invoices.id='$id'";
$invoice = $conn->query($sql)->fetch_assoc();

$items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id='$id'");

$html = "<h2>Invoice {$invoice['invoice_number']}</h2>
<p><b>Client:</b> {$invoice['client_name']} ({$invoice['email']})</p>
<p><b>Address:</b> {$invoice['address']}</p>
<p><b>Issue Date:</b> {$invoice['issue_date']} | <b>Due Date:</b> {$invoice['due_date']}</p>
<table border='1' cellpadding='10'>
<tr><th>Description</th><th>Qty</th><th>Price</th><th>Total</th></tr>";

while($item = $items->fetch_assoc()) {
    $line_total = $item['quantity'] * $item['price'];
    $html .= "<tr>
        <td>{$item['description']}</td>
        <td>{$item['quantity']}</td>
        <td>\${$item['price']}</td>
        <td>\${$line_total}</td>
    </tr>";
}
$html .= "</table><h3>Total: \${$invoice['total']}</h3>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("invoice_{$invoice['invoice_number']}.pdf");


