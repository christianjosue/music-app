<script setup>
import { ref } from 'vue';
const props = defineProps({
  'search': {
    type: Function,
    default: {}
  }
});

const isFocused = ref(false);
const searchInput = ref(null);
const searchResults = ref(null);
var timeout = setTimeout(() => {}, 0);

const handleFocus = () => {
  isFocused.value = true;
}
const handleBlur = () => {
  isFocused.value = false;
}
const handleSearch = () => {
  clearTimeout(timeout);
  timeout = setTimeout(async () => {
    if (searchInput.value != null && searchInput.value.value != "") {
      searchResults.value = await props.search(searchInput.value.value);
    }
  }, 250);
}
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
      <h3>Songs</h3>
      <div class="songs-container">
        <div v-for="song in searchResults?.songs" :key="song.idTrack" class="song-card">
          <div class="song-thumbnail">
            <img :src="song.thumbnail" alt="thumbnail">
          </div>
          <div class="song-info">
            <div class="song-title">{{ song.title }}</div>
            <div class="song-artist">{{ song.artists[0]?.name }}</div>
          </div>
          <div class="song-duration">{{ song.duration }}</div>
          <div class="song-actions">...</div>
        </div>
      </div>
      <h3>Artists</h3>
      <div class="artists-container">
        <div v-for="artist in searchResults?.artists" :key="artist.idArtist" class="artist-card">
          <div class="artist-thumbnail-container">
            <div class="artist-thumbnail" :style="{ backgroundImage: `url(${artist.thumbnail})`}"></div>
          </div>
          <div class="artist-name">{{ artist.name }}</div>
          <div class="artist-footer">Artist</div>
        </div>
      </div>
      <h3>Albums</h3>
      <div class="albums-container">
        <div v-for="album in searchResults?.albums" :key="album.idAlbum" class="album-card">
          <div class="album-cover" :style="{ backgroundImage: `url(${album.cover})` }"></div>
          <div class="album-title">{{ album.title }}</div>
          <div class="album-artist">{{ album.artist?.name }}</div>
        </div>
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

/* Songs container styles */
.song-card {
  display: flex;
  align-items: center;
  transition: all .3s ease;
  padding: 10px;
  border-radius: 10px;
}
.song-card:hover {
  background: #333;
}
.song-thumbnail img {
  width: 60px;
  height: 60px;
  border-radius: 10px;
}
.song-info {
  flex: 7;
  margin-left: 20px;
}
.song-artist {
  font-size: 14px;
  color: #888;
  margin-top: 5px;
}
.song-duration,
.song-actions {
  color: #888;
  display: flex;
  justify-content: center;
  flex: 1;
}

/* Artists container styles */
.artists-container {
  display: flex;
  justify-content: left;
}
.artist-card {
  margin-right: 10px;
  transition: all .3s ease;
  padding: 15px;
  border-radius: 10px;
  cursor: pointer;
}
.artist-card:hover {
  background-color: #333;
}
.artist-thumbnail {
  width: 130px;
  height: 130px;
  background-size: cover;
  background-position: center center;
  border-radius: 50%;
}
.artist-name {
  margin: 7px 0;
}
.artist-footer {
  font-size: 14px;
  color: #888;
}

/* Albums container styles */
.albums-container {
  display: flex;
}
.album-card {
  margin-right: 10px;
  padding: 15px;
  transition: all .3s ease;
  width: 130px;
  border-radius: 10px;
  cursor: pointer;
}
.album-card:hover {
  background-color: #333;
}
.album-cover {
  width: 130px;
  height: 130px;
  background-size: cover;
  background-position: center center;
  border-radius: 10px;
}
.album-title {
  margin: 7px 0;
}
.album-artist {
  color: #888;
  font-size: 14px;
}
</style>
