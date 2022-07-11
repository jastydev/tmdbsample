<?php include('php/session.php'); ?>
<?php if(isset($_SESSION['session_user_id'])){
	header("Location: /");
} ?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>The Movie Theatre</title>
		
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
		
        <link href="css/styles.css" rel="stylesheet">
	</head>
	
	<body>
		<!--Navbar-->
		<nav class="navbar navbar-expand-md navbar-dark">
			<a class="navbar-brand" href="/"><img src="images/mp.png"></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarContent">
				<ul class="navbar-nav ml-auto">
					<li id="home" class="nav-item">
						<a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
					</li>
					<?php if(isset($_SESSION['session_user_id'])){
						echo "<li id=\"favorites\" class=\"nav-item\"><a class=\"nav-link\" href=\"/favourites.php\">Favourites</a></li>";
					} ?>
					<li id="contact" class="nav-item">
						<a class="nav-link" href="/contact.php">Contact </a>
					</li>
				</ul>
				<form class="form-inline">
					<input class="form-control form-control-sm search-input mlr5" id="moviesearch" name="moviesearch" type="search" placeholder="Search" aria-label="Search">
				</form>
				<div class="welcome">
				<?php
					if(isset($_SESSION['session_user_id'])){
						echo "<span class=\"user-welcome\">Welcome " . $_SESSION['username'] . "</span>";
						echo "<a href=\"logout.php\" type=\"button\" class=\"btn btn-primary login-btn mlr5\">Logout</a>";
					} else {
						echo "<a href=\"login.php\" type=\"button\" class=\"btn btn-primary login-btn mlr5\">Login</a>";
					}
				?>
				</div>
			</div>
		</nav>
		
		
		<?php
			include("php/db_connection.php");
			
			$username = $password = "";
			$username_err = $password_err = "";
			
			// Validate fields
			function validate($data){
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
				
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (empty($_POST["username"])) {
					$username_err = "Username is required";
				} else {
					$username = validate($_POST["username"]);
				}
				
				if (empty($_POST["password"])) {
					$password_err = "Password is required";
				} else {
					$password = validate($_POST["password"]);
				}
			}
			
			$connection = openConnection();
			
			$sql = "SELECT * FROM users WHERE username='$username'";
			$result = mysqli_query($connection, $sql);
			
			// Check password
			if (mysqli_num_rows($result) === 1) {
            	$row = mysqli_fetch_assoc($result);
				
				$hashed_password = $row['password'];
				
				if (password_verify($password, $hashed_password)) {
					
					$_SESSION['session_user_id'] = $row['id'];
					$_SESSION['username'] = $row['username'];
					header("Location: /");
				} else {
					include('php/logout.php'); 
				}
			}
			closeConnection($connection);
		?>
		
		<!--Login form-->
		<div class="container">
			<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" style="width: 50%; margin: 0 auto; position: relative; top: calc(100vh - 75vh);">
				<div class="form-group">
					<input type="text" class="form-control form-control-sm" name="username" id="username" placeholder="Username">
					<span class="error"><?php echo $username_err; ?></span>
				</div>
				<div class="form-group">
					<input type="password" class="form-control form-control-sm" name="password" id="password" placeholder="Password">
					<span class="error"><?php echo $password_err; ?></span>
				</div>
				<button type="submit" class="btn btn-primary float-right mb10">Login</button>
				<a href="/" type="button" class="btn btn-primary float-left">Back</a>
			</form>
		</div>
		
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	</body>
</html>