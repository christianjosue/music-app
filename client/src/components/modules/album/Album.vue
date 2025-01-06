<script setup>
import { onMounted, ref, watch } from 'vue';
import { ALBUM_ID_TRACKLIST } from '../../../../config';
import SongCard from '../song/SongCard.vue';
import ArtistCard from '../artist/ArtistCard.vue';

const props = defineProps({
    album: {
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
const numberTracks = ref(0);
const totalTime = ref('');
const backgroundColor = ref('#2e114f');
const albumTracklist = ref({});

// Gets total time of the album in minutes and seconds
const getTotalTime = () => {
    let totalSeconds = 0;
    let totalMinutes = 0;
    props.album.songs.forEach(song => {
        const [minutes, seconds] = song.duration.split(':');
        totalMinutes += parseInt(minutes);
        totalSeconds += parseInt(seconds);
    });
    let minutesToAdd = Math.trunc(totalSeconds / 60);
    let realSeconds = totalSeconds % 60;
    totalMinutes += minutesToAdd;

    let totalTime = `${totalMinutes}min`;
    if (realSeconds > 0) {
        totalTime += ` ${realSeconds}s`;
    }

    return totalTime;
}
// Sets background color depending on the main color of the album's cover
const setBackgroundColor = () => {
  Vibrant.from(props.album.cover).getPalette((err, palette) => {
    backgroundColor.value = `rgb(${palette.Vibrant._rgb[0]}, ${palette.Vibrant._rgb[1]}, ${palette.Vibrant._rgb[2]})`;
  });
}
// Handles the play of the track
const handlePlayTrack = (idTrack) => {
  // Set the current playing tracklist
  props.setPlayingTracklist(albumTracklist.value);
  // Sets the current track, send track's id for playing it and 0 for pause it
  props.setCurrentTrack(idTrack, ALBUM_ID_TRACKLIST, 1);
}
// Updates the number of tracks and the total time of the sum of it every time that the album changes
watch(
    () => props.album,
    () => {
        numberTracks.value = props.album.songs.length;
        totalTime.value = getTotalTime();
        // Tracklists with negative ids are temporary tracklists. In this case, the temporary tracklist would be formed by 
        // the album tracks
        albumTracklist.value.idTracklist = ALBUM_ID_TRACKLIST;
        albumTracklist.value.tracks = props.album?.songs;
    },
    { immediate: true }
)

onMounted(() => {
    setBackgroundColor();
});
</script>

<template>
<div class="album-view-container">
    <div class="album-container" :style="{ backgroundColor: backgroundColor }">
        <div class="thumbnail" :style="{ backgroundImage: `url(${album.cover})` }"></div>
        <div class="album-content">
            <h5>Album</h5>
            <h1>{{ album.title }}</h1>
            <div class="album-details">
                <div style="font-weight: bold;">{{ album.artist.name }} &nbsp;&nbsp;·&nbsp;&nbsp;</div>
                <div>{{ numberTracks }} {{ numberTracks == 1 ? 'track' : 'tracks' }},&nbsp;&nbsp;{{ totalTime }}</div>
            </div>
        </div>
    </div>
    <div class="album-info" :style="{ background: `linear-gradient(to bottom, ${backgroundColor} 0%, transparent 10%)` }">
        <div class="album-songs">
            <h3>Songs</h3>
            <SongCard v-for="song in album?.songs"
                :key="song.idTrack"
                :song="song"
                :handle-play-track="handlePlayTrack"
                :play-track="playTrack"
                :id-tracklist="ALBUM_ID_TRACKLIST"
            />
        </div>
        <div class="album-artist">
            <h3>Artist</h3>
            <div class="album-artist-container">
                <ArtistCard :artist="album.artist" />
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
/* Album header */
.album-view-container {
    background-color: #111;
    border-radius: 10px;
    color: white;
    flex: 7;
    overflow-y: auto;
}
.album-container {
    display: flex;
    align-items: end;
    box-sizing: border-box;
    padding: 25px;
    color: white;
    height: fit-content;
}
h1 {
    font-size: 80px;
    margin: 0;
    margin-bottom: 30px;
}
h5 {
    font-size: 15px;
    font-weight: 400;
    margin: 0;
}
.thumbnail {
    width: 230px;
    height: 230px;
    border-radius: 4px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
}
.album-content {
    margin-left: 20px;
}
.album-details {
    display: flex;
    font-size: 14px;
}
/* Album songs and artist */
.album-info {
    width: 100%;
    height: 100%;
}
.album-songs {
    margin: auto;
    padding-top: 25px;
    width: 80%;
}
.album-artist {
    margin: auto;
    width: 80%;
}
.album-artist-container {
    display: flex;
    justify-content: left;
}
h3 {
    margin: 0;
    padding: 20px 0;
}

@media screen and (max-width: 565px) {
    .album-container {
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .thumbnail {
        width: 180px;
        height: 180px;
    }
    h1 {
        font-size: 50px;
    }
}
</style>