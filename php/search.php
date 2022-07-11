<?php
	include("auth.php");
	include("data.php");
	
	$searched_movies = [];
	if(!empty($_GET["term"])){
		$criteria = $_GET["term"];
		$searched_movies = searchMovies($criteria);
	} 
	
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($searched_movies);