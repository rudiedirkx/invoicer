<?php

namespace rdx\invoicer\Types;

use rdx\invoicer\Invoice;
use rdx\invoicer\InvoiceLine;
use rdx\invoicer\InvoiceType;

class Specific implements InvoiceType {

	public function getLabel() : string {
		return "Specifics";
	}

	public function getSummary(Invoice $invoice) : string {
		return html_money($invoice->total_subtotal);
	}

	public function printLinesHeader(Invoice $invoice) : void {
		?>
		<tr>
			<th>Description</th>
			<th>Price</th>
		</tr>
		<?php
	}

	public function printLine(InvoiceLine $line, int $index) : void {
		?>
		<tr>
			<td><input class="invoice-line-desc" name="lines[<?= $line->id ?>][description]" value="<?= html($line->description) ?>" /></td>
			<td><input class="invoice-line-subtotal" name="lines[<?= $line->id ?>][subtotal]" value="<?= html($line->subtotal) ?>" /></td>
		</tr>
		<?php
	}

	public function printLinesFooter(Invoice $invoice) : void {
		?>
		<tr>
			<td align="right">Netto total</td>
			<td><?= html_money($invoice->total_subtotal) ?></td>
		</tr>
		<?php
	}

}
