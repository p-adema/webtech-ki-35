from __future__ import annotations
import mysql.connector
from mysql.connector import Error
from time import sleep

CONN: MySQLConnection | None = None
CUR = None


def connect():
    conn = None
    try:
        conn = mysql.connector.connect(host='localhost',
                                       database='db',
                                       user='root',
                                       password='4S&qx6tbCH&HS5RT')
        if conn.is_connected():
            global CONN, CUR
            CONN = conn
            CUR = CONN.cursor()
            print('Connected')
            return

    except Error as e:
        print(e)


def get_video_id(tag: str) -> int:
    CUR.execute("SELECT id FROM items WHERE tag = '" + tag + "'")
    item_id = CUR.fetchone()
    return item_id[0]


def disconnect() -> None:
    if CONN is not None:
        if CUR is not None:
            CUR.close()
        CONN.close()
    print('Disconnected')


class Connection:
    def __enter__(self):
        connect()

    def __exit__(self, exc_type, exc_val, exc_tb):
        disconnect()


if __name__ == '__main__':
    with Connection():
        get_video_id('3jP3PwIGD8oaaXdUvQ7PTmGx1cgNckuwTsazRh5VQIUW1YJZ6Djdt7KGAPv2kIWa')
        get_video_id('3jP3PwIGD8oaaXdUvQ7PTmGx1cgNckuwTsazRh5VQIUW1YJZ6Djdt7KGAPv2kIWa')
        get_video_id('nqJYb1b97uVeoWAHUyWdBeCcrY5IYwDwOrauhwmMYpkApt9nGzMDMYEl6zzuTv55')
