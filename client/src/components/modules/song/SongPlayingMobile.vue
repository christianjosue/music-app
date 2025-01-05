<script setup>
import { LYRICS_VIEW } from '../../../../config';

const props = defineProps({
    currentTrack: {
        type: Object,
        default: {}
    },
    setCurrentView: {
        type: Function,
        default: {}
    }
});
</script>

<template>
<div class="song-playing-mobile-container">
    <h4>Is playing</h4>
    <div class="song-data">
        <img :src="currentTrack.thumbnail" alt="thumbnail">
        <div class="song-title">{{ currentTrack.title }}</div>
        <div class="song-artist">
            <div v-for="artist in currentTrack.artists">
                {{ artist.name }}
            </div>
        </div>
        <div class="lyrics" @click="setCurrentView(LYRICS_VIEW)">
            Lyrics <font-awesome-icon :icon="['fas', 'microphone']" />
        </div>
    </div>
</div>
</template>

<style scoped>
.song-playing-mobile-container {
    background-color: #111;
    border-radius: 10px;
    color: white;
    flex: 7;
    overflow-y: auto;
    box-sizing: border-box;
    padding: 15px;
}
.song-data {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 30px;
}
.song-title {
    color: white;
    font-weight: bold;
    margin-top: 15px;
    font-size: 20px;
}
.song-artist {
    font-size: 16px;
    color: #888;
    margin-top: 5px;
}
.lyrics {
    font-size: 18px;
    margin-top: 40px;
    border: 1px solid #888;
    border-radius: 10px;
    padding: 10px 20px;
    transition: all .2s ease;
}
.lyrics:hover {
    background: white;
    color: black;
}
img {
    width: 50%;
    height: auto;
    border-radius: 10px;
}
@media screen and (max-width: 600px) {
    img {
        width: 70%;
    }
}
@media screen and (max-width: 430px) {
    img {
        width: 90%;
    }
}
</style>