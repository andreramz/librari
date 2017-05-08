<?php
	session_start();
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if($_POST['command'] === 'login') {
			login();
		}
	}

	function connectDB() {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "personal_library";

		$connection = mysqli_connect($servername, $username, $password, $database);

		if (!$connection) {
			die("Connection failed: " + mysqli_connect_error());
		}
		return $connection;
	}

	function login(){
		$connection = connectDB();
		$username = $_POST['username'];
		$password = $_POST['password'];
		$sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
		$result = mysqli_query($connection, $sql);
		$fetched = mysqli_fetch_row($result);
		$role = $fetched[3];
		if($result->num_rows > 0){
			$_SESSION['nama'] = $username;
			$_SESSION['id'] = $fetched[0];
			$_SESSION['role'] = $role;
			header("Location: home.php");
		}
		else{
			$_SESSION['login'] = "Gagal";
			header("Location: index.php");
		}
		mysqli_close($connection);
	}
	function logout(){
		session_destroy();
		header("Location: index.php");
	}

	function writeReview($review){
		$conn = connectDB();
		$idBook = $_SESSION['bookIDpage'];
		$idUser = $_SESSION['id'];
		$theReview = $review;
		$sql = "INSERT INTO review (book_id, user_id, date, content) values ('$idBook','$idUser',CURDATE(),'$theReview')";
		$result = mysqli_query($conn, $sql);
		if(!$result){
			die("Error: $sql");
		}
		$_SESSION['reviewstatus'] = 'success';
		mysqli_close($conn);
	}

	if (isset($_GET['action']) && $_GET['action'] == 'logout') {
		logout();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_SESSION['bookIDpage']) && $_POST['command'] === 'review') {
			writeReview($_POST['revcontent']);
			$bookpage = $_SESSION['bookIDpage'];
			header("Location: bookinfo.php?id=$bookpage");
		}
	}
?>