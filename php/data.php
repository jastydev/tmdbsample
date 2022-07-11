<?php
	include("auth.php");
	include("db_connection.php");
	
	function getTMDBMovies($page = 1){
		$api_key = $GLOBALS['api_key'];
		$url = "https://api.themoviedb.org/3/discover/movie?api_key=$api_key&language=en-US&page=$page&include_adult=false";
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$headers = array(
			"Authorization: Bearer $api_key",
			"Content-Type: application/json;charset=utf-8",
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		
		$movies = array_slice($response->results, 0, 9, true);
		
		for($x = 0; $x < count($movies); $x++){
			$m_id = $movies[$x]->id;
		}
		return $movies;
	}
	
	function getFavouriteMovies($user_id){
			$connection = openConnection();

		$sql = "SELECT * FROM favourites WHERE user_id='$user_id'";
		
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);
		
		$json = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$favourites = json_encode($json);
			closeConnection($connection);
		return $favourites;
	}
		
	function getMovie($movie_id){
		
		$api_key = $GLOBALS['api_key'];
		
		$url = "https://api.themoviedb.org/3/movie/$movie_id?api_key=$api_key&language=en-US";
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$headers = array(
			"Authorization: Bearer $api_key",
			"Content-Type: application/json;charset=utf-8",
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$movie = json_decode(curl_exec($curl));
		curl_close($curl);
		
		return $movie;
	}
	
	function searchMovies($criteria){
		$data = trim($criteria);
		$data = stripslashes($criteria);
		$data = htmlspecialchars($criteria);
		$new_criteria = "&query=" . urlencode($criteria);
		
		$api_key = $GLOBALS['api_key'];
		
		$url = "https://api.themoviedb.org/3/search/movie?$new_criteria&api_key=$api_key&language=en-US&include_adult=false";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$headers = array(
			"Authorization: Bearer $api_key",
			"Content-Type: application/json;charset=utf-8",
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		$results = [];
		foreach($response->results as $movie){
			array_push($results, ['id'=>$movie->id, 'value'=>$movie->title]);
		}
		
		return $results;
	}
	