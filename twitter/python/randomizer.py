import sys, random, re, codecs

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


dic = []
with open(sys.argv[1]) as f:
    for line in f:
        #parts = line.split(';')
        dic.append(line)

o1 = codecs.open(sys.argv[1] + ".all", "w+", "utf-8")
o2 = codecs.open(sys.argv[1] + ".txt", "w+", "utf-8")

for i in range(1, 140):
    r =  random.choice(dic)
    o1.write('\n%s' % r)
    o2.write('%s\n' % removeMentions(removeURL(r.split(';')[1])))
