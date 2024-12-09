<script setup>
import { ref, watch } from 'vue';
import SongCard from '../song/SongCard.vue';
import AlbumCard from '../album/AlbumCard.vue';
import { ARTIST_ID_TRACKLIST } from '../../../../config';

const props = defineProps({
    artist: {
        type: Object,
        default: {}
    },
    setCurrentTrack: {
        type: Function,
        default: {}
    },
    setPlayingTracklist: {
        type: Function,
        default: {}
    },
    playTrack: {
        type: Boolean,
        default: false
    }
});

const artistTracklist = ref({});

// Format the number of listeners for add it dots
const formatMonthlyListeners = () => {
    const numberString = props.artist.monthlyListeners.toString();
    // Use a regular expression to add dots every 3 digits till the end of the string
    return numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
// Handles the play of the track
const handlePlayTrack = (idTrack) => {
  // Set the current playing tracklist
  props.setPlayingTracklist(artistTracklist.value);
  // Sets the current track, send track's id for playing it and 0 for pause it
  props.setCurrentTrack(idTrack, ARTIST_ID_TRACKLIST, 1);
}

watch(
    () => props.artist,
    () => {
        // Tracklists with negative ids are temporary tracklists. In this case, the temporary tracklist would be formed by 
        // the artist tracks
        artistTracklist.value.idTracklist = ARTIST_ID_TRACKLIST;
        artistTracklist.value.tracks = props.artist?.songs;
    },
    { immediate: true }
);
</script>

<template>
<div class="artist-view-container">
    <div class="artist-front-page">
        <div class="cover-container">
            <div class="cover" :style="{ backgroundImage: `url(${artist.cover})` }"></div>
            <div class="left-background"></div>
            <div class="filter"></div>
            <div class="artist-info">
                <div class="artist-name">{{ artist.name }}</div>
                <div class="artist-monthly-listeners">{{ formatMonthlyListeners() }} monthly listeners</div>
            </div>
        </div>
    </div>
    <div class="artist-songs">
        <h3>Songs</h3>
        <SongCard v-for="song in artist?.songs"
          :key="song.idTrack"
          :song="song"
          :handle-play-track="handlePlayTrack"
          :play-track="playTrack"
          :id-tracklist="ARTIST_ID_TRACKLIST" 
        />
    </div>
    <div class="artist-albums">
        <h3>Albums</h3>
        <AlbumCard 
          v-for="album in artist?.albums"
          :key="album.idAlbum"
          :album="album"
        />
    </div>
</div>
</template>

<style scoped>
.artist-view-container {
    background-color: #111;
    border-radius: 10px;
    color: white;
    flex: 7;
    overflow-y: auto;
}
/* Artist cover */
.cover-container {
    height: 320px;
    width: 100%;
    position: relative;
}
.cover {
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    width: 60%;
    z-index: 1;
}
.filter {
    background: linear-gradient(to right, black 0%, transparent 50%);
    position: absolute;
    top: 0;
    right: 0;
    width: 60%;
    height: 100%;
    z-index: 2;
    pointer-events: none;
}
.left-background {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: black;
}
/* Artist info */
.artist-info {
    font-family: 'Montserrat', sans-serif;
    position: absolute;
    bottom: 0;
    color: white;
    z-index: 3;
    padding: 25px;
}
.artist-name {
    font-size: 7rem;
    font-weight: 900;
}
.artist-monthly-listeners {
    font-size: 20px;
    font-weight: 100;
}
/* Artist songs and albums */
.artist-songs,
.artist-albums {
    margin: auto;
    width: 80%;
}
</style>