<?php

use rdx\invoicer\Invoice;

Invoice::eagers($invoices, ['num_lines', 'total_subtotal']);

?>

<div class="vscroll" tabindex="-1">
	<table>
		<thead>
			<tr>
				<th>Number</th>
				<? if ($show_client): ?>
					<th>Client</th>
				<? endif ?>
				<th>Subject</th>
				<th>Summary</th>
			</tr>
		</thead>
		<tbody>
			<? $total = 0 ?>
			<? foreach ($invoices as $invoice): ?>
				<? $total += $invoice->total_subtotal_money ?>
				<tr>
					<td nowrap>
						<a href="<?= get_url('invoice', ['id' => $invoice->id]) ?>"><?= html($invoice->number_full) ?></a>
						<? if ($invoice->billing_date): ?>
							<span class="billed" title="<?= html($invoice->billing_date) ?>">&#10004;</span>
						<? endif ?>
					</td>
					<? if ($show_client): ?>
						<td><?= html($invoice->client) ?></td>
					<? endif ?>
					<td><?= html($invoice->description) ?></td>
					<td><?= html($invoice->summary) ?></td>
				</tr>
			<? endforeach ?>
		</tbody>
		<? if ($show_total): ?>
			<tfoot>
				<tr>
					<td colspan="2"></td>
					<td style="font-weight: bold"><?= html_money($total) ?></td>
				</tr>
			</tfoot>
		<? endif ?>
	</table>
</div>
