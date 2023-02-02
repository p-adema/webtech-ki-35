import yt_dlp
import json
import random
import html
import os
import mysql
import db


def get_comments(video: str) -> None:
    options = {'skip_download': True, 'outtmpl': {'default': 'comments/' + video}, 'writeinfojson': True,
               'getcomments': True,
               'extractor_args': {'youtube': {'max_comments': ['50', '20', '50', '5'], 'comment_sort': ['top']}}}

    ydl = yt_dlp.YoutubeDL(options)
    ydl.download(video)


def gen_tag() -> str:
    options = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
    return ''.join(random.choice(options) for _ in range(64))


class Tags(dict):
    def __init__(self):
        super().__init__(root='null')

    def __getitem__(self, yt_id: str) -> str:
        if super().__contains__(yt_id):
            return super().__getitem__(yt_id)

        tag = gen_tag()
        self[yt_id] = tag
        return tag


class Users(dict):
    def __init__(self, offset) -> None:
        super().__init__(CrashCourse=3)
        self._count = offset

    def _next_id(self) -> int:
        self._count += 1
        return self._count

    def __getitem__(self, username: str) -> int:

        if super().__contains__(username):
            return super().__getitem__(username)

        uid = self._next_id()
        self[username] = uid
        return uid

    @property
    def count(self):
        return self._count

    def __iter__(self):
        for username in self.keys():
            if username != 'CrashCourse':
                yield username


def sanitize(string: str) -> str:
    return html.escape(''.join(char if ord(char) < 128 else '?' for char in string)).replace('\n', '<br />')


def get_info(course: str, video: str):
    with open(f'{course}/{video}.info.json', 'r') as info:
        data = json.load(info)
        return sanitize(data['description']), data['view_count']


def load_comments(course: str, video: str, users: Users):
    with open(f'{course}/{video}.info.json', 'r') as comments:
        tags = Tags()
        for comment in json.load(comments)['comments']:
            author = users[sanitize(comment['author'])]
            yield tags[comment['id']], sanitize(comment['text']), comment['timestamp'], comment['like_count'], \
                author, tags[comment['parent']]


def parse_comments(course: str, video: str, users: Users):
    comments = []
    for tag, text, timestamp, likes, uid, reply_tag in load_comments(course, video, users):
        data = {
            'tag': tag,
            'text': text,
            'timestamp': timestamp,
            'likes': likes,
            'uid': uid,
            'vid': db.get_video_id(video),
            'reply_tag': "'" + reply_tag + "'" if reply_tag != 'null' else 'null',
        }
        comments.append(data)

    return comments


users_sql_h = """
INSERT INTO db.users 
    (name, email, password, verified)
VALUES\n"""

user_sql = "    ('{name}', '{email}@gmail.com', 'NOLOGIN', 1),\n"

comments_sql_h = """
INSERT INTO comments 
    (tag, commenter_id, item_id, text, date, reply_tag, score) 
VALUES\n"""

comment_sql = "    ('{tag}', {uid}, {vid}, '{text}', FROM_UNIXTIME({timestamp}), {reply_tag}, {likes}),\n"


def sql_comments(comments: list[dict[str, str | int]], users: Users, filename: str) -> None:
    if not comments:
        return

    with open(f'{filename}.comments.sql', 'a') as file:
        for comment in comments:
            file.write(comment_sql.format(**comment))


def sql_users(users: Users, filename: str) -> None:
    with open(f'{filename}.users.sql', 'w') as file:
        file.write(users_sql_h)
        for username in users:
            user = user_sql.format(name=username, email=gen_tag())
            file.write(user)


def gen_all() -> None:
    with db.Connection():
        courses = os.listdir('.')
        users = Users(4)
        for course in next(os.walk('.'))[1]:
            print('Parsing', course, '...')
            videos = os.listdir('./' + course)
            for video in (file.split('.')[0] for file in videos if file.endswith('.info.json') and len(file) > 48):
                # print(course, video)
                video_comments = parse_comments(course, video, users)
                sql_comments(video_comments, users, course)

    sql_users(users, 'all')


if __name__ == '__main__':
    gen_all()
