<?php
namespace LecAdmin;

/**
* Single user and permission management class.
*
* This class deals with the login / logout, session and permission getting for a given user.
*
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
* @license    As license.txt in root
*
*/ 
class adminUser extends \Lectric\lecPDO 
{

	private $_user_table = 'lec-admin_users';
	private $_permissions_table = 'lec-admin_user_permissions';
	private $_permissions_types_table = 'lec-admin_user_permission_types';
	
	public $name = 0;
	private $unique = '';
	
	/**
	* Override of base construct to include loadUserData()
	*
	* This construct checks to see if the session has been set, if so load up user data based on that id into class members.
	*
	* @param object $DBH the database handle passed to every extension of SQLQueryPDO
	*/
		function __construct($DBH)
		{
			parent::__construct($DBH);
			if (isset($_SESSION['admin_userid'])){
				$this->loadUserData();
			}
		}
	
	/* UTITLITY  function */
	
		
		/**
		* Load user data into private members
		*
		* @return void
		*/		
			private function loadUserData(): void
			{
				
				try {
					$this->setWhereFields( ['id' => $_SESSION['admin_userid']] );
					$this->setWhereOps('=');
					$user = $this->selStrict($this->_user_table, \Lectric\lecPDO::SINGLE);
				} catch (\Exception $e){
					if (DEBUG){
						echo 'Failed to load user data from database in loadUserData(): '.$e->getMessage();
					}
					//def don't carry on...
					$this->adminLogout();
					return;
				}
				
				$this->name = $user['name'];
				$this->unique = $user['unique'];
				return;
				
			}

			
		/**
		* Unset all session info and private members
		*
		* @return void
		*/	
			public function adminLogout(): void
			{
				unset($_SESSION['admin_userid']);
				$this->name = 0;
				$this->unique = '';
				return;
			}
		
			
		/**
		* Admin Login function
		*
		* @return \Lectric\controlAction
		*/	
			public function do_adminLogin(): \Lectric\controlAction
			{ 
				$postData = $_POST;
			
				if (isset($postData['admin_password']) && isset($postData['admin_username'])){
			
					if ((filter_var($postData['admin_username'], FILTER_VALIDATE_EMAIL) != false && !preg_match( "/[\r\n]/", $postData['admin_username']))){
						
						$username = $postData['admin_username'];
						$pass = $postData['admin_password'];
					
						//does the username  exist?
						$user = $this->loadUserArrayEmail(htmlentities($username));
						
						if ($user === null){
						
							$this->adminLogout();
							return new \Lectric\controlAction('view', '/lec-admin/login/', 'Invalid username or password.');
						
						} else {
							
							//password verify as best pass solution!
							if(password_verify($pass.$user['salt'], $user['password'])){
								
								$_SESSION['admin_userid'] = $user['id'];
								$_SESSION['unique'] = password_hash(date('Y-m-d H:i:s'), PASSWORD_DEFAULT);
								
								try {
									$this->setWhereFields(['id'=>$user['id']]);
									$this->setWhereOps('=');
									$this->setUpdateFields(['last_logged_in'=>date('Y-m-d H:i:s'), 'unique'=>$_SESSION['unique']]);
									$this->updateStrict($this->_user_table);
								} catch (\Exception $e){
									if(DEBUG){
										echo 'Failed to update last login: '.$e->getMessage();
										return new \Lectric\controlAction();
									}
									return new \Lectric\controlAction('view', '/lec-admin/login/', 'Login Failed due to a database error. ');
								}
								
								return new \Lectric\controlAction('view', '/lec-admin/');
								
							} else {
								$this->adminLogout();
								return new \Lectric\controlAction('view', '/lec-admin/login/', 'Invalid username or password.');
							}
							
						}
						
					} else {
						return new \Lectric\controlAction('view', '/lec-admin/login/', 'Invalid username or password.');
					}
					
					
				} else {
					if(DEBUG){
						echo 'username / pass not passed...';
						return new \Lectric\controlAction();
					}
				}
			
			}
		
		
        /**
         * Logs the user out and closes session, moving them back to login page...
         * 
         * @return \Lectric\controlAction
         */
			public function do_adminLogout(): \Lectric\controlAction
			{
				$postData = $_POST;
				if (isset($postData['logout'])){

					$this->adminLogout();
					return new \Lectric\controlAction('view', '/lec-admin/login/', 'Logged Out');

				}  
				
				return new \Lectric\controlAction('view', '/');
				
			}
					
		
		
		/**
		* See if a user has permission to do action / view object
		*
		* @param string $permissionIdent text identifier of permission
		*
		* @return array
		*/
			public function getAdminPermission(string $permissionIdent): ?bool
			{
				
				try {
					
					$userid = $_SESSION['admin_userid'];
					
					//is permission required? for example, in most cases webpages don't require permission
					if ($permissionIdent === 'none'){
						return true;           
					}
					
					//load up permission
					$this->setWhereFields(['identifier'=>$permissionIdent]);
					$this->setWhereOps('=');
					$permissionLoaded = $this->selStrict($this->_permissions_types_table, \Lectric\lecPDO::SINGLE);
					
					if ($permissionLoaded === null){
						return false;
					} else {
						
						$this->setWhereFields(['user'=>$userid, 'permission'=>$permissionLoaded['id']]);
						$this->setWhereOps('==');
						$result = $this->selStrict($this->_permissions_table, \Lectric\lecPDO::SINGLE);
						if ($result !== null){
							return true;
						} else {
							return false;
						}
						
					}
									
				} catch (\Exception $e){
					if (DEBUG){
						
					}
					return false;
				}
				
			}
			
		
		/**
		* Load a user array by selecting by username
		*
		* @param string $email username of user
		*
		* @return array
		*/
			public function loadUserArrayEmail(string $email): ?array
			{
			
				try {
					$this->setWhereFields( ['username' => $email] );
					$this->setWhereOps('=');
					$user = $this->selStrict($this->_user_table,  \Lectric\lecPDO::SINGLE);
				} catch (SQLException $e){
					if (DEBUG){
						echo 'Failed to load userin loadUserArray(): '.$e->getMessage();
					}
					return null;
				}
				
				return $user;
			}
			
		/**
		* Return readonly integer flag for use in filemanager
		*
		* @return int
		*/
			public function loggedIn(): ?int
			{
				if (isset($_SESSION['admin_userid']) && isset($_SESSION['unique'])){
					
					if ($_SESSION['unique'] === $this->unique){
						return true;
					} else {
						$this->adminLogout();
						return false;
					}
					
				} else {
					$this->adminLogout();
					return false;
				}
			}
		
	/* END UTILITY  function */
	
}
