# -*- coding: utf-8 -*-

import sys,getopt,got,datetime,codecs,re

def removeURL(s):
	i = s.find("http://")
	if i == -1:
		i = s.find("https://")
	if i != -1:
		s = s[0:i]

	i = s.find("pic.twitter.com")
	if i != -1:
		s = s[0:i]

	return s

def removeMentions(s):
	return ' '.join(re.sub("(@[A-Za-z0-9]+)|(\w+:\/\/\S+)"," ",s).split())

def main(argv):

	if len(argv) == 0:
		print 'You must pass some parameters. Use \"-h\" to help.'
		return

	if len(argv) == 1 and argv[0] == '-h':
		print """\nTo use this jar, you can pass the folowing attributes:
    username: Username of a specific twitter account (without @)
       since: The lower bound date (yyyy-mm-aa)
       until: The upper bound date (yyyy-mm-aa)
 querysearch: A query text to be matched
   maxtweets: The maximum number of tweets to retrieve

 \nExamples:
 # Example 1 - Get tweets by username [barackobama]
 python Exporter.py --username "barackobama" --maxtweets 1\n

 # Example 2 - Get tweets by query search [europe refugees]
 python Exporter.py --querysearch "europe refugees" --maxtweets 1\n

 # Example 3 - Get tweets by username and bound dates [barackobama, '2015-09-10', '2015-09-12']
 python Exporter.py --username "barackobama" --since 2015-09-10 --until 2015-09-12 --maxtweets 1\n

 # Example 4 - Get the last 10 top tweets by username
 python Exporter.py --username "barackobama" --maxtweets 10 --toptweets\n"""
		return

	try:
		opts, args = getopt.getopt(argv, "", ("username=", "since=", "until=", "querysearch=", "toptweets", "maxtweets="))

		tweetCriteria = got.manager.TweetCriteria()

		for opt,arg in opts:
			if opt == '--username':
				tweetCriteria.username = arg

			elif opt == '--since':
				tweetCriteria.since = arg

			elif opt == '--until':
				tweetCriteria.until = arg

			elif opt == '--querysearch':
				tweetCriteria.querySearch = arg

			elif opt == '--toptweets':
				tweetCriteria.topTweets = True

			elif opt == '--maxtweets':
				tweetCriteria.maxTweets = int(arg)


		outputFile = codecs.open(tweetCriteria.since + ".all", "w+", "utf-8")
		outputFile2 = codecs.open(tweetCriteria.since + ".txt.nourl" , "w+", "utf-8")
		outputFile3 = codecs.open(tweetCriteria.since + ".txt.nourl.nomention" , "w+", "utf-8")

		#outputFile.write('username;date;retweets;favorites;text;geo;mentions;hashtags;id;permalink')

		print 'Searching...\n'

		def receiveBuffer(tweets):
			for t in tweets:
				#outputFile.write(('\n%s;%s;%d;%d;"%s";%s;%s;%s;"%s";%s' % (t.username, t.date.strftime("%Y-%m-%d %H:%M"), t.retweets, t.favorites, t.text, t.geo, t.mentions, t.hashtags, t.id, t.permalink)))
				outputFile.write(('\n%s;"%s";%s' % (t.date.strftime("%Y-%m-%d %H:%M"), t.text, t.id)))
				outputFile2.write(('%s\n' % (removeURL(t.text))))
				outputFile3.write(('%s\n' % (removeMentions(removeURL(t.text)))))
			outputFile.flush();
			outputFile2.flush();
			outputFile3.flush();
			print 'More %d saved on file...\n' % len(tweets)

		got.manager.TweetManager.getTweets(tweetCriteria, receiveBuffer)

	except arg:
		print 'Arguments parser error, try -h' + arg
	finally:
		outputFile.close()
		outputFile2.close()
		outputFile3.close()
		print 'Done. Output file generated "output_got.csv".'

if __name__ == '__main__':
	main(sys.argv[1:])
