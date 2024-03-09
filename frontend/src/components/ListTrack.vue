<script setup>
import { computed, ref, watch } from 'vue';
import ListTrackPlayer from './ListTrackPlayer.vue';

const props = defineProps({
    track: {
        type: Object,
        default: {}
    },
    isPlaying: {
        type: Boolean,
        default: false
    },
    index: {}
});
defineEmits(['playTrack', 'pauseTrack']);
const album = computed(() => {
    return !props.track.idAlbum ? props.track.title : 'Test';
});
const addedAt = ref('');
const onHover = ref(false);
const isPlayingNow = ref(false);

watch(
  () => props.isPlaying,
  () => {
    isPlayingNow.value = props.isPlaying;
  },
  { immediate: true }
);

addedAt.value = formatTime();
function formatTime() {
    const date = props.track.created_at.split('T')[0];
    var dateObject = new Date(date);

    var day = dateObject.getDate();
    var month = dateObject.getMonth() + 1; // Note: months in JavaScript are between 0 and 11
    var year = dateObject.getFullYear();

    var months = [
        "jan", "feb", "mar", "apr", "may", "jun",
        "jul", "aug", "sep", "oct", "nov", "dec"
    ];

    return `${day} ${months[month - 1]} ${year}`;
}
</script>

<template>
    <tr 
        class="track" 
        @mouseover="onHover = true" 
        @mouseleave="onHover = false"
    >
        <ListTrackPlayer
            :is-playing="isPlayingNow"
            :on-hover="onHover"
            :index="index"
            @play-track="$emit('playTrack')"
            @pause-track="$emit('pauseTrack')"
        />
        <td>
            <div class="title-section">
                <div class="thumbnail" :style="{ backgroundImage: `url(${track.thumbnail})` }"></div>
                <div class="title-content">
                    <p class="track-name">{{ track.title }}</p>
                    <p class="artist">
                        <div v-for="artist in track.artists">
                            {{ artist.name }}
                        </div>
                    </p>
                </div>
            </div>
        </td>
        <td class="album">{{ album }}</td>
        <td class="date">{{ addedAt }}</td>
        <td class="time">{{ track.duration }}</td>
    </tr>
</template>

<style scoped>
tr:first-child {
  border-bottom: 1px solid rgba(255, 255, 255, .2);
}
th {
    padding-bottom: 10px;
    color: #838383;
    font-weight: 400;
    text-align: left;
}
th:first-child,
td:first-child,
th:last-child,
td:last-child {
    text-align: center;
}
td {
    padding: 15px 0;
}
.title-section {
    display: flex;
    align-items: center;
}
.title-content {
    margin-left: 15px;
}
.title-section p {
    margin: 0;
}
.track-name {
    margin-bottom: 3px !important;
}
.artist,
.album,
.list-number,
.date,
.time {
    color: #838383;
    font-size: 14px;
}
.thumbnail {
    width: 50px;
    height: 50px;
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    border-radius: 5px;
}
.track:hover {
    background: rgba(255, 255, 255, .1);
}
</style>