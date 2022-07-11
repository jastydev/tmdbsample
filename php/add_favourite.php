<?php
	
	include("db_connection.php");
	include('session.php');
	
	$movie = json_decode(base64_decode($_POST["movie_data"]));
	
	$connection = openConnection();
	
	$user_id = $_SESSION['session_user_id'];
	
	$sql = "SELECT * FROM users WHERE id='$user_id'";
	$user = mysqli_query($connection, $sql);
	
	$sql = "SELECT count(*) FROM favourites WHERE movie_id='$movie->id' and user_id='$user_id'";
	$favorite_movie_exists = mysqli_query($connection, $sql);
	$row = mysqli_fetch_assoc($favorite_movie_exists);
	
	if(!$row['count(*)'] > 0){
		if(mysqli_num_rows($user) > 0){
			$sql = $connection->prepare("INSERT INTO favourites (user_id, movie_id, title, poster_path, overview, release_date) VALUES (?, ?, ?, ?, ?, ?)");
			$sql->bind_param("iissss", $user_id, $movie->id, $movie->title, $movie->poster_path, $movie->overview, $movie->release_date);
			$sql->execute();
			$sql->close();
		}
	}
	
	closeConnection($connection);