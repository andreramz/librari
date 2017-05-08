<?php
	session_start();
	if (isset($_SESSION['nama'])) {
		header("Location: home.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>LIBRARI | Rent your books here</title>
	</head>
	<body id="mainpage">
		<div id="top-of-page">
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
    							<input type="text" class="form-control" id="username" name ="username" required>
  							</div>
  							<div class="form-group">
    							<label for="password">Password:</label>
    							<input type="password" class="form-control" id="password" name ="password" required>
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
						<h1 id="titlepage">L I B R A R I</h1>
						<h2 id="titletag">Rent your books here!</h2>
						<a class="btn btn-default" href="books.php" style="padding: 15px;"><span class="glyphicon glyphicon-search"></span> View our books here</a>				
					</div>
					<div class="col-sm-2"></div>
				</div>
				<div class="row" style = "margin-top: 2%;">
					<div class="col-sm-8">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2>What's in LIBRARI?</h2>
							</div>
							<div class="panel-body">
								<h4>Rent Your Books</h4>
								<p>Kamu bisa meminjam buku dari LIBRARI secara gratis, tidak dipungut biaya sepeserpun. Waktu peminjaman selama 1 minggu atau lebih.</p>
								<h4>Review The Books</h4>
								<p>Kamu bisa melakukan <span>review</span> pada buku-buku yang kamu pinjam. Penilaian kamu terhadap buku tersebut dapat membantu untuk pembaruan perpustakaan.</p>
								<h6>Enjoy your reading!</h6>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2>About LIBRARI</h2>
							</div>
							<div class="panel-body">
								<p>LIBRARI adalah suatu platform untuk peminjaman buku di perpustakaan universitas. Sebagai pelopor platform peminjaman buku teks, LIBRARI telah menyediakan lebih dari 20.000 buku teks yang bisa dipinjamkan. LIBRARI bertujuan untuk mencerdaskan masyarakat Indonesia dengan cara yang efektif dan efisien.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="big-top-offset"></div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script>
		</script>
	</body>
</html>