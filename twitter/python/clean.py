import fileinput, re

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

for line in fileinput.input():
	#print len(line)
	#print line, line.split(';')[1]
	print removeMentions(removeURL(line.split(';')[1])).strip('\"')
