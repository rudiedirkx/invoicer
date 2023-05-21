<?php

use rdx\invoicer\Invoice;

Invoice::eagers($invoices, ['num_lines', 'total_subtotal']);

?>
<style>
body:not(.show-dates) .dates-date {
	display: none;
}
</style>

<div class="vscroll" tabindex="-1">
	<table>
		<thead>
			<tr>
				<th>Num</th>
				<th class="c">
					<input type="checkbox" onchange="document.body.classList.toggle('show-dates')" title="Toggle showing dates" />
				</th>
				<th class="dates-date r" nowrap>Billed</th>
				<th class="dates-date" nowrap>Billing</th>
				<th class="dates-date" nowrap>Paid</th>
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
					<td nowrap><a href="<?= get_url('invoice', ['id' => $invoice->id]) ?>"><?= html($invoice->number_full) ?></a></td>
					<td class="c">
						<? if ($invoice->paid_date): ?>
							<span class="paid" title="<?= out_date($invoice->paid_date) ?> (<?= out_date($invoice->billing_date) ?>)">&#128176;</span>
						<? elseif ($invoice->billing_date): ?>
							<span class="billed" title="<?= out_date($invoice->billing_date) ?>">&#128338;</span>
						<? endif ?>
					</td>
					<td class="dates-date r" nowrap><?= html_money(round($invoice->total_subtotal_money_inc_vat), 0, currency: false) ?></td>
					<td class="dates-date" nowrap><?= $invoice->billing_date ?></td>
					<td class="dates-date" nowrap><?= $invoice->paid_date ?></td>
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
					<td colspan="3"></td>
					<td colspan="2" class="dates-date"></td>
					<td style="font-weight: bold"><?= html_money($total, 0) ?></td>
				</tr>
			</tfoot>
		<? endif ?>
	</table>
</div>
