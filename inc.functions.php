<?php

use rdx\invoicer\InvoiceLine;

function get_ascii( string $raw, string $delim = '-' ) {
	return trim(preg_replace('#[' . preg_quote($delim, '#') . ']+#', $delim, preg_replace('#[^a-z0-9]+#i', $delim, $raw)), $delim);
}

function get_url( $path, $query = array() ) {
	$query = $query ? '?' . http_build_query($query) : '';
	$path = $path ? $path . '.php' : basename($_SERVER['SCRIPT_NAME']);
	return $path . $query;
}

function do_redirect( $path, $query = array() ) {
	$url = get_url($path, $query);
	header('Location: ' . $url);
}

function html_asset( $src ) {
	$buster = '?_' . filemtime($src);
	return $src . $buster;
}

function html_money( float $amount, $decimals = 2, string $locale = INVOICER_LOCALE ) {
	$seps = InvoiceLine::MONEY_SEPARATORS[$locale];
	return INVOICER_CURRENCY . ' ' . number_format($amount, $decimals, $seps[1], $seps[0]);
}

function html_options( $options, $selected = null, $empty = '' ) {
	$selected = (array) $selected;

	$html = '';
	$empty && $html .= '<option value="">' . $empty;
	foreach ( $options AS $value => $label ) {
		$isSelected = in_array($value, $selected) ? ' selected' : '';
		$html .= '<option value="' . html($value) . '"' . $isSelected . '>' . html($label) . '</option>';
	}
	return $html;
}

function html( $text ) {
	return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8') ?: htmlspecialchars((string)$text, ENT_QUOTES, 'ISO-8859-1');
}
