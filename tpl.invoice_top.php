<p class="us">
	<b><?= html($config->company_name) ?></b>
	<?= $config->company_header ?>
</p>

<p class="them">
	<b><?= html($invoice->client->name) ?></b>
	<?= html($invoice->client->address) ?>
</p>

<table class="meta">
	<tr>
		<th>Factuurdatum</th>
		<td><?= date('j-n-Y', strtotime($invoice->billing_date)) ?></td>
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
