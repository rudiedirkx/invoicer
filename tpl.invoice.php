<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?= html($invoice->client) ?> - <?= html($invoice->number_full) ?></title>
		<style>
			@page {
				margin: 2.5cm 2.5cm;
			}

			html {
				font-family: sans-serif;
				line-height: 1.5;
				font-size: 16px;
			}

			p, table {
				margin: 0 0 1em;
			}

			table {
				border-collapse: collapse;
			}
			th, td {
				text-align: left;
				padding: 0;
				/*border: solid 1px orange;*/
			}

			.us, .them {
				white-space: pre-line;
			}
			.us {
				text-align: right;
			}

			.meta th {
				width: 4.5cm;
			}

			.lines {
				width: 100%;
			}
			tr.space-before > * {
				padding-top: 1em;
			}
			tr.space-after > * {
				padding-bottom: 1em;
			}

			.total, .money {
				font-weight: bold;
			}
		</style>
	</head>

	<body>
		<p class="us">
			<b>WebBlocks</b>
			Schootsestraat 73-27
			5616RB Eindhoven
			<b>KvK</b> 17266003
			<b>BTW</b> NL002184258B62
			<b>IBAN</b> NL60 INGB0007988151
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

		<? require $include; ?>
	</body>
</html>
