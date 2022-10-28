<?php

use rdx\invoicer\Client;
use rdx\invoicer\InvoiceLine;

require 'inc.bootstrap.php';

$client = Client::find($_GET['client'] ?? 0);
$lines = InvoiceLine::all("invoice_id in (select id from invoices where client_id = ?) and description like ? order by invoice_id asc, id asc", [
	$_GET['client'] ?? 0,
	'%' . ($_GET['text'] ?? '') . '%',
]);
InvoiceLine::eager('invoice', $lines);
// print_r($lines);

require 'tpl.header.php';

?>
<style>
.invoice-line > td:first-child {
	padding-left: 1.5em;
}
</style>

<h1>
	<a href="<?= get_url('index') ?>">&lt;&lt;</a> |
	Search - <?= html($client) ?>
</h1>

<table>
	<thead>
		<tr>
			<th>Line</th>
			<th>Time/price</th>
		</tr>
	</thead>
	<tbody>
		<? $inv = null; $total = 0 ?>
		<? foreach ($lines as $line): ?>
			<? if ($inv != $line->invoice_id): ?>
				<tr class="invoice-start">
					<td colspan="3">
						<a href="<?= get_url('invoice', ['id' => $line->invoice_id]) ?>"><?= html($line->invoice->number_full) ?></a>
						<?= html($line->invoice->description) ?>
					</td>
				</tr>
				<? $inv = $line->invoice_id ?>
			<? endif ?>
			<tr class="invoice-line">
				<td><?= html($line->description) ?></td>
				<td><?= html($line->subtotal_pretty) ?></td>
			</tr>
			<? $total += $line->subtotal_money ?>
		<? endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td style="font-weight: bold"><?= html_money($total) ?></td>
		</tr>
	</tfoot>
</table>

<?php

require 'tpl.footer.php';
