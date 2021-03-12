<?php
    require_once "controller/DatabaseConnection.php";
    use database\DatabaseConnection;

    class post_manager{
        private static $instance;
		private $database;

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new post_manager();
			}
			return self::$instance;
		}

        private function __construct() {
			$this->database = new DatabaseConnection("localhost", "root", "", "project");

			if (!$this->database->connect()) {
				die("Could not connect to the database.");
			}
		}

        private function execute($query){
            return $this ->database -> query($query);
            $array = $this ->database -> toarray($array);
            return $array;
        }

        public function get_all_posts(){
            return $this -> execute("SELECT * FROM post WHERE active = 1");
        }
    }
?>