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

        private function execute($query){
            $array = $this ->database -> query($query);
			if (mysqli_num_rows($array)==0){
				$array = [["title"=> "No posts yet", "post_id" => "noposts"]];
			}else{
				$array = $this ->database -> toarray($array);
			}
            return $array;
        }

        public function get_all_posts(){
            return $this -> execute("SELECT * FROM post WHERE active = 1 ORDER BY created_date DESC");
        }

        public function createPost() {
			$userid = 1;

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

	}
?>