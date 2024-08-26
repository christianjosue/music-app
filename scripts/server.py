from flask import Flask
from lyricsgenius import Genius

app = Flask(__name__)

@app.route('/get-lyrics')
def get_lyrics():
    token = "QHDDpxgdqrSFP4EaPVWXGqGHJNto-EAMkxtCPAykHgid6c7qwyYFiHqcZv2twcUw"
    genius = Genius(token)
    song = genius.search_song("JC Reyes", "Dejavu")
    return song.lyrics

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)