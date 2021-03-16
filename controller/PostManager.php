<?php
    require_once "controller/DatabaseConnection.php";
    use database\DatabaseConnection;

    class PostManager{
        private static $instance;
		private $database;

		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new PostManager();
			}
			return self::$instance;
		}

        private function __construct() {
			$this->database = new DatabaseConnection("localhost", "root", "", "project");

			if (!$this->database->connect()) {
				die("Could not connect to the database.");
			}
		}

		public function getUserPost(){
			$userid = $_SESSION["user_id"];
			return $this -> execute("SELECT * FROM post WHERE user_id = \"". $userid ."\"");
		}

        private function execute($query){
            $array = $this ->database -> query($query);
			if (mysqli_num_rows($array)==0){
				$array = [["title"=> "No posts yet", "post_id" => "noposts", "active" => 0, "image_ending" => "png", "likes"=>"99999999", "license"=> "cc0"]];
			}else{
				$array = $this ->database -> toarray($array);
			}
            return $array;
        }

        public function get_all_posts(){
			if (isset($_POST["sort"])){
				if($_POST["id"] == "new"){
					$sort = "created_date DESC";
				}else{
					$sort = "likes DESC";
				}
			}else{
				if($_SESSION["sort"] == "new"){
					$sort = "created_date DESC";
				}else{
					$sort = "likes DESC";
				}
			}
            return $this -> execute("SELECT * FROM post WHERE active = 1 ORDER BY ". $sort);
        }

		public function likepost(){
			if(!isset($_POST["id"])){
				return "";
			}
			if(!isset($_POST["like"])){
				return "";
			}
			if(!isset($_POST["likes"])){
				return "";
			}
			try{
				$likes = intval($_POST["likes"]) + 1;
			}catch(Exception $e){
				return "";
			}
			$this -> database -> query("UPDATE post SET likes=". $likes ." WHERE post_id = \"". $_POST["id"] ."\"");
		}

		public function changeactive(){
			$postid = $_POST["id"];
			if ($postid == "nopost"){
				return "";
			}
			$current = $this -> execute("SELECT * FROM post WHERE post_id = \"". $postid ."\"");
			if($current[0]["post_id"] == "noposts"){
				return "";
			}
			if($current[0]["active"] == 1){
				$will = 0;
			}else{
				$will = 1;
			}
			$this ->database -> query("UPDATE post SET active=". $will ." WHERE post_id = \"". $postid ."\"");
		}

        public function createPost() {
			$userid = $_SESSION["user_id"];

			if (!isset($_POST["title"]) || empty($_POST["title"])) {
				return "You must specify a title.";
			}
			$title = $_POST["title"];

			if (strlen($title) > 30) {
				return "The title is too long.";
			}


			$allowedLicenses = array("all-rights-reserved", "cc0", "cc-by", "cc-by-sa", "cc-by-nc", "cc-by-nc-sa", "cc-by-nd", "cc-by-nc-nd");
			if (!isset($_POST["license"]) || !in_array($_POST["license"], $allowedLicenses)) {
				return "Please select a license.";
			}
			$license = $_POST["license"];

			$active = $_POST["active"];
			if($active != "0" && $active != "1"){
				return "Please select if Post should be active";
			}

			if (!isset($_FILES["image"])) {
				return "You must upload an image file.";
			}

			$tempPath = $_FILES["image"]["tmp_name"];
			if ($_FILES["image"]["size"] > 100000000000) {
				return "The uploaded file is too big.";
			}

			$allowedTypes = array("image/png", "image/webp");
			$fileInfo = new finfo();
			$mimeType = $fileInfo->file($tempPath, FILEINFO_MIME_TYPE);
			if (!in_array($mimeType, $allowedTypes)) {
				return "The file must either be a PNG or a WebP file.";
			}

			$uploadDirectory = "post/images";
			$imageid = $this -> getrandomid();
			$imageending = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
			$destinationFilePath = $uploadDirectory . DIRECTORY_SEPARATOR . $imageid . "." . $imageending;

			if (!move_uploaded_file($tempPath, $destinationFilePath)) {
				return "An error occurred while saving the image. Please try again later.";
			}

			$this->generateThumbnail($destinationFilePath, $imageid);

			$result = $this->database->query("INSERT INTO post(title, license, user_id, image_ending, post_id, active) VALUES(?, ?, ?, ?, ?, ?)", array($title, $license, $userid, $imageending,$imageid, $active), array("s", "s", "s", "s", "s", "s"));

			if ($result !== true) {
				return "An error occurred while saving the post.";
			}

			return "sucsessfully created Post " . $imageid .". To view go to ";
		}

        private function getrandomid(){
            $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            $id = "";
            for ($x = 0; $x < 10; $x++) {
                $id = $id . $str[rand(0, strlen($str)-1)];
            }
            return $id;
        }

		private function generateThumbnail($imagePath, $imageid) {
			
			$imageData = getimagesize($imagePath);
			$width = $imageData[0];
			$height = $imageData[1];
			$imageType = $imageData[2];


			$image = null;
			if ($imageType == IMAGETYPE_PNG) {
				$image = imagecreatefrompng($imagePath);
			}
			else if ($imageType == IMAGETYPE_WEBP) {
				$image = imagecreatefromwebp($imagePath);
			}
			else {
				return $imagePath;
			}

			if ($width >= 128) {
				$image = imagescale($image, 128);
			}

			$pathInfo = pathinfo($imagePath);
			$thumbnailPath = "post/thumbnail/" . $imageid . "." . $pathInfo["extension"];

			if ($imageType == IMAGETYPE_PNG) {
				imagepng($image,$filename=$thumbnailPath);
			}
			else if ($imageType == IMAGETYPE_WEBP) {
				imagewebp($image,$filename= $thumbnailPath, 100);
			}

			return $thumbnailPath;
		}

		public function logout(){
			$_SESSION["username"] = null;
			$_SESSION["logedin"] = null;
			$_SESSION["user_id"] = null;
		}

		public function login(){
			$result = $this->execute("SELECT * FROM user WHERE username =\"". $_POST["username"] ."\"");
			if($result == [["title"=> "No posts yet", "post_id" => "noposts"]]){
				echo "FUCK ME";
				return "Username or Password wrong";
			}
			$password = $result[0]["password"];
			if (!password_verify ($_POST["password"] , $password)){
				return "Username or Password wrong";
			}
			$_SESSION["username"] = $result[0]["username"];
			$_SESSION["logedin"] = True;
			$_SESSION["user_id"] = $result[0]["id"];
			return "You are loged In";
		}

		public function signup(){
			if (!isset($_POST["username"]) || empty($_POST["username"])) {
				return "You must specify a username.";
			}
			if (!isset($_POST["password"]) || empty($_POST["password"])) {
				return "You must specify a password.";
			}
			if(strlen($_POST["password"]) < 8 && strlen($_POST["password"]) > 256){
				return "Password must be 8 characters or longer.";
			}
			if(strlen($_POST["username"]) > 20 && strlen($_POST["username"]) < 5){
				return "Username must be between 5 and 20 characters.";
			}
			if (preg_match('/[\'^%&*()}{#~?><>,|=+¬-]/', $_POST["username"])){
				return "Username cant have any special characters.";
			}
			if (!preg_match('/[\'^£$%&*()}{@#~?><!>,|=_+¬-]/', $_POST["password"])){
					return "Password needs at least 1 special character.";
			}
			$password = password_hash($_POST["password"], PASSWORD_ARGON2I);
			$result = $this->database->query("INSERT INTO user(username, password) VALUES(?, ?)", array($_POST["username"],$password), array("s", "s"));
			if ($result !== true) {
				return "An error occurred while saving your Account Data.";
			}
			return "Created account succsessfully";
		}

	}
?>