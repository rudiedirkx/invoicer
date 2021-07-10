<?php

namespace rdx\invoicer\Types;

use rdx\invoicer\Invoice;
use rdx\invoicer\InvoiceLine;
use rdx\invoicer\InvoiceType;

class DayTime implements InvoiceType {

	public function getSummary(Invoice $invoice) : string {
		return html_money($invoice->total_subtotal / 60 * $invoice->rate) . ', ' . InvoiceLine::minutesToPretty($invoice->total_subtotal);
	}

	public function printLinesHeader(Invoice $invoice) : void {
		?>
		<tr>
			<th>Day</th>
			<th>Description</th>
			<th>Time</th>
			<th>Rate</th>
		</tr>
		<?php
	}

	public function printLine(InvoiceLine $line, int $index) : void {
		?>
		<tr>
			<td><input class="invoice-line-day" name="lines[<?= $line->id ?>][day]" value="<?= html($line->day) ?>" type="number" /></td>
			<td><input class="invoice-line-desc" name="lines[<?= $line->id ?>][description]" value="<?= html($line->description) ?>" /></td>
			<td><input class="invoice-line-subtotal" name="lines[<?= $line->id ?>][subtotal]" value="<?= html($line->id ? $line->subtotal_pretty : '') ?>" /></td>
			<td>
				<? if ($index == 0): ?>
					<input class="invoice-rate" name="rate" type="number" value="<?= html($invoice->rate) ?>" />
					/ hour
				<? endif ?>
			</td>
		</tr>
		<?php
	}

	public function printLinesFooter(Invoice $invoice) : void {
		?>
		<tr>
			<td></td>
			<td align="right">Netto total</td>
			<td><?= html($invoice->total_subtotal_pretty) ?></td>
			<td><?= html_money($invoice->total_subtotal / 60 * $invoice->rate) ?></td>
		</tr>
		<?php
	}

}
