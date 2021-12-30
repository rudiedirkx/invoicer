<html>
	<head>
		<? require $root . '/tpl.invoice_head.php'; ?>
	</head>

	<body>
		<? require $root . '/tpl.invoice_top.php'; ?>

		<table class="lines">
			<tr class="space-after">
				<th>Beschrijving</th>
				<th>Prijs</th>
			</tr>
			<? foreach (array_values($invoice->lines) as $n => $line): ?>
				<tr>
					<td><?= html($line->description) ?></td>
					<td><?= html_money($line->subtotal) ?></td>
				</tr>
			<? endforeach ?>
			<tr class="space-before">
				<td>Subtotaal</td>
				<td class="money"><?= html_money($subtotal = $invoice->total_subtotal) ?></td>
			</tr>
			<tr class="space-before">
				<td>BTW <?= $vat ?>%</td>
				<td class="money"><?= html_money($vat = $subtotal * $vat/100) ?></td>
			</tr>
			<?if ($invoice->client->billing_footer): ?>
				<tr class="space-before client-footer">
					<td colspan="2"><?= $invoice->client->billing_footer ?></td>
				</tr>
			<? endif ?>
			<tr class="space-before total">
				<td>Totaal</td>
				<td class="money"><?= html_money($subtotal + $vat) ?></td>
			</tr>
		</table>

		<? require $root . '/tpl.invoice_bottom.php'; ?>
	</body>
</html>
