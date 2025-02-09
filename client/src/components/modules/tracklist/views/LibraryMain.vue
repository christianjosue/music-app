<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    tracklist: {
        type: Object,
        default: {}
    }
});
const numberTracks = ref(0);
const totalTime = ref('');

// Gets total time of the tracklist in hours and minutes
function getTotalTime() {
    let totalSeconds = 0;
    let totalMinutes = 0;
    props.tracklist.tracks.forEach(track => {
        const [minutes, seconds] = track.duration.split(':');
        totalMinutes += parseInt(minutes);
        totalSeconds += parseInt(seconds);
    });
    let minutesToAdd = Math.trunc(totalSeconds / 60);
    let realSeconds = totalSeconds % 60;
    totalMinutes += minutesToAdd;

    return `${totalMinutes}min ${realSeconds}s`;
}
// Updates the number of tracks and the total time of the sum of it every time that the playlist changes
watch(
    () => props.tracklist,
    () => {
        numberTracks.value = props.tracklist.tracks.length;
        totalTime.value = getTotalTime();
    },
    { immediate: true }
)
</script>

<template>
<div class="tracklist-container">
    <div class="thumbnail" :style="{ backgroundImage: `url(${tracklist.thumbnail_image.src})` }"></div>
    <div class="tracklist-content">
        <h5>Playlist</h5>
        <h1>{{ tracklist.name }}</h1>
        <div class="tracklist-details">
            <div style="font-weight: bold;">Admin&nbsp;&nbsp;·&nbsp;&nbsp;</div>
            <div>{{ numberTracks }} tracks,&nbsp;&nbsp;{{ totalTime }}</div>
        </div>
    </div>
</div>
</template>

<style scoped>
.tracklist-container {
    display: flex;
    align-items: end;
    box-sizing: border-box;
    padding: 0 25px 25px 25px;
    color: white;
    height: fit-content;
}

h1 {
    font-size: 6vw;
    margin: 0;
    margin-bottom: 30px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis; 
}

h5 {
    font-size: 15px;
    font-weight: 400;
    margin: 0;
}

.thumbnail {
    width: 230px;
    height: 230px;
    min-width: 230px;
    border-radius: 4px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
}

.tracklist-content {
    margin-left: 20px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.tracklist-details {
    display: flex;
    font-size: 14px;
}

@media screen and (max-width: 800px) {
    .thumbnail {
        min-width: 180px;
        height: 180px;
        width: 180px;
    }
}

@media screen and (max-width: 600px) {
    .tracklist-container {
        flex-direction: column;
        align-items: center;
    }
    .tracklist-content {
        margin-top: 20px;
    }
}
</style>