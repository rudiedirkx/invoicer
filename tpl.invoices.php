<?php

use rdx\invoicer\Invoice;

Invoice::eagers($invoices, ['num_lines', 'total_subtotal']);

?>

<div class="vscroll" tabindex="-1">
	<table>
		<thead>
			<tr>
				<th>Number</th>
				<? if ($show_client ?? false): ?>
					<th>Client</th>
				<? endif ?>
				<th>Subject</th>
				<th>Summary</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($invoices as $invoice): ?>
				<tr>
					<td nowrap>
						<a href="<?= get_url('invoice', ['id' => $invoice->id]) ?>"><?= html($invoice->number_full) ?></a>
						<? if ($invoice->billing_date): ?>
							<span class="billed" title="<?= html($invoice->billing_date) ?>">&#10004;</span>
						<? endif ?>
					</td>
					<? if ($show_client ?? false): ?>
						<td><?= html($invoice->client) ?></td>
					<? endif ?>
					<td><?= html($invoice->description) ?></td>
					<td><?= html($invoice->summary) ?></td>
				</tr>
			<? endforeach ?>
		</tbody>
	</table>
</div>
