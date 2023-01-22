import os
import sys
from random import choice

course = sys.argv[1]
videos = os.listdir('./' + course)


def gen_tag() -> str:
    options = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
    return ''.join(choice(options) for _ in range(64))


data = []

for video in videos:
    *name_l, end = video.split()
    name = ' '.join(name_l)
    num = end.split('.')[0][1:]
    tag = gen_tag()
    data.append((int(num), tag, name))
    os.rename(f'{course}/{video}', f'{course}/{tag}.mp4')

data.sort()

with open(course + '.csv', 'w') as file:
    file.write('NUM;TAG;NAME\n')
    for num, tag, name in data:
        file.write(f'{num};{tag};{name}\n')
