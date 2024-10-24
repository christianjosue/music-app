<script setup>
import { inject, ref } from 'vue';
import ListTrack from './ListTrack.vue';
defineEmits(['playTrack', 'pauseTrack']);
const props = defineProps({
    tracklist: {
        type: Object,
        default: {}
    }
});
const setCurrentTrack = inject('setCurrentTrack');
const openDeleteTracklistDialog = inject('openDeleteTracklistDialog');
const openEditTracklistDialog = inject('openEditTracklistDialog');
const openAddSongsModal = inject('openAddSongsModal');
</script>

<template>
<div class="list-container">
    <div class="list-options">
        <div @click="openAddSongsModal" class="btn-add-song">Add songs <font-awesome-icon :icon="['fas', 'music']" /></div>
        <div class="actions">
            <font-awesome-icon @click="openEditTracklistDialog(tracklist)" :icon="['fas', 'pencil']" class="edit-icon" />
            <font-awesome-icon @click="openDeleteTracklistDialog(tracklist.id)" :icon="['fas', 'trash']" class="trash-icon"/>
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
                v-for="(track, index) in tracklist.tracks"
                :key="track.id"
                :index="index+1"
                :track="track"
                :id-tracklist="tracklist.id"
                @play-track="setCurrentTrack(track.id, tracklist.id, 1)"
                @pause-track="setCurrentTrack(0, tracklist.id, 1)"
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
}
.list-options {
    display: flex;
    justify-content: space-between;
}
.actions {
    display: flex;
    gap: 20px;
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
.trash-icon,
.edit-icon {
    color: #838383;
    font-size: 20px;
    transition: all .3s ease;
    cursor: pointer;
}
.trash-icon:hover {
    color: red;
    transform: scale(1.05);
}
.edit-icon:hover {
    color: white;
    transform: scale(1.05);
}
.btn-add-song {
    color: #838383;
    padding: 10px 20px;
    border-radius: 10px;
    border: 1px solid #838383;
    cursor: pointer;
    transition: all .3s ease;
}
.btn-add-song:hover {
    color: black;
    border: 1px solid white;
    background-color: white;
    transform: scale(1.05);
}
</style>