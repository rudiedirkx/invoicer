<?php

use rdx\invoicer\Client;
use rdx\invoicer\Invoice;

require 'inc.bootstrap.php';

$client = Client::find($_GET['id'] ?? 0);
if (!$client) exit('No client');

if (isset($_POST['name'], $_POST['prefix'], $_POST['address'])) {
	$data = array_intersect_key($_POST, array_flip(['name', 'prefix', 'address']));
	$client->update($data);

	return do_redirect('client', ['id' => $client->id]);
}

require 'tpl.header.php';

$invoices = $client->invoices;

?>
<h1>
	<a href="<?= get_url('index') ?>">&lt;&lt;</a> |
	<?= html($client) ?>
</h1>

<form method="post" action>
	<table>
		<tr>
			<th>Name</th>
			<td><input name="name" value="<?= html($client->name) ?>" /></td>
		</tr>
		<tr>
			<th>Prefix</th>
			<td><input name="prefix" value="<?= html($client->prefix) ?>" /></td>
		</tr>
		<tr>
			<th>Address</th>
			<td><textarea name="address"><?= html($client->address) ?></textarea></td>
		</tr>
	</table>

	<p><button>Save</button></p>
</form>

<h2>Invoices</h2>

<ul>
	<? foreach ($invoices as $invoice): ?>
		<li>
			<a href="<?= get_url('invoice', ['id' => $invoice->id]) ?>"><?= html($invoice->number_full) ?></a>
			-
			<?= html($invoice->description) ?>
		</li>
	<? endforeach ?>
</ul>

<?php

require 'tpl.footer.php';
