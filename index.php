<?php

use rdx\invoicer\Client;
use rdx\invoicer\Invoice;

require 'inc.bootstrap.php';

if (isset($_POST['config'])) {
	$config->update($_POST['config']);

	return do_redirect('index');
}

$clients = Client::all('1 ORDER BY name');
// Client::eagers($clients, ['invoices', 'invoices.lines']);
$invoices = Invoice::all('1 ORDER BY updated_on DESC');
Invoice::eagers($invoices, ['num_lines', 'total_subtotal']);

require 'tpl.header.php';

?>
<h2>Invoices</h2>
<table>
	<thead>
		<tr>
			<th>Number</th>
			<th>Client</th>
			<th>Subject</th>
			<th>Summary</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($invoices as $invoice): ?>
			<tr>
				<td><a href="<?= get_url('invoice', ['id' => $invoice->id]) ?>"><?= html($invoice->number_full) ?></a></td>
				<td><?= html($invoice->client) ?></td>
				<td><?= html($invoice->description) ?></td>
				<td><?= html($invoice->summary) ?></td>
			</tr>
		<? endforeach ?>
	</tbody>
</table>

<h2>Clients</h2>
<table>
	<thead>
		<tr>
			<th>Prefix</th>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($clients as $client): ?>
			<tr>
				<td><a href="<?= get_url('client', ['id' => $client->id]) ?>"><?= html($client->prefix) ?></a></td>
				<td><?= html($client) ?></td>
			</tr>
		<? endforeach ?>
	</tbody>
</table>

<h2>Config</h2>
<form method="post" action>
	<table>
		<tr>
			<th>Company name</th>
			<td><input class="config-wide" name="config[company_name]" value="<?= html($config->company_name) ?>" /></td>
		</tr>
		<tr>
			<th>Company header</th>
			<td><textarea class="config-wide" name="config[company_header]" rows="5"><?= html($config->company_header) ?></textarea></td>
		</tr>
		<tr>
			<th>VAT</th>
			<td><input class="config-number" name="config[vat]" value="<?= html($config->vat) ?>" type="number" /> %</td>
		</tr>
	</table>

	<p><button>Save</button></p>
</form>

<?php

require 'tpl.footer.php';
