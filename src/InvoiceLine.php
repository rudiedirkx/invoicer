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



	protected function get_subtotal_pretty() {
		return self::minutesToPretty($this->subtotal);
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
			elseif ($m = self::prettyToMinutes($data['subtotal'])) {
				$data['subtotal'] = $m;
			}
			else {
				throw new RuntimeException(sprintf("Can't convert '%s' to minutes", $data['subtotal']));
			}
		}
	}

	static public function prettyToMinutes(string $pretty, string $locale = INVOICER_LOCALE) : ?int {
		[$_h, $_m] = self::PRETTY_MINUTES[$locale];
		$pattern = "#^(\d+)([$_h$_m])(?: (\d+)([$_m]))?$#";
		if (!preg_match($pattern, $pretty, $match)) {
			return null;
		}

		$factors = [$_h => 60];
		return $match[1] * ($factors[$match[2]] ?? 1) + ($match[3] ?? 0);
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
