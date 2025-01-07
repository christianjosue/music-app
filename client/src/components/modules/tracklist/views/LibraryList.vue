<script setup>
import { inject, ref, computed, watch } from 'vue';
import ListTrack from './ListTrack.vue';
defineEmits(['playTrack', 'pauseTrack']);
const props = defineProps({
    tracklist: {
        type: Object,
        default: {}
    }
});
const openAddSongsModal = inject('openAddSongsModal');
const openDeleteTracklistDialog = inject('openDeleteTracklistDialog');
const openEditTracklistDialog = inject('openEditTracklistDialog');
const setCurrentTrack = inject('setCurrentTrack');
const setSort = inject('setSort');
const sortColumn = inject('sortColumn');
const sortDirection = inject('sortDirection');
const sortedTracks = inject('sortedTracks');
const displaySearchInput = ref(false);
const searchQuery = ref('');

// Filtered tracks based on search query
const filteredTracks = computed(() => {
    if (!searchQuery.value) return sortedTracks.value;
    const query = searchQuery.value.toLowerCase();
    return sortedTracks.value.filter(track =>
        track.title.toLowerCase().includes(query) ||
        track.artists[0].name.toLowerCase().includes(query) ||
        track.album.title.toLowerCase().includes(query)
    );
});
// Cleans up the input for search songs
watch(
    () => displaySearchInput.value,
    () => {
        if (!displaySearchInput.value && searchQuery.value != "") {
            searchQuery.value = "";
        }
    },
    { immediate: true }
);
// Cleans up the input for search songs and hide it
watch(
    () => props.tracklist,
    () => { 
        searchQuery.value = "";
        displaySearchInput.value = false;
    },
    { immediate: true }
);
</script>

<template>
<div class="list-container">
    <div class="list-options">
        <div @click="openAddSongsModal" class="btn-add-song">Add songs <font-awesome-icon :icon="['fas', 'music']" /></div>
        <div class="actions">
            <div class="search-container">
                <font-awesome-icon 
                    :icon="['fas', 'magnifying-glass']"
                    :class="['search-icon', { active: displaySearchInput }]"
                    @click="displaySearchInput = !displaySearchInput"
                />
                <input
                    type="text"
                    :class="['search-input', { 'search-input-active': displaySearchInput }]"
                    v-model="searchQuery"
                    placeholder="Search for song, artist or album"
                >
            </div>
            <font-awesome-icon @click="openEditTracklistDialog(tracklist)" :icon="['fas', 'pencil']" class="edit-icon" />
            <font-awesome-icon @click="openDeleteTracklistDialog(tracklist.idTracklist)" :icon="['fas', 'trash']" class="trash-icon"/>
        </div>
    </div>
    <div class="tracklist">
        <table>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;" @click="setSort('title')">
                    Title <font-awesome-icon v-if="sortColumn === 'title'" :icon="['fas', sortDirection > 0 ? 'arrow-up' : 'arrow-down']" />
                </th>
                <th class="album-column" @click="setSort('album')">
                    Album <font-awesome-icon v-if="sortColumn === 'album'" :icon="['fas', sortDirection > 0 ? 'arrow-up' : 'arrow-down']" />
                </th>
                <th class="date-column" @click="setSort('date')">
                    Added's <font-awesome-icon v-if="sortColumn === 'date'" :icon="['fas', sortDirection > 0 ? 'arrow-up' : 'arrow-down']" />
                </th>
                <th class="time-column" @click="setSort('time')">
                    <font-awesome-icon icon="fa-solid fa-clock" style="margin-right: 5px;" /> 
                    <font-awesome-icon v-if="sortColumn === 'time'" :icon="['fas', sortDirection > 0 ? 'arrow-up' : 'arrow-down']" />
                </th>
                <th style="width: 5%;"></th>
            </tr>
            <ListTrack 
                v-for="(track, index) in filteredTracks"
                :key="track.idTrack"
                :index="index+1"
                :track="track"
                :id-tracklist="tracklist.idTracklist"
                @play-track="setCurrentTrack(track.idTrack, tracklist.idTracklist, 1)"
                @pause-track="setCurrentTrack(0, tracklist.idTracklist, 1)"
            />
        </table>
    </div>
</div>
</template>

<style scoped>
::selection {
    background-color: transparent;
    color: inherit;
}
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
    align-items: center;
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
    cursor: pointer;
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
.edit-icon,
.search-icon {
    color: #838383;
    font-size: 20px;
    transition: all .3s ease;
    cursor: pointer;
}
.trash-icon:hover {
    color: red;
    transform: scale(1.05);
}
.edit-icon:hover,
.search-icon:hover {
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
.search-container {
    display: flex;
    align-items: center;
    position: relative;
}
.search-input {
    width: 0;
    opacity: 0;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 10px;
    outline: none;
    transition: all .3s ease;
    position: absolute;
    right: 30px;
    background-color: rgba(10, 10, 10, .1);
    color: white;
}
.search-input-active {
    width: 200px;
    opacity: 1;
    position: relative;
    right: 0;
    margin-left: 10px;
}
.search-icon.active {
    color: white !important;
}
.album-column {
    width: 30%;
}
.date-column {
    width: 15%;
}
.time-column {
    width: 10%;
}

@media screen and (max-width: 1000px) {
    .album-column {
        display: none;
    }
    .date-column {
        width: 30%;
    }
}

@media screen and (max-width: 800px) {
    .list-options {
        flex-direction: column;
        align-items: center;
    }
    .search-input {
        padding: 10px;
    }
    .actions {
        margin-top: 20px;
    }
}

@media screen and (max-width: 600px) {
    .date-column,
    .time-column {
        display: none;
    }
}
</style>