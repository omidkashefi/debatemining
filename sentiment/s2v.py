import sense2vec
model = sense2vec.load()
freq, query_vector = model["debate|NOUN"]
print model.most_similar(query_vector, n=3)
