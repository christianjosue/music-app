<script setup>
import { ref } from 'vue';
import ListTrack from './ListTrack.vue';

const props = defineProps({
    tracks: {
        type: Array,
        default: []
    }
});

const idPlayingTrack = ref(0);

function setPlayingTrack(id) {
    idPlayingTrack.value = id;
}

function checkPlayingTrack(id) {
    return idPlayingTrack.value == id;
}
</script>

<template>
<div class="list-container">
    <div class="list-options">
        <div class="options">
            <font-awesome-icon icon="fa-solid fa-circle-play" class="play" />
            <font-awesome-icon icon="fa-solid fa-shuffle" class="random hover-effect" />
            <font-awesome-icon icon="fa-solid fa-plus" class="add hover-effect" />
            <font-awesome-icon icon="fa-solid fa-circle-down" class="download hover-effect" />
            <font-awesome-icon icon="fa-solid fa-ellipsis" class="more-options hover-effect" />
        </div>
        <div class="options">
            <font-awesome-icon icon="fa-solid fa-magnifying-glass" class="search hover-effect" />
            <div class="order">
                <h6>Custom order</h6>
                <font-awesome-icon icon="fa-solid fa-bars" class="bars" />
            </div>
        </div>
    </div>
    <div class="tracklist">
        <table>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 40%;">Title</th>
                <th style="width: 30%;">Álbum</th>
                <th style="width: 15%;">Added's</th>
                <th style="width: 10%;"><font-awesome-icon icon="fa-solid fa-clock" /></th>
            </tr>
            <ListTrack 
                v-for="(track, index) in tracks"
                :key="track.id"
                :index="index+1"
                :track="track"
                @play-track="setPlayingTrack(track.id)"
                @pause-track="setPlayingTrack(0)"
                :is-playing="checkPlayingTrack(track.id)"
            />
        </table>
    </div>
</div>
</template>

<style scoped>
h6 {
    font-size: 15px;
    font-weight: 500;
    margin: 0 10px 0 10px;
}
.list-container {
    box-sizing: border-box;
    padding: 25px;
    width: 100%;
    height: 500px;
    background: linear-gradient(to bottom, rgba(0, 0, 0, .3) 0%, transparent 20%);
}
.list-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.options {
    display: flex;
    align-items: center;
    color: #838383;
}
.play {
    color: #1db954;
    background: black;
    border-radius: 50%;
    font-size: 60px;
    cursor: pointer;
}
.play:hover {
    transform: scale(1.05);
}
.random {
    color: #838383;
    font-size: 35px;
    margin: 0 30px 0 30px;
}
.hover-effect:hover {
    color: white;
    transform: scale(1.05);
    cursor: pointer;
}
.add {
    font-size: 35px;
}
.download {
    font-size: 35px;
    margin: 0 30px 0 30px;
}
.more-options {
    font-size: 35px;
}
.search {
    font-size: 20px;
    padding: 7px;
    margin: 0 18px 0 18px;
    transition: background .3s ease;
    border-radius: 50%;
}
.search:hover {
    background: rgba(255, 255, 255, .1);
}
.bars {
    font-size: 20px;
}
.order {
    display: flex;
    align-items: center;
}
.order:hover {
    color: white;
    cursor: pointer;
}
table {
  border-collapse: collapse;
  width: 100%;
  color: white;
  margin-top: 40px;
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
</style>