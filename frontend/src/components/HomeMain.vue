<script setup>
defineProps({
  color: {
    type: String,
    default() {
      return "linear-gradient(to bottom, #2e114f 0%, #111 30%)";
    },
  },
});

// Handles the greet message displayed on the home view depending on the current time
function greet() {
  const time = new Date();
  const hours = time.getHours();
  var message = "Good afternoon!";
  if (hours >= 21 || hours <= 6) {
    message = "Good night!";
  } else if (hours >= 7 && hours <= 12) {
    message = "Good morning!";
  }

  return message;
}
</script>

<template>
  <div
    class="main"
    :style="{ background: `linear-gradient(to bottom, ${color} 0%, #111 30%)` }"
  >
    <h1>{{ greet() }}</h1>
    <div class="main-playlist-container">
      <div class="main-playlist-item">
        <div
          class="playlist-thumbnail"
          style="background-image: url('/img/me-gustan.PNG')"
        ></div>
        <div class="playlist-title">Canciones que te gustan</div>
      </div>
      <div class="main-playlist-item">
        <div
          class="playlist-thumbnail"
          style="background-image: url('/img/delaossa-playa-virginia.jpg')"
        ></div>
        <div class="playlist-title">Playa Virginia</div>
      </div>
      <div class="main-playlist-item">
        <div
          class="playlist-thumbnail"
          style="background-image: url('/img/travis-scott.jpg')"
        ></div>
        <div class="playlist-title">Travis Scott - Utopia</div>
      </div>
      <div class="main-playlist-item">
        <div
          class="playlist-thumbnail"
          style="background-image: url('/img/kaydy.jpg')"
        ></div>
        <div class="playlist-title">Kaydy Cain</div>
      </div>
      <div class="main-playlist-item">
        <div
          class="playlist-thumbnail"
          style="background-image: url('/img/drake.jpg')"
        ></div>
        <div class="playlist-title">Drake</div>
      </div>
      <div class="main-playlist-item">
        <div
          class="playlist-thumbnail"
          style="background-image: url('/img/cruzi.jpg')"
        ></div>
        <div class="playlist-title">Me muevo con Dios</div>
      </div>
    </div>
    <!-- <img @mouseover="$emit('changeBackground')" src="/img/maldives.jpg" alt="maldives"> -->
  </div>
</template>

<style scoped>
h1 {
  color: white;
  margin: 0;
  margin-bottom: 20px;
}

.main {
  width: 100%;
  height: calc(100% - 60px);
  border-radius: 0 0 10px 10px;
  box-sizing: border-box;
  padding: 15px;
  transition: background 1s ease;
  overflow-x: hidden;
  overflow-y: auto;
}

.main-playlist-container {
  width: 100%;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.main-playlist-item {
  display: flex;
  width: 470px;
  height: 80px;
  background: rgba(95, 69, 125, 0.3);
  margin-top: 15px;
  border-radius: 10px;
}

.main-playlist-item:hover {
  cursor: pointer;
}

.playlist-thumbnail {
  width: 22%;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center center;
  border-radius: 5px 0 0 10px;
  /* box-shadow: 1px 0 75px rgb(0, 0, 0); */
}

.playlist-title {
  display: flex;
  align-items: center;
  color: white;
  width: 100%;
  box-sizing: border-box;
  padding: 20px;
  font-size: 16px;
  font-weight: 600;
}

::-webkit-scrollbar {
  width: 12px;
}

::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}
::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.4);
  transition: all 0.3s ease;
}
</style>
