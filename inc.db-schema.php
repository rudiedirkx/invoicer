<?php

return [
	'version' => 5,
	'tables' => [
		'config' => [
			'name',
			'value',
		],
		'clients' => [
			'id' => ['pk' => true],
			'name',
			'prefix',
			'address',
		],
		'invoices' => [
			'id' => ['pk' => true],
			'updated_on' => ['unsigned' => true],
			'client_id' => ['unsigned' => true, 'references' => ['clients', 'id', 'cascade']],
			'type',
			'number',
			'description',
			'rate' => ['unsigned' => true],
			'billing_date' => ['type' => 'date'],
		],
		'invoice_lines' => [
			'id' => ['pk' => true],
			'invoice_id' => ['unsigned' => true, 'references' => ['invoices', 'id', 'cascade']],
			'day' => ['unsigned' => true],
			'description',
			'subtotal' => ['unsigned' => true],
		],
	],
];
