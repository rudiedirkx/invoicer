<?php

use rdx\invoicer\Client;
use rdx\invoicer\Invoice;

require 'inc.bootstrap.php';

$client = ($_GET['id'] ?? 0) === 'new' ? new Client(['name' => 'NEW']) : Client::find($_GET['id'] ?? 0);
if (!$client) exit('No client');

if (isset($_POST['name'], $_POST['prefix'], $_POST['billing_name'], $_POST['billing_footer'], $_POST['address'], $_POST['notes'])) {
	$data = array_intersect_key($_POST, array_flip(['name', 'prefix', 'billing_name', 'billing_footer', 'address', 'notes']));
	if ($client->id) {
		$client->update($data);
	}
	else {
		$client->id = Client::insert($data);
	}

	return do_redirect('client', ['id' => $client->id]);
}

if (isset($_POST['new_invoice_type'])) {
	$id = Invoice::insert([
		'type' => $_POST['new_invoice_type'],
		'client_id' => $client->id,
		'number' => 0,
	]);

	return do_redirect('invoice', ['id' => $id]);
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
			<td><input class="client-wide" name="name" value="<?= html($client->name) ?>" /></td>
		</tr>
		<tr>
			<th>Prefix</th>
			<td><input class="client-wide" name="prefix" value="<?= html($client->prefix) ?>" /></td>
		</tr>
		<tr>
			<th>Billing name</th>
			<td><input class="client-wide" name="billing_name" value="<?= html($client->billing_name) ?>" /></td>
		</tr>
		<tr>
			<th>Address</th>
			<td><textarea class="client-wide" name="address" rows="3"><?= html($client->address) ?></textarea></td>
		</tr>
		<tr>
			<th>Billing footer</th>
			<td><textarea class="client-wide" name="billing_footer" rows="3"><?= html($client->billing_footer) ?></textarea></td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="client-wider" name="notes" rows="3" placeholder="Notes..."><?= html($client->notes) ?></textarea></td>
		</tr>
	</table>

	<p><button>Save</button></p>
</form>

<h2>Search</h2>

<form method="get" action="search.php">
	<input type="hidden" name="client" value="<?= $client->id ?>" />
	<p>Text: <input name="text" required /></p>
	<p><button>Search</button></p>
</form>

<h2>Invoices</h2>

<form method="post" action>
	<p>New invoice: <select name="new_invoice_type"><?= html_options(Invoice::getTypesOptions()) ?></select> <button>Create</button></p>
</form>

<?php

$show_client = false;
include 'tpl.invoices.php';

?>

<?php

require 'tpl.footer.php';
