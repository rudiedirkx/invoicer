<?php

namespace rdx\invoicer;

use Dompdf\Dompdf;
use Dompdf\Options;

class Invoice extends Model {

	const MONTHS = [
		'nl' => ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
		'en' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	];

	static public $_table = 'invoices';

	static public $types = [
		'time' => Types\Time::class,
		'day_time' => Types\DayTime::class,
		'specific' => Types\Specific::class,
	];

	static public function getTypesOptions() : array {
		return array_map(function($class) {
			return (new $class())->getLabel();
		}, self::$types);
	}



	public function copy( array $override, bool $copyLines = false ) : int {
		$id = self::insert($override + [
			'updated_on' => time(),
			'client_id' => $this->client_id,
			'number' => $this->number,
			'description' => $this->description,
			'type' => $this->type,
			'rate' => $this->rate,
		]);

		if ($copyLines) {
			foreach ($this->lines as $line) {
				InvoiceLine::insert([
					'invoice_id' => $id,
					'day' => $line->day,
					'description' => $line->description,
					'subtotal' => $line->subtotal,
				]);
			}
		}

		return $id;
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



	protected function get_searchable_descriptions() {
		$descs = array_unique(array_column($this->lines, 'description'));
		natcasesort($descs);
		return $descs;
	}

	protected function get_next_number() {
		return $this->number + 1;
	}

	protected function get_next_description() {
		$months = self::MONTHS[INVOICER_LOCALE];
		$pattern = '#\b(' . implode('|', array_map('preg_quote', $months)) . ') (\d{4})\b#i';
		if (preg_match($pattern, $this->description, $match)) {
			$m = array_search($match[1], $months);
			$upper = false;
			if ($m === false) {
				$m = array_search(strtolower($match[1]), $months);
				$upper = $m !== false;
			}
			if ($m !== false) {
				$y = $match[2];
				$m++;
				if ($m == count($months)) {
					$m = 0;
					$y++;
				}

				$month = $months[$m];
				if ($upper) {
					$month = mb_strtoupper($month[0]) . substr($month, 1);
				}
				return str_replace($match[0], $month . ' ' . $y, $this->description);
			}
		}

		return $this->description;
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

	protected function get_total_subtotal_money() {
		return $this->typer->getMoney($this, $this->total_subtotal);
	}

	protected function get_total_subtotal_time() {
		return InvoiceLine::minutesToPretty($this->total_subtotal);
	}

}
