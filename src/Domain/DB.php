<?php

namespace Scheduler\Domain;

use Exception;

class DB {
	private static $connection = NULL;

	//DB connection values
	const DB_SERVER = 'localhost';
	const DB_USER = 'schedulerguy';
	const DB_PASSWORD = 'arglebargle';
	const DB_NAME = 'scheduler_api';

	private static function ensureConnection() {
		$conn_string = 'mysql:host=' . self::DB_SERVER . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';
		SELF::$connection = new \PDO($conn_string, self::DB_USER, self::DB_PASSWORD);	
	}
	
	public static function query($query)
	{
		SELF::ensureConnection();
		
		$stmt = SELF::$connection->query($query);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public static function exec($sql)
	{
		SELF::ensureConnection();
		
		$stmt = SELF::$connection->prepare($sql);
		return $stmt->execute();
	}

}