import yt_dlp

# options = {
#     'format': 'mp4',
# }

options = {
    'format': 'bestaudio/best',
    'postprocessors': [{
        'key': 'FFmpegExtractAudio',
        'preferredcodec': 'mp3',
        'preferredquality': '192',
    }],
}

ydl = yt_dlp.YoutubeDL(options)

# ydl.download("URL")
