<script setup>
import { provide, ref } from "vue";
import Home from "./components/Home.vue";
import Search from "./components/Search.vue";
import Player from "./components/Player.vue";

const soundView = ref(false);
const isHome = ref(true);
const currentTrack = ref({});
const tracks = ref([
  {
    title: "Dejavu",
    artist: "JC Reyes",
    src: "/audio/dejavu.mp3",
    thumbnail:
      "https://is1-ssl.mzstatic.com/image/thumb/Music126/v4/98/f2/e6/98f2e60a-d2ac-c20d-4ac2-e7d2f5811ed5/190296284182.jpg/600x600bf-60.jpg",
  },
  {
    title: "Baby Nueva",
    artist: "Bad Bunny",
    src: "/audio/baby-nueva.mp3",
    thumbnail:
      "https://upload.wikimedia.org/wikipedia/en/7/74/Bad_Bunny_-_Nadie_Sabe_Lo_Que_Va_a_Pasar_Ma%C3%B1ana.png",
  },
]);
provide("soundView", soundView);
provide("currentTrack", currentTrack);

function activeSoundView() {
  soundView.value = soundView.value ? false : true;
}
</script>

<template>
  <div class="container">
    <div class="menu">
      <div class="menu-item" @click="isHome = true">
        <div :class="['menu-item-content', { active: isHome }]">
          <font-awesome-icon icon="fa-solid fa-house" class="icon" />Home
        </div>
      </div>
      <div class="menu-item" @click="isHome = false">
        <div :class="['menu-item-content', { active: !isHome }]">
          <font-awesome-icon
            icon="fa-solid fa-magnifying-glass"
            class="icon"
          />Search
        </div>
      </div>
    </div>
    <div class="main">
      <Home v-if="isHome" :active-sound-view="soundView" />
      <Search v-else />
    </div>
  </div>
  <div class="player">
    <Player
      @active-sound-view="activeSoundView"
      @update-track="(track) => (currentTrack = track)"
      :tracks="tracks"
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
  background-color: #111;
  border-radius: 10px;
  margin-right: 10px;
  box-sizing: border-box;
  padding: 25px;
}

.main {
  flex: 4;
  border-radius: 10px;
}

.menu-item {
  font-size: 17px;
  font-weight: 400;
  box-sizing: border-box;
  margin-bottom: 30px;
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
