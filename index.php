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

require 'tpl.header.php';

?>
<h2>Invoices</h2>
<?php

$show_client = true;
include 'tpl.invoices.php';

?>

<h2>
	Clients
	<a href="<?= get_url('client', ['id' => 'new']) ?>">+</a>
</h2>
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
				<td><?= html($client->prefix) ?></td>
				<td><a href="<?= get_url('client', ['id' => $client->id]) ?>"><?= html($client) ?></a></td>
			</tr>
		<? endforeach ?>
	</tbody>
</table>

<h2>Search</h2>
<form method="get" action="search.php">
	<p>Client: <select name="client" required><?= html_options(array_column($clients, 'name', 'id')) ?></select></p>
	<p>Text: <input name="text" required /></p>
	<p><button>Search</button></p>
</form>

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
