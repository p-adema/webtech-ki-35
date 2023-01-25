import yt_dlp


def download_course(playlist_id: str) -> None:
    options = {
        'outtmpl': '%(playlist_title)s/%(title)s.%(ext)s',
        'format': 'mp4',
    }
    ydl = yt_dlp.YoutubeDL(options)
    ydl.download(playlist_id)

    # Remember to rename the preview to '... preview #0.mp4'
