<?php

namespace rdx\invoicer;

use Dompdf\Dompdf;
use Dompdf\Options;

class Invoice extends Model {

	static public $_table = 'invoices';

	static public $types = [
		'day_time' => Types\DayTime::class,
		'specific' => Types\Specific::class,
	];

	static public function getTypesOptions() : array {
		return array_map(function($class) {
			return (new $class())->getLabel();
		}, self::$types);
	}



	public function copy( array $override ) : int {
		return self::insert($override + [
			'updated_on' => time(),
			'client_id' => $this->client_id,
			'number' => $this->number,
			'description' => $this->description,
			'type' => $this->type,
			'rate' => $this->rate,
		]);
	}

	public function renderHtml() : string {
		$html = call_user_func(function(Invoice $invoice) {
			$config = $GLOBALS['config'];
			$vat = (int) $config->vat;
			$root = dirname(__DIR__);
			ob_start();
			require $this->typer->getPdfTemplate();
			return ob_get_clean();
		}, $this);

		return $html;
	}

	public function renderPdf() : Dompdf {
		$html = $this->renderHtml();

		$dompdf = new Dompdf(new Options(['defaultPaperSize' => 'a4']));
		$dompdf->loadHtml($html);
		$dompdf->render();

		return $dompdf;
	}

	public function getTypedSummary() : string {
		return $this->typer->getSummary($this);
	}

	static public function presave(array &$data) {
		self::presaveTrim($data);
		self::presaveNull($data, ['rate', 'billing_date']);

		$data['updated_on'] = time();
	}



	protected function relate_client() {
		return $this->to_one(Client::class, 'client_id');
	}

	protected function relate_lines() {
		return $this->to_many(InvoiceLine::class, 'invoice_id')->order('day asc, id asc');
	}

	protected function relate_num_lines() {
		return $this->to_count(InvoiceLine::$_table, 'invoice_id');
	}

	protected function relate_total_subtotal() {
		return $this->to_aggregate(InvoiceLine::$_table, 'sum(subtotal)', 'invoice_id');
	}



	protected function get_typer() : InvoiceType {
		$class = self::$types[$this->type];
		return new $class();
	}

	protected function get_filename() {
		return get_ascii("{$this->client->name} {$this->number_full}", '-') . '.pdf';
	}

	protected function get_summary() {
		return rtrim($this->num_lines . ' lines, ' . $this->getTypedSummary(), ', ');
	}

	protected function get_number_full() {
		return $this->client->prefix_padded . $this->number_padded;
	}

	protected function get_number_padded() {
		return str_pad($this->number, 3, '0', STR_PAD_LEFT);
	}

	protected function get_total_subtotal_pretty() {
		return InvoiceLine::minutesToPretty($this->total_subtotal);
	}

}
