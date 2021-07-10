<html>
	<head>
		<? require __DIR__ . '/tpl.invoice_head.php'; ?>
	</head>

	<body>
		<? require __DIR__ . '/tpl.invoice_top.php'; ?>

		<table class="lines">
			<tr class="space-after">
				<th>Dag</th>
				<th>Beschrijving</th>
				<th>Aantal</th>
				<th>Uurtarief</th>
			</tr>
			<? $day = 0 ?>
			<? foreach (array_values($invoice->lines) as $n => $line): ?>
				<tr>
					<td><?= $day == $line->day ? '' : html($line->day) ?></td>
					<td><?= html($line->description) ?></td>
					<td><?= html($line->subtotal_pretty) ?></td>
					<td><?= $n > 0 ? '' : html_money($invoice->rate) ?></td>
				</tr>
				<? $day = $line->day ?>
			<? endforeach ?>
			<tr class="space-before">
				<td colspan="2">Subtotaal</td>
				<td><?= html($invoice->total_subtotal_pretty) ?></td>
				<td class="money"><?= html_money($subtotal = $invoice->total_subtotal / 60 * $invoice->rate) ?></td>
			</tr>
			<tr class="space-before">
				<td colspan="2">BTW <?= $vat ?>%</td>
				<td></td>
				<td class="money"><?= html_money($vat = $subtotal * $vat/100) ?></td>
			</tr>
			<tr class="space-before total">
				<td colspan="2">Subtotaal</td>
				<td></td>
				<td class="money"><?= html_money($subtotal + $vat) ?></td>
			</tr>
		</table>
	</body>
</html>
