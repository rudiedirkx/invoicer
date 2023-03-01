<?php

use rdx\invoicer\Invoice;
use rdx\invoicer\InvoiceLine;

require 'inc.bootstrap.php';

$invoice = Invoice::find($_GET['id'] ?? 0);
if (!$invoice) exit('No invoice');

if (isset($_POST['number'], $_POST['description'], $_POST['billing_date'], $_POST['paid_date'], $_POST['lines'])) {
	$action = $_POST['_action'] ?? '';

	if ($action === 'delete') {
		$invoice->delete();
		return do_redirect('client', ['id' => $invoice->client_id]);
	}

	$lines = $invoice->lines;
	foreach ($_POST['lines'] as $id => $line) {
		$data = array_intersect_key($line, array_flip(['day', 'description', 'subtotal']));
		$empty = !strlen(trim($data['description'] ?? '') . trim($data['subtotal'] ?? '', ' 0'));
		if ($id == 0) {
			if (!$empty) {
				InvoiceLine::insert(['invoice_id' => $invoice->id] + $data);
			}
		}
		elseif (isset($lines[$id])) {
			if ($empty) {
				$lines[$id]->delete();
			}
			else {
				$lines[$id]->update($data);
			}
		}
	}

	$data = array_intersect_key($_POST, array_flip(['number', 'description', 'billing_date', 'paid_date', 'rate']));
	$invoice->update($data);

	if ($action === 'finish') {
		$dompdf = $invoice->renderPdf();
		$dompdf->stream($invoice->filename, ['Attachment' => 1]);
		exit;
	}

	return do_redirect('invoice', ['id' => $invoice->id]);
}

if (isset($_POST['_copy'], $_POST['number'], $_POST['description'])) {
	$id = $invoice->copy([
		'number' => $_POST['number'],
		'description' => $_POST['description'],
	]);

	return do_redirect('invoice', ['id' => $id]);
}

require 'tpl.header.php';

?>
<h1>
	<a href="<?= get_url('index') ?>">&lt;&lt;</a> |
	<a href="<?= get_url('client', ['id' => $invoice->client_id]) ?>"><?= html($invoice->client) ?></a> -
	<?= html($invoice->number_full) ?> -
	<a href="<?= get_url('pdf', ['id' => $invoice->id]) ?>">PDF</a>
</h1>

<form method="post" action>
	<p>
		<input class="invoice-number" name="number" value="<?= html($invoice->number) ?>" type="number" />
		<input class="invoice-desc" name="description" value="<?= html($invoice->description) ?>" placeholder="Invoice description..." />
		<? $invoice->typer->printInvoiceHeader($invoice) ?>
	</p>

	<table>
		<thead>
			<? $invoice->typer->printLinesHeader($invoice) ?>
		</thead>
		<tbody>
			<? foreach ([...$invoice->lines, new InvoiceLine(['id' => 0])] as $i => $line): ?>
				<? $invoice->typer->printLine($line, $i) ?>
			<? endforeach ?>
		</tbody>
		<tfoot>
			<? $invoice->typer->printLinesFooter($invoice) ?>
		</tfoot>
	</table>

	<datalist id="dl-descriptions">
		<? foreach ($invoice->searchable_descriptions as $desc): ?>
			<option value="<?= html($desc) ?>"></option>
		<? endforeach ?>
	</datalist>

	<p>
		<span class="mobile-line">
			<button name="_action" value="save">Save</button>
		</span>
		<!-- &nbsp; | &nbsp; -->
		<span class="mobile-line">
			Billing date: <input name="billing_date" type="date" value="<?= html($invoice->billing_date) ?>" />
			<button name="_action" value="finish">Finish &amp; download</button>
		</span>
		<span class="mobile-line">
			Paid: <input name="paid_date" type="date" value="<?= html($invoice->paid_date) ?>" />
		</span>
		<!-- &nbsp; | &nbsp; -->
		<span class="mobile-line">
			<button name="_action" value="delete" style="color: #c00" onclick="return confirm('Sure??') && confirm('Nooooo. Yes?')">Delete</button>
		</span>
	</p>
</form>

<h2>Copy</h2>
<form method="post" action>
	<table>
		<tr>
			<th>New number</th>
			<td>
				<input type="hidden" name="number" value="<?= html($invoice->next_number) ?>" />
				<?= html($invoice->next_number) ?>
			</td>
		</tr>
		<tr>
			<th>Description</th>
			<td>
				<input class="invoice-desc" name="description" value="<?= html($invoice->next_description) ?>" />
			</td>
		</tr>
	</table>

	<p><button name="_copy" value="">Copy</button></p>
</form>
<?php

require 'tpl.footer.php';
