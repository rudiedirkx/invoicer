<?php

namespace rdx\invoicer;

interface InvoiceType {

	public function printLinesHeader(Invoice $invoice) : void;

	public function printLine(InvoiceLine $line, int $index) : void;

	public function printLinesFooter(Invoice $invoice) : void;

}
