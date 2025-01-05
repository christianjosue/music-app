<script setup>
import { computed } from 'vue';

const props = defineProps({
    currentTrack: {
        type: Object,
        default: {}
    }
});

const thumbnail = computed(() => {
  return Object.keys(props.currentTrack).length == 0 ? "https://static-00.iconduck.com/assets.00/musical-notes-icon-256x256-d8u6pk7h.png" : props.currentTrack.thumbnail;
});
</script>

<template>
<div class="playing-song-mobile">
    <div
        class="track-thumbnail"
        :style="{ backgroundImage: `url(${thumbnail})` }"
    ></div>
    <div class="track-data">
        <div class="track-title">{{ currentTrack.title }}</div>
        <div class="track-artist">
            <div v-for="artist in currentTrack.artists">
                {{ artist.name }}
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
.playing-song-mobile {
  display: flex;
  align-items: center;
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 70px;
  background-color: #222;
  box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
  box-sizing: border-box;
  padding: 5px;
  z-index: 1000;
}
.track-data {
  margin-left: 15px;
}
.track-title {
  color: white;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 2px;
}
.track-artist {
  color: #838383;
  font-size: 12px;
}
.track-thumbnail {
  width: 60px;
  height: 60px;
  background-size: cover;
  background-repeat: no-repeat;
  border-radius: 5px;
}
</style>