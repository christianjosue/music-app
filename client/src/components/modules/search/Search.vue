<script setup>
import { computed, onMounted, ref } from 'vue';
import ArtistCard from '../artist/ArtistCard.vue';
import SongCard from '../song/SongCard.vue';
import AlbumCard from '../album/AlbumCard.vue';

const props = defineProps({
  'search': {
    type: Function,
    default: {}
  },
  'getInitialDataSearch': {
    type: Function,
    default: {}
  }
});

const isFocused = ref(false);
const searchInput = ref(null);
const searchResults = ref(null);
const searchInitialResults = ref(null);
const preloader = ref(false);
var timeout = setTimeout(() => {}, 0);

const handleFocus = () => {
  isFocused.value = true;
}
const handleBlur = () => {
  isFocused.value = false;
}
// Handles whether search by the input's text or show initial default data
const handleSearch = () => {
  clearTimeout(timeout);
  timeout = setTimeout(async () => {
    if (searchInput.value?.value != "") {
      searchResults.value = await props.search(searchInput.value.value);
    } else {
      searchResults.value = searchInitialResults.value;
    }
  }, 250);
}
// Manages whether shows a message indicating there is no results or not
const noResults = computed(() => {
  return searchResults.value?.songs.length == 0 && searchResults.value?.artists.length == 0 && searchResults.value?.albums.length == 0 && searchInput.value?.value != "";
});
// Retrieves initial values to show in the view and make a copy of them
onMounted(async () => {
  preloader.value = true;
  searchInitialResults.value = await props.getInitialDataSearch();
  searchResults.value = searchInitialResults.value;
  preloader.value = false;
});
</script>

<template>
  <div class="search-view-container">
    <div class="search-container">
      <font-awesome-icon 
        :class="['search-icon', { focused: isFocused }]" 
        :icon="['fas', 'magnifying-glass']" 
      />
      <input 
        @focus="handleFocus"
        @blur="handleBlur"
        @keyup="handleSearch"
        type="text" 
        ref="searchInput"
        placeholder="What do you want to play?" 
      >
    </div>
    <div class="search-results-container">
      <div v-if="preloader" class="kabobloader">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
      </div>
      <h3 v-if="noResults">There are no results for your search</h3>
      <h3 v-if="searchResults?.songs.length > 0">Songs</h3>
      <div class="songs-container">
        <SongCard 
          v-for="song in searchResults?.songs"
          :key="song.idTrack"
          :song="song"
        />
      </div>
      <h3 v-if="searchResults?.artists.length > 0">Artists</h3>
      <div class="artists-container">
          <ArtistCard 
            v-for="artist in searchResults?.artists"
            :key="artist.idArtist"
            :artist="artist"
          />
      </div>
      <h3 v-if="searchResults?.albums.length > 0">Albums</h3>
      <div class="albums-container">
        <AlbumCard 
          v-for="album in searchResults?.albums" 
          :key="album.idAlbum"
          :album="album"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.search-view-container {
  background-color: #111;
  border-radius: 10px;
  color: white;
  flex: 7;
  overflow-y: auto;
}
.search-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: auto;
  width: 50%;
  margin-top: 10px;
}
.search-container input {
  width: 100%;
  border-radius: 30px;
  font-size: 16px;
  background-color: transparent;
  border: 1px solid #888;
  padding: 15px;
  outline: none;
  color: white;
  transition: all .3s ease;
}
.search-container input:focus {
  border: 1px solid white;
}
.search-container input:focus ~ .search-icon {
  color: white !important;
}
.search-icon {
  font-size: 24px;
  margin-right: 12px;
  color: #888;
  transition: all .3s ease;
}
.search-icon.focused {
  color: white !important;
}
.search-results-container {
  width: 80%;
  margin: auto;
}
/* Artists container styles */
.artists-container {
  display: flex;
  justify-content: left;
}
/* Albums container styles */
.albums-container {
  display: flex;
}
/* Preloader animation */
.kabobloader {
  margin: auto;
  top:0;
  right:0;
  bottom:0;
  left:0;
  position:absolute;
  width: 70px;
  height: 70px;
}
.kabobloader > div {
  width: 18px;
  height: 18px;
  background-color: #2EBD59;
  border-radius: 100%;
  display: inline-block;
  -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}
.kabobloader .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}
.kabobloader .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}
@-webkit-keyframes sk-bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0) }
  40% { -webkit-transform: scale(1.0) }
}
@keyframes sk-bouncedelay {
  0%, 80%, 100% { 
    -webkit-transform: scale(0);
    transform: scale(0);
  } 40% { 
    -webkit-transform: scale(1.0);
    transform: scale(1.0);
  }
}
</style>
