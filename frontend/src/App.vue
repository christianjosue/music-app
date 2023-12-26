<script setup>
import { computed, provide, ref } from "vue";
import Home from "./components/Home.vue";
import Search from "./components/Search.vue";
import Player from "./components/Player.vue";
import Library from "./components/Library.vue";

const soundView = ref(false);
const playlistColor = ref('#838383');
const isHome = ref(true);
const isSearch = ref(false);
const isLibrary = ref(false);
const currentIndex = ref(0);
const currentTrack = computed(() => {
  return tracks.value[currentIndex.value];
});
const tracks = ref([
  {
    title: "Dejavu",
    artist: "JC Reyes",
    src: "/audio/dejavu.mp3",
    thumbnail:
      "https://is1-ssl.mzstatic.com/image/thumb/Music126/v4/98/f2/e6/98f2e60a-d2ac-c20d-4ac2-e7d2f5811ed5/190296284182.jpg/600x600bf-60.jpg",
    artistImg2: '/img/jcreyes-info.jpg',
    monthlyListeners: 4173670
  },
  {
    title: "Baby Nueva",
    artist: "Bad Bunny",
    src: "/audio/baby-nueva.mp3",
    thumbnail:
      "https://upload.wikimedia.org/wikipedia/en/7/74/Bad_Bunny_-_Nadie_Sabe_Lo_Que_Va_a_Pasar_Ma%C3%B1ana.png",
    artistImg2: '/img/bad-bunny-thumbnail.webp',
    monthlyListeners: 4173670
  },
]);
provide("currentTrack", currentTrack);

function prevTrack(currentTime) {
  if (currentTime < 3) {
    if (currentIndex.value > 0) {
      currentIndex.value--;
    } else {
      currentIndex.value = tracks.value.length - 1;
    }
  }
}

function nextTrack() {
  if (currentIndex.value < tracks.value.length - 1) {
    currentIndex.value++;
  } else {
    currentIndex.value = 0;
  }
}

function handleChangePlaylistColor(color) {
  if (soundView.value == true) {
    playlistColor.value = '#1db954';
  } else {
    playlistColor.value = color;
  }
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
        <div class="menu-item" @click="isHome = false; isLibrary = true; isSearch = false;">
          <div :class="['menu-item-content', { active: isLibrary }]">
            <font-awesome-icon icon="fa-solid fa-headphones" class="icon" />Your library
          </div>
        </div>
      </div>
    </div>
    <div class="main">
      <Home v-if="isHome" 
        :sound-view="soundView" 
        @close-sound-view="soundView = false; playlistColor = '#838383';" 
      />
      <Search v-else-if="isSearch" />
      <Library v-else />
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
  background-color: #111;
  border-radius: 10px;
  box-sizing: border-box;
  padding: 25px 25px 0 25px;
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
