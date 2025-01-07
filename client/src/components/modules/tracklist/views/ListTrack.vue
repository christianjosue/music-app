<script setup>
import { getCurrentInstance, inject, ref } from 'vue';
import IconMusicPlayingAnimation from '../../../icons/IconMusicPlayingAnimation.vue';
const props = defineProps({
    track: {
        type: Object,
        default: {}
    },
    idTracklist: {
        type: Number,
        default: 0
    },
    key: {
        type: Number
    },
    index: {}
});
defineEmits(['playTrack', 'pauseTrack']);
const { emit } = getCurrentInstance();
const removeSongFromTracklist = inject('removeSongFromTracklist');
const addedAt = ref('');
const onHover = ref(false);

addedAt.value = formatTime();

// Formats the date and time
function formatTime() {
    const tracklistTrack = props.track.tracklist_track.find((tracklistTrack) => tracklistTrack.idTracklist == props.idTracklist);
    const date = tracklistTrack.created_at.split('T')[0];
    var dateObject = new Date(date);

    var day = dateObject.getDate();
    // Months in JavaScript are between 0 and 11
    var month = dateObject.getMonth() + 1;
    var year = dateObject.getFullYear();

    var months = [
        "jan", "feb", "mar", "apr", "may", "jun",
        "jul", "aug", "sep", "oct", "nov", "dec"
    ];

    return `${day} ${months[month - 1]} ${year}`;
}
const idPlayingTrack = inject('idPlayingTrack');
const checkPlayingTracklist = inject('checkPlayingTracklist');
const isPlaying = inject('isPlaying');
const isMobileView = inject('isMobileView');

// Play the song displayed in the player when mobile view is active
const handlePlaySongMobile = () => {
    if (isMobileView.value) {
        emit('playTrack');
    }
}
</script>

<template>
    <tr 
        class="track" 
        @mouseover="onHover = true" 
        @mouseleave="onHover = false"
        @click="handlePlaySongMobile"
    >
        <td class="container-list">
            <font-awesome-icon v-if="onHover && idPlayingTrack == track.idTrack && checkPlayingTracklist(idTracklist) && isPlaying && !isMobileView"
                @click="$emit('pauseTrack')"
                icon="fa-solid fa-pause"
            />
            <font-awesome-icon v-else-if="onHover && (!isPlaying || idPlayingTrack != track.idTrack || (idPlayingTrack == track.idTrack && !checkPlayingTracklist(idTracklist))) && !isMobileView"
                @click="$emit('playTrack')"
                icon="fa-solid fa-play"
            />
            <IconMusicPlayingAnimation 
                v-else-if="!onHover && idPlayingTrack == track.idTrack && checkPlayingTracklist(idTracklist) && isPlaying"
                style="padding-left: 20px;"
            />
            <div class="list-number" v-else>{{ index }}</div>
        </td>
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
        <td class="album">{{ track.album.title }}</td>
        <td class="date">{{ addedAt }}</td>
        <td class="time">{{ track.duration }}</td>
        <td>
            <font-awesome-icon 
                :icon="['fas', 'trash']"
                class="remove"
                @click="removeSongFromTracklist(track.idTrack)"
            />
        </td>
    </tr>
</template>

<style scoped>
::selection {
    background-color: transparent;
    color: inherit;
}
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
.list-number {
  color: #838383;
  font-size: 14px;
}
.remove {
    color: #838383;
    cursor: pointer;
    transition: all .3s ease;
}
.remove:hover {
    color: red;
    transform: scale(1.05);
}

@media screen and (max-width: 1000px) {
    .album {
        display: none;
    }
}

@media screen and (max-width: 600px) {
    .date,
    .time {
        display: none;
    }
}
</style>