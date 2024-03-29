<p class="us">
	<b><?= html($config->company_name) ?></b>
	<?= $config->company_header ?>
</p>

<p class="them">
	<b><?= html($invoice->client->billing_name) ?></b>
	<?= html($invoice->client->address) ?>
</p>

<table class="meta">
	<tr>
		<th>Factuurdatum</th>
		<td><?= $invoice->billing_date ? date('j-n-Y', strtotime($invoice->billing_date)) : '<span style="font-weight: bold; color: red">PREVIEW</span>' ?></td>
	</tr>
	<tr>
		<th>Factuurnummer</th>
		<td><?= html($invoice->number_full) ?></td>
	</tr>
	<tr>
		<th>Beschrijving</th>
		<td><?= html($invoice->description) ?></td>
	</tr>
</table>
