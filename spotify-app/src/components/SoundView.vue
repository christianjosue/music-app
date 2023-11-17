<script setup>
import { inject } from 'vue';

const track = inject('currentTrack');
</script>

<template>
  <div class="sound-view-container">
    <div class="menu">
        <div style="font-weight: 500;">Canciones que te gustan</div>
        <font-awesome-icon class="cross" icon="fa-solid fa-xmark" style="color: #ffffff;" />
    </div>
    <div class="thumbnail" :style="{ backgroundImage: `url(${track.thumbnail})` }"></div>
  </div>
</template>

<style scoped>
.sound-view-container {
  background-color: #111;
  color: white;
  border-radius: 10px;
  height: 100%;
  flex: 1;
  margin-left: 10px;
}
.menu {
    box-sizing: border-box;
    padding: 20px;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cross {
    font-size: 18px;
}
.thumbnail {
    border-radius: 10px;
    width: 90%;
    margin: auto;
    height: 300px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
}
</style>
