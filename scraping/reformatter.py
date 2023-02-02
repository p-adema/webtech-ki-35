import os
import sys
from random import choice


def gen_tag() -> str:
    options = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
    return ''.join(choice(options) for _ in range(64))


def format_course(course):
    files = os.listdir('./' + course)
    data = []

    for video in (video for video in files if video.endswith('.mp4')):
        *name_l, end = video.split()
        name = ' '.join(name_l)
        thumbnail = video.replace('.mp4', '.jpg')
        info = video.replace('.mp4', '.info.json')
        num = end.split('.')[0][1:]
        tag = gen_tag()
        data.append((int(num), tag, name))
        os.rename(f'{course}/{video}', f'{course}/{tag}.mp4')
        os.rename(f'{course}/{thumbnail}', f'{course}/{tag}.jpg')
        os.rename(f'{course}/{info}', f'{course}/{tag}.info.json')

    data.sort()

    with open(course + '.csv', 'w') as file:
        file.write('NUM;TAG;NAME\n')
        for num, tag, name in data:
            file.write(f'{num};{tag};{name}\n')


if __name__ == '__main__':
    format_course(sys.argv[1])
