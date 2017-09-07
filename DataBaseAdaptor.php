 <?php

	class DatabaseAdaptor {
		private $DB;
		public function __construct() {
			$db = 'mysql:dbname=onepassword;host=127.0.0.1';
			$user = 'root';
			$password = '';
			
			try {
				$this->DB = new PDO ( $db, $user, $password );
				$this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			} catch ( PDOException $e ) {
				echo ('Error establishing connection.');
				exit ();
			}
		}
		
		public function getPasswordsArray() {
			$id = $_SESSION['id'];
			$stmt = $this->DB->prepare ( "SELECT id, password, descrip FROM passwords WHERE username = '$id'" );
			$stmt->execute ();
			return $stmt->fetchAll ( PDO::FETCH_ASSOC );
		}
		
		// Creates table of users and passwords if it doesn't exist
		public function makeTables() {
			$stmt = $this->DB->prepare ( "CREATE TABLE IF NOT EXISTS `users` (`id` int(11) 
			NOT NULL auto_increment, `username` varchar(64) NOT NULL default '',
			`password` varchar(255) NOT NULL default '',
			PRIMARY KEY  (`id`), UNIQUE KEY `username` (`username`))" );
			$stmt->execute();
			$stmt = $this->DB->prepare ( "CREATE TABLE IF NOT EXISTS `passwords` (`id` int(11) 
			NOT NULL auto_increment, `username` varchar(64) NOT NULL default '', `password` varchar(255)
			NOT NULL default '', `descrip` varchar(255) NOT NULL default '', PRIMARY KEY (`id`))");
			$stmt->execute ();
		}
		
		// Create a new password for logged-in user
		public function addNewPass($user, $pass, $descrip) {
			$stmt = $this->DB->prepare ( "INSERT INTO passwords (username, password, descrip) values(:user, :pass, :descrip)");
			$stmt->bindParam ('user', $user );
			$stmt->bindParam ('pass', $pass);
			$stmt->bindParam ('descrip', $descrip);
			$stmt->execute();
		}
		
		// Registers a user to the table of users
		public function addUser($user, $password){
			$stmt = $this->DB->prepare("INSERT INTO users (username, password) values(:username, :password)");
			$stmt->bindParam ('username', $user );
			$stmt->bindParam ('password', $password);
			$stmt->execute();
		}
		
		public function logout(){
			session_destroy();
		}
		
		public function deletePass($ID){
			$stmt = $this->DB->prepare ( "DELETE FROM passwords WHERE id= :ID" );
			$stmt->bindParam('ID', $ID);
			$stmt->execute();
		}
		
		// Yes if username available, no if not
		public function checkValid($username){
		$stmt = $this->DB->prepare("SELECT * FROM users WHERE username= :username");
		$stmt->bindParam('username', $username);
		$stmt->execute();
		if (count($stmt->fetchAll( PDO::FETCH_ASSOC )) > 0){
			return 'No';
		}
		return 'Yes';
		}	
		
		// returns pass from username if exists
		public function getPass($username){
			$stmt = $this->DB->prepare( "SELECT * FROM users WHERE username='$username'" );
			$stmt->execute();
			$pWords = $stmt->fetchAll( PDO::FETCH_ASSOC );
			if (count($pWords) > 0){
				foreach ($pWords as $item){
					return $item['password'];
				}
			}
			return 'No';
		}
		
	} // end DatabaseAdaptor
	
	$myDatabaseFunctions = new DatabaseAdaptor ();
	?>