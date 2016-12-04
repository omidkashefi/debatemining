import sys

with open(sys.argv[1] + "all") as fa, open(sys.argv[1] + "sent") as fs:
    for la, ls in zip(fa, fs):
        parts = la.split(';')
        if len(parts) < 2:
            continue
        date = parts[0]
        text = parts[1]

        if len(parts) > 4:
            date = parts[1]
            text = parts[4]

        print '{},{},{}'.format(date, text, ls)
