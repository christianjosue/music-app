print("hola")
from flask import Flask
import speech_recognition as sr
from os import path
from pydub import AudioSegment

app = Flask(__name__)

@app.route('/run-script')
def run_script():
    # transcribe audio file                                                         
    AUDIO_FILE = "18-13-52.wav"

    # use the audio file as the audio source                                        
    r = sr.Recognizer()
    with sr.AudioFile(AUDIO_FILE) as source:
        audio = r.record(source)  # read the entire audio file
        return r.recognize_google(audio)

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
