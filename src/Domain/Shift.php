<?php
namespace Scheduler\Domain;

use Scheduler\Domain\DAO;

class Shift extends DAO
{
	public $id;
	public $manager_id;
	public $employee_id;
	public $break;
	public $start_time;
	public $end_time;
	public $error_message;
		
    public function __construct($id = null) {
    	if ($id) {
			$results = DB::query("select * from shifts where id = $id");

			if (empty($results)) {
				throw new NotFoundException("Shift $id not found");
			}
		
			$this->id = $results[0]['id'];
			$this->manager_id = $results[0]['manager_id'];
			$this->employee_id = $results[0]['employee_id'];
			$this->break = $results[0]['break'];
			$this->start_time = $results[0]['start_time'];
			$this->end_time = $results[0]['end_time'];    	
    	}
    }
    
    public function save() {
    	$sql = '';
    	
    	if ($this->id) {
    		// Update
    		$sql = 'update shifts set manager_id = ' . $this->manager_id .
    		', employee_id = ' . ($this->employee_id === null ? 'NULL' : $this->employee_id) . 
    		', break = ' . ($this->break === null ? 0 : $this->break) .
    		', start_time = "' . $this->formattedTime($this->start_time) .
    		'", end_time = "' . $this->formattedTime($this->end_time) .
    		'" WHERE id = ' . $this->id;
    	} else {
    		// New Save
			$sql = 'insert into shifts ( manager_id, employee_id, break, start_time, end_time, created_at ) VALUES (' . 
				$this->manager_id . ', ' . 
    			($this->employee_id === null ? 'NULL' : $this->employee_id) . ', ' . 
    			($this->break === null ? 0 : $this->break) . ', "' . 
    			$this->formattedTime($this->start_time) . '", "' . 
    			$this->formattedTime($this->end_time) . 
    			'", NOW())';
    	}
    	
		try {
			return DB::exec($sql);
		} catch (Exception $e) {
			return false;
		}    	
    }
    
    public function formattedTime($time) {
    	return date("Y-m-d H:i:s", $time);
    }
    
    public static function getShiftsInRange($start_time, $end_time) {
    	$sql = 'select users.name as employee_name, shifts.break, shifts.start_time, shifts.end_time from shifts, users where shifts.start_time >= "' . self::formattedTime($start_time) .
    		'" AND shifts.end_time <= "' . self::formattedTime($end_time) . 
    		'" AND shifts.employee_id = users.id ORDER BY shifts.start_time';

    	return DB::query($sql);
    }
    
    public static function getShiftsForUserId($user_id, $start_time = false, $end_time = false) {
    	$sql = 'select users.name as manager_name, users.email as manager_email, users.phone as manager_phone, shifts.break, shifts.start_time, shifts.end_time from shifts, users where shifts.employee_id = ' . $user_id .
    		' AND shifts.manager_id = users.id';
    	
    	if ($start_time && $end_time) {
    		$sql .= ' AND shifts.start_time >= "' . self::formattedTime($start_time) .
    			'" AND shifts.end_time <= "' . self::formattedTime($end_time) . '"';
    	}
    	
    	$sql .= ' ORDER BY shifts.start_time';

    	return DB::query($sql);    	
    }
    
    public static function getEmployeesForTimes($start_time, $end_time) {
    	$sql = 'select users.id, users.name, shifts.start_time, shifts.end_time from users, shifts where shifts.start_time >= "' . self::formattedTime($start_time) .
    		'" AND shifts.end_time <= "' . self::formattedTime($end_time) . 
    		'" AND users.id = shifts.employee_id ORDER BY shifts.start_time';
    		
    	return DB::query($sql);
    }
}
