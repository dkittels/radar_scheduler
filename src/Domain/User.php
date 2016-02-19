<?php
namespace Scheduler\Domain;

use Scheduler\Domain\DAO;

class User extends DAO
{
	public $id;
	public $name;
	public $role;
	public $email;
	public $phone;
	
    public function __construct($id) {
		$results = DB::query("select * from users where id = $id");

		if (empty($results)) {
			throw new NotFoundException("User $id not found");
		}
		
		$this->id = $results[0]['id'];
		$this->name = $results[0]['name'];
		$this->role = $results[0]['role'];
		$this->email = $results[0]['email'];
		$this->phone = $results[0]['phone'];
    }
    
    public function isManager() {
    	return $this->role == 'manager';
    }
}
