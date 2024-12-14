<script setup>

import { inject } from 'vue';
defineProps({
    album: {
        type: Object,
        default: {}
    }
});

const handleAlbumView = inject('handleAlbumView');
</script>

<template>
    <div class="album-card" @click="handleAlbumView(album.idAlbum)">
        <div class="album-cover" :style="{ backgroundImage: `url(${album.cover})` }"></div>
        <div class="album-title">{{ album.title }}</div>
        <div class="album-artist">{{ album.artist?.name }}</div>
    </div>
</template>

<style scoped>
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