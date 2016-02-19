<?php
namespace Scheduler\Domain;

use Scheduler\Domain\User;
use Scheduler\Domain\Shift;
use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;
use Exception;

class ApplicationService
{
	// Was going to make this a CONST but then found out I'm only running PHP 5.5 right now
	private $manager_actions = array(
		'readUser',
		'createShift',
		'updateShift',
		'readSchedule'
	);
	
	private $user;
	private $payload;
	
	public function __call($method, $arguments) {
		$this->payload = new Payload();
		
		// Authorize user
		if (!is_numeric($arguments[0]['user_id'])) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_AUTHENTICATED)
				->setInput($input)
				->setMessages([
					'user_id' => 'user_id is not an integer or is not present'
				]);
		}

		try {
			$this->user = new User($arguments[0]['user_id']);
		} catch (Exception $e) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_VALID)
				->setInput($input)
				->setMessages([
					'user_id' => 'user_id is not valid'
				]);
		}
		
		if(method_exists($this, $method)) {
			if (in_array($method, $this->manager_actions) && !$this->user->isManager()) {
				return $this->payload
					->setStatus(PayloadStatus::NOT_VALID)
					->setInput($input)
					->setMessages([
						'user' => $user->name . ' is not authorized for this request'
					]);
			}
            return call_user_func_array(array($this,$method),$arguments);
        }
	}
	
	private function authUser(array $input) {
		$this->user = new User($input['user_id']);
	}
		
	private function readShift(array $input) {
		$shift = new Shift($input['id']);
		
		if (!$shift) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_FOUND)
				->setInput($input);
		} else if ($shift->user_id != $this->user->id && !$this->user->isManager()) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_VALID)
				->setInput($input)
				->setMessages([
					'user' => $user->name . ' is not authorized for this request'
				]);		
		}
		
		return $this->payload
			->setStatus(PayloadStatus::SUCCESS)
			->setInput($input)
			->setOutput($shift);
	}
	
	private function readUser(array $input) {
		$user = new User($input['id']);
        
		if (!$user) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_FOUND)
				->setInput($input);
		}
		
		return $this->payload
			->setStatus(PayloadStatus::SUCCESS)
			->setInput($input)
			->setOutput($user);		
	}
	
	private function createShift(array $input) {
		$shift = new Shift();
		
		return $this->saveShift($shift, $input);		
	}
	
	private function updateShift(array $input) {
		$shift = new Shift($input['id']);
		
		return $this->saveShift($shift, $input);			
	}
	
	private function saveShift(Shift $shift, array $input) {
		$error_message = '';
		
		if ($input['manager_id']) {
			if (!is_numeric($input['manager_id'])) {
				$error_message .= "manager_id must be an integer; ";
			} else {
				try {
					$manager = new User($input['manager_id']);
					if (!$manager->isManager()) {
						$error_message .= 'user_id ' . $input['manager_id'] . ' is not a manager; ';
					} else {
						$shift->manager_id = $manager->id;
					}
				} catch (Exception $e) {
					$error_message .= "manager_id not found or is invalid`; ";				
				}
			}
		} else if ($shift->id == NULL) {
			// For a new shift, we can just default the current user
			$shift->manager_id = $this->user->id;
		}
		
		if ($input['employee_id']) {
			if (!is_numeric($input['employee_id'])) {
				$error_message .= "employee_id must be an integer; ";
			} else {
				try {
					$employee = new User($input['employee_id']);
					$shift->employee_id = $employee->id;
				} catch (Exception $e) {
					$error_message .= "employee_id not found or is invalid`; ";				
				}
			}
		}

		$start_time = ($input['start_time'] === null) ? strtotime($shift->start_time) : strtotime($input['start_time']);
		$end_time = ($input['end_time'] === null) ? strtotime($shift->end_time) : strtotime($input['end_time']);
		
		if ((!$start_time || !$end_time) && (!$shift->id)) {
			$error_message .= "start_time and end_time must be included for new shift; ";
		} else if ($end_time <= $start_time) {
			$error_message .= "end_time must be after start_time; ";
		} else {
			$shift->start_time = $start_time;
			$shift->end_time = $end_time;
		}

		if ($input['break'] !== null) {
			if (!is_numeric($input['break'])) {
				$error_message .= "break must be numeric";
			} else {
				$shift->break = $input['break'];
			}
		}
		
		if ($error_message) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_VALID)
				->setInput($input)
				->setMessages([
					'errors' => $error_message
				]);
		}  
		
		if (!$shift->save()) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_VALID)
				->setInput($input)
				->setMessages([
					'shift' => 'Error saving shift ' . $shift->id
				]);		
		}
		
		return $this->payload
			->setStatus(PayloadStatus::SUCCESS)
			->setInput($input)
			->setOutput($shift);	
	}
	
	private function readSchedule(array $input) {
		if (!$input['start_time'] || !$input['end_time']) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_VALID)
				->setInput($input)
				->setMessages([
					'errors' => 'start_time and end_time must be provided'
				]);		
		}
		
		$start_time = strtotime($input['start_time']);
		$end_time = strtotime($input['end_time']);
		
		if ($end_time <= $start_time) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_VALID)
				->setInput($input)
				->setMessages([
					'errors' => 'start_time must be before end_time'
				]);
		}
		
		return $this->payload
			->setStatus(PayloadStatus::SUCCESS)
			->setInput($input)
			->setOutput(Shift::getShiftsInRange($start_time, $end_time));
	}
	
	private function readUserSchedule(array $input) {
		return $this->payload
			->setStatus(PayloadStatus::SUCCESS)
			->setInput($input)
			->setOutput(Shift::getShiftsForUserId($this->user->id));		
	}
	
	private function readUserWeeklyHours(array $input) {
		if (!$input['start_date'] || !$input['end_date']) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_VALID)
				->setInput($input)
				->setMessages([
					'errors' => 'start_date and end_date must be provided'
				]);
		}
		
		// Iterate from calendar week to calendar week until we hit end date
		// This could be more elegant and right now could misappropriate an hour during daylight savings
		
		$week_start = strtotime($input['start_date']);
		$range_end = strtotime($input['end_date']);
		
		$results = array();

		while ($week_start < $range_end) {
			$week_end = $week_start + 60 * 60 * 24 * (6 - date('w', $week_start));
			if ($week_end > $range_end) $week_end = $range_end;
			
			$row = array();
			$row['week_start'] = date('Y-m-d', $week_start);
			$row['week_end'] = date('Y-m-d', $week_end);
			$row['data'] = Shift::getShiftsForUserId($this->user->id, $week_start, $week_end);
			$results[] = $row;
			$week_start = $week_end + 60 * 60 * 24;
		}
		
		$return_array = array();
		
		foreach ($results as $row) {
			$hours = 0;
			$breaks = 0;
			foreach ($row['data'] as $data_row) {
				$hours = $hours + strtotime($data_row['end_time']) - strtotime($data_row['start_time']);
				$breaks = $breaks + $data_row['break'];
			}
			
			$return_row = array(
				'hours' => number_format( $hours / 3600, 2 ),
				'time_on_break' => number_format( $breaks, 2 ),
				'total_hours_worked' => number_format( $hours / 3600 - $breaks, 2 )
			);
			$return_array[$row['week_start'] . '-' . $row['week_end']] = $return_row;
		}
		
		return $this->payload
			->setStatus(PayloadStatus::SUCCESS)
			->setInput($input)
			->setOutput($return_array);
	}
	
	private function readCoworkers(array $input) {
		if (!$input['start_date'] || !$input['end_date']) {
			return $this->payload
				->setStatus(PayloadStatus::NOT_VALID)
				->setInput($input)
				->setMessages([
					'errors' => 'start_date and end_date must be provided'
				]);
		}
		
		$shifts = Shift::getShiftsForUserId($this->user->id, strtotime($start_time), strtotime($end_time));

		$return_array = array();
				
		foreach ($shifts as $shift) {
			$row = array();
			$row['start_date'] = $shift['start_time'];
			$row['end_date'] = $shift['end_time'];
			$row['co-workers'] = array();

			$user_start = strtotime($shift['start_time']);
			$user_end = strtotime($shift['end_time']);
			
			$workers = Shift::getEmployeesForTimes(strtotime($shift['start_time']), strtotime($shift['end_time']));

			foreach ($workers as $co_worker) {
				if ($co_worker['id'] != $this->user->id) {
					$co_worker_start = strtotime($co_worker['start_time']);
					$co_worker_end = strtotime($co_worker['end_time']);
					
					if (($co_worker_end > $user_start) && ($co_worker_start < $user_end)) {
						$working_with_from = max($co_worker_start, $user_start);
						$working_with_to = min($co_worker_end, $user_end);
						
						$co_worker_row = array(
							'co-worker' => $co_worker['name'],
							'from' => Shift::formattedTime($working_with_from),
							'to' => Shift::formattedTime($working_with_to)
						);
						
						$row['co-workers'][] = $co_worker_row;
					}					
				}
			}
			
			$return_array[] = $row;
		}

		return $this->payload
			->setStatus(PayloadStatus::SUCCESS)
			->setInput($input)
			->setOutput($return_array);
	}
}