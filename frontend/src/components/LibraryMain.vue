<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    tracklist: {
        type: Object,
        default: {}
    }
});
const privacy = computed(() => {
    return props.tracklist.privacy ? 'Public' : 'Private';
});
const numberFollowers = ref(0);
const numberTracks = ref(0);
const totalTime = ref('');
numberFollowers.value = props.tracklist.followers.length;
numberTracks.value = props.tracklist.tracks.length;
totalTime.value = getTotalTime();

// Gets total time of the tracklist in hours and minutes
function getTotalTime() {
    let totalHours = 0;
    let totalMinutes = 0;
    props.tracklist.tracks.forEach(track => {
        const [hours, minutes] = track.duration.split(':');
        totalHours += parseInt(hours);
        totalMinutes += parseInt(minutes); 
    });
    let hoursToAdd = Math.trunc(totalMinutes / 60);
    let realMinutes = totalMinutes % 60;
    totalHours += hoursToAdd;

    return `${totalHours}h ${realMinutes}min`;
}
</script>

<template>
<div class="tracklist-container">
    <div class="thumbnail" :style="{ backgroundImage: `url(${tracklist.thumbnail})` }"></div>
    <div class="tracklist-content">
        <h5>{{ privacy }} List</h5>
        <h1>{{ tracklist.name }}</h1>
        <div class="tracklist-details">
            <div style="font-weight: bold;">{{ tracklist.owners[0].name }}&nbsp;&nbsp;·&nbsp;&nbsp;</div>
            <div v-if="numberFollowers > 0">{{ numberFollowers }} likes&nbsp;&nbsp;·&nbsp;&nbsp;</div>
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

.tracklist-content {
    margin-left: 20px;
}

.tracklist-details {
    display: flex;
    font-size: 14px;
}
</style>