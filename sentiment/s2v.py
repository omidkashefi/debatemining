import sense2vec
import fileinput

model = sense2vec.load()
print "Enter topic: "
for line in fileinput.input():
    freq, query_vector = model[u"{}|NOUN".format(line.strip())]
    print model.most_similar(query_vector, n=10)
    print "Enter topic: "
