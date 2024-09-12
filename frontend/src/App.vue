<script setup>
import { onMounted, provide, ref, watch } from "vue";
import { API_URL } from '../config.js';
import Home from "./components/Home.vue";
import Search from "./components/Search.vue";
import Player from "./components/Player.vue";
import Library from "./components/Library.vue";
import LibraryMenu from "./components/LibraryMenu.vue";
import Lyrics from "./components/Lyrics.vue";

const HOME_VIEW = 1;
const SEARCH_VIEW = 2;
const LIBRARY_VIEW = 3;
const LYRICS_VIEW = 4;

const currentIndex = ref(0);
const currentLyricsLine = ref("");
const currentTrack = ref({});
const currentTracklist = ref({});
const currentView = ref(HOME_VIEW);
const idPlayingTrack = ref(0);
const idPlayingTracklist = ref(0);
const idTracklist = ref(0);
const playlistColor = ref('#838383');
const playTrack = ref(true);
const randomMode = ref(false);
const repeatMode = ref(false);
const song = ref('');
const soundView = ref(false);
const user = ref({});

var lyricsObject = {};
provide('currentTrack', currentTrack);
provide('isPlaying', playTrack);
provide('idPlayingTrack', idPlayingTrack);
provide('randomMode', randomMode);
provide('checkPlayingTracklist', checkPlayingTracklist);
provide('setCurrentTrack', setCurrentTrack);

// Watch when the value of tracklist's id changes to make a request to the server side to get the correspondant tracklist
watch(
  () => idTracklist.value,
  async () => {
    const response = await fetch(`${API_URL}/api/tracklist/${idTracklist.value}`);
    currentTracklist.value = await response.json();
  },
  { immediate: true }
);
// Fetch de tracklists corresponding to the logged user
onMounted(async () => {
  const response = await fetch(`${API_URL}/api/tracklists/1`);
  user.value = await response.json();
});
// Go to the previous track in the tracklist
function prevTrack(currentTime) {
  // If the current track's time is under three seconds, change to the previous track
  // Otherwise if it's over that three seconds, track will be restarted
  if (currentTime < 3 && Object.keys(currentTrack.value).length > 0) {
    // If the random mode is selected, sets a random track of the tracklist excepts the one is currently playing
    // Otherwise, it goes to the previous one in the list
    if (randomMode.value) {
      currentIndex.value = setRandomTrack();
    } else if (currentIndex.value > 0) {
      currentIndex.value--;
    } else {
      currentIndex.value = 0;
    }
    let track = currentTracklist.value.tracks[currentIndex.value];
    getTrack(track, currentIndex.value, track.id, currentTracklist.value.id);
  }
}
// Go to the next track in the tracklist
function nextTrack() {
  // Check if there is any track loaded in the player
  if (Object.keys(currentTrack.value).length > 0) {
    // If the random mode is active, plays a random track from the playlist except current one
    if (randomMode.value) {
      currentIndex.value = setRandomTrack();
    } else if (currentIndex.value < currentTracklist.value.tracks.length - 1) {
      // If there is a next track play it, otherwise start from the first track of the tracklist
      currentIndex.value++;
    } else {
      currentIndex.value = 0;
    }
  }
  let track = currentTracklist.value.tracks[currentIndex.value];
  getTrack(track, currentIndex.value, track.id, currentTracklist.value.id);
}
// Handle de background color of the app depending on the tracklist's main color
function handleChangePlaylistColor(color) {
  if (soundView.value == true) {
    playlistColor.value = '#1db954';
  } else {
    playlistColor.value = color;
  }
}
// Set the value of the tracklist's id
function setIdTracklist(id) {
  idTracklist.value = id;
}
// Load the track is going to be played
function setCurrentTrack(idTrack, idTracklist, isTracklistPlayer = 0) {
  if (Object.keys(currentTrack.value).length > 0 || isTracklistPlayer) {
    if (idTrack == 0) {
      playTrack.value = false;
    } else {
      // Search the selected track in the tracklist, get it and send it to the player
      currentTracklist.value.tracks.forEach((track, index) => {
        if (track.id == idTrack) {
          getTrack(track, index, idTrack, idTracklist);
        }
      });
    }
  }
}
// Get the specified track from the AWS S3 bucket
const getTrack = async (track, index, idTrack, idTracklist) => {
  const response = fetch(`${API_URL}/api/getTrack`, {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
      "artist": track.artists[0].name,
      "song": track.title,
      "src": track.src
    })
  });
  response.then(async (res) => {
    song.value = await res.json();
    currentIndex.value = index;
    currentTrack.value = track;
    playTrack.value = true;
  }).then(() => {
    lyricsToObject();
    idPlayingTrack.value = idTrack;
    idPlayingTracklist.value = idTracklist;
  });
}
// Check if the given tracklist is the same that the selected one
function checkSelectedTracklist(id) {
  return idTracklist.value == id;
}
// Check if the playing tracklist is the same that the given one
function checkPlayingTracklist(idTracklist) {
  return idPlayingTracklist.value == idTracklist;
}

// Transforms the string where lyrics comes from into an object in the desired format
function lyricsToObject() {
  let lyrics = song.value.lyrics.split("\n");
  lyricsObject = {};
  lyrics.forEach(line => {
    let time = line.substring(line.indexOf("[") + 1, line.indexOf("]"));
    let text = line.substring(line.indexOf("]") + 2);
    let timeArray = time.split(":");
    let minutes = parseInt(timeArray[0]);
    let seconds = parseFloat(timeArray[1]);
    let totalTime = minutes * 60 + seconds;
    lyricsObject = { ...lyricsObject, [totalTime]: text };
  });
}

// Set the current main view
// 1: Home
// 2: Search
// 3: Library
// 4: Lyrics
function setCurrentView(view) {
  currentView.value = view;
}

// Checks if the specified view is the current main view
function checkCurrentView(view) {
  return currentView.value == view;
}

// Update the current line of the lyrics while song is playing
function updateLyrics(audio) {
  for (const [time, text] of Object.entries(lyricsObject)) {
    if (parseFloat(audio.currentTime) >= parseFloat(time) - 0.2 && audio.currentTime <= parseFloat(time) + 0.2) {
      currentLyricsLine.value = time;
    }
  }
}

// Set a random track from the tracklist
function setRandomTrack() {
  let index = Math.floor(Math.random() * currentTracklist.value.tracks.length);
  return index == currentIndex.value ? setRandomTrack() : index;
}

// Activates or desactivates the random mode
function handleRandomMode() {
  randomMode.value = !randomMode.value;
}

// Activates or desactivates the repeat mode
function handleRepeatMode() {
  repeatMode.value = !repeatMode.value;
}
</script>

<template>
  <div class="container">
    <div class="menu">
      <div class="menu-top">
        <div class="menu-item" @click="setCurrentView(HOME_VIEW)">
          <div :class="['menu-item-content', { active: currentView == HOME_VIEW }]">
            <font-awesome-icon icon="fa-solid fa-house" class="icon" />Home
          </div>
        </div>
        <div class="menu-item" @click="setCurrentView(SEARCH_VIEW)">
          <div :class="['menu-item-content', { active: currentView == SEARCH_VIEW }]">
            <font-awesome-icon
              icon="fa-solid fa-magnifying-glass"
              class="icon"
            />Search
          </div>
        </div>
      </div>
      <div class="menu-bottom">
        <div class="menu-item library">
          <div :class="['menu-item-content', { active: currentView == LIBRARY_VIEW }]">
            <font-awesome-icon icon="fa-solid fa-headphones" class="icon" />Your library
          </div>
        </div>
        <LibraryMenu 
          v-for="tracklist in user.tracklists"
          :key="tracklist.id"
          :tracklist="tracklist"
          :is-active="checkSelectedTracklist(tracklist.id)"
          @click="
            setCurrentView(LIBRARY_VIEW);
            setIdTracklist(tracklist.id);
          "
        />
      </div>
    </div>
    <div class="main">
      <Home v-if="checkCurrentView(HOME_VIEW)" 
        :sound-view="soundView" 
        @close-sound-view="soundView = false; playlistColor = '#838383';" 
      />
      <Search v-else-if="checkCurrentView(SEARCH_VIEW)" />
      <Library v-else-if="checkCurrentView(LIBRARY_VIEW)" :tracklist="currentTracklist" />
      <Lyrics 
        v-else="checkCurrentView(LYRICS_VIEW)" 
        :lyrics="lyricsObject"
        :current-line="currentLyricsLine"
      />
    </div>
  </div>
  <div class="player">
    <Player
      :audio-url="song.url"
      :lyrics="lyricsObject"
      :current-track="currentTrack"
      :sound-view="soundView"
      :playlist-color="playlistColor"
      :play-track="playTrack"
      :set-current-track="setCurrentTrack"
      :id-playing-track="idPlayingTrack"
      :id-playing-tracklist="idPlayingTracklist"
      :repeat-mode="repeatMode"
      :update-lyrics="updateLyrics"
      :handle-random-mode="handleRandomMode"
      :handle-repeat-mode="handleRepeatMode"
      @active-sound-view="soundView = !soundView"
      @prev-track="prevTrack"
      @next-track="nextTrack"
      @change-playlist-color="handleChangePlaylistColor"
      @show-lyrics="setCurrentView(LYRICS_VIEW)"
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
