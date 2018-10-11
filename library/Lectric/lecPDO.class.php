<?php
namespace Lectric;

/**
* lecPDO Class
*
* Handles Strict Select, Update, Insert and Delete functions
*
* @package    Lectric Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
*
*/ 
class lecPDO 
{ 

	public $DBH;
	
	public const SINGLE = 1;
	public const MULTI = 2;
	public const STRICT = 3;
	public const TABLED = 4;
	public const SQL_ECHO = 5;
	
	protected $_selectFields = '*';
	protected $_insertFields = null;
	protected $_updateFields = null;
	protected $_whereArray = null;
	protected $_whereOps = null;
	protected $_orderByArray = null;
	protected $_limit = null;
	protected $_groupBy = null;

	function __construct(&$DBH)
	{
		$this->DBH = $DBH;
	}
	
	//Query Functions
		
		/**
		* Strict Select function
		* @param string $table the table to select data from
		* @param string $args array of args for single/multi, strict, tabled prefix return array and echo.
		* @return array
		*/
			public function selStrict(string $table = '', string ...$args): ?array
			{
				
				if (trim($table) === ''){
					throw new \Exception ('Table argument empty string.');
				}
				
				$sql = 'SELECT '. $this->getFieldInjSelect().' FROM `'.trim($table,'`').'` '.$this->getWhereInj().' '.$this->getGroupByInj().' '.$this->getOrderByInj().' '.$this->getLimitInj();
				
				if (in_array(self::SQL_ECHO, $args)){
					echo $sql;
				} 
				
				return $this->runSelect($sql, $table, in_array(self::SINGLE, $args), in_array(self::STRICT, $args), in_array(self::TABLED, $args), $this->_whereArray);
				
			}
			
		/**
		* Lax Select function
		* @param string $query the table to select data from
		* @param string $args array of args for single/multi, strict, tabled prefix return array and echo.
		* @return array
		*/
			public function selSLax(string $query, array $boundArray = null, string ...$args): ?array
			{
				
				if (in_array(self::SQL_ECHO, $args)){
					echo $query;
				}
				
				if(mb_stripos($query, 'select') === false){
					throw new \Exception('"SELECT" not part of the query in selLax();');
				}
				
				return $this->runSelect($query, 'table', in_array(self::SINGLE, $args), in_array(self::STRICT, $args), in_array(self::TABLED, $args), $boundArray);
				
			}
			
		/**
		* Strict Update function
		* @param string $table the table to update the data into
		* @param string $args array of args
		* @return bool
		*/
			public function updateStrict(string $table, ...$args): void
			{
				
				$sql = 'UPDATE '.$table.' SET '.$this->getUpdateFieldInj().' '.$this->getWhereInj('w_').'';
				
				if (in_array(self::SQL_ECHO, $args)){
					echo $sql;
				}
				
				if (is_array($this->_whereArray)){
					$boundArray = array_merge ($this->_updateFields, $this->_whereArray);
				} else {
					$boundArray = $this->_updateFields;
				}
				
				$this->queryLax($sql, $boundArray);
				
				return;
					
			}
			
		/**
		* Strict Insert function
		* Function uses defined clauses as set before this function call to insert that data as key=>value pairs into database table
		* @param string $args array of args
		* @return int
		*/
			public function insertStrict(string $table, ...$args): ?int
			{
			
				$inj = $this->getFieldToValueInsert();
				$sql = 'INSERT INTO '.$table.' ('.$inj['fields'].') VALUE ('.$inj['values'].')';
				
				if (in_array(self::SQL_ECHO, $args)){
					echo $sql;
				}
				
				$this->queryLax($sql, $this->_insertFields);
				
				return $this->DBH->lastInsertId();
			}
			
		/**
		* Strict Delete function
		* @param string $table the table to delete data from
		* @param string $args array of args
		* @return void
		*/
			public function deleteStrict(string $table, ...$args): void
			{
					
				$sql = 'DELETE FROM '.$table.' '.$this->getWhereInj().'';
				
				if (in_array(self::SQL_ECHO, $args)){
					echo $sql;
				}
				
				$this->queryLax($sql, $this->_whereArray);
				
				return;
			}
		
		/**
		* Straight Query function for passed query strings, none return
		* @param string $query the query to run
		* @param array $boundArray array of bound parameters
		* @return void
		*/
			public function queryLax(string $query, array $boundArray = null): void
			{
				
				try {
					$STH = $this->DBH->prepare($query);
					if ($boundArray === null){
						$STH->execute();
					} else {
						$STH->execute($boundArray);
					}
					$this->clearProperties();
				} catch (\PDOException $e){
					throw new SQLException($e->getMessage());
				} finally {
					return;
				}
			}
			
	//End Query Functions
	
	//Utility Functions
	
		/**
		* Run the select that's set up by selStrict and selLax functions
		* @param string $query the query to run
		* @param bool $singleResult single row or multi row return
		* @param bool $strict throw exception on empty data or not
		* @param bool $arrayType prefix return array with a ['table'] element in each row
		* @param array $boundArray array of bound paramters for sql
		* @return array
		*/
			private function runSelect(string $query, string $table, bool $singleResult = false, bool $strict = false, bool $tabled = false, array $boundArray = null): ?array
			{

				//Try to prepare the SQL statement, throw exception if this fails, for example without any where fields + ops?
				try {
					
					$STH = $this->DBH->prepare($query);
					
					if ($boundArray === null){
						$STH->execute();
					} else {
						$STH->execute($boundArray);
					}
					
				} catch (\PDOException $e) {
					throw new \Exception($e->getMessage());
				} finally {
					$this->clearProperties();
				}

				$STH->setFetchMode(\PDO::FETCH_ASSOC);
					
				if ($tabled === true){
					
					$returnedResult = [];
					$tempResults = [];
					
					if ($singleResult === true){
						
						$fetched = $STH->fetch();
						
						if ($fetched === false){
						
							//no rows
							if($strict === true){
								throw new \Exception('No Results');
							}
							
							return null;
							
						}
						
						return [$table => $fetched];
						
					} else {
						
						$fetched = $STH->fetchAll();
						
						if (empty($fetched)){
							
							//no rows
							if($strict === 'STRICT'){
								throw new \Exception('No Results');
							}
							
							return null;
							
						} else {
							
							foreach($fetched as $row){
								foreach ($row as $key=>$value){
										$tempResults[$table][$key] = $value;
								}
								$returnedResult[] = $tempResults;
							}
							
							return $returnedResult;
							
						}
						
					}
					
				} else {
					
					if ($singleResult === true){
						
						$fetched = $STH->fetch();
						
						if ($fetched === false){
							
							//no rows
							if($strict === 'STRICT'){
								throw new \Exception('No Results');
							}
								
							return null;
							
						} else {
							return $fetched;
						}
						
					} else {
						
						$fetched = $STH->fetchAll();
						
						if (empty($fetched)){
							
							//no rows
							if($strict === 'STRICT'){
								throw new \Exception('No Results');
							}
							
							return null;
							
						} else {
							return $fetched;
						}
						
					}
				}
			
			}
			
		/**
		* Parse and verify the SQL clause members
		* @param string $table the table to get the cols from
		* @param string $type where's the check request coming from
		* @param string $type the function that requested the check
		* @return void
		*/
			protected function checkSQLClauses(string $table, string $type, string $function): void
			{
			
				//insert and delete specific checks
				if($type === 'insert' ||  $type === 'update'){
					if (!is_array($this->_updateFields)){
						throw new \Exception ('Fields not an array in '.$function);
					}
				}
				
				if($type !== 'insert'){
					
				}
				
				
			}
			
		/**
		* Check the sql function passed is ok
		* @param string $value a sql function
		* @return bool
		*/
			protected function checkFunction(string $value = ''): ?bool
			{
				switch ($value){
					case 'NOW()':
						return true;
					break;
					default:
						return false;
					break;
				}
			}
			
		/**
		* Clear out the members for next transaction
		* @return void
		*/
			public function clearProperties(): void
			{
				$this->_selectFields = '*';
				$this->_insertFields = null;
				$this->_updateFields = null;
				$this->_whereArray = null;
				$this->_whereOps = null;
				$this->_orderByArray = null;
				$this->_groupBy = null;
				$this->_limit =  null;
			}
		
	//End Utility Functions
	
	//Get Functions
		
		/**
		* Build the whole where injection.
		* @return string
		*/
			protected function getWhereInj(string $prefix = ''): ?string
			{
			
				/* This function takes all of the values (and arrays) in the Where Array and turns them into a string with bound parametres. */
				/* Note all the trimming, as this can catch whether or not the supplied is with '`' or not. */
			
				if (is_array($this->_whereArray)){
					
					//check the necessary members
						foreach ($this->_whereArray as $key => $value){
							if (trim($key) === ''){
								throw new \Exception ('Where array key empty.');
							}
						}
						
						if ($this->_whereOps === ''){
							throw new \Exception ('Where Ops empty in '.$function);
						}
						
						if (strlen($this->_whereOps) !== count($this->_whereArray)){
							throw new \Exception ('Ops does not match Where array count in '.$function);
						}
				
					//Set up the counting variable and initial string
					$i = 1;
					$whereInj = 'WHERE ';
					$indexCount = count($this->_whereArray);
					
					foreach ($this->_whereArray as $key=>$value){
					
						// If not the first loop, and AND to string
							if ($i <= $indexCount && $i > 1){
								$whereInj .= ' AND ';
							}
					
						$op = $this->getOP( $i);
						
						
						//don't need this now, as will cause mis-matched number of bound array elements error
							$keyUse = $key;
							unset ($this->_whereArray[$keyUse]);
						
						//If the OP is in, then $value is an array, but the key is also set (not assigned int index)
							if ($op === 'IN'){
								
								if(!is_array($value)){
									$valBits = explode(',',$value);
									$valBits = array_filter( $valBits, function($valBit) { return $valBit !== ''; });
								}
								
								if(empty($value)){
									throw new \exception('IN operation in Where Fields array need to be a valid array or explodable string.');
								}
								
								$valueIdentifiers = [];
								$i = 1;
								foreach($valBits as $valBit){
									$valueIdentifiers[] = ':'.$prefix.$keyUse.'_'.$i;
									$this->_whereArray[$prefix.$keyUse.'_'.$i] = $valBit;
									$i++;
								}
								
								//don't need this now, as will cause mis-matched number of bound array elements error
								
								$whereInj .= ' `'.$keyUse.'` IN ('.implode(', ',$valueIdentifiers).') ';
						
						//otherwise, then if $value is an array, that means $keyUse has been auto assigned as an int, and it's part of multiple field where (eg range `date` > X AND `date < Y)
							} elseif (is_array($value)){
							
								foreach ($value as $subKey => $subValue){
									
									//set up bound parameter as appending the current loop number to it to make it unique in the bound array.
									$whereInj .= ' `'.$subKey.'` '.$op.' :'.$prefix.$subKey.$i.' ';
									$this->_whereArray[$prefix.$subKey.$i] = $subValue;
									
								}
								
						//if op is not IN and $value not an array, then just a flat where clause (possibly like)
							} else {
								
								$value = ($op === 'LIKE') ? '%'.$value.'%' : $value;
								$this->_whereArray[$prefix.$keyUse] = $value;
								$whereInj .= ' `'.$keyUse.'` '.$op.' :'.$prefix.$keyUse.' ';

							}
						
						$i++;
						
					}
				} else {
					$whereInj = '';
				}
			
				return $whereInj;
			}
			
		/**
		* Get SQL operator as opposed to framework operator
		* @return string
		*/
			private function getOp(&$i): ?string
			{
				$op = substr($this->_whereOps, ($i-1), 1);
				switch ($op){
					case '=':
						return '=';
					break;
					case 'N':
						return '!=';
					break;
					case '<':
						return '<';
					break;
					case '>':
						return '>';
					break;
					case 'L':
						return '<=';
					break;
					case 'G':
						return '>=';
					break;
					case 'X':
						return 'LIKE';
					break;
					case 'I':
						return 'IN';
					break;
					default:
						throw new \Exception ('Invalid Ops Parameter at index '.$i);
					break;
				}
				
			}
			
		/**
		* Build field injection string for select
		* @return string
		*/
			private function getFieldInjSelect(): ?string{
				
				if(is_array($this->_selectFields)){
					
					foreach ($this->_selectFields as $value){
						if ($value === ''){
							throw new \Exception ('Blank Select Fields array element found.');
						}
					}
			
					$fieldInj = '';
				
					$i = 1;
					$indexCount = count($this->_selectFields);
					
					foreach ($this->_selectFields as $value){
						if ($i <= $indexCount && $i > 1){
							$fieldInj .= ',';
						}
						$fieldInj .= ' `'.$value.'` ';
						
						$i++;
					}
					
					return $fieldInj;
				
				} else {
					return '*';
				}
			
			}
			
		/**
		* Build order by injection
		* @return string
		*/
			private function getOrderByInj(): ?string
			{
				
				//check the order by array
				if(is_array($this->_orderByArray)){
					foreach ($this->_orderByArray as $key => $value){
						if (trim($key) === ''){
							throw new \Exception ('Order By array key empty.');
						}
						
						if(strtolower($value) !== 'asc' && strtolower($value) !== 'desc'){
							throw new \Exception ('Order By array value must be "ASC" or "DESC".');
						}
					}
					
					$i = 1;
					$orderbyInj =  ' ORDER BY ';
					$indexCount = count($this->_orderByArray);
					foreach ($this->_orderByArray as $key => $value){
						if ($i <= $indexCount && $i > 1){
							$orderbyInj .= ' , ';
						}
										
						$orderbyInj .= ' `'.$key.'` '.$value.' ';						
						$i++;
					}
					return $orderbyInj;
					
				} else {
					return '';
				}
			}
		
		/**
		* Build group by injection
		* @return string
		*/
			private function getGroupByInj(): ?string
			{
				
				//check the group by array
				if(is_array($this->_groupBy)){
					foreach ($this->_groupBy as $value){
						if ($value === ''){
							throw new \Exception ('Blank Group By array element found.');
						}
					}
					
					$groupByInj = ' GROUP BY ';
					$i = 1;
					$groupCount = count($this->_groupBy);
					foreach ($this->_groupBy as $value){
					
						if ($i <= $groupCount && $i > 1){
							$groupByInj .= ' , ';
						}
						
						$groupByInj .= ' `'.$value.'` ';
						
						$i++;
					
					}
					
					return $groupByInj;
					
				} elseif (trim($this->_groupBy) !== '' ){
					return ' GROUP BY `'.trim($this->_groupBy).'` ';
				} else {
					return '';
				}
				
			}
			
		/**
		* Build limit injection
		* @return string
		*/
			private function getLimitInj(): ?string
			{
				
				//check limit array
				if(is_array($this->_limit)){
					foreach ($this->_limit as $value){
						if (!is_int((int)$value)){
							throw new \Exception ('All Limit array elements must be of type integer.');
						}
					}
					
					return ' LIMIT '.$this->_limit[0].','.$this->_limit[1];
					
				} elseif(trim($this->_limit) !== ''){
					return ' LIMIT 0,'.(int)$this->_limit;
				} else {
					return '';
				}
			}
			
		/**
		* Build field injection string 
		* @return string
		*/
			private function getUpdateFieldInj(): ?string
			{
			
				if (!is_array($this->_updateFields)){
					throw new \Exception ('Update Fields not set.');
				}
			
				$fieldInj = '';
			
				$i = 1;
				$indexCount = count($this->_updateFields);
				
				foreach ($this->_updateFields as $key=>$value){
					if ($i <= $indexCount && $i > 1){
						$fieldInj .= ',';
					}
					
					//check passed function
					if ($this->checkFunction($value)){
						$fieldInj .= ' `'.$key.'` = '.$value.'';
						unset($this->_updateFields[$key]);
					} else {
						$fieldInj .= ' `'.$key.'` = :'.$key.'';
					}
					$i++;
				}
				
				return $fieldInj;
			
			}
			
		/**
		* Build field => value array for insert and update
		* @return array
		*/
			private function getFieldToValueInsert(): ?array
			{
				
				//check insert array
				if(!is_array($this->_insertFields)){
					throw new \Exception ('Insert Fields array not set.');
				}
				
				$inj = [
					'fields' => '', 'values' => ''
				];
				
				$i = 1;
				$indexCount = count($this->_insertFields);
				
				foreach ($this->_insertFields as $key=>$value){
					
					$value = ($value == null) ? '' : $value; // so checkFunction works if trying to insert null data type
					
					if ($i <= $indexCount && $i > 1){
						$inj['fields'] .= ',';
						$inj['values'] .= ',';
					}
					
					$inj['fields'] .= ' `'.$key.'` ';
					
					//check if passed function
					if ($this->checkFunction($value) === true){
						$inj['values'] .= ' '.$value.' ';
						unset($this->_insertFields[$key]);
					} else {
						$inj['values'] .= ' :'.$key.' ';
					}
					
					$i++;
					
				}
				
				return $inj;
			}
			
	//End Get Functions
	
	//Set Functions
			
		/**
		* Set the select where fields member
		*
		*	If a field is needed more than once in the where array, then do the following:
		*		
		*	instead of the usual:
		*			
		*	$this-_whereArray = array(
		*		'someKey' => $someValue,
		*		'keySame' => $value1,
		*		'keySame' => $value2
		*	);
		*			
		*	YOU MUST use:
		*		
		*	$this-_whereArray = array(
		*		'someKey' => $someValue,
		*		array( 'keySame' => $value1) ,
		*		array('keySame' => $value2)
		*	);
		*		
		* this will get picked up in the get where injection function and dealt with accordingly.
		*
		* @return void
		*/
			public function setWhereFields(array $array): void
			{		
				$this->_whereArray = $array;
			}
			
		/**
		* Set the where ops member
		* @return void
		*/
			public function setWhereOps(string $ops): void
			{
				$this->_whereOps = $ops;
				return;
			}
			
		/**
		* Set the select fields member
		* @param array $array The Select Fields Array
		* @return void
		*/
			public function setSelectFields(array $array): void
			{
				$this->_selectFields = $array;
				return;
			}
		
		/**
		* Set the order by member
		* @param array $array The Order By Array
		* @return void
		*/
			public function setOrderBy(array $array): void
			{
				$this->_orderByArray = $array;
				return;
			}
			
		/**
		* Set the group by member
		* @param mixed $groupBy The Group By Array
		* @return void
		*/
			public function setGroupBy($groupBy): void
			{
				$this->_groupBy = $groupBy;
				return;
			}
			
		/**
		* Set the limit by member
		* @param mixed $limit The Limit Array
		* @return void
		*/
			public function setLimit($limit): void
			{
				$this->_limit = $limit;
				return;
			}
			
		/**
		* Set the select insert fields member
		* @return void
		*/
			public function setUpdateFields(array $array): void
			{
				$this->_updateFields = $array;
				return;
			}
			
		/**
		* Set the select insert fields member
		* @return void
		*/
			public function setInsertFields(array $array): void
			{
				$this->_insertFields = $array;
				return;
			}
}