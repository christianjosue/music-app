<script setup>
import { computed, onBeforeUnmount, onMounted, provide, ref, watch, watchEffect } from "vue";
import { HOME_VIEW, SEARCH_VIEW, LIBRARY_VIEW, LYRICS_VIEW, ARTIST_VIEW, ALBUM_VIEW, LIBRARY_LIST_VIEW } from "../config.js";
import { API_URL } from '../config.js';
import { useToast } from "vue-toastification";
import Home from "./components/modules/home/Home.vue";
import Search from "./components/modules/search/Search.vue";
import Player from "./components/modules/player/Player.vue";
import Library from "./components/modules/tracklist/views/Library.vue";
import LibraryMenu from "./components/modules/tracklist/views/LibraryMenu.vue";
import Lyrics from "./components/modules/player/Lyrics.vue";
import Artist from "./components/modules/artist/Artist.vue";
import Album from "./components/modules/album/Album.vue";
import SoundView from "./components/modules/player/SoundView.vue";
import AddSongsModal from "./components/modules/tracklist/services/AddSongsModal.vue";
import CreateTracklistModal from "./components/modules/tracklist/services/CreateTracklistModal.vue";
import EditTracklistModal from "./components/modules/tracklist/services/EditTracklistModal.vue";
import DeleteTracklistModal from "./components/modules/tracklist/services/DeleteTracklistModal.vue";
import Breadcrumbs from "./components/utils/Breadcrumbs.vue";
import CryptoJS from 'crypto-js';
import MenuMobile from "./components/modules/menu/MenuMobile.vue";
import LibraryListMobile from "./components/modules/tracklist/views/LibraryListMobile.vue";

const activeLyricsIcon = ref(false);
const album = ref({});
const artist = ref({});
const breadcrumbs = ref([HOME_VIEW]);
const currentActionsSongId = ref(0);
const currentBreadcrumb = ref(0);
const currentIndex = ref(0);
const currentLyricsLine = ref("");
const currentTrack = ref({});
const currentTracklist = ref({});
const currentView = ref(HOME_VIEW);
const idPlayingTrack = ref(0);
const idPlayingTracklist = ref(0);
const idTracklist = ref(0);
const idTracklistToDelete = ref(0);
const isMobileView = ref(false);
const playingTracklist = ref({});
const playTrack = ref(true);
const randomMode = ref(false);
const repeatMode = ref(false);
const searchedSongs = ref([]);
const showAddSongsModal = ref(false);
const showDeleteTracklistModal = ref(false);
const showEditTracklistModal = ref(false);
const showTracklistModal = ref(false);
const song = ref('');
const songLoading = ref(false);
// Default columnn it will be order by
const sortColumn = ref('date');
// 1 for ascendant, -1 for descendant
const sortDirection = ref(-1);
const soundView = ref(false);
const thumbnails = ref([]);
const tracklists = ref({});
const tracklistToEdit = ref({});
const toast = useToast();

var lyricsObject = {};

onMounted(() => {
  // Fetch all the tracklists
  getTracklists();
  // Fetch all the thumbnails
  getThumbnails();
  // Update screen size
  updateScreenSize();
  // Update screen size every time this resizes
  window.addEventListener('resize', updateScreenSize);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', updateScreenSize);
});

// Check if the screen size is under 800px (mobile view)
const updateScreenSize = () => {
  isMobileView.value = window.innerWidth <= 800;
};
// Creates a new tracklist
const createTracklist = async (tracklistData) => {
  const response = await fetch(`${API_URL}/api/tracklist`, {
    method: 'POST',
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
      'name': tracklistData.tracklistName,
      'thumbnail': tracklistData.thumbnail
    })
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
// Get all the tracklists
const getTracklists = async () => {
  const response = await fetch(`${API_URL}/api/tracklists`);
  tracklists.value = await response.json();
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
    let track = playingTracklist.value.tracks[currentIndex.value];
    getTrack(track, currentIndex.value, track.idTrack, playingTracklist.value.idTracklist);
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
    } else if (currentIndex.value < playingTracklist.value.tracks.length - 1) {
      // If there is a next track play it, otherwise start from the first track of the tracklist
      currentIndex.value++;
    } else {
      currentIndex.value = 0;
    }
    let track = playingTracklist.value.tracks[currentIndex.value];
    getTrack(track, currentIndex.value, track.idTrack, playingTracklist.value.idTracklist);
  }
}
// Set the value of the tracklist's id
function setIdTracklist(id) {
  idTracklist.value = id;
}
/**
 * Load the track is going to be played
 * @param idTrack 
 * @param idTracklist 
 * @param isTracklistPlayer 
 */
function setCurrentTrack(idTrack, idTracklist, isTracklistPlayer = 0) {
  if (Object.keys(currentTrack.value).length > 0 || isTracklistPlayer) {
    if (idTrack == 0) {
      playTrack.value = false;
    } else if (idPlayingTrack.value != idTrack || (idPlayingTrack.value == idTrack && idPlayingTracklist.value != idTracklist)) {
      // If there is no playlist playing currently or we change from a playlist to another, we assign it data 
      // from the current tracklist (which is the playlist is currently displayed in the screen)
      if (Object.keys(playingTracklist.value).length === 0 || (idPlayingTracklist.value !== idTracklist && idTracklist !== 0)) {
        playingTracklist.value = currentTracklist.value;
      }
      songLoading.value = true;
      // If idPlayingTrack is 0, that means the player has not started yet
      if (idPlayingTrack.value !== 0) playTrack.value = false;
      // Search the selected track in the tracklist, get it and send it to the player
      playingTracklist.value.tracks.forEach((track, index) => {
        if (track.idTrack == idTrack) {
          getTrack(track, index, idTrack, idTracklist);
        }
      });
    } else {
      playTrack.value = true;
    }
  }
}
// Sets as the current tracklist the temporal one. It could be the songs of an album, artist or the list of searched songs
const setPlayingTracklist = (tracklist) => {
  playingTracklist.value = tracklist;
  idPlayingTracklist.value = tracklist.idTracklist;
}
// Get the specified track from the AWS S3 bucket
const getTrack = async (track, index, idTrack, idTracklist) => {
  var lyrics = getLyricsFromLocalStorage(track.title);
  const response = await fetch(`${API_URL}/api/getTrack`, {
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
  song.value = await response.json();
  storeLyricsInLocalStorage(track.title, song.value.lyrics);
  currentIndex.value = index;
  currentTrack.value = track;
  lyricsToObject();
  idPlayingTrack.value = idTrack;
  idPlayingTracklist.value = idTracklist;
  songLoading.value = false;
  playTrack.value = true;
}
// Get the only the audio of a song
const getAudioTrack = async (src) => {
  const response = await fetch(`${API_URL}/api/getAudioTrack/${src}`);
  const data = await response.json();
  return data.audioUrl;
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
function setCurrentView(view) {
  currentView.value = view;
  // Deactive lyrics button when the view is not lyrics one
  if (currentView.value != LYRICS_VIEW) {
    activeLyricsIcon.value = false;
  }
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
  let index = Math.floor(Math.random() * playingTracklist.value.tracks.length);
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
// Opens the delete tracklist modal
const openDeleteTracklistDialog = (idTracklist) => {
  showDeleteTracklistModal.value = true;
  idTracklistToDelete.value = idTracklist;
}
const openEditTracklistDialog = (tracklist) => {
  showEditTracklistModal.value = true;
  tracklistToEdit.value = tracklist;
}
// Closes Edit tracklist modal
const closeEditModal = () => showEditTracklistModal.value = false;
// Opens add songs modal
const openAddSongsModal = () => showAddSongsModal.value = true;
// Closes add songs modal
const closeAddSongsModal = () => showAddSongsModal.value = false;
// Closes delete tracklist modal
const handleCloseDeleteDialog = () => showDeleteTracklistModal.value = false;
// Removes the selected tracklist
const deleteTracklist = async () => {
  const response = await fetch(`${API_URL}/api/tracklist/${idTracklistToDelete.value}`, {
    method: "DELETE"
  });
  const data = await response.json();
  // Changes view
  setCurrentView(HOME_VIEW);
  // Add the current view to breadcrumbs
  addViewToBreadcrumbs(HOME_VIEW);
  // Closes the dialog
  showDeleteTracklistModal.value = false;
  // Display notifications
  if (data.success) {
    toast.success("Tracklist removed successfully", {
      timeout: 3000
    });
  } else {
    toast.error("An error ocurred removing the tracklist", {
      timeout: 3000
    });
  }
  // Reload the tracklist
  getTracklists();
}
// Edit tracklist
const editTracklist = async (tracklist) => {
  const response = await fetch(`${API_URL}/api/tracklist`, {
    method: "PUT",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
      "idTracklist": tracklist.idTracklist,
      "name": tracklist.name,
      "thumbnail": tracklist.thumbnail
    })
  });
  const data = await response.json();
  // Display the correspondant notification
  if (data.success) {
    toast.success("Tracklist edited successfully!", {
      timeout: 3000
    });
  } else {
    toast.error("An error occurred while editing the tracklist", {
      timeout: 3000
    });
  }
  closeEditModal();
  // Reload lists of tracklists
  getTracklists();
  // Reload current tracklist view
  reloadTracklist();
}
// Retrieve the data from the current tracklist
const reloadTracklist = async () => {
  const response = await fetch(`${API_URL}/api/tracklist/${idTracklist.value}`);
  currentTracklist.value = await response.json();
  // Check if there is a tracklist playing at the moment
  if (Object.keys(playingTracklist.value).length > 0) {
    // If the tracklist is been watching at the moment is the same that the one is playing, we update the last one
    if (currentTracklist.value.idTracklist == playingTracklist.value.idTracklist)
    playingTracklist.value = currentTracklist.value;
  }
}
// Retrieves all the thumbnails
const getThumbnails = async () => {
  const response = await fetch(`${API_URL}/api/thumbnails`);
  thumbnails.value = await response.json();
}
// Search for the song given from parameters in the DB
const searchSong = async (song) => {
  const response = await fetch(`${API_URL}/api/search/${song}`);
  searchedSongs.value = await response.json();
}
// Search by song artist and albums
const search = async (text) => {
  const response = await fetch(`${API_URL}/api/search/all/${text}`);
  return await response.json();
}
// Retrieves songs, artists and albums to show in the Search view when no search has been made
const getInitialDataSearch = async () => {
  const response = await fetch(`${API_URL}/api/search/initial`);
  return await response.json();
}
// Adds a song to the current tracklist
const addSongToTracklist = async (idTrack, idTracklist = 0) => {
  const response = await fetch(`${API_URL}/api/addTrack`, {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
      idTrack,
      "idTracklist": idTracklist > 0 ? idTracklist : currentTracklist.value.idTracklist
    })
  });
  const data = await response.json();
  // Display the correspondant notification
  if (data.success) {
    toast.success("Song added to playlist successfully!", {
      timeout: 3000
    });
  } else {
    toast.error("An error occurred while adding song to the playlist", {
      timeout: 3000
    });
  }
  // Reload lists of tracklists
  getTracklists();
  // Reload current tracklist view
  reloadTracklist();
  
  return data.success;
}
// Removes a song from the current tracklist
const removeSongFromTracklist = async (idTrack) => {
  const response = await fetch(`${API_URL}/api/removeTrack`, {
    method: "DELETE",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
      idTrack,
      "idTracklist": currentTracklist.value.idTracklist
    })
  });
  const data = await response.json();
  // Display the correspondant notification
  if (data.success) {
    toast.success("Song removed from playlist successfully!", {
      timeout: 3000
    });
  } else {
    toast.error("An error occurred while removing song from the playlist", {
      timeout: 3000
    });
  }
  // Reload lists of tracklists
  getTracklists();
  // Reload current tracklist view
  reloadTracklist();
  
  return data.success; 
}
// Handles whether the lyrics view is displayed or not and change icon lyrics button color
const handleActiveLyrics = () => {
  if (Object.keys(currentTrack.value).length > 0) {
    activeLyricsIcon.value = true;
    setCurrentView(LYRICS_VIEW);
    // Add the current view to breadcrumbs
    addViewToBreadcrumbs(LYRICS_VIEW);
  } else {
    activeLyricsIcon.value = false;
  }
}
// Handles all the data to display on the artist view
const handleArtistView = async (idArtist) => {
  const response = await fetch(`${API_URL}/api/artist/${idArtist}`);
  const data = await response.json();
  if (data.success) {
    artist.value = data.artist;
    // After retrieve all the information of the artist, render the artist view
    setCurrentView(ARTIST_VIEW);
    // Add the current view to breadcrumbs
    addViewToBreadcrumbs(ARTIST_VIEW);
  } else {
    toast.error("The artist you are looking for does not exist", {
      timeout: 3000
    });
  }
}
// Handles all the data to display on the album view
const handleAlbumView = async (idAlbum) => {
  const response = await fetch(`${API_URL}/api/album/${idAlbum}`);
  const data = await response.json();
  if (data.success) {
    album.value = data.album;
    // After retrieve all the information of the album, render the album view
    setCurrentView(ALBUM_VIEW);
    // Add the current view to breadcrumbs
    addViewToBreadcrumbs(ALBUM_VIEW);
  } else {
    toast.error("The album you are looking for does not exist", {
      timeout: 3000
    });
  }
}
// Open the modal to create a playlist
const openCreatePlaylistModal = () => {
  showTracklistModal.value = true;
}
// Add the given view to breadcrumbs
const addViewToBreadcrumbs = (view) => {
  breadcrumbs.value.push(view);
  currentBreadcrumb.value = breadcrumbs.value.length - 1;
}
// Handles whether go to previous or next breadcrumb
// 1: Previous | 2: Next
const handleBreadcrumbAction = (actionCode) => {
  // Update the current breadcrumb value
  if (actionCode == 1) {
    currentBreadcrumb.value = ((currentBreadcrumb.value - 1) >= 0) ? currentBreadcrumb.value - 1 : currentBreadcrumb.value;
  } else if (actionCode == 2) {
    currentBreadcrumb.value = ((currentBreadcrumb.value + 1) < breadcrumbs.value.length) ? currentBreadcrumb.value + 1 : currentBreadcrumb.value;
  }
  // Set current view based on current breadcrumb
  setCurrentView(breadcrumbs.value[currentBreadcrumb.value]);
}
// Handles song actions
const updateCurrentActionsSongId = (idTrack) => {
  currentActionsSongId.value = idTrack;
}
// Changes the direction and order of a column of a tracklist
const setSort = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value *= -1;
  } else {
    sortColumn.value = column;
    sortDirection.value = 1;
  }
}
// Transform duration from 'm:ss' format to seconds
const convertDurationToSeconds = (duration) => {
  const [minutes, seconds] = duration.split(':').map(Number);
  return minutes * 60 + seconds;
}
// Sorts the current tracklist's songs
const sortedTracks = computed(() => {
  const sorted = [...currentTracklist.value.tracks].sort((a, b) => {
    if (sortColumn.value === 'title') {
      return a.title.localeCompare(b.title) * sortDirection.value;
    } else if (sortColumn.value === 'album') {
      return a.album.title.localeCompare(b.album.title) * sortDirection.value;
    } else if (sortColumn.value === 'date') {
      let tracklistTrackA = a.tracklist_track.find(tracklist_track => tracklist_track.idTracklist == currentTracklist.value.idTracklist);
      let tracklistTrackB = b.tracklist_track.find(tracklist_track => tracklist_track.idTracklist == currentTracklist.value.idTracklist);
      let dateA = tracklistTrackA.created_at;
      let dateB = tracklistTrackB.created_at;
      return (new Date(dateA) - new Date(dateB)) * sortDirection.value;
    } else if (sortColumn.value === 'time') {
      let valueA = convertDurationToSeconds(a.duration);
      let valueB = convertDurationToSeconds(b.duration);
      return (valueA - valueB) * sortDirection.value;
    }
    return 0;
  });

  // Updates the current index of the tracklist's song
  if (Object.keys(currentTrack.value).length > 0 && currentTracklist.value.idTracklist == playingTracklist.value.idTracklist) {
    sorted.forEach((track, index) => {
      if (track.idTrack == currentTrack.value.idTrack) {
        currentIndex.value = index;
      }
    });
  }
  // Updates the tracks of the current tracklist
  currentTracklist.value.tracks = sorted;

  return sorted;
});
// Watch when the value of tracklist's id changes to make a request to the server side to get the correspondant tracklist
watchEffect(async () => {
  reloadTracklist();
});
// Hide sound view when screen size is under 800px
watch(
  () => isMobileView.value,
  () => {
    if (isMobileView.value) {
      soundView.value = false;
    }
    // Set the home view as the current one if we are not in the mobile view and the library list view was displayed
    if (!isMobileView.value && checkCurrentView(LIBRARY_LIST_VIEW)) {
      setCurrentView(HOME_VIEW);
    }
  },
  { immediate: true }
)

provide('activeLyricsIcon', activeLyricsIcon);
provide('addSongToTracklist', addSongToTracklist);
provide('checkPlayingTracklist', checkPlayingTracklist);
provide('currentActionsSongId', currentActionsSongId);
provide('currentTracklist', currentTracklist);
provide('getAudioTrack', getAudioTrack);
provide('getInitialDataSearch', getInitialDataSearch);
provide('handleAlbumView', handleAlbumView);
provide('handleArtistView', handleArtistView);
provide('idPlayingTrack', idPlayingTrack);
provide('isPlaying', playTrack);
provide('openAddSongsModal', openAddSongsModal);
provide('openCreatePlaylistModal', openCreatePlaylistModal);
provide('openDeleteTracklistDialog', openDeleteTracklistDialog);
provide('openEditTracklistDialog', openEditTracklistDialog);
provide('randomMode', randomMode);
provide('removeSongFromTracklist', removeSongFromTracklist);
provide('searchedSongs', searchedSongs);
provide('searchSong', searchSong);
provide('setCurrentTrack', setCurrentTrack);
provide('setSort', setSort);
provide('sortColumn', sortColumn);
provide('sortDirection', sortDirection);
provide('sortedTracks', sortedTracks);
provide('tracklists', tracklists);
provide('updateCurrentActionsSongId', updateCurrentActionsSongId);
</script>

<template>
  <div class="container">
    <div class="menu" v-if="!isMobileView">
      <div class="menu-top">
        <div class="menu-item" 
          @click="
            setCurrentView(HOME_VIEW);
            addViewToBreadcrumbs(HOME_VIEW);
          ">
          <div :class="['menu-item-content', { active: currentView == HOME_VIEW }]">
            <font-awesome-icon icon="fa-solid fa-house" class="icon" />Home
          </div>
        </div>
        <div class="menu-item" 
          @click="
            setCurrentView(SEARCH_VIEW);
            addViewToBreadcrumbs(SEARCH_VIEW);
          ">
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
        <div class="library-content">
          <div class="library-tracklists">
            <LibraryMenu 
              v-for="tracklist in tracklists"
              :key="tracklist.idTracklist"
              :tracklist="tracklist"
              :is-active="checkSelectedTracklist(tracklist.idTracklist)"
              @click="
                setCurrentView(LIBRARY_VIEW);
                addViewToBreadcrumbs(LIBRARY_VIEW);
                setIdTracklist(tracklist.idTracklist);
              "
            />
          </div>
        </div>
      </div>
    </div>
    <div class="main">
      <CreateTracklistModal
        @create-tracklist="createTracklist"
        @close-dialog="showTracklistModal = !showTracklistModal"
        :show-modal="showTracklistModal"
        :thumbnails="thumbnails"
      />
      <EditTracklistModal 
        :show-modal="showEditTracklistModal"
        :close-modal="closeEditModal"
        :edit-tracklist="editTracklist"
        :tracklist="tracklistToEdit"
        :thumbnails="thumbnails"
      />
      <DeleteTracklistModal 
        :delete-tracklist="deleteTracklist"
        :handle-close-dialog="handleCloseDeleteDialog" 
        :show-delete-tracklist-modal="showDeleteTracklistModal"
      />
      <AddSongsModal 
        :close-modal="closeAddSongsModal"
        :show-modal="showAddSongsModal"
      />
      <Home 
        v-if="checkCurrentView(HOME_VIEW)" 
        :sound-view="soundView"
      />
      <Search 
        v-else-if="checkCurrentView(SEARCH_VIEW)" 
        :search="search"
        :get-initial-data-search="getInitialDataSearch"
        :set-playing-tracklist="setPlayingTracklist"
        :set-current-track="setCurrentTrack"
        :play-track="playTrack"
      />
      <Library 
        v-else-if="checkCurrentView(LIBRARY_VIEW)" 
        :tracklist="currentTracklist" 
      />
      <Artist 
        v-else-if="checkCurrentView(ARTIST_VIEW)" 
        :artist="artist"
        :set-current-track="setCurrentTrack"
        :set-playing-tracklist="setPlayingTracklist"
        :play-track="playTrack"
      />
      <Album 
        v-else-if="checkCurrentView(ALBUM_VIEW)"
        :album="album"
        :set-current-track="setCurrentTrack"
        :set-playing-tracklist="setPlayingTracklist"
        :play-track="playTrack"
      />
      <LibraryListMobile 
        v-else-if="checkCurrentView(LIBRARY_LIST_VIEW)"
        :tracklists="tracklists"
        :set-current-view="setCurrentView"
        :set-id-tracklist="setIdTracklist"
        :check-selected-tracklist="checkSelectedTracklist"
        :open-create-playlist-modal="openCreatePlaylistModal"
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
      :is-mobile-view="isMobileView"
      :repeat-mode="repeatMode"
      :song-loading="songLoading"
      :update-lyrics="updateLyrics"
      :update-lyrics-progress-bar="updateLyricsProgressBar"
      :handle-random-mode="handleRandomMode"
      :handle-repeat-mode="handleRepeatMode"
      :handle-active-lyrics="handleActiveLyrics"
      @active-sound-view="handleSoundView"
      @prev-track="prevTrack"
      @next-track="nextTrack"
    />
  </div>
  <MenuMobile 
    v-if="isMobileView" 
    :current-view="currentView"
    :set-current-view="setCurrentView"
  />
</template>

<style scoped>
.container {
  display: flex;
  width: 100%;
  height: 90%;
}
.player {
  height: 10%;
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
  display: flex;
  flex-direction: column;
  height: 100%;
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
  color: black;
  border: 1px solid white;
  background-color: white;
}
.library-content {
  display: flex;
  flex-direction: column;
  flex: 1;
}
.library-tracklists {
  overflow-y: auto;
}

@media screen and (max-width: 800px) {
  .container {
    height: 80%;
  }
}
</style>
