<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  db.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  ??
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class DataBase {
	
	private $Host      = DB_HOST;
	private $User      = DB_USER;
	private $Pass      = DB_PASS;
	private $DB_Name   = DB_NAME;

	private $DBH;
	private $STMT;
	private $Error;

	public function __construct() {
		// Set DSN
		$DSN = 'mysql:host=' . $this->Host . ';dbname=' . $this->DB_Name;
		// Set options
		$Options = array(
			PDO::ATTR_EMULATE_PREPARES, TRUE,
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE,
			PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ
		);
		// Create a new PDO instanace
		try {
			$this->DBH = new PDO($DSN, $this->User, $this->Pass, $Options);
		}
		// Catch any errors
		catch(PDOException $e) {
			$this->Error = $e->getMessage();
		}
	}

	public function Query($Query) {
		$this->STMT = $this->DBH->prepare($Query);
	}

	public function Bind($Param, $Value, $Type = NULL) {
		if (is_null($Type)) {
			switch (TRUE) {
				case is_int($Value):
					$Type = PDO::PARAM_INT;
				break;
				case is_bool($Value):
					$Type = PDO::PARAM_BOOL;
				break;
				case is_null($Value):
					$Type = PDO::PARAM_NULL;
				break;
				default:
					$Type = PDO::PARAM_STR;
			}
		}
		$this->STMT->bindValue($Param, $Value, $Type);
	}

	public function Execute() {
		return $this->STMT->execute();
	}

	public function ResultSet() {
		$this->execute();
		return $this->STMT->fetchAll(PDO::FETCH_ASSOC);
	}

	public function Single() {
		$this->execute();
		return $this->STMT->fetch(PDO::FETCH_ASSOC);
	}

	public function RowCount() {
		return $this->STMT->rowCount();
	}

	public function LastID() {
		return $this->DBH->lastInsertId();
	}

	public function StartTransaction() {
		return $this->DBH->beginTransaction();
	}

	public function EndTransaction() {
		return $this->DBH->commit();
	}

	public function CancelTransaction() {
		return $this->DBH->rollBack();
	}

	public function DebugParams() {
		return $this->STMT->debugDumpParams();
	}
}