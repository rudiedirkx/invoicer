<?php

use rdx\invoicer\Invoice;

require 'inc.bootstrap.php';

$invoice = Invoice::find($_GET['id'] ?? 0);
if (!$invoice) exit('No invoice');

$dompdf = $invoice->renderPdf();
$dompdf->stream('preview.pdf', ['Attachment' => 0]);
