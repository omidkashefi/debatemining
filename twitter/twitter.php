<?php
	require_once('TwitterAPIExchange.php');
	
	/** Set access tokens here **/
	$settings = array(
	'oauth_access_token' => "258873860-oKReHIJsIBCease6hN2nXt6lE3vf6EHCfDnHMUvZ",
	'oauth_access_token_secret' => "1SzTa8PxNb5otYvmzexfoAJo6y73kYxL80jH4j7RszNBK",
	'consumer_key' => "EcTYhzTSYJgyM6rMuVt19R7ki",
	'consumer_secret' => "FoOrhjlu5gTiIKnGWbsVd40Li8y30iTtbEXsIw5llhs8UKYxoj"
	);

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
