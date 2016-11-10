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

	$twitter = new TwitterAPIExchange($settings);

	/** twitter search url **/
	$url = "https://api.twitter.com/1.1/search/tweets.json";

	$requestMethod = "GET";

	/** Set query params here **/
	$query = urlencode("steelers");
	$result_type = "popular"; //mixed, recent, popular
	$count = "100";
	$since = "2016-11-06"; //YYYY-MM-DD
	$until = "2016-11-07"; //YYYY-MM-DD
	$lang = "en";
	$max_id = "995390121885528064"; //set max_id to the lowest id you got

	$tweetcount = 0;


 	do
	{
		$getfield = "?q=" . $query . " since:" . $since . " until:" . $until . "&count=" . $count . "&result_type=" . $result_type . "&max_id=" . $max_id . "&lang=" . $lang ;

		$string = json_decode($twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest(),$assoc = TRUE);

		if(array_key_exists('errors', $string) && $string["errors"][0]["message"] != "")
		{
			echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string["errors"][0]["message"]."</em></p>";
			exit();
		}

		//$res = json_encode($string['statuses']);
		//file_put_contents("/var/www/html/".$since, $res, FILE_APPEND);
		//echo "LEN: " . count($string['statuses']);

		foreach($string['statuses'] as $items)
		{
			$tweetcount += 1;
			echo "Time: ".$items['created_at']."<br />";
			echo "ID: ". $items['id']."<br />";

			if ($max_id > $items['id'])
				$max_id = $items['id'];

			echo "Tweet: ". $items['text']."<br />";
			//echo "Tweeted by: ". $items['user']['name']."<br />";
			//echo "Screen name: ". $items['user']['screen_name']."<br />";
			//echo "Followers: ". $items['user']['followers_count']."<br />";
			//echo "Friends: ". $items['user']['friends_count']."<br />";
			//echo "Listed: ". $items['user']['listed_count']."<br /><hr />";
			echo "<hr />";
		}
	} while (count($string['statuses']) > 10 );
	//count($string['statuses']) != 0
	echo "Count: ". $tweetcount;
?>
