<?php
namespace Lectric;

/**
* Main database access class
*
* Class handles all database access, auto preparing all statmenets and smart building standard queries
*
* @package    RWS Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
* @license    As license.txt in root
*
*/ 
class SQLQueryPDO 
{ 
	
	/**
	* Database handler member object
	*
	* @var DBH
	*/
	public $DBH;
	
	//Initialized to '' for validation purposes.
	private $_selectFields = '*';
	private $_queryFields = null;
	private $_whereArray = null;
	private $_whereOps = null;
	private $_orderByArray = null;
	private $_limitArray = null;
	private $_groupByArray = null;
	
	function __construct($DBH)
	{
       $this->DBH = $DBH;
	}
	
	/***
	****Start Query Functions
	***/
	
		/**
		* Straight Query function for passed query strings, none return
		*
		* @param string $query the query to run
		*
		* @param array $boundArray array of bound parameters
		*
		* @return void
		*/
		public function query(string $query, array $boundArray = null): void
		{
			/* Simple delete/update function. also used in $this->updateStrict */
			
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
		
		/**
		* Strict Insert function
		*
		* Function uses defined clauses as set before this function call to insert that data as key=>value pairs into database table
		*
		* @param string $table the table to insert the data into
		*
		* @return int
		*/
		public function insertStrict(string $table): ?int
		{
		
			/* . Takes $data in single level key=>value array format. $table as string */
			try {
				$this->checkSQLClauses($table, 'insert', 'Insert Strict');
				
				$table = $this->formatTable($table);
		
				$inj = $this->getFieldToValueInsert();
				
				$STH = $this->DBH->prepare('INSERT INTO '.$table.' ('.$inj['fields'].') VALUE ('.$inj['values'].')');
				$STH->execute($this->_queryFields);
				$this->clearProperties();
				return $this->DBH->lastInsertId();
				
			} catch (Exception | \PDOException $e){
				throw new SQLException ($e->getMessage());
				return null;
			}
		}
		
		/**
		* Strict Update function
		*
		* Strict Update function. $table as string, $fields as single level key=>value array $where is optional, as single level key=>value array when used. Where keys MUST be prefixed with 'W_'. $ops as string eg '=<X'
		*
		* @param string $table the table to update the data into
		*
		* @param string $echo flag for echoing out sql before running
		*
		* @return bool
		*/
		public function updateStrict(string $table, string $echo = 'NOT_ECHO'): ?bool
		{
			
			try {
				$this->checkSQLClauses($table, 'update', 'Update Strict');
			} catch (Exception $e){
				throw new SQLException ($e->getMessage());
				return null;
			}
			
			$table = $this->formatTable($table);
			
			//$inj = $this->getFieldToValueInsert();
			$field_inj = $this->getFieldInj();
			
			if ($this->_whereArray !== null && is_array($this->_whereArray)){
				$i = 1;
				$where_inj = 'WHERE ';
				$indexCount = count($this->_whereArray);
				foreach ($this->_whereArray as $key=>$value){
					if ($i <= $indexCount && $i > 1){
						$where_inj .= ' AND ';
					}
					
					try {
						$op = $this->getOP( $i);
						if ($op === 'X'){
							$this->_whereArray[$key] = '%'.$value.'%';
						}
					} catch (Exception $e){
						throw new SQLException ($e->getMessage());
						return false;
					}
					
					$keyFinal = str_replace('W_','',$key);
					
					$where_inj .= ' `'.$keyFinal.'` '.$op.' :'.$key.' ';
					$i++;
				}
			} else {
				$where_inj = '';
			}
			
			$sql = 'UPDATE '.$table.' SET '.$field_inj.' '.$where_inj.'';
			
			if ($echo === 'ECHO') {echo $sql;}
			
			if (is_array($this->_whereArray)){
				$boundArray = array_merge ($this->_queryFields, $this->_whereArray);
			} else {
				$boundArray = $this->_queryFields;
			}
			
			$this->query($sql, $boundArray);
			
			return true;
				
		}
		
		//depricated select strict
			/**
			* Strict Select function (deprecated)
			*
			* Strict Select function, requires only table param defined (will select all)
			*
			* @param string $table the table to select data from
			*
			* @param string $singleResult single row or multi row return
			*
			* @param string $strict throw exception on empty data or not
			*
			* @param string $echoSql echo out sql before running or not
			*
			* @param string $arrayType prefix return array with a ['table'] element in each row
			*
			* @return array
			*/
			public function selectStrict(string $table, string $singleResult = 'MULTI', string $strict = 'NOT_STRICT', string $echoSql = 'NO_ECHO', string $arrayType = 'TABLED'): ?array
			{
			
				try {
					$this->checkSQLClauses($table, 'select', 'Select Strict');
				} catch (Exception $e){
					throw new SQLException ($e->getMessage());
					return null;
				}
				
				$table = $this->formatTable($table);
				
				if ($this->_selectFields !== '*'){
					$field_inj = $this->getFieldInjSelect();
				} else {
					$field_inj = '*';
				}
					
				$where_inj = $this->getWhereInj();
				$orderby_inj = $this->getOrderByInj();
				$limit_inj = $this->getLimitInj();
				$group_inj = $this->getGroupByInj();
				
				$sql = 'SELECT '.$field_inj.' FROM '.$table.' '.$where_inj.' '.$orderby_inj.' '.$group_inj.' '.$limit_inj;
				
				if ($echoSql === 'ECHO'){
					echo $sql;
				} 
								
				return $this->runSelect($sql, $singleResult, $strict, $this->_whereArray, $arrayType);
				
			}
		
		//newer select strict function
			/**
			* Strict Select function
			*
			* Strict Select function. Newer variadic function with same functionality as deprecated selectStrict
			*
			* @param string $table the table to select data from
			*
			* @param string $args array of args for single/multi, strict, tabled prefix return array and echo.
			*
			* @return array
			*/
			public function selStrict(string $table, string ...$args): ?array
			{
			
				$singleResult = (in_array('SINGLE', $args)) ? 'SINGLE' : 'MULTI';
				$strict = (in_array('STRICT', $args)) ? 'STRICT' : 'NOT_STRICT';
				$arrayType = (in_array('NOT_TABLED', $args)) ? 'NOT_TABLED' : 'TABLED';
			
				try {
					$this->checkSQLClauses($table, 'select', 'Select Strict');
				} catch (Exception $e){
					throw new SQLException ($e->getMessage());
					return null;
				}
				
				$table = $this->formatTable($table);
				
				if ($this->_selectFields !== '*'){
					$field_inj = $this->getFieldInjSelect();
				} else {
					$field_inj = '*';
				}
					
				$where_inj = $this->getWhereInj();
				$orderby_inj = $this->getOrderByInj();
				$limit_inj = $this->getLimitInj();
				$group_inj = $this->getGroupByInj();
				
				$sql = 'SELECT '.$field_inj.' FROM '.$table.' '.$where_inj.' '.$orderby_inj.' '.$group_inj.' '.$limit_inj;
				
				if (in_array('ECHO', $args)){
					echo $sql;
				} 
				
				return $this->runSelect($sql, $singleResult, $strict, $this->_whereArray, $arrayType);
				
			}

		/**
		* Lax Select function
		*
		* Lax Select function. Select function for complex queries (nested queries, joins etc)
		*
		* @param string $query the query to run
		*
		* @param string $singleResult single row or multi row return
		*
		* @param string $strict throw exception on empty data or not
		*
		* @param string $echoSql echo out sql before running or not
		*
		* @param array $boundArray array of bound paramters for sql
		*
		* @param string $arrayType prefix return array with a ['table'] element in each row
		*
		* @return array
		*/
		public function select(string $query, string $singleResult = 'MULTI', string $strict = 'NOT_STRICT', string $echoSql = 'NOT_ECHO', array $boundArray = null,  string $arrayType = 'TABLED'): ?array
		{
			
			if ($echoSql === 'ECHO'){
				echo $query;
			}
			
			//if (!preg_match('/SELECT /i', $query)){
			//	throw new SQLException('Query does not contain "SELECT ".');
			//	return null;
			//}
			
			return $returned = $this->runSelect($query, $singleResult, $strict, $boundArray, $arrayType);
		}
		
		/**
		* Strict Delete function
		*
		* Strict Delete function. Deletion by set up members
		*
		* @param string $table the table to delete data from
		*
		* @param string $echoSql echo out sql before running or not
		*
		* @return void
		*/
		public function deleteStrict(string $table, string $echoSql = 'NO_ECHO'): void
		{
		
			try {
				$this->checkSQLClauses($table, 'delete', 'Delete Strict');
			} catch (Exception $e){
				throw new SQLException ($e->getMessage());
				return;
			}
			
			$table = $this->formatTable($table);
			
			$where_inj = $this->getWhereInj();
				
			$sql = 'DELETE FROM '.$table.' '.$where_inj.'';
			
			if ($echoSql === 'ECHO'){
				echo $sql;
			}
							
			$this->query($sql, $this->_whereArray);
			return;
		}
		
		/**
		* Check if row exists function, by parameters
		*
		* @param string $table the table to delete data from
		*
		* @param string $strict throw exception on empty data or not
		*
		* @param string $echoSql echo out sql before running or not
		*
		* @return bool
		*/
		public function rowExists(string $table, string $strict = 'NOT_STRICT', string $echoSql = 'NO_ECHO'): ?bool
		{
		
			try {
				$this->checkSQLClauses($table, 'rowExists', 'Row Exists');
			} catch (Exception $e){
				throw new SQLException ($e->getMessage());
				return null;
			}
			
			$table = $this->formatTable($table);
			
			if ($this->_selectFields !== '*'){
				$field_inj = $this->getFieldInjSelect();
			} else {
				$field_inj = '*';
			}
				
			$where_inj = $this->getWhereInj();
			$orderby_inj = $this->getOrderByInj();
			$limit_inj = $this->getLimitInj();
			$group_inj = $this->getGroupByInj();
			
			$sql = 'SELECT '.$field_inj.' FROM '.$table.' '.$where_inj.' '.$orderby_inj.' '.$group_inj.' '.$limit_inj;
			
			if ($echoSql === 'ECHO'){
				echo $sql;
			} 
							
			$result = $this->runSelect($sql, 'SINGLE', $strict, $this->_whereArray);
			
			if ($result !== null){
				return true;
			} else {
				return false;
			}
			
		}
		
		/**
		* Run the select that's set up by selStrict, selectSctrict and select functions
		*
		* @param string $query the query to run
		*
		* @param string $singleResult single row or multi row return
		*
		* @param string $strict throw exception on empty data or not
		*
		* @param array $boundArray array of bound paramters for sql
		*
		* @param string $arrayType prefix return array with a ['table'] element in each row
		*
		* @return bool
		*/
		private function runSelect(string $query, string $singleResult, string $strict = 'NOT_STRICT', array $boundArray = null, string $arrayType = 'TABLED'): ?array
		{

			//Try to prepare the SQL statement, throw exception if this fails, for example without any where fields + ops?
			try {
				$STH = $this->DBH->prepare($query);
				if ($boundArray === null){
					$STH->execute();
				} else {
					$STH->execute($boundArray);
				}
				
				//clear all statement injection arrays
				$this->clearProperties();
				
			} catch (\PDOException $e) {
			
				//clear all statement injection arrays
				$this->clearProperties();
				throw new SQLException($e->getMessage());
				return null;
			}

			$STH->setFetchMode(\PDO::FETCH_ASSOC);
				
			if ($arrayType === 'TABLED'){
				
				$returnedResult = [];
				$tempResults = [];
				$col_details = $STH->getColumnMeta(0);
				$table = $col_details['table'];
				
				if ($singleResult === 1 || $singleResult === 'SINGLE'){
					
					$fetched = $STH->fetch();
					
					if ($fetched === false){
					
						//no rows
						if($strict === 'STRICT'){
							throw new SQLException('No Results', 0);
							return null;
						} else {
							return null;
						}
					}
					
					return [$table => $fetched];
					
				} else {
					
					$fetched = $STH->fetchAll();
					
					if (empty($fetched)){
						return null;
					} else {
						foreach($fetched as $row){
							foreach ($row as $key=>$value){
									$tempResults[$table][$key] = $value;
							}
							if ($singleResult === 1 || $singleResult === 'SINGLE'){
								return $tempResults;
							}
							$returnedResult[] = $tempResults;
						}
						return $returnedResult;
					}
					
				}
				
			} else {
				
				if ($singleResult === 1 || $singleResult === 'SINGLE'){
					
					$fetched = $STH->fetch();
					
					if ($fetched === false){
						
						//no rows
						if($strict === 'STRICT'){
							throw new SQLException('No Results', 0);
							return null;
						} else {
							return null;
						}
						
					} else {
						return $fetched;
					}
					
				} else {
					
					$fetched = $STH->fetchAll();
					
					if (empty($fetched)){
						return null;
					} else {
						return $fetched;
					}
					
					
				}
			}
		
		}
		
		/**
		* Build array of columns on a table
		*
		* @param string $table the table to get the cols from
		*
		* @return array
		*/
		public function getTableColumns(string $table): ?array
		{
		
			$table = $this->formatTable($table);
			
			//load fields names from DESCRIBE into array
			$STH = $this->DBH->prepare("DESCRIBE ".$table);
			$STH->execute();
			return $table_fields = $STH->fetchAll(\PDO::FETCH_COLUMN);
		
		}
	
	/***
	****End Query Functions
	***/
	
	/***
	****Start Utility Functions
	***/
	
		/**
		* Parse and verify the SQL clause members
		*
		* @param string $table the table to get the cols from
		*
		* @param string $type where's the check request coming from
		*
		* @param string $type the function that requested the check
		*
		* @return void
		*/
		private function checkSQLClauses(string $table, string $type, string $function): void
		{
		
			/* Checks the FIELDS, WHERES, LIMITS and ORDER BYS for the "strict" functions. */
		
			if ($table === ''){
					throw new Exception ('Table Argument empty string in '.$function);
					return;
			}
		
			if ($type === 'select' || $type === 'rowExists'){
				if ($this->_selectFields !== '*' && !is_array($this->_selectFields)){
					throw new Exception ('Fields not an array, or * in '.$function);
					return;
				}
			} elseif($type !== 'insert' &&  $type !== 'delete'){
				if (!is_array($this->_queryFields)){
					throw new Exception ('Fields not an array in '.$function);
					return;
				}
			}
			
			if(is_array($this->_whereArray)){
				$whereArrayCount = count($this->_whereArray);
				if ($whereArrayCount < 1){
					throw new Exception ('Where array empty in '.$function);
					return;
				}
				if ($this->_whereOps === ''){
					throw new Exception ('Ops empty in '.$function);
					return;
				}
				if (strlen($this->_whereOps) !== $whereArrayCount){
					throw new Exception ('Ops does not match Where array count in '.$function);
					return;
				}
			}
			
			if(is_array($this->_orderByArray)){
				foreach ($this->_orderByArray as $key => $value){
					if (!preg_match('/^[A-Za-z0-9_]+$/', $key)){
						throw new Exception ('Invalid Order By key '.$key.' in '.$function);
						return;
					}
					if (!preg_match('/ASC/', $value) && !preg_match('/DESC/', $value) && !preg_match('/ /', $value)){
						throw new Exception ('Invalid Order By value '.$value.' in '.$function);
						return;
					}
				}
			}
			
			if(is_array($this->_groupByArray)){
				foreach ($this->_groupByArray as $key => $value){
					if ($value === ''){
						throw new Exception ('Invalid GROUP Value '.$value.' in '.$function);
						return;
					}
				}
			}
			
			if(is_array($this->_limitArray)){
				foreach ($this->_limitArray as $key => $value){
					if (!is_int((int)$value)){
						throw new Exception ('Invalid Limit Value '.$value.' in '.$function);
						return;
					}
				}
			}
			
		}
		
		/**
		* Remove ` chars if present, then put them back
		*
		* @param string $table the table to remove ` chars from
		*
		* @return string
		*/
		private function formatTable(string $table): ?string
		{
			$table = str_replace('`', '', $table);
			$table = '`'.$table.'`';
			return $table;
		}
		
		/**
		* Build field injection string for select
		*
		* @return string
		*/
		private function getFieldInjSelect(): ?string{
		
			$field_inj = '';
		
			$i = 1;
			$indexCount = count($this->_selectFields);
			
			foreach ($this->_selectFields as $value){
				if ($i <= $indexCount && $i > 1){
					$field_inj .= ',';
				}
				
				if (preg_match('/^COUNT\(([a-zA-Z`]+)\) AS \"([a-zA-Z0-9]+)\"$/', trim($value))){
					$field_inj .= ' '.$value.' ';
				} else {
					$field_inj .= ' `'.trim($value,'`').'` ';
				}
				
				$i++;
			}
			
			return $field_inj;
		
		}
		
		/**
		* Build field injection string for where
		*
		* @return string
		*/
		private function getFieldInj(): ?string
		{
		
			$field_inj = '';
		
			$i = 1;
			$indexCount = count($this->_queryFields);
			
			foreach ($this->_queryFields as $key=>$value){
				if ($i <= $indexCount && $i > 1){
					$field_inj .= ',';
				}
				
				//check passed function
				if ($this->checkFunction($value)){
					$field_inj .= ' `'.trim($key, '`').'` = '.trim($value, '`').'';
					unset($this->_queryFields[$key]);
				} else {
					$field_inj .= ' `'.trim($key, '`').'` = :'.trim($key, '`').'';
				}
				$i++;
			}
			
			return $field_inj;
		
		}
		
		/**
		* Build field => value array for insert and update
		*
		* @return array
		*/
		private function getFieldToValueInsert(): ?array
		{
			$inj = [];
			$inj['fields'] = '';
			$inj['values'] = '';
			
			$i = 1;
			$indexCount = count($this->_queryFields);
			
			foreach ($this->_queryFields as $key=>$value){
				if ($i <= $indexCount && $i > 1){
					$inj['fields'] .= ',';
					$inj['values'] .= ',';
				}
				
				//check passed function
				if ($this->checkFunction($value)){
					$inj['fields'] .= ' `'.trim($key, '`').'` ';
					$inj['values'] .= ' '.$value.' ';
					unset($this->_queryFields[$key]);
				} else {
					$inj['fields'] .= ' `'.trim($key, '`').'` ';
					$inj['values'] .= ' :'.trim($key, '`').' ';
				}
				$i++;
			}
			
			return $inj;
		}
		
		/**
		* Build the whole where injection.
		*
		* @return string
		*/
		private function getWhereInj(): ?string
		{
		
			/* This function takes all of the values (and arrays) in the Where Array and turns them into a string with bound parametres. */
			/* Note all the trimming, as this can catch whether or not the supplied is with '`' or not. */
		
			if ($this->_whereArray !== null && is_array($this->_whereArray)){
			
				//possible analysis of array to see whether any nested arrays contain the same keys as the top level, and thus rearranging here, not further down....
			
				//Set up the counting variable and initial string
				$i = 1;
				$where_inj = 'WHERE ';
				$indexCount = count($this->_whereArray);
				
				foreach ($this->_whereArray as $key=>$value){
				
					// If not the first loop, and AND to string
					if ($i <= $indexCount && $i > 1){
							$where_inj .= ' AND ';
						}
				
					// If the value is an array (to prevent key matching on creation of arrays)
					// then loop through this lower array to find values
					if (is_array($value)){
					
						foreach ($value as $keyValue => $valueValue){
						
							try {
								
								$op = $this->getOP($i);
								
								//unset this key (zero indexed) from the main where array, to add back in as a bound array key
								unset ($this->_whereArray[trim($key, '`')]);
								
								if ($op === 'LIKE'){
									$this->_whereArray[trim($key, '`')][trim($keyValue, '`')] = '%'.$valueValue.'%';
									$where_inj .= ' `'.trim($keyValue, '`').'` '.$op.' :'.trim($keyValue, '`').$i.' ';
									$boundKey = trim($keyValue, '`').$i;
									$this->_whereArray[$boundKey] = $valueValue;
								} else if ($op === 'IN'){
									$where_inj .= ' `'.trim($keyValue, '`').'` '.$op.' ('.$valueValue.') ';
								} else {
									//set up bound parameter as appending the current loop number to it to make it unique in the bound array.
									$where_inj .= ' `'.trim($keyValue, '`').'` '.$op.' :'.trim($keyValue, '`').$i.' ';
									$boundKey = trim($keyValue, '`').$i;
									$this->_whereArray[$boundKey] = $valueValue;
								}
								
							} catch (SQLException $e){
								throw new SQLException ($e->getMessage());
							}
							
						}
					
					} else {
				
						//Value is not an array, thus can pull values and bound key straight from $key, $value.
						try {
							$op = $this->getOP( $i);
							if ($op === 'LIKE'){
								$this->_whereArray[trim($key, '`')] = '%'.$value.'%';
								$where_inj .= ' `'.trim($key, '`').'` '.$op.' :'.trim($key, '`').' ';
							} else if ($op === 'IN'){
								unset ($this->_whereArray[trim($key, '`')]);
								$where_inj .= ' `'.trim($key, '`').'` '.$op.' ('.$value.') ';
							} else {
								$where_inj .= ' `'.trim($key, '`').'` '.$op.' :'.trim($key, '`').' ';
							}
							} catch (SQLException $e){
								throw new SQLException ($e->getMessage());
							}
					}
					
					$i++;
					
				}
			} else {
				$where_inj = '';
			}
		
			return $where_inj;
		}
		
		/**
		* Build order by injection
		*
		* @return string
		*/
		private function getOrderByInj(): ?string
		{
		
			if ($this->_orderByArray === null){
				return '';
			} else {
				$i = 1;
				$orderby_inj =  ' ORDER BY ';
				$indexCount = count($this->_orderByArray);
				foreach ($this->_orderByArray as $key=>$value){
					if ($i <= $indexCount && $i > 1){
						$orderby_inj .= ' , ';
					}
									
					$orderby_inj .= ' `'.trim($key, '`').'` '.$value.' ';						
					$i++;
				}
				return $orderby_inj;
			}
		}
		
		/**
		* Build group by injection
		*
		* @return string
		*/
		private function getGroupByInj(): ?string
		{
			if ($this->_groupByArray === null){
				return '';
			} else {
				$groupBy_inj = ' GROUP BY ';
				$i = 1;
				$groupCount = count($this->_groupByArray);
				foreach ($this->_groupByArray as $value){
				
					if ($i <= $groupCount && $i > 1){
						$groupBy_inj .= ' , ';
					}
					
					$groupBy_inj .= ' `'.trim($value, '`').'` ';
					
					$i++;
				
				}
				
				return $groupBy_inj;
			}
		}
		
		/**
		* Build limit injection
		*
		* @return string
		*/
		private function getLimitInj(): ?string
		{
			return ($this->_limitArray === null) ? '' : ' LIMIT '.$this->_limitArray[0].','.$this->_limitArray[1];
		}
		
		/**
		* Get SQL operator as opposed to framework operator
		*
		* @return string
		*/
		private function getOp($i): ?string
		{
			$op = substr($this->_whereOps, ($i-1), 1);
			switch ($op){
				case '=':
					$op = '=';
				break;
				case 'N':
					$op = '!=';
				break;
				case '<':
					$op = '<';
				break;
				case '>':
					$op = '>';
				break;
				case 'L':
					$op = '<=';
				break;
				case 'G':
					$op = '>=';
				break;
				case 'X':
					$op = 'LIKE';
				break;
				case 'I':
					$op = 'IN';
				break;
				default:
					throw new SQLException ('Invalid Ops Parameter');
					return null;
				break;
			}
			return $op;
		}
		
		/**
		* Check the sql function passed is ok
		*
		* @param string $value a sql function
		*
		* @return bool
		*/
		private function checkFunction(string $value = ''): ?bool
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
		*
		* @return void
		*/
		public function clearProperties(): void
		{
			$this->_selectFields = '*';
			$this->_queryFields = null;
			$this->_whereArray = null;
			$this->_whereOps = null;
			$this->_orderByArray = null;
			$this->_groupByArray = null;
			$this->_limitArray =  null;
		}
	
	/***
	****End Utitlity Functions
	***/
	
	/***
	****Start Setters Functions
	***/
	
		/**
		* Set the select fields member
		*
		* @return void
		*/
		public function setSelectFields(array $array): void
		{
			$this->_selectFields = $array;
			return;
		}
		
		/**
		* Set the select query fields member
		*
		* @return void
		*/
		public function setQueryFields(array $array): void
		{
			$this->_queryFields = $array;
			return;
		}
		
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
		*
		* @return void
		*/
		public function setWhereOps($ops): void
		{
			//can be string or integer...
			$this->_whereOps = $ops;
			return;
		}
		
		/**
		* Set the order by member
		*
		* @return void
		*/
		public function setOrderBy(array $array): void
		{
			$this->_orderByArray = $array;
			return;
		}
		
		/**
		* Set the group by member
		*
		* @return void
		*/
		public function setGroupBy(array $array): void
		{
			$this->_groupByArray = $array;
			return;
		}
		
		/**
		* Set the limit by member
		*
		* @return void
		*/
		public function setLimit(array $array): void
		{
			$this->_limitArray = $array;
			return;
		}
		
		/**
		* Get last insertion id
		*
		* @return int
		*/
		public function lastInsertedId(): ?int
		{
			return $this->DBH->lastInsertId(); 
		}
	
	/***
	****End Setters Functions
	***/
}

class SQLException extends \Exception {}
