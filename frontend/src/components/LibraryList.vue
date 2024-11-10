<script setup>
import { inject, ref, computed } from 'vue';
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
const displaySearchInput = ref(false);
const searchQuery = ref('');

// Columnn it will be order by
const sortColumn = ref('date');
// 1 for ascendant, -1 for descendant
const sortDirection = ref(-1);

// Sorts the songs
const sortedTracks = computed(() => {
    return [...props.tracklist.tracks].sort((a, b) => {
        if (sortColumn.value === 'title') {
            return a.title.localeCompare(b.title) * sortDirection.value;
        } else if (sortColumn.value === 'album') {
            return a.album.title.localeCompare(b.album.title) * sortDirection.value;
        } else if (sortColumn.value === 'date') {
            let tracklistTrackA = a.tracklist_track.find(tracklist_track => tracklist_track.idTracklist == props.tracklist.idTracklist);
            let tracklistTrackB = b.tracklist_track.find(tracklist_track => tracklist_track.idTracklist == props.tracklist.idTracklist);
            let dateA = tracklistTrackA.created_at;
            let dateB = tracklistTrackB.created_at;
            return (new Date(dateA) - new Date(dateB)) * sortDirection.value;
        } else if (sortColumn.value === 'time') {
            let valueA = convertDurationToSeconds(a.duration);
            let valueB = convertDurationToSeconds(b.duration);
            return (valueA - valueB) * sortDirection.value;
        }
        return 0;
    });
});

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

// Changes the direction and order of a column
const setSort = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value *= -1;
    } else {
        sortColumn.value = column;
        sortDirection.value = 1;
    }
}

// Transform duration from 'm:ss' format to seconds
const convertDurationToSeconds = (duration) => {
    const [minutes, seconds] = duration.split(':').map(Number);
    return minutes * 60 + seconds;
}
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
                <th style="width: 30%;" @click="setSort('album')">
                    Álbum <font-awesome-icon v-if="sortColumn === 'album'" :icon="['fas', sortDirection > 0 ? 'arrow-up' : 'arrow-down']" />
                </th>
                <th style="width: 15%;" @click="setSort('date')">
                    Added's <font-awesome-icon v-if="sortColumn === 'date'" :icon="['fas', sortDirection > 0 ? 'arrow-up' : 'arrow-down']" />
                </th>
                <th style="width: 10%;" @click="setSort('time')">
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
</style>