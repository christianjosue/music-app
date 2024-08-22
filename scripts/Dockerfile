FROM python:latest

RUN apt-get update && apt-get install -y ffmpeg flac
RUN pip install flask SpeechRecognition pydub

WORKDIR /scripts
COPY . /scripts

# Mantener el contenedor activo
# ENTRYPOINT ["tail", "-f", "/dev/null"]

CMD ["python", "script.py"]
