<?php

namespace rdx\invoicer\Types;

use rdx\invoicer\Invoice;
use rdx\invoicer\InvoiceLine;
use rdx\invoicer\InvoiceType;

class Time implements InvoiceType {

	public function getLabel() : string {
		return "Time";
	}

	public function getSummary(Invoice $invoice) : string {
		return html_money($invoice->total_subtotal / 60 * $invoice->rate) . ', ' . InvoiceLine::minutesToPretty($invoice->total_subtotal);
	}

	public function hasTime() : bool {
		return true;
	}

	public function getMoney(Invoice $invoice, int $subtotal) : int {
		return round($subtotal / 60 * $invoice->rate);
	}

	public function getPretty(int $subtotal) : string {
		return InvoiceLine::minutesToPretty($subtotal);
	}

	public function getPdfTemplate() : string {
		return __DIR__ . '/../../tpl.invoice__time.php';
	}

	public function printInvoiceHeader(Invoice $invoice) : void {
		?>
		|
		<input class="invoice-rate" name="rate" type="number" value="<?= html($invoice->rate) ?>" />
		/ h
		<?php
	}

	public function printLinesHeader(Invoice $invoice) : void {
		?>
		<tr>
			<th>Description</th>
			<th>Time</th>
		</tr>
		<?php
	}

	public function printLine(InvoiceLine $line, int $index) : void {
		$autofocus = $line->id ? '' : 'autofocus';
		?>
		<tr>
			<td><input class="invoice-line-desc" name="lines[<?= $line->id ?>][description]" value="<?= html($line->description) ?>" list="dl-descriptions" <?= $autofocus ?> /></td>
			<td><input class="invoice-line-subtotal" name="lines[<?= $line->id ?>][subtotal]" value="<?= html($line->id ? $line->subtotal_time : '') ?>" /></td>
		</tr>
		<?php
	}

	public function printLinesFooter(Invoice $invoice) : void {
		?>
		<tr>
			<td align="right">Netto total</td>
			<td><?= html($invoice->total_subtotal_time) ?></td>
		</tr>
		<tr>
			<td></td>
			<td nowrap><?= html_money($invoice->total_subtotal / 60 * $invoice->rate) ?></td>
		</tr>
		<?php
	}

}
