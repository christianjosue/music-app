FROM python:latest

RUN apt-get update && apt-get install -y ffmpeg flac
RUN pip install flask lyricsgenius

WORKDIR /scripts
COPY . /scripts

CMD ["python", "server.py"]
