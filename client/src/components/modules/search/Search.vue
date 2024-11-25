<script setup>
import { ref } from 'vue';
import ArtistCard from '../artist/ArtistCard.vue';
import SongCard from '../song/SongCard.vue';
import AlbumCard from '../album/AlbumCard.vue';

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
</style>
