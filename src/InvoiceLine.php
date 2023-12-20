<?php

namespace rdx\invoicer;

use RuntimeException;

class InvoiceLine extends Model {

	const PRETTY_MINUTES = [
		'nl' => ['u', 'm'],
		'en' => ['h', 'm'],
	];
	const MONEY_SEPARATORS = [
		'nl' => ['.', ','],
		'en' => [',', '.'],
	];

	static public $_table = 'invoice_lines';



	protected function get_subtotal_time() {
		return self::minutesToPretty($this->subtotal);
	}

	protected function get_subtotal_pretty() {
		return $this->invoice->typer->getPretty($this->subtotal);
	}

	protected function get_subtotal_money() {
		return $this->invoice->typer->getMoney($this->invoice, $this->subtotal);
	}



	static public function presave(array &$data) {
		self::presaveTrim($data);
		self::presaveNull($data, ['day']);

		if ($data['subtotal']) {
			if (filter_var($data['subtotal'], FILTER_VALIDATE_INT) !== false) {
				// minutes or money
			}
			elseif (is_numeric($data['subtotal'])) {
				// hours
				$data['subtotal'] *= 60;
			}
			elseif (($m = self::prettyToMinutes($data['subtotal'])) !== null) {
				$data['subtotal'] = $m;
			}
			else {
				throw new RuntimeException(sprintf("Can't convert '%s' to minutes", $data['subtotal']));
			}
		}
	}

	public function init() {
		if (isset($this->subtotal)) {
			$this->subtotal = (int) $this->subtotal;
		}
	}

	static public function prettyToMinutes(string $pretty, string $locale = INVOICER_LOCALE) : ?int {
		[$_h, $_m] = self::PRETTY_MINUTES[$locale];

		// 30m, 30
		if (preg_match("#^(\d+)(?:$_m)?$#", $pretty, $match)) {
			return (int) $match[1];
		}

		// 1u
		if (preg_match("#^(\d+)$_h$#", $pretty, $match)) {
			return 60 * $match[1];
		}

		// 1u 15m, 1u 15
		if (preg_match("#^(\d+)$_h (\d+)(?:$_m)?$#", $pretty, $match)) {
			return 60 * $match[1] + $match[2];
		}

		return null;
	}

	static public function minutesToPretty(int $mins, string $locale = INVOICER_LOCALE) : string {
		[$_h, $_m] = self::PRETTY_MINUTES[$locale];

		$h = floor($mins / 60);
		if ($h == 0) {
			return $mins . $_m;
		}

		$m = $mins - $h * 60;
		if ($m == 0) {
			return $h . $_h;
		}

		return "$h$_h $m$_m";
	}



	protected function relate_invoice() {
		return $this->to_one(Invoice::class, 'invoice_id');
	}

}
