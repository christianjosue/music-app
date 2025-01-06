<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import ArtistCard from '../artist/ArtistCard.vue';
import SongCard from '../song/SongCard.vue';
import AlbumCard from '../album/AlbumCard.vue';
import IconPreloaderAnimation from '../../icons/IconPreloaderAnimation.vue';
import { SEARCH_ID_TRACKLIST } from '../../../../config';

const props = defineProps({
  search: {
    type: Function,
    default: {}
  },
  getInitialDataSearch: {
    type: Function,
    default: {}
  },
  setPlayingTracklist: {
    type: Function,
    default: {}
  },
  setCurrentTrack: {
    type: Function,
    default: {}
  },
  playTrack: {
    type: Boolean,
    default: false
  }
});

const isFocused = ref(false);
const searchInput = ref(null);
const searchResults = ref(null);
const searchInitialResults = ref(null);
const searchTracklist = ref({});
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
// Handles the play of the track
const handlePlayTrack = (idTrack) => {
  // Set the current playing tracklist
  props.setPlayingTracklist(searchTracklist.value);
  // Sets the current track, send track's id for playing it and 0 for pause it
  props.setCurrentTrack(idTrack, SEARCH_ID_TRACKLIST, 1);
}
// Retrieves initial values to show in the view and make a copy of them
onMounted(async () => {
  preloader.value = true;
  searchInitialResults.value = await props.getInitialDataSearch();
  searchResults.value = searchInitialResults.value;
  preloader.value = false;
});

watch(
  () => searchResults.value,
  () => {
    // Tracklists with negative ids are temporary tracklists. In this case, the temporary tracklist would be formed by 
    // the searched tracks
    searchTracklist.value.idTracklist = -1;
    searchTracklist.value.tracks = searchResults.value?.songs;
  },
  { immediate: true }
)
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
      <IconPreloaderAnimation v-if="preloader" />
      <h3 v-if="noResults">There are no results for your search</h3>
      <h3 v-if="searchResults?.songs.length > 0">Songs</h3>
      <div class="songs-container">
        <SongCard 
          v-for="song in searchResults?.songs"
          :key="song.idTrack"
          :song="song"
          :handle-play-track="handlePlayTrack"
          :play-track="playTrack"
          :id-tracklist="SEARCH_ID_TRACKLIST"
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
  flex-wrap: wrap;
}
/* Albums container styles */
.albums-container {
  display: flex;
  flex-wrap: wrap;
}

@media screen and (max-width: 800px) {
  .search-container {
    width: 80%;
  }
}
</style>
