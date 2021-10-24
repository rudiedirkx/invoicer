<?php

namespace rdx\invoicer;

interface InvoiceType {

	public function getLabel() : string;

	public function getSummary(Invoice $invoice) : string;

	public function getMoney(Invoice $invoice, int $subtotal) : int;

	public function getPretty(int $subtotal) : string;

	public function getPdfTemplate() : string;

	public function printInvoiceHeader(Invoice $invoice) : void;

	public function printLinesHeader(Invoice $invoice) : void;

	public function printLine(InvoiceLine $line, int $index) : void;

	public function printLinesFooter(Invoice $invoice) : void;

}
