# Use the latest official image of python
FROM python:3.10-slim

# Install all required dependencies
RUN apt-get update && apt-get install -y ffmpeg flac
RUN pip install flask syncedlyrics gunicorn

# Set the working directory and copy all files on it
WORKDIR /tasks
COPY . /tasks

# Command to runs python server
CMD ["gunicorn", "-w", "4", "-b", "0.0.0.0:5000", "server:app"]
