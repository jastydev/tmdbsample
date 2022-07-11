<?php 
	include('php/session.php');
	include('php/data.php');

	$movie = getMovie($_GET['movie_id']);
?>

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
		<nav class="navbar navbar-expand-md navbar-dark">
			<a class="navbar-brand"><img src="images/mp.png"></a>
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
		
		<div class="container">
			<div class="row mt50 selected-movie">
				
				<div class="col-sm-4"> 
					<img class="poster-image" src="https://image.tmdb.org/t/p/w342<?php echo $movie->poster_path ?>">
					
					<div class="movie">
					<?php if(isset($_SESSION['session_user_id'])){ ?>
						<div class="center">
							<div class="fav_<?php echo $movie->id ?> heart addToFavourites" data-movie_data="<?php echo base64_encode(json_encode($movie)) ?>" data-movie_id="<?php echo $movie->id ?>"></div>
						</div>
					<?php } else { ?>
						<div class="center"><a href="login.php" class="heart"></a></div>
					<?php } ?>
					</div>
				</div>
				<div class="col-sm-8">
					<h4><?php echo $movie->title ?></h4>
					<p class="blue-text"><?php echo "Release date: " . $movie->release_date ?></p>
					<p class="text-white"><?php echo $movie->overview ?></p>
				</div>
				
			</div>
		</div>
		
		<!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
		
		<script>
			$(".addToFavourites").click(function(){
				var movie_id = $(this).data('movie_id')
				var data = {
					movie_data: $(this).data('movie_data')
				};
				$.ajax({
					type: "POST",
					url: "/php/add_favourite.php",
					data: data,
					success : function(data){
						$('.fav_' + movie_id).addClass('bg-red');
						$('head').append('<style>'+'.fav_'+ movie_id +'.heart::before{background-color: #ff3333 !important;} '+'.fav_'+ movie_id +'.heart::after{background-color: #ff3333 !important;}</style>');
					}
				});
			})
			$("#moviesearch").autocomplete({
				source: "php/search.php",
				minLength: 2,
				select: function( event, ui ) {
					window.location.href = '/movie.php?movie_id=' + ui.item.id;
					console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
				}
			});
			$(document).ready(function() {
				$('.nav-item').removeClass('active');
			});
		</script>
	</body>
</html>



