from __future__ import annotations
import random
import sys
import comments

sql_items_h = """\n
INSERT INTO db.items 
    (tag, type, price)
VALUES
"""
sql_item = "('{tag}', '{type}', {price:.2f}),\n"

sql_videos_h = """\n
INSERT INTO db.videos 
    (tag, name, free, description, subject, uploader, views)
VALUES 
"""
sql_video = "('{tag}', '{name}', {free}, '{description}', '" + sys.argv[2] + "', 3, {views}),\n"

sql_rels_h = """\n
INSERT INTO db.course_videos 
    (video_tag, course_tag, `order`)
VALUES
"""
sql_rel = "('{video_tag}', '{course_tag}', {order}),\n"

sql_course = """\n
INSERT INTO db.courses 
    (tag, name, description, subject, creator)
VALUES 
    ('{course_tag}', 'Placeholder Title', 'Placeholder description', '""" + sys.argv[2] + """', 3);
"""


def gen_tag() -> str:
    options = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
    return ''.join(random.choice(options) for _ in range(64))


def parser(course) -> Generator[tuple[int, str, str]]:
    with open(f'{course}.csv', 'r') as file:
        file.readline()
        for order, line in enumerate(row.split(';') for row in file):
            yield order, int(line[0]), line[1], line[2]


def pricer(vid_num: int) -> tuple[int, float]:
    if vid_num:
        return 0, random.randint(50, 300) / 100
    return 1, 0.00


def generate_sql(course):
    course_tag = gen_tag()
    items = [sql_items_h, sql_item.format(tag=course_tag, type='course', price=50)]
    print(f'\nRename the course thumbnail to {course_tag}.jpg')
    videos = [sql_videos_h]
    rels = [sql_rels_h]
    users = comments.Users(4)

    with open(f'{course}.users.sql', 'w') as file:
        file.write('\n')

    with open(f'{course}.comments.sql', 'w') as file:
        file.write('\n')

    for order, num, tag, name_raw in parser(course):
        name = name_raw.replace("'", "\\'").replace('\n', '')
        free, price = pricer(num)
        description, views = comments.get_info(course, tag)
        item = sql_item.format(tag=tag, type='video', price=price)
        items.append(item)
        video = sql_video.format(tag=tag, name=name, free=free, description=description, views=views)
        videos.append(video)
        rel = sql_rel.format(video_tag=tag, course_tag=course_tag, order=order)
        rels.append(rel)

        comments.sql_comments(comments.parse_comments(course, tag, users), users, course)

    items[-1] = items[-1][:-2] + ';'
    videos[-1] = videos[-1][:-2] + ';'
    rels[-1] = rels[-1][:-2] + ';'

    with open(f'{course}.videos.sql', 'w') as file:
        file.writelines(items)
        file.write(sql_course.format(course_tag=course_tag))
        file.writelines(videos)
        file.writelines(rels)


if __name__ == '__main__':
    generate_sql(sys.argv[1])
