<script setup>
import { inject } from 'vue';
import IconFavourite from './icons/IconFavourite.vue';
defineEmits(['close']);

// Retrieves the information of the track is currently playing for displays it
const track = inject('currentTrack');
</script>

<template>
  <div class="sound-view-container">
    <div class="menu">
        <div class="menu-title">Canciones que te gustan</div>
        <div @click="$emit('close')" class="cross-container">
          <font-awesome-icon class="cross" icon="fa-solid fa-xmark" />
        </div>
    </div>
    <div class="thumbnail" :style="{ backgroundImage: `url(${track.thumbnail})` }"></div>
    <div class="track-info-container">
      <div class="track-info">
        <h1>{{ track.title }}</h1>
        <h4>{{ track.artist }}</h4>
      </div>
      <div class="track-options">
        <div class="track-favourite">
          <IconFavourite :width="16" />
        </div>
        <div class="track-more-options">...</div>
      </div>
    </div>
    <div class="artist-container">
      <div class="artist-thumbnail" :style="{ backgroundImage: `url(${track.artistImg2})` }"></div>
      <div class="artist-info">
        <h1>{{ track.artist }}</h1>
        <div class="artist-options">
          <div class="monthly-listeners">{{ track.monthlyListeners }} monthly listeners</div>
          <div class="follow-btn">Follow</div>
        </div>
      </div>
    </div>
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
  box-sizing: border-box;
  padding: 15px;
  overflow-y: auto;
}
.menu {
  box-sizing: border-box;
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}
.menu-title {
  font-size: 15px;
  font-weight: 600;
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
  margin: auto;
  width: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
}
.track-info-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.track-info h1 {
  margin: 15px 0 5px 0;
  font-size: 19px;
}
.track-info h4 {
  margin: 0 0 20px 0;
  font-size: 15px;
  font-weight: 400;
  color: #838383;
}
.track-options {
  display: flex;
  justify-content: center;
}
.track-more-options {
  display: flex;
  align-items: center;
  letter-spacing: 3px;
  font-size: 20px;
}
.track-favourite {
  margin-top: 5px;
  margin-right: 15px;
}
.artist-container {
  border-radius: 10px;
}
.artist-info {
  background-color: #1d1d1d;
  box-sizing: border-box;
  padding: 20px;
  border-radius: 0 0 10px 10px;
}
.artist-info h1 {
  margin: 0;
  font-weight: 600;
  font-size: 17px;
}
.artist-thumbnail {
  box-sizing: border-box;
  padding-top: 70%;
  width: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  border-radius: 10px 10px 0 0;
}
.artist-thumbnail h3 {
  font-size: 15px;
  font-weight: 600;
}
.artist-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.follow-btn {
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.4);
  padding: 7px 15px;
  font-size: 14px;
  font-weight: 400;
  background: transparent;
}
.monthly-listeners {
  color: #838383;
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
