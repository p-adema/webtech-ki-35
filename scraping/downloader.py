import yt_dlp

options = {
    'outtmpl': 'courses/%(playlist_title)s/%(title)s.%(ext)s',
    'format': 'mp4',
}

# options = {
#     'format': 'bestaudio/best',
#     'postprocessors': [{
#         'key': 'FFmpegExtractAudio',
#         'preferredcodec': 'mp3',
#         'preferredquality': '192',
#     }],
# }

ydl = yt_dlp.YoutubeDL(options)

ydl.download("PL8dPuuaLjXtN0ge7yDk_UA0ldZJdhwkoV")

# Remember to rename the preview to '... preview #0.mp4'
