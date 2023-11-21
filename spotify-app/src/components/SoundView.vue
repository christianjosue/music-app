<script setup>
import { inject } from 'vue';
defineEmits(['close']);

const track = inject('currentTrack');
</script>

<template>
  <div class="sound-view-container">
    <div class="menu">
        <div style="font-weight: 600;">Canciones que te gustan</div>
        <div @click="$emit('close')" class="cross-container">
          <font-awesome-icon class="cross" icon="fa-solid fa-xmark" />
        </div>
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
    color: #fff;
}
.cross-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 30px;
  height: 30px;
  background: rgba(255, 255, 255, 0);
  border-radius: 50%;
  transition: background .2s ease;
  cursor: pointer;
  position: relative;
}
.cross-container:hover {
  background: rgba(255, 255, 255, 0.1);
}
.thumbnail {
    border-radius: 10px;
    box-sizing: border-box;
    padding-top: 100%;
    width: 90%;
    margin: auto;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
}
</style>
