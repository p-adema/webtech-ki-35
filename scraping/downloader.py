import yt_dlp


def download_course(playlist_id: str) -> None:
    options = {'outtmpl': '%(playlist_title)s/%(title)s.%(ext)s',
               'format': 'mp4', 'writethumbnail': True,
               'writesubtitles': True,
               'postprocessors': [
                   {'key': 'FFmpegThumbnailsConvertor', 'format': 'jpg', 'when': 'before_dl'},
                   {'key': 'FFmpegEmbedSubtitle', 'already_have_subtitle': False}
               ]}

    ydl = yt_dlp.YoutubeDL(options)
    ydl.download(playlist_id)

    # Remember to rename the preview to '... preview #0.mp4'
