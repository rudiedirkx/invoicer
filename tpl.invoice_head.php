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
		border-spacing: 1px;
	}
	tr {
		vertical-align: top;
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
	.total .money {
		text-decoration: underline;
	}
</style>
