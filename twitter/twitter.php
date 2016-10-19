<?php
	require_once('TwitterAPIExchange.php');
	
	/** read access tokens from file **/
	$myfile = fopen("access.key", "r") or die("Unable to open file!");

	$settings = array(
	'oauth_access_token' => trim(fgets($myfile), "\n"),
	'oauth_access_token_secret' => trim(fgets($myfile), "\n"),
	'consumer_key' => trim(fgets($myfile), "\n"),
	'consumer_secret' => trim(fgets($myfile), "\n")
	);

	fclose($myfile);

	/** twitter search url **/
	$url = "https://api.twitter.com/1.1/search/tweets.json";
	
	$requestMethod = "GET";

	/** Set query params here **/
	$query = urlencode("#steelers"); 
	$result_type = "popular"; //mixed, recent, popular
	$count = "500";
	$since = "2016-10-15"; //YYYY-MM-DD
	$until = "2016-10-20"; //YYYY-MM-DD

	$getfield = "?q=" . $query . " since:" . $since . " until:" . $until . "&count=" . $count . "&result_type=" . $result_type;

	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);

	if($string["errors"][0]["message"] != "") 
	{
		echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";
		exit();
	}

	foreach($string['statuses'] as $items)
	{
		echo "Time and Date of Tweet: ".$items['created_at']."<br />";
		echo "Tweet: ". $items['text']."<br />";
		echo "Tweeted by: ". $items['user']['name']."<br />";
		echo "Screen name: ". $items['user']['screen_name']."<br />";
		echo "Followers: ". $items['user']['followers_count']."<br />";
		echo "Friends: ". $items['user']['friends_count']."<br />";
		echo "Listed: ". $items['user']['listed_count']."<br /><hr />";
	}
?>
