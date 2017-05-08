<?php
	session_start();
	$obtained = "";
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

	function getBookData($id) {
		$conn = connectDB();
		$sql = "SELECT * FROM book WHERE book_id = $id";
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			die("Error: $sql");
		}
		$arrayres = mysqli_fetch_assoc($result);
		mysqli_close($conn);
		return $arrayres;
	}

	function getReview($id) {
		$conn = connectDB();
		$sql = "SELECT * FROM review WHERE book_id = $id ORDER BY review_id ASC";
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			die("Error: $sql");
		}
		mysqli_close($conn);
		return $result;
	}

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$obtained = getBookData($_GET['id']);
		$_SESSION['bookIDpage'] = $_GET['id'];
	}
	else {
		die("Where's the book's ID?");
	}

	function getUserName($id) {
		$conn = connectDB();
		$sql = "SELECT username FROM user WHERE user_id = $id";
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			die("Error: $sql");
		}
		$arrayres = mysqli_fetch_assoc($result);
		$name = $arrayres['username'];
		mysqli_close($conn);
		return $name;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="clockscript.js"></script>
		<title>
			<?php
				if (!$obtained) {
					echo "Book not found | LIBRARI";
				}
				else {
					echo $obtained['title'] ." | LIBRARI";
				}
			?>
		</title>
	</head>
	<body onload="startTime()">
		<?php 
			if (isset($_SESSION['nama'])) { ?>
				<nav class="navbar navbar-default">
					<div class="container-fluid">
						<div class="navbar-header">
							<a class="navbar-brand" href="index.php" style="color: white; font-weight: bolder;"><strong>L I B R A R I</strong></a>
						</div>
						<ul class="nav navbar-nav navbar-right">
							<li>
								<a href="home.php" style="color:white;"><span class="glyphicon glyphicon-home"></span> Home</a>
							</li>
							<li>
								<a href="profile.php" style="color:white;"><span class="glyphicon glyphicon-user"></span> <?php if (isset($_SESSION['nama'])) {echo $_SESSION['nama'];} ?></a>
							</li>
		  					<li>
		  						<a href="config.php?action=logout" style="color:white;"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
		  					</li>
						</ul>
					</div>
				</nav>
		<?php	}
			else { ?>
				<nav class="navbar navbar-default">
					<div class="container-fluid">
						<div class="navbar-header">
							<a class="navbar-brand" href= "index.php" style="color: white; font-weight: bolder;"><strong>L I B R A R I</strong></a>
						</div>
						<form class="navbar-form navbar-right texting" method="post" action="config.php">
								<?php
								if(isset($_SESSION['login'])&&$_SESSION['login'] == "Gagal"){
									echo "<div class='alert alert-danger form-group'><strong>Username atau Password Salah!</strong></div>";
									session_unset($_SESSION['login']);
								}
								?>	
  							<div class="form-group">
    							<label for="username">Username:</label>
    							<input type="text" class="form-control" id="username" name ="username">
  							</div>
  							<div class="form-group">
    							<label for="password">Password:</label>
    							<input type="password" class="form-control" id="password" name ="password">
  							</div>
  							<input type="hidden" id="login-command" name="command" value="login">
  							<button type="submit" class="btn btn-default">Submit</button>
						</form>
					</div>
				</nav>
		<?php	}
		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<?php
						if (isset($_SESSION['addstatus']) && $_SESSION['addstatus'] == 'success') {
							echo "<div class='alert alert-success'>
								<p><strong>Add book success!</strong></p>
							</div>";
						}
						if (isset($_SESSION['reviewstatus']) && $_SESSION['reviewstatus'] == 'success') {
							echo "<div class='alert alert-success'>
								<p><strong>Review submitted!</strong></p>
							</div>";
						}
						unset($_SESSION['addstatus']);
						unset($_SESSION['reviewstatus']);
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<?php echo "<img src='".$obtained['img_path']."' style='width:100%;' alt='".$obtained['title']."'>"?>
				</div>
				<div class="col-sm-8">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2>Book Info</h2>
						</div>
						<div class="panel-body">
							<?php 
								if (!$obtained) {
									echo "Book not exist!";
								}
								else { ?>
									<h3><?php echo $obtained['title']?></h3>
									<p><strong>Author : </strong><?php echo $obtained['author']?></p>
									<p><strong>Publisher : </strong><?php echo $obtained['publisher']?></p>
									<p><?php echo $obtained['description']?></p>
									<p>Quantity : </p>
									<h4><?php echo $obtained['quantity']?></h4>
							<?php }
							?>
							
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2 class="text-center">Time</h2>
						</div>
						<div class="panel-body" id="timeNow">
							<h2 class="text-center" id="date"></h2>
							<h2 class="text-center" id="clock"></h2>
						</div>
					</div>
					<?php 
						if (isset($_SESSION['nama'])) { ?>
							<div class="panel panel-default" name = "writeReview" id = "writeReview">
								<div class="panel-heading">
									<h2 class="text-center">Write Your Review</h2>
								</div>
								<div class="panel-body">
									<form id="writeReview" method="post" action="config.php">
										<textarea placeholder="Give your review :)" id ="writeReviewField" name="revcontent" style="width:100%; height: 200px;"></textarea>
										<input type="hidden" name="command" value="review">
										<button type="submit" class="btn btn-default" name="submit-review">Submit</button>
									</form>
								</div>
							</div>				
					<?php } ?>	
				</div>
				<div class="col-sm-8">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2>Review About This Book</h2>
						</div>
						<div class="panel-body">
							<?php
								$reviews = getReview($_GET['id']);
								for ($i = 0; $i < $reviews->num_rows;$i++) {
									$fetched = mysqli_fetch_assoc($reviews);
									echo "<div class='panel panel-default'>
										<div class='panel-heading'>
											<h4>".getUserName($fetched['user_id'])."</h4>
											<p style='font-size:12pt;'>on ".$fetched['date']."</p>
										</div>
										<div class='panel-body'>
											<p>".$fetched['content']."</p>
										</div>
									</div>";
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>