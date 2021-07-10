<?php

namespace rdx\invoicer;

use db_generic;
use ReflectionClass;

class Config {

	const TABLE = 'config';

	protected $db;

	public $company_name;
	public $company_header;
	public $vat;

	static public function create(db_generic $db) : self {
		$store = $db->select_fields(self::TABLE, 'name, value', '1');

		$config = new self($db);
		foreach ((new ReflectionClass(__CLASS__))->getProperties() as $prop) {
			if (!$prop->isPublic()) continue;
			$name = $prop->getName();

			if (isset($store[$name])) {
				$config->$name = $store[$name];
			}
			else {
				$config->add($name, '');
				$config->$name = '';
			}
		}

		return $config;
	}

	public function __construct(db_generic $db) {
		$this->db = $db;
	}

	public function add(string $name, string $value) : void {
		$this->db->insert(self::TABLE, compact('name', 'value'));
	}

	public function update(array $config) {
		foreach ($config as $name => $value) {
			$this->set($name, $value);
		}
	}

	public function set(string $name, string $value) : void {
		$this->db->update(self::TABLE, compact('value'), compact('name'));
	}

}
