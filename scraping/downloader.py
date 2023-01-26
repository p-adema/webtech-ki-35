import yt_dlp
from os import mkdir

playlist_ids = {
    'Physics': 'PL8dPuuaLjXtN0ge7yDk_UA0ldZJdhwkoV',
    'Climate': 'PL8dPuuaLjXtOikZljhKAe28AkupJXnS2u',
    'IUCN': 'PLkDmAh6O4MGpeE3meltou_rkmLxRs3kck'
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
               ]}

    ydl = yt_dlp.YoutubeDL(options)
    ydl.download(playlist_id)
