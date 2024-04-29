<script setup>
import { computed, onMounted, provide, ref, watch } from "vue";
import { API_URL } from '../config.js';
import Home from "./components/Home.vue";
import Search from "./components/Search.vue";
import Player from "./components/Player.vue";
import Library from "./components/Library.vue";
import LibraryMenu from "./components/LibraryMenu.vue";

const idPlayingTrack = ref(0);
const soundView = ref(false);
const playlistColor = ref('#838383');
const isHome = ref(true);
const isSearch = ref(false);
const isLibrary = ref(false);
const currentIndex = ref(0);
const user = ref({});
const playTrack = ref(false);
const idTracklist = ref(0);
const currentTracklist = ref({});
const currentTrack = ref({
  title: "Dejavu",
  artists: [{name: "JC Test"}],
  src: "/audio/dejavu.mp3",
  thumbnail:
    "https://is1-ssl.mzstatic.com/image/thumb/Music126/v4/98/f2/e6/98f2e60a-d2ac-c20d-4ac2-e7d2f5811ed5/190296284182.jpg/600x600bf-60.jpg",
  artistImg2: '/img/jcreyes-info.jpg',
  monthlyListeners: 4173670
});
const tracks = ref([]);
provide('isPlaying', playTrack);
provide('idPlayingTrack', idPlayingTrack);
provide('setCurrentTrack', setCurrentTrack);

watch(
  () => idTracklist.value,
  async () => {
    const response = await fetch(`${API_URL}/api/tracklist/${idTracklist.value}`);
    currentTracklist.value = await response.json();
  },
  { immediate: true }
);

provide("currentTrack", currentTrack);

onMounted(async () => {
  const response = await fetch(`${API_URL}/api/tracklists/1`);
  user.value = await response.json();
  console.log(user.value);
});

function prevTrack(currentTime) {
  if (currentTime < 3) {
    if (currentIndex.value > 0) {
      currentIndex.value--;
    } else {
      currentIndex.value = 0;
    }
    setCurrentTrackByIndex();
  }
}

function nextTrack() {
  if (currentIndex.value < currentTracklist.value.tracks.length - 1) {
    currentIndex.value++;
  } else {
    currentIndex.value = 0;
  }
  setCurrentTrackByIndex();
}

function handleChangePlaylistColor(color) {
  if (soundView.value == true) {
    playlistColor.value = '#1db954';
  } else {
    playlistColor.value = color;
  }
}

function setIdTracklist(id) {
  idTracklist.value = id;
}

function setCurrentTrack(idTrack) {
  idPlayingTrack.value = idTrack;
  if (idTrack == 0) {
    playTrack.value = false;
  } else {
    currentTracklist.value.tracks.forEach((track, index) => {
      if (track.id == idTrack) {
        currentIndex.value = index;
        currentTrack.value = track;
        playTrack.value = true;
      }
    });
  }
}

function setCurrentTrackByIndex() {
  console.log(currentIndex.value);
  currentTrack.value = currentTracklist.value.tracks[currentIndex.value];
  idPlayingTrack.value = currentTrack.value.id;
  playTrack.value = true;
}

function checkSelectedTracklist(id) {
  return idTracklist.value == id;
}
</script>

<template>
  <div class="container">
    <div class="menu">
      <div class="menu-top">
        <div class="menu-item" @click="isHome = true; isLibrary = false; isSearch = false;">
          <div :class="['menu-item-content', { active: isHome }]">
            <font-awesome-icon icon="fa-solid fa-house" class="icon" />Home
          </div>
        </div>
        <div class="menu-item" @click="isHome = false; isLibrary = false; isSearch = true;">
          <div :class="['menu-item-content', { active: isSearch }]">
            <font-awesome-icon
              icon="fa-solid fa-magnifying-glass"
              class="icon"
            />Search
          </div>
        </div>
      </div>
      <div class="menu-bottom">
        <div class="menu-item library">
          <div :class="['menu-item-content', { active: isLibrary }]">
            <font-awesome-icon icon="fa-solid fa-headphones" class="icon" />Your library
          </div>
        </div>
        <LibraryMenu 
          v-for="tracklist in user.tracklists"
          :key="tracklist.id"
          :tracklist="tracklist"
          :is-active="checkSelectedTracklist(tracklist.id)"
          @click="
            isHome = false;
            isLibrary = true; 
            isSearch = false; 
            setIdTracklist(tracklist.id);
          "
        />
      </div>
    </div>
    <div class="main">
      <Home v-if="isHome" 
        :sound-view="soundView" 
        @close-sound-view="soundView = false; playlistColor = '#838383';" 
      />
      <Search v-else-if="isSearch" />
      <Library v-else :tracklist="currentTracklist" />
    </div>
  </div>
  <div class="player">
    <Player
      @active-sound-view="soundView = !soundView"
      @prev-track="prevTrack"
      @next-track="nextTrack"
      @change-playlist-color="handleChangePlaylistColor"
      :current-track="currentTrack"
      :sound-view="soundView"
      :playlist-color="playlistColor"
      :play-track="playTrack"
      :set-current-track="setCurrentTrack"
      :id-playing-track="idPlayingTrack"
    />
  </div>
</template>

<style scoped>
.container {
  display: flex;
  width: 100%;
  height: 90%;
}

.menu {
  flex: 1;
  margin-right: 10px;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.menu-top {
  background-color: #111;
  border-radius: 10px;
  box-sizing: border-box;
  padding: 25px 25px 0 25px;
  height: fit-content;
}

.menu-bottom {
  flex: 1;
  margin-top: 10px;
  padding: 10px;
  background-color: #111;
  border-radius: 10px;
  box-sizing: border-box;
}

.library {
  padding: 15px 15px 30px 15px !important;
}

.main {
  flex: 4;
  border-radius: 10px;
}

.menu-item {
  font-size: 17px;
  font-weight: 400;
  box-sizing: border-box;
  padding-bottom: 30px;
  display: flex;
  align-items: center;
}

.menu-item-content {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: rgb(175, 175, 175);
}

.menu-item-content:hover {
  color: white;
  transition: all 0.3s ease;
  cursor: pointer;
}

.icon {
  margin-right: 18px;
  height: 22px;
}

.active {
  color: white !important;
}
</style>
