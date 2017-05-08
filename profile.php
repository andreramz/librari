<?php
	session_start();
	if (!isset($_SESSION['nama']) && !isset($_SESSION['id'])) {
		header("Location: index.php");
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

	function getLoanedBookId() {
		$conn = connectDB();
		$userID = $_SESSION['id'];
		$sql = "SELECT book_id FROM loan WHERE user_id = $userID";
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			die("Error: $sql");
		}
		mysqli_close($conn);
		return $result;
	}

	function getLoan() {
		$conn = connectDB();
		$userID = $_SESSION['id'];
		$sql = "SELECT * FROM loan WHERE user_id = $userID";
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			die("Error: $sql");
		}
		mysqli_close($conn);
		return $result;
	}

	function showBook($id) {
		$conn = connectDB();
		$sql = "SELECT * FROM book WHERE book_id = $id";
		$result = mysqli_query($conn, $sql);

		if (!$result) {
			die("Error: $sql");
		}
		mysqli_close($conn);
		return $result;
	}

	function returnBook(){		
		$conn = connectDB();
		$loan_id = $_POST['loanID'];	
		$sql = "DELETE FROM loan WHERE loan_id = $loan_id"; 
		$result = mysqli_query($conn, $sql);

		if($result) {
			update_quantity_return();
			$_SESSION['returnstatus'] = 'success';
		}
		else{
			die("Error: $sql");
		}
		mysqli_close($conn);
	}

	function update_quantity_return(){
		$conn = connectDB();
		$book_id = $_POST['bookID'];
		$qtyBorrow = getQuantity();
		$newQty = $qtyBorrow + 1; 
		$sql = "UPDATE book SET quantity = $newQty WHERE book_id = $book_id";
		$result = mysqli_query($conn, $sql);
		
		if(!$result) {
				die("Error: $sql");
			} 
		mysqli_close($conn);
	}

	function getQuantity(){
		$conn = connectDB();
		$book_id = $_POST['bookID'];
		$sql = "SELECT quantity FROM book WHERE book_id = $book_id";

		if($result = mysqli_query($conn, $sql)) {
				$quantity = mysqli_fetch_assoc($result);
				$qty = $quantity['quantity'];
				return $qty;
		}
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if ($_POST['command'] === 'return') {
			returnBook();
		}
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
				if (isset($_SESSION['nama'])) {
					echo $_SESSION['nama'];
				} ?>'s Profile
		</title>
	</head>
	<body onload="startTime()">
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
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<?php
						if (isset($_SESSION['returnstatus']) && $_SESSION['returnstatus'] == 'success') {
							echo "<div class='alert alert-success'>
									<p><strong>Return success!</strong> Check your home to see the returned book.</p>
								</div>";
							unset($_SESSION['returnstatus']);
						}
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<img class="img-responsive" src="nouserimage.png" alt="Profile" >
				</div>
				<div class="col-sm-8">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2>Profile</h2>
						</div>
						<div class="panel-body">
							<h2><?php echo $_SESSION['nama']; ?></h2><br>
							<h5>ID Number: <?php echo $_SESSION['id']; ?></h5>
						</div>
						<div style="margin-top:55px;"></div>
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
				</div>
				<div class="col-sm-8">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2>Books In Hand</h2>
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
										</tr>
									</thead>
									<tbody>
										<?php
											$getID = getLoanedBookId();
											$loandata = getLoan();
											for ($i = 0; $i < $getID->num_rows; $i++) {
												$loan = mysqli_fetch_assoc($loandata);
												$loanID = $loan['loan_id'];
												$fetched = mysqli_fetch_assoc($getID);
												$bookid = $fetched['book_id'];
												$currentContent = showBook($bookid);
												echo "<tr>";
												while ($row2 = mysqli_fetch_row($currentContent)) {
													echo "<td>$row2[0]</td>";
													echo "<td><img src='$row2[1]' height='150' alt='$row2[2]'></td>";
													echo "<td><a style='text-decoration: none;' href='bookinfo.php?id=".$row2[0]."'>".$row2[2]."</a></td>";
													echo "<td>$row2[3]</td>";
													echo "<td>$row2[4]</td>";
													echo "<td><button class='btn btn-warning' data-toggle='modal' data-target='#returnModal' onclick='setID($row2[0], $loanID)'>Return</button></td>";
												}
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
			<div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="updateModalLabel">Return Book</h4>
						</div>
						<div class="modal-body">
							<form action="profile.php" method="post">
								<div class="form-group">
									<p>Do you want to return this book?</p>
								</div>
								<input type="hidden" id="book-number" name="bookID" value = "">
								<input type="hidden" id="loan-number" name="loanID" value = "">
								<input type="hidden" name="command" value="return">
								<button type="submit" class="btn btn-warning">Return</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			function setID(id, loan) {
				$("#book-number").val(id);
				$("#loan-number").val(loan);
			}
		</script>
	</body>
</html>