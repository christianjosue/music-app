# 🎵 Music App

This project is a music application that combines a Laravel backend, a Vue.js frontend client, and a Python auxiliary Flask server for specific tasks. The entire project is dockerized and can be easily started with `docker-compose up`.

This app is inspired by Spotify, just for development purposes. It has no authentication because it would be a barrier for people to test the app. The goal is
everyone can easily use it without the necessity of been registered.

## 🗂️ Project Structure

Description of Each Part:

**/server:**
Contains the REST API built with Laravel. Handles backend logic and database access.

**/client:**
Frontend application built with Vue.js that interacts with the backend to display the user interface.

**/tasks:**
Contains an auxiliary Python Flask server used for specific tasks, such as data processing or integration with external services.

It is also used an AWS S3 in order to store songs audio files.

## 🔐 Database 
The project uses MySQL as its database management system. It serves as the backend database for storing and managing application data.

Additionally, phpMyAdmin is included in the project setup to provide a user-friendly web interface for interacting with the MySQL database. With phpMyAdmin, you can:

- Browse and manage database tables.
- Execute SQL queries.
- Import and export data.
- Perform database operations like backups and optimizations.

Both the MySQL database and phpMyAdmin are configured and accessible via Docker, making it easy to set up and manage the database environment.

You can take a look the DB structure diagram through this URL:

https://drive.google.com/file/d/1Rh5LN8v86jOz7EGyfIYVJ-HAUTizAOXK/view?usp=sharing

## 🚀 Requirements
To run the project, make sure you have the following installed:

- Windows/MacOS: Docker Desktop
- Linux: Docker and Docker Compose

## 🔧 Setup and Execution
1. Clone the repository:

```bash
git clone https://github.com/christianjosue/music-app.git
cd music-app
```

2. Set up environment variables:

Copy and customize the example configuration files.
```bash
cp server/.env.example server/.env
cp client/.env.example client/.env
```
Make sure to configure database and other parameters according to your environment.
In order to get AWS credentials, ask for it to the project owner.

3. Extra configuration:

If you are using Linux or macOS, I recommend you to remove polling option. It is only needed if you use Windows, otherwise hot reloading on node server will not work.
In order to remove this polling option, go to client\vite.config.js file and remove the following piece of code:

```js
server: {
    watch: {
      usePolling: true
    },
    host: '0.0.0.0'
}
```

4. Start services with Docker Compose:

```bash
docker-compose up --build
```

Running the previous command migrations and seeders will be also executed

5. Access the application:

- Music app: http://localhost:5173
- phpMyAdmin: http://localhost:8081
- Backend: http://localhost:8000 (API)
- Python Server: http://localhost:5000 (API)

## ✨ Features
- Modern Frontend: Interactive and user-friendly interface built with Vue.js.
- REST API: Scalable and efficient backend built with Laravel.
- Specific Tasks: Python server for additional processing and AWS S3 for storing songs audio files
- Dockerized: Simple and portable setup with Docker Compose.

## 📞 Contact
If you have any questions or suggestions, feel free to contact me:

📩 Email: christianjosuefuentes@gmail.com

## 📝 License
This project is licensed under the MIT License.
