<?php

include "db_connection.php";
	class User{
		public $db;
		
		public function __construct(){
			$instance = ConnectDb::getInstance();
			$this->db = $instance->getConnection();
		}

		public function register($data){

			$password = md5($data['pass']);
			$email = $data['email'];
			$name = $data['name'];
			$stmt =	$this->db->prepare("SELECT * FROM users WHERE user_email='$email'");
			$stmt->execute();
			if ($stmt->rowCount()==0){
				$sql  = "INSERT INTO users SET user_name='$name', user_password='$password', user_email='$email'";
        		return $this->db->exec($sql);
			}
			else { 
				return false;
			}
		}

		public function login($data){

        	$password = md5($data['pass']);
			$email = $data['email'];
			$stmt = $this->db->prepare("SELECT * from users WHERE user_email='$email' and user_password='$password'");
			$stmt->execute();	
	        if ($stmt->rowCount() == 1) {		
				$result = $stmt->fetchAll();
	            $_SESSION['login'] = true;
	            $_SESSION['user'] = $result;
	            return true;
	        }
	        else{
			    return false;
			}
    	}

	    public function get_session(){
	        return isset($_SESSION['login']);
	    }

	    public function user_logout() {
	        $_SESSION['login'] = FALSE;
	        session_destroy();
	    }
		public function xss($data, $array = false){
		
			$safe = 'yes';
			$char = '';
			foreach ($data as $k => $row) {
				if($array && is_array($_POST[$k])){
					return "Invalid post ".$k;
				}
				else{
					if($row == ''){
							$char .= 'The field '.$k.' is required <br>';
							$safe = 'no';
							$row = 'aa';
					}
					if (preg_match('/[\'^":()}{#~><>|=¬]/', $row, $match)) {
						if ($k !== 'pass' && $k !== 're_pass') {
							$safe = 'no';
							$char .= 'Invalid character "'.$match[0].'" <br>';
						}
					}
				}
			}
			if($safe != "yes"){
				return $char;
			}
		}
	}
?>