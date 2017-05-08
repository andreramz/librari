<?php
	session_start();
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

	function selectTable($table) {
		$connection = connectDB();
		$sql = "SELECT book_id, img_path, title, author, publisher, description, quantity FROM $table";
		$result = mysqli_query($connection, $sql);

		if (!$result) {
			die("Error: $sql");
		}
		mysqli_close($connection);
		return $result;
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>List of Books</title>
	</head>
	<body>
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
		<div class="container">
			<div class="row">
				<div class="col-sm-2"></div>
				<div class="col-sm-8 centered">
					<h1 id="loginpage">L I B R A R I</h1>
					<h2 id="logintag">Rent your books here!</h2>
				</div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2>Our Books</h2>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>ID</th>
											<th>Image</th>
											<th>Title</th>
											<th>Author</th>
											<th>Publisher</th>
											<th>Quantity</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$contents = selectTable("book");
											while ($row = mysqli_fetch_row($contents)) {
												echo "<tr>";
												echo "<td>".$row[0]."</td>";
												echo "<td><img src='".$row[1]."' height='150'></td>";
												echo "<td><a style='text-decoration: none;' href='bookinfo.php?id=".$row[0]."'>".$row[2]."</a></td>";
												echo "<td>".$row[3]."</td>";
												echo "<td>".$row[4]."</td>";
												echo "<td>".$row[6]."</td>";
												echo "</tr>";
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>