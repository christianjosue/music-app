<script setup>
import { inject, onMounted, ref } from 'vue';
import AlbumCard from '../album/AlbumCard.vue';
import ArtistCard from '../artist/ArtistCard.vue';
import IconPreloaderAnimation from '../../icons/IconPreloaderAnimation.vue';

defineProps({
  color: {
    type: String,
    default() {
      return "linear-gradient(to bottom, #2e114f 0%, #111 30%)";
    },
  },
});

const getInitialDataSearch = inject('getInitialDataSearch');
const data = ref(null);
const preloader = ref(false);

// Handles the greet message displayed on the home view depending on the current time
const greet = () => {
  const time = new Date();
  const hours = time.getHours();
  var message = "Good afternoon!";
  if (hours >= 21 || hours <= 6) {
    message = "Good night!";
  } else if (hours >= 7 && hours <= 12) {
    message = "Good morning!";
  }

  return message;
}

onMounted(async () => {
  preloader.value = true;
  data.value = await getInitialDataSearch();
  preloader.value = false;
});
</script>

<template>
  <div
    class="main"
    :style="{ background: `linear-gradient(to bottom, ${color} 0%, #111 30%)` }"
  >
    <h1>{{ greet() }}</h1>
    <div class="home-results-container">
      <IconPreloaderAnimation v-if="preloader" />
      <h3 v-if="data?.artists.length > 0">Artists</h3>
      <div class="artists-container">
          <ArtistCard 
            v-for="artist in data?.artists"
            :key="artist.idArtist"
            :artist="artist"
          />
      </div>
      <h3 v-if="data?.albums.length > 0">Albums</h3>
      <div class="albums-container">
        <AlbumCard 
          v-for="album in data?.albums" 
          :key="album.idAlbum"
          :album="album"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
h1 {
  color: white;
  margin: 0;
  margin-bottom: 20px;
}
.main {
  width: 100%;
  height: calc(100% - 60px);
  border-radius: 0 0 10px 10px;
  box-sizing: border-box;
  padding: 15px;
  transition: background 1s ease;
  overflow-x: hidden;
  overflow-y: auto;
}
.home-results-container {
  width: 90%;
  padding-top: 20px;
  margin: auto;
  color: white;
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
::-webkit-scrollbar {
  width: 12px;
}
::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}
::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.4);
  transition: all 0.3s ease;
}
</style>
