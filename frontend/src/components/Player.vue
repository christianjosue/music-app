<script setup>
import { computed, getCurrentInstance, onMounted, ref, watch } from "vue";
import IconPlay from "./icons/IconPlay.vue";
import IconPause from "./icons/IconPause.vue";
import IconNextTrack from "./icons/IconNextTrack.vue";
import IconPrevTrack from "./icons/IconPrevTrack.vue";
import IconFavourite from "./icons/IconFavourite.vue";
import IconPlaylist from "./icons/IconPlaylist.vue";
import IconLyrics from "./icons/IconLyrics.vue";
const props = defineProps({
  currentTrack: {
    type: Object,
    default: {}
  },
  soundView: {
    type: Boolean,
    default: false
  },
  playlistColor: String,
  playTrack: {
    type: Boolean,
    default: false
  },
  idPlayingTrack: {
    type: Number
  },
  idPlayingTracklist: {
    type: Number
  },
  setCurrentTrack: {
    type: Function
  },
  audioUrl: {
    type: String
  },
  lyrics: {
    type: Object,
    default: {}
  },
  updateLyrics: {
    type: Function
  }
});
var audio;
const idPlayingTrackCopy = ref(0);
const idPlayingTracklistCopy = ref(0);
const currentTime = ref("00:00");
const duration = ref("00:00");
const isPlaying = ref(false);
const volume = ref(100);
const colorVolume = ref("#fff");
const currentProgressTrack = ref(0);
const colorProgressTrack = ref("#fff");
const reload = ref(true);
const activeLyricsIcon = ref(false);
const volumeIcon = computed(() => {
  switch (true) {
    case volume.value === 0:
      return "xmark";
    case volume.value > 0 && volume.value <= 25:
      return "off";
    case volume.value > 25 && volume.value <= 75:
      return "low";
    case volume.value > 75:
      return "high";
  }
});
const thumbnail = computed(() => {
  return Object.keys(props.currentTrack).length == 0 ? "https://static-00.iconduck.com/assets.00/musical-notes-icon-256x256-d8u6pk7h.png" : props.currentTrack.thumbnail;
});

onMounted(() => {
  const { emit } = getCurrentInstance();
  audio = new Audio();
  audio.src = props.audioUrl;
  audio.ontimeupdate = () => {
    generateTime();
    props.updateLyrics(audio);
  };
  audio.onloadedmetadata = () => {
    generateTime();
  };
  audio.onended = () => {
    isPlaying.value = true;
    Promise.resolve()
      .then(() => emit('nextTrack'))
      .then(() => resetPlayer());
  };
});

watch(
  () => props.currentTrack,
  () => {
    if (audio && idPlayingTrackCopy.value != props.currentTrack.id || idPlayingTracklistCopy.value != props.idPlayingTracklist) {
      isPlaying.value = true;
      resetPlayer();
    }
  },
  { immediate: true }
);

watch(
  () => props.playTrack,
  () => {
    if (audio) {
      playMusic();
    }
  },
  { immediate: true }
)

watch(
  () => props.idPlayingTrack,
  () => {
    if (props.idPlayingTrack != 0) {
      idPlayingTrackCopy.value = props.idPlayingTrack;
      idPlayingTracklistCopy.value = props.idPlayingTracklist;
    }
  }
)

function playMusic() {
  if (audio.paused) {
    audio.play();
    isPlaying.value = true;
  } else {
    audio.pause();
    isPlaying.value = false;
  }
}

function generateTime() {
  let curmin, cursec;
  let durmin = Math.floor(audio.duration / 60);
  let dursec = Math.floor(audio.duration - durmin * 60);
  if (reload.value) {
    currentProgressTrack.value = (audio.currentTime * 100) / audio.duration;
    curmin = Math.floor(audio.currentTime / 60);
    cursec = Math.floor(audio.currentTime - curmin * 60);
  } else {
    const currentTime = currentProgressTrack.value * audio.duration / 100;
    curmin = Math.floor(currentTime / 60);
    cursec = Math.floor(currentTime - curmin * 60);
  }
  if (durmin < 10) {
    durmin = "0" + durmin;
  }
  if (dursec < 10) {
    dursec = "0" + dursec;
  }
  if (curmin < 10) {
    curmin = "0" + curmin;
  }
  if (cursec < 10) {
    cursec = "0" + cursec;
  }
  duration.value = `${durmin}:${dursec}`;
  currentTime.value = `${curmin}:${cursec}`;
}

function resetPlayer() {
  if (Object.keys(props.currentTrack).length > 0) {
    audio.currentTime = 0;
    audio.src = props.audioUrl;
    setTimeout(() => {
      if (isPlaying.value) {
        audio.play();
      } else {
        audio.pause();
      }
    }, 300);
  }
}

function changeVolume(e) {
  const range = e.target.value;
  volume.value = (range / e.target.max) * 100;
  audio.volume = volume.value / 100;
}

function changeProgressTrack(e) {
  if (Object.keys(props.currentTrack).length > 0) {
    isPlaying.value = true;
    currentProgressTrack.value = e.target.value;
  }
}

function changeVolumeColor() {
  colorVolume.value = colorVolume.value == "#fff" ? "#1db954" : "#fff";
}

function changeProgressTrackColor() {
  colorProgressTrack.value = colorProgressTrack.value === "#fff" ? "#1db954" : "#fff";
}

function updateProgressBar() {
  if (Object.keys(props.currentTrack).length > 0) {
    audio.currentTime = audio.duration * currentProgressTrack.value / 100;
    reload.value = true;
  }
}
</script>

<template>
  <div class="container">
    <div class="track-container">
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
      <div class="track-favourite">
        <IconFavourite :width="15" />
      </div>
    </div>
    <div class="player">
      <div class="player-buttons">
        <IconPrevTrack
          @click="
            $emit('prevTrack', audio.currentTime);
            this.$nextTick(() => resetPlayer())
          "
        />
        <div class="play-btn">
          <IconPlay v-show="!isPlaying" @click="setCurrentTrack(idPlayingTrackCopy, idPlayingTracklistCopy)" />
          <IconPause v-show="isPlaying" @click="setCurrentTrack(0, idPlayingTrackCopy)" />
        </div>
        <IconNextTrack
          @click="
            $emit('nextTrack');
            this.$nextTick(() => resetPlayer())
          "
        />
      </div>
      <div class="progress" ref="progress">
        <div class="progress-time current-time">{{ currentTime }}</div>
        <input 
          @input="changeProgressTrack"
          @mouseup="updateProgressBar"
          @mousedown="reload = false"
          @mouseover="changeProgressTrackColor"
          @mouseleave="changeProgressTrackColor"
          :style="{
            background: `linear-gradient(to right, ${colorProgressTrack} ${currentProgressTrack}%, #838383 ${currentProgressTrack}%)`,
          }"
          type="range" 
          class="progress-track"
          min="0"
          max="100"
          :value="currentProgressTrack"
        />
        <div class="progress-time total-time">{{ duration }}</div>
      </div>
    </div>
    <div class="player-options">
      <IconLyrics 
        @click="
          $emit('showLyrics');
          activeLyricsIcon = !activeLyricsIcon;
        "
        :active-lyrics-icon="activeLyricsIcon"
      />
      <IconPlaylist 
        @active-sound-view="$emit('activeSoundView')"
        @change-playlist-color="$emit('changePlaylistColor', $event)"
        :is-active="soundView"
        :color="playlistColor"
        />
      <font-awesome-icon
        :icon="['fas', `volume-${volumeIcon}`]"
        style="color: #ffffff; margin-left: 15px"
      />
      <div class="range">
        <input
          @input="changeVolume"
          @mouseover="changeVolumeColor"
          @mouseleave="changeVolumeColor"
          :style="{
            background: `linear-gradient(to right, ${colorVolume} ${volume}%, #838383 ${volume}%)`,
          }"
          type="range"
          min="0"
          max="100"
          :value="volume"
          class="range-volume"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  display: flex;
  justify-content: space-between;
  height: 100%;
}
.player {
  flex: 4;
}
.player-options {
  flex: 3;
  display: flex;
  justify-content: right;
  align-items: center;
  margin-top: 15px;
  margin-right: 10px;
}
.play-btn {
  width: 35px;
  height: 35px;
  background: white;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
}

.play-btn:hover {
  transform: scale(1.05);
}

.player-buttons {
  width: 100%;
  margin-top: 20px;
  margin-bottom: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.progress {
  display: flex;
  align-items: center;
}

.progress-time {
  margin-top: 2px;
  color: white;
  font-weight: 400;
  font-size: 12px;
  opacity: 0.7;
}

.current-time {
  margin-right: 10px;
}

.total-time {
  margin-left: 10px;
}

.track-container {
  flex: 3;
  display: flex;
  align-items: center;
  margin-top: 10px;
  margin-left: 5px;
}

.track-data {
  margin-right: 20px;
  margin-left: 20px;
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
  width: 70px;
  height: 70px;
  background-size: cover;
  background-repeat: no-repeat;
  border-radius: 5px;
}

.range-volume,
.progress-track {
  -webkit-appearance: none;
  appearance: none;
  cursor: pointer;
  outline: none;
  border-radius: 15px;
  height: 4px;
  background: #ccc;
  cursor: auto;
}

.range-volume {
  width: 100px;
}

.progress-track {
  width: 100%;
}

.range-volume::-webkit-slider-thumb,
.progress-track::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  display: none;
  height: 13px;
  width: 13px;
  background-color: #fff;
  border-radius: 50%;
  border: none;
  transition: 0.2s ease-in-out;
}

.range-volume:hover::-webkit-slider-thumb,
.progress-track:hover::-webkit-slider-thumb {
  display: block;
}

.range-volume::-moz-range-thumb,
.progress-track::-moz-range-thumb {
  height: 15px;
  width: 15px;
  background-color: #f50;
  border-radius: 50%;
  border: none;
  transition: 0.2s ease-in-out;
}

.range {
  display: flex;
  align-items: center;
  margin-left: 10px;
}
</style>
