<script setup>
import { onMounted, provide, ref, watch } from "vue";
import { API_URL } from '../config.js';
import { useToast } from "vue-toastification";
import Home from "./components/Home.vue";
import Search from "./components/Search.vue";
import Player from "./components/Player.vue";
import Library from "./components/Library.vue";
import LibraryMenu from "./components/LibraryMenu.vue";
import Lyrics from "./components/Lyrics.vue";
import SoundView from "./components/SoundView.vue";
import TracklistModal from "./components/TracklistModal.vue";
import CryptoJS from 'crypto-js';

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
const playTrack = ref(true);
const randomMode = ref(false);
const repeatMode = ref(false);
const song = ref('');
const songLoading = ref(false);
const soundView = ref(false);
const showTracklistModal = ref(false);
const toast = useToast();
const user = ref({});

var lyricsObject = {};
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
onMounted(() => {
  getTracklists();
});
// Creates a new tracklist
const createTracklist = async (tracklistData) => {
  const formData = new FormData();
  formData.append('name', tracklistData.tracklistName);
  formData.append('privacy', tracklistData.privacy);
  formData.append('thumbnail', tracklistData.thumbnail);
  const response = await fetch(`${API_URL}/api/tracklist`, {
    method: 'POST',
    body: formData
  });
  const data = await response.json();
  // Display the correspondant notification
  if (data.tracklist == null) {
    toast.error("An error occurred while creating the tracklist", {
      timeout: 3000
    });
  } else {
    toast.success("Tracklist created successfully!", {
      timeout: 3000
    });
    // Close the dialog
    showTracklistModal.value = false;
    // Reload tracklists
    getTracklists();
  }
}
// Get user's tracklists
const getTracklists = async () => {
  const response = await fetch(`${API_URL}/api/tracklists/1`);
  user.value = await response.json();
}
// Go to the previous track in the tracklist
function prevTrack(currentTime) {
  // If the current track's time is under three seconds, change to the previous track
  // Otherwise if it's over that three seconds, track will be restarted
  if (currentTime < 3 && Object.keys(currentTrack.value).length > 0) {
    // Pause the track and display the loader icon
    songLoading.value = true;
    playTrack.value = false;
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
    // Pause the track and display the loader icon
    songLoading.value = true;
    playTrack.value = false;
    // If the random mode is active, plays a random track from the playlist except current one
    if (randomMode.value) {
      currentIndex.value = setRandomTrack();
    } else if (currentIndex.value < currentTracklist.value.tracks.length - 1) {
      // If there is a next track play it, otherwise start from the first track of the tracklist
      currentIndex.value++;
    } else {
      currentIndex.value = 0;
    }
    let track = currentTracklist.value.tracks[currentIndex.value];
    getTrack(track, currentIndex.value, track.id, currentTracklist.value.id);
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
    } else if (idPlayingTrack.value != idTrack) {
      songLoading.value = true;
      // If idPlayingTrack is 0, that means the player has not started yet
      if (idPlayingTrack.value !== 0) playTrack.value = false;
      // Search the selected track in the tracklist, get it and send it to the player
      currentTracklist.value.tracks.forEach((track, index) => {
        if (track.id == idTrack) {
          getTrack(track, index, idTrack, idTracklist);
        }
      });
    } else {
      playTrack.value = true;
    }
  }
}
// Get the specified track from the AWS S3 bucket
const getTrack = async (track, index, idTrack, idTracklist) => {
  var lyrics = getLyricsFromLocalStorage(track.title);
  const response = fetch(`${API_URL}/api/getTrack`, {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
      "artist": track.artists[0].name,
      "song": track.title,
      "src": track.src,
      "lyrics": lyrics
    })
  });
  response.then(async (res) => {
    song.value = await res.json();
    storeLyricsInLocalStorage(track.title, song.value.lyrics);
    currentIndex.value = index;
    currentTrack.value = track;
  }).then(() => {
    lyricsToObject();
    idPlayingTrack.value = idTrack;
    idPlayingTracklist.value = idTracklist;
    songLoading.value = false;
    playTrack.value = true;
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
  for (const [time, _] of Object.entries(lyricsObject)) {
    if (parseFloat(audio.currentTime) >= parseFloat(time) - 0.15 && audio.currentTime <= parseFloat(time) + 0.15) {
      currentLyricsLine.value = time;
    }
  }
}

// Update lyrics when progress bar is clicked
function updateLyricsProgressBar(audio) {
  let times = Object.keys(lyricsObject);
  if (times.length > 0) {
    setTimeout(() => {
      currentLyricsLine.value = times.reduce(function(prev, current) {
        return (Math.abs(current - audio.currentTime) < Math.abs(prev - audio.currentTime) ? current : prev);
      });
    }, 1000);
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

// Show or hide the sound view
function handleSoundView() {
  if (Object.keys(currentTrack.value).length > 0) {
    soundView.value = !soundView.value;
  }
}

// Store in localStorage a song's lyrics
function storeLyricsInLocalStorage(songName, lyrics) {
  if (getLyricsFromLocalStorage(songName) == "") {
    let hash = CryptoJS.SHA256(songName).toString();
    localStorage.setItem(hash, lyrics);
  }
}

// Get the lyrics from localStorage in case it is stored
function getLyricsFromLocalStorage(songName) {
  let hash = CryptoJS.SHA256(songName).toString();
  let lyrics = localStorage.getItem(hash);
  return lyrics != null ? lyrics : "";
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
          <button @click="showTracklistModal = !showTracklistModal;" class="createTracklist">+</button>
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
      <TracklistModal
        @create-tracklist="createTracklist"
        @close-dialog="showTracklistModal = !showTracklistModal"
        :show-modal="showTracklistModal"
      />
      <Home 
        v-if="checkCurrentView(HOME_VIEW)" 
        :sound-view="soundView"
      />
      <Search v-else-if="checkCurrentView(SEARCH_VIEW)" />
      <Library 
        v-else-if="checkCurrentView(LIBRARY_VIEW)" 
        :tracklist="currentTracklist" 
      />
      <Lyrics 
        v-else="checkCurrentView(LYRICS_VIEW)" 
        :lyrics="lyricsObject"
        :current-line="currentLyricsLine"
      />
      <SoundView 
        v-show="soundView"
        :track="currentTrack"
        @close="soundView = false" 
      />
    </div>
  </div>
  <div class="player">
    <Player
      :audio-url="song.url"
      :lyrics="lyricsObject"
      :current-track="currentTrack"
      :sound-view="soundView"
      :play-track="playTrack"
      :set-current-track="setCurrentTrack"
      :id-playing-track="idPlayingTrack"
      :id-playing-tracklist="idPlayingTracklist"
      :repeat-mode="repeatMode"
      :song-loading="songLoading"
      :update-lyrics="updateLyrics"
      :update-lyrics-progress-bar="updateLyricsProgressBar"
      :handle-random-mode="handleRandomMode"
      :handle-repeat-mode="handleRepeatMode"
      @active-sound-view="handleSoundView"
      @prev-track="prevTrack"
      @next-track="nextTrack"
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
  display: flex;
  justify-content: space-between;
}

.main {
  flex: 4;
  border-radius: 10px;
  display: flex;
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

.createTracklist {
  background-color: #111;
  border: 1px solid gray;
  color: gray;
  font-size: 20px;
  padding: 2px 8px;
  margin: 0;
  border-radius: 5px;
  cursor: pointer;
  transition: all .3s ease;
}

.createTracklist:hover {
  font-size: 22px;
  color: black;
  border: 1px solid white;
  background-color: white;
}
</style>
