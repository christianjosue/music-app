<script setup>
import { computed, inject, ref, watch } from 'vue';
const props = defineProps({
    track: {
        type: Object,
        default: {}
    },
    key: {
        type: Number
    },
    index: {}
});
defineEmits(['playTrack', 'pauseTrack']);
const album = computed(() => {
    return !props.track.idAlbum ? props.track.title : 'Test';
});
const addedAt = ref('');
const onHover = ref(false);

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
const idPlayingTrack = inject('idPlayingTrack');
</script>

<template>
    <tr 
        class="track" 
        @mouseover="onHover = true" 
        @mouseleave="onHover = false"
    >
        <td class="container-list">
            <font-awesome-icon v-if="onHover && idPlayingTrack == track.id" 
                @click="$emit('pauseTrack')"
                icon="fa-solid fa-pause"
            />
            <font-awesome-icon v-else-if="onHover && idPlayingTrack != track.id" 
                @click="$emit('playTrack')"
                icon="fa-solid fa-play"
            />
            <div class="icon" v-else-if="!onHover && idPlayingTrack == track.id">
                <span />
                <span />
                <span />
            </div>
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
.list-number {
  color: #838383;
  font-size: 14px;
}
.icon {
  position: relative;
  display: flex;
  justify-content: space-between;
  width: 13px;
  height: 13px;
  padding-left: 20px;
}

span {
  width: 3px;
  height: 100%;
  background-color: #1db954;
  border-radius: 3px;
  transform-origin: bottom;
  animation: bounce 2.2s ease infinite alternate;
  content: '';
}

span {
  &:nth-of-type(2) {
      animation-delay: -2.2s; /* Start at the end of animation */
  }

  &:nth-of-type(3) {
      animation-delay: -3.7s; /* Start mid-way of return of animation */
  }
}

@keyframes bounce {
  10% {
    transform: scaleY(0.3); /* start by scaling to 30% */
  }

  30% {
    transform: scaleY(1); /* scale up to 100% */
  }

  60% {
    transform: scaleY(0.5); /* scale down to 50% */
  }

  80% {
    transform: scaleY(0.75); /* scale up to 75% */
  }

  100% {
    transform: scaleY(0.6); /* scale down to 60% */
  }
}
</style>