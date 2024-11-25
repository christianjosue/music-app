<script setup>
defineProps({
    song: {
        type: Object,
        default: {}
    }
})
</script>

<template>
    <div class="song-card">
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
</template>

<style scoped>
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
</style>