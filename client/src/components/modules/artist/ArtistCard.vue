<script setup>
import { inject } from 'vue';

defineProps({
    artist: {
        type: Object,
        default: {}
    }
});

const handleArtistView = inject('handleArtistView');
</script>

<template>
    <div class="artist-card" @click="handleArtistView(artist.idArtist)">
        <div class="artist-thumbnail-container">
            <div class="artist-thumbnail" :style="{ backgroundImage: `url(${artist.thumbnail})`}"></div>
        </div>
        <div class="artist-name">{{ artist.name }}</div>
        <div class="artist-footer">Artist</div>
    </div>
</template>

<style scoped>
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
</style>