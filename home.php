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

	if (!isset($_SESSION['nama'])) {
		header("Location: index.php");
	}
	function addBook(){		
		$conn = connectDB();		
		$imageSource = $_POST['insert-url'];
		$bookTitle = $_POST['insert-title'];
		$authorname = $_POST['insert-author'];
		$publishername = $_POST['insert-publisher'];
		$descript = $_POST['insert-description'];
		$quan = $_POST['insert-quantity'];
		$cekBook = checkTheBook($bookTitle);
		if ($cekBook == "nothing"){
			$sql = "INSERT into book (img_path,title,author,publisher,description,quantity) values ('$imageSource','$bookTitle','$authorname','$publishername','$descript','$quan')";
			$_SESSION['addstatus'] = 'success'; 
		}
		else{
			$qtyGet = getQuantityFromTitle($bookTitle);
			$newQty = $qtyGet + $quan;
			$sql = "UPDATE book SET quantity = $newQty WHERE title = '$bookTitle'";
			$_SESSION['addstatus'] = 'warning';
		}

		$result = mysqli_query($conn, $sql);
		if($result) {
			if ($cekBook == "nothing") {
				headToBook($bookTitle);
			}
		}
		else{
			die("Error: $sql");
		}
		mysqli_close($conn);
	}

	function headToBook($title) {
		$newBookID = getNewBookID($title);
		header("Location: bookinfo.php?id=$newBookID");
	}

	function getNewBookID($title) {
		$conn = connectDB();
		$sql = "SELECT book_id FROM book WHERE title = '$title'";
		$result = mysqli_query($conn, $sql);
		if(!$result) {
			die("Error: $sql");
		}
		$arrayid = mysqli_fetch_assoc($result);
		$getID = $arrayid['book_id'];
		return $getID;
	}

	function checkTheBook($title){
		$conn = connectDB();
		$sql = "SELECT * FROM book WHERE title = '$title'";
		$result = mysqli_query($conn, $sql);
		if (!$result) {
			die("Error: $sql");
		}

		if($result->num_rows > 0) {
			$bookdata = mysqli_fetch_assoc($result);
			mysqli_close($conn);
			return $bookdata;
		}
		else {
			mysqli_close($conn);
			return "nothing";
		}
	}

	function borrow(){		
		$conn = connectDB();		
		$id_book = $_POST['bookIDtoBorrow'];
		$session_user = $_SESSION['id'];
		$sql = "INSERT into loan (book_id,user_id) values ('$id_book', '$session_user')"; 
		$result = mysqli_query($conn, $sql);

		if($result) {
			update_quantity_borrow();
			$_SESSION['borrowstatus'] = 'success';
		}
		else{
			die("Error: $sql");
		}
		mysqli_close($conn);
	}

	function updateBook(){		
		$conn = connectDB();
		$bookid = $_POST['update-id'];		
		$url = $_POST['update-url'];
		$title = $_POST['update-title'];
		$author = $_POST['update-author'];
		$publisher = $_POST['update-publisher'];
		$quantity = $_POST['update-quantity'];
		$session_user = $_SESSION['id'];
		$sql = "UPDATE book SET img_path='$url', title='$title', author='$author', publisher='$publisher', quantity=$quantity WHERE book_id=$bookid"; 
		$result = mysqli_query($conn, $sql);

		if($result) {
			$_SESSION['updatestatus'] = 'success';
		}
		else{
			die("Error: $sql");
		}
		mysqli_close($conn);
	}

	function update_quantity_borrow(){
		$conn = connectDB();
		$book_id = $_POST['bookIDtoBorrow'];
		$qtyBorrow = getQuantity();
		$newQty = $qtyBorrow - 1; 
		$sql = "UPDATE book SET quantity = $newQty WHERE book_id = $book_id";
		$result = mysqli_query($conn, $sql);
		
		if(!$result) {
				die("Error: $sql");
			} 
		mysqli_close($conn);
	}

	function getQuantity(){
		$conn = connectDB();
		$book_id = $_POST['bookIDtoBorrow'];
		$sql = "SELECT quantity FROM book WHERE book_id = $book_id";

		if($result = mysqli_query($conn, $sql)) {
				$quantity = mysqli_fetch_assoc($result);
				$qty = $quantity['quantity'];
				return $qty;
		}
	}

	function getQuantityFromTitle($title){
		$conn = connectDB();
		$sql = "SELECT quantity FROM book WHERE title = '$title'";

		if($result = mysqli_query($conn, $sql)) {
				$quantity = mysqli_fetch_assoc($result);
				$qty = $quantity['quantity'];
				return $qty;
		}
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if ($_POST['command'] === 'borrow') {
			borrow();
		}
		if ($_POST['command'] === 'insertbook') {
			addBook();
		}
		if ($_POST['command'] === 'updatebook') {
			updateBook();
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
		<title>Home | LIBRARI</title>
		<script src="clockscript.js"></script>
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
				<div class="col-sm-2"></div>
				<div class="col-sm-8 centered">
					<h1 id="loginpage">L I B R A R I</h1>
					<h2 id="logintag">Rent your books here!</h2>
				</div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php
						if (isset($_SESSION['addstatus']) && $_SESSION['addstatus'] == 'warning') {
							echo "<div class='alert alert-warning'>
								<p><strong>Warning!</strong> You added the book with the title that already exists. The quantity of the book has been added.</p>
							</div>";
						}
						if (isset($_SESSION['updatestatus']) && $_SESSION['updatestatus'] == 'success') {
							echo "<div class='alert alert-success'>
								<p><strong>Edit success!</strong></p>
							</div>";
						}						
						if (isset($_SESSION['borrowstatus']) && $_SESSION['borrowstatus'] == 'success') {
							echo "<div class='alert alert-success'>
								<p><strong>Borrow success!</strong> Check your profile to see your loaned book.</p>
							</div>";
						}
						unset($_SESSION['updatestatus']);
						unset($_SESSION['addstatus']);
						unset($_SESSION['borrowstatus']);
					?>
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
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2 class="text-center">Menu</h2>
						</div>
						<div class="panel-body">
							<nav>
								<ul class="nav nav-pills nav-stacked" style='font-size: 14pt;'>
									<?php
										if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
											echo "<li><a data-toggle='modal' href='#insertModalBook'><span class='glyphicon glyphicon-plus'></span> Add Book</a></li>";
										}
									?>
									<li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
								</ul>
							</nav>
						</div>
					</div>
				</div>
				<div class="col-sm-8">
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
												echo "<td><img src='".$row[1]."' height='150' alt='".$row[2]."'></td>";
												echo "<td><a style='text-decoration: none;' href='bookinfo.php?id=".$row[0]."'>".$row[2]."</a></td>";
												echo "<td>".$row[3]."</td>";
												echo "<td>".$row[4]."</td>";
												echo "<td>".$row[6]."</td>";
												if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
													echo '<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModalBook" onclick="setUpdateData(\''.$row[0].'\',\''.$row[1].'\',\''.$row[2].'\',\''.$row[3].'\',\''.$row[4].'\','.$row[6].')">Edit</button></td>';
												}
												else if (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
													if ($row[6] > 0) {
														echo "<td><input type='hidden' id='borrow-command' name='command' value='borrow'>
													<button type='button' class='btn btn-success' data-toggle='modal' data-target='#borrowModal' onclick='setID(".$row[0].")'>Borrow</button></td>";
													}
													else {
														echo "<td>Out of stock</td>";
													}
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
			<div class="modal fade" id="updateModalBook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="updateModalLabel">Update Book</h4>
						</div>
						<div class="modal-body">
							<form action="home.php" method="post">
								<div class="form-group">
									<label for="book-id">ID</label>
									<input type="text" class="form-control" id="update-id" name="update-id" readonly="readonly">
								</div>
								<div class="form-group">
									<label for="url">Book Image URL</label>
									<input type="text" class="form-control" id="update-url" name="update-url">
								</div>
								<div class="form-group">
									<label for="title">Title</label>
									<input type="text" class="form-control" id="update-title" name="update-title">
								</div>
								<div class="form-group">
									<label for="author">Author</label>
									<input type="text" class="form-control" id="update-author" name="update-author">
								</div>
								<div class="form-group">
									<label for="publisher">Publisher</label>
									<input type="text" class="form-control" id="update-publisher" name="update-publisher">
								</div>
								<div class="form-group">
									<label for="quantity">Quantity</label>
									<input type="number" class="form-control" id="update-quantity" name="update-quantity">
								</div>
								<input type="hidden" id="update-command" name="command" value="updatebook">
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="insertModalBook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="updateModalLabel">Add Book</h4>
						</div>
						<div class="modal-body">
							<form action="home.php" method="post">
								<div class="form-group">
									<label for="url">Book Image URL</label>
									<input type="text" class="form-control" id="insert-url" name="insert-url">
								</div>
								<div class="form-group">
									<label for="title">Title</label>
									<input type="text" class="form-control" id="insert-title" name="insert-title">
								</div>
								<div class="form-group">
									<label for="author">Author</label>
									<input type="text" class="form-control" id="insert-author" name="insert-author">
								</div>
								<div class="form-group">
									<label for="publisher">Publisher</label>
									<input type="text" class="form-control" id="insert-publisher" name="insert-publisher">
								</div>
								<div class="form-group">
									<label for="description">Description</label>
									<input type="text" class="form-control" id="insert-description" name="insert-description">
								</div>
								<div class="form-group">
									<label for="quantity">Quantity</label>
									<input type="number" class="form-control" id="insert-quantity" name="insert-quantity">
								</div>
								<input type="hidden" id="update-command" name="command" value="insertbook">
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="borrowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="borrowModalLabel">Borrow Book</h4>
						</div>
						<div class="modal-body">
							<form action="home.php" method="post">
								<div class="form-group">
									<p>Do you want to borrow this book?</p>
								</div>
								<input type="hidden" id="book-number-borrow" name="bookIDtoBorrow" value="">
								<input type="hidden" name="command" value="borrow">
								<button type="submit" class="btn btn-warning">Borrow</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script>
			function setID(id) {
				$("#book-number-borrow").val(id);
			}
			function setUpdateData(id, url, title, author, publisher, quantity) {
				$("#update-id").val(id);
				$("#update-url").val(url);
				$("#update-title").val(title);
				$("#update-author").val(author);
				$("#update-publisher").val(publisher);
				$("#update-quantity").val(quantity);
			}
		</script>
	</body>
</html>