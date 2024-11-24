# Use the latest official image of python
FROM python:latest

# Install all required dependencies
RUN apt-get update && apt-get install -y ffmpeg flac
RUN pip install flask syncedlyrics

# Set the working directory and copy all files on it
WORKDIR /tasks
COPY . /tasks

# Command to runs python server
CMD ["python", "server.py"]
