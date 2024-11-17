from flask import Flask, request, jsonify
import syncedlyrics
import logging

app = Flask(__name__)

logging.basicConfig(level=logging.INFO)

@app.route('/get-lyrics', methods = ['POST'])
def get_lyrics():
    data = request.get_json()
    song = data['song']
    artist = data['artist']
    lrc = syncedlyrics.search("[{}] [{}]".format(song, artist))

    return jsonify({'lyrics': lrc})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)