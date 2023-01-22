from __future__ import annotations
import random
import sys

sql_items_h = """
INSERT INTO db.items 
    (tag, type, price)
VALUES
"""
sql_item = """('{tag}', 'video', {price:.2f}),\n"""

sql_videos_h = """
INSERT INTO db.videos 
    (tag, name, free, description, subject, uploader)
VALUES 
"""
sql_video = """('{tag}', '{name}', {free}, 'TODO: descrition', 'physics', 3),\n"""

course = sys.argv[1]


def parser() -> Generator[tuple[int, str, str]]:
    with open(f'{course}.csv', 'r') as file:
        file.readline()
        for line in (row.split(';') for row in file):
            yield int(line[0]), line[1], line[2]


def pricer(vid_num: int) -> tuple[int, float]:
    if vid_num:
        return 0, random.randint(300, 1000) / 100
    return 1, 0.00


items = [sql_items_h]
videos = [sql_videos_h]

for num, tag, name_raw in parser():
    name = name_raw.replace("'", "\\'").replace('\n', '')
    free, price = pricer(num)
    item = sql_item.format(tag=tag, price=price)
    items.append(item)
    video = sql_video.format(tag=tag, name=name, free=free)
    videos.append(video)

items[-1] = items[-1][:-2] + ';'
videos[-1] = videos[-1][:-2] + ';'

with open(f'{course}.sql', 'w') as file:
    file.writelines(items)
    file.writelines(videos)
