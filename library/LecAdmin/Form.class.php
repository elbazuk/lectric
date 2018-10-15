<?php
namespace LecAdmin;

/**
* Form elements generator
*
* A form element generator with added utility DB fetchers for selects
*
* @package    lectricFence
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
*
*/ 
class Form 
{
	
        /**
         * Start the form html output
         * 
         * @param string $form_id the id attr
         * @param string $form_method the method attr
         * @param string $form_action the action attr
         * @param string $attr_insert  allows the user to add own attrs
         * 
         * @return string
         */
			public static function startForm(string $form_id, string $form_method, string $form_action, string $attr_insert = ''): string
			{
				return '<form name="'.$form_id.'" id="'.$form_id.'" method="'.$form_method.'" action="'.$form_action.'" '.$attr_insert.'>';
			}
		
        /**
         * Litterally outputs </form>
         * 
         * @return string
         */
			public static function closeForm(): string{ return '</form>'; }
			
		/**
		 * 
		 * 
         * @param string $id the id attr
         * @param string $type type of input 
		 * @param string $name name attr
         * @param string $value value of the input
		 * @param string $placeholder  placeholder attr
         * @param string $attr_insert allows the user to add own attrs
		 * 
		 * @return string
		 */
			public static function makeInput(string $id, string $type = 'text', string $name = '', string $value = '', string $placeholder = '', string $attr_insert = ''): string
			{
				
				$name = ($name === '') ? $id : $name;
				$placeholder = ($placeholder === '') ? '' : ' placeholder="'.$placeholder.'" ';
				
				
				if ($type !== 'textarea'){
					$value = ($value === '') ? '' : ' value="'.$value.'" ';
					return $outputHtml = '<input type="'.$type.'" id="'.$id.'" '.$attr_insert.' name="'.$name.'" '.$value.' '.$placeholder.' />';
				} else {
					return $outputHtml = '<textarea id="'.$id.'" '.$attr_insert.' name="'.$name.'" '.$placeholder.' >'.$value.'</textarea>';
				}
				
			}
		

        /**
         * Select output providing own array of options as option_value => option_html
         * 
         * @param string $id the id attr
         * @param array $options array of options as option_value => option_html
         * @param string $attr_insert allows the user to add own attrs
		 * @param string $name name attr
         * @param string $value value of the input
         * @param string $first html of first option
         * @param string $firstVal option value of first option
         * 
         * @return <type>
         */
			public static function makeSelect(string $id, array $options, string $atrr_insert = '', string $name = '', string $value = '', string $first = '', string $firstVal = '')
			{
				$name = ($name === '') ? $id : $name;
				$outputHtml = '<select name="'.$name.'" id="'.$id.'" '.$atrr_insert.' >';
				
				if ($first != ''){
					if ($firstVal === ''){
						$outputHtml .= '<option value="'.$first.'">'.$first.'</option>';
					} else {
						$outputHtml .= '<option value="'.$firstVal.'">'.$first.'</option>';
					}
					
				}
				
				if(!empty($options)){
					foreach ($options as $option_value=>$option_html){
						//warning: 0 and '' are picked up as equal, however rarely a problem. possibly use === instead? but would cause text '0' not to match...
						$selected = ($value == $option_value) ? 'selected="selected"' :'' ;
						$outputHtml .= '<option value="'.$option_value.'" '.$selected.'>'.$option_html.'</option>';
					}
				}
				
				$outputHtml .= '</select>';
				return $outputHtml;
			}
		
        /**
         * A sequential numbered select generator
         * 
         * @param string $id the id attr
         * @param string $attr_insert allows the user to add own attrs
         * @param string $value value of the input
         * @param int $limit  integer limit
         * @param string $name  integer limit
         * @param string $direction  up or down?
         * @param string $first  html of first option
         * @param string $firstVal option value of first option
         * 
         * @return string
         */
			public static function makeSelectNumbered(string $id, string $attr_insert = '', string $value = '', int $limit = 100, string $name = '', string $direction = 'ascending', string $first = '', string $firstVal = ''): string
			{
			
				$name = ($name === '') ? $id : $name;
				$outputHtml = '<select name="'.$name.'" id="'.$id.'" '.$attr_insert.' >';
				
				if ($first != ''){
					$firstVal = ($firstVal === '') ? $first : $firstVal;
					$outputHtml .= '<option value="'.$firstVal.'">'.$first.'</option>';
				}
				
				if ($direction == 'ascending'){
					for($i = 1; $i <= $limit; $i++){
						$selected = ($value == $i) ? 'selected="selected"' :'' ;
						$outputHtml .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
					}
				} else {
					for($i = $limit; $i > 0; $i--){
						$selected = ($value == $i) ? 'selected="selected"' :'' ;
						$outputHtml .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
					}
				}
				
				$outputHtml .= '</select>';
				return $outputHtml;
			}
	
        /**
         * Function to load up one field from a table in it's entirety
         * 
         * @param PDO $DBH db handler
         * @param string $field which table field?
         * @param string $table of what table?
         * 
         * @return array
         */
			public static function loadOptionsFromDb($DBH, string $field, string $table): ?array
			{
			
				$field = trim($field, '`');
				$table = trim($table, '`');
			
				$query = new \Lectric\lecPDO($DBH);
			
				try{
					$query->setSelectFields(array($field));
					$result = $query->selStrict($table, \Lectric\lecPDO::MULTI);
					$array = array();		
					foreach ($result as $key => $row){
						$array[$row[$field]] = $row[$field];
					}
					
					return $array;
					
				} catch (\Lectric\SQLException $e){
					if(DEBUG){
						echo $e->getMessage();
					}
					return null;
				}
			
			}
		
        /**
         * Load up values by field from table with limit and order by
         * 
         * @param PDO $DBH db handler
         * @param array $field which table field?
         * @param string $table of what table?
         * @param int $limit  
         * @param array $orderBy  
         * 
         * @return array
         */
			public static function loadOptionsFromDbArray($DBH, array $fields, string $table, int $limit = 0, array $orderBy = []): ?array
			{
			
				$table = trim($table, '`');
			
				$query = new \Lectric\lecPDO($DBH);
			
				try{
					$query->setSelectFields($fields);
					
					if ($limit !== 0){
						$query->setLimit([0, $limit]);
					}
					
					if (!empty($orderBy)){
						$query->setOrderBy($orderBy);
					}
					
					$result = $query->selStrict($table, \Lectric\lecPDO::MULTI);
					$array = [];

					if ($result != null){
						foreach ($result as $key => $row){
							$array[$row[$fields[0]]] = $row[$fields[1]];
						}
					} else {
						return ['No Items'=>''];
					}
					
					return $array;
					
				} catch (\Lectric\SQLException $e){
					if(DEBUG){
						echo $e->getMessage();
					}
					return null;
				}
			
			}
}
