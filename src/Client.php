<?php

namespace rdx\invoicer;

class Client extends Model {

	static public $_table = 'clients';



	protected function relate_invoices() {
		return $this->to_many(Invoice::class, 'client_id')->order('number DESC');
	}



	public function __toString() {
		return $this->name;
	}

	protected function get_prefix_padded() {
		return str_pad($this->prefix, 2, '0', STR_PAD_LEFT);
	}

}
