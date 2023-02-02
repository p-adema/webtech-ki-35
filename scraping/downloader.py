import yt_dlp
from os import mkdir

playlist_ids = {
    'Physics': 'PL8dPuuaLjXtN0ge7yDk_UA0ldZJdhwkoV',
    'Environment': 'PL8dPuuaLjXtOikZljhKAe28AkupJXnS2u',
    'IUCN': 'PLkDmAh6O4MGpeE3meltou_rkmLxRs3kck',
    'Climate': 'PL8dPuuaLjXtMx8ZIQV9NduU_HFjDwykuj',
    'Chemistry': 'PL8dPuuaLjXtPHzzYuWy6fYEaX9mQQ8oGr',
    'AI': 'PL8dPuuaLjXtO65LeD2p4_Sb5XQ51par_b',
    'Computer': 'PL8dPuuaLjXtNlUrzyH5r6jN9ulIgZBpdo',
    'Biology': 'PL3EED4C1D684D3ADF',
    'Statistics': 'PL8dPuuaLjXtNM_Y-bUAhblSAdWRnmBUcr'
}


def download_course(course: str) -> None:
    if course not in playlist_ids:
        print('Please add this playlist ID to the list')
        exit(1)

    mkdir(course)
    playlist_id = playlist_ids[course]
    options = {'outtmpl': f'{course}/%(title)s.%(ext)s',
               'format': 'mp4', 'writethumbnail': True,
               'writesubtitles': True,
               'postprocessors': [
                   {'key': 'FFmpegThumbnailsConvertor', 'format': 'jpg', 'when': 'before_dl'},
                   {'key': 'FFmpegEmbedSubtitle', 'already_have_subtitle': False}
               ],
               'writeinfojson': True,
               'getcomments': True,
               'extractor_args': {'youtube': {'max_comments': ['50', '20', '50', '5'], 'comment_sort': ['top']}},
               'quiet': True}

    ydl = yt_dlp.YoutubeDL(options)
    ydl.download(playlist_id)
