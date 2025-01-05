<script setup>
import { LIBRARY_VIEW } from '../../../../../config';
import LibraryMenu from './LibraryMenu.vue';

const props = defineProps({
    tracklists: {
        type: Array,
        default: []
    },
    setCurrentView: {
        type: Function,
        default: {}
    },
    setIdTracklist: {
        type: Function,
        default: {}
    },
    checkSelectedTracklist: {
        type: Function,
        default: {}
    },
    openCreatePlaylistModal: {
        type: Function,
        default: {}
    }
});
</script>

<template>
<div class="library-list-view-container">
    <h3>Your library</h3>
    <button @click="openCreatePlaylistModal">+ New playlist</button>
    <LibraryMenu 
        v-for="tracklist in tracklists"
        :key="tracklist.idTracklist"
        :tracklist="tracklist"
        :is-active="checkSelectedTracklist(tracklist.idTracklist)"
        @click="
            setCurrentView(LIBRARY_VIEW);
            setIdTracklist(tracklist.idTracklist);
        "
    />
</div>
</template>

<style scoped>
.library-list-view-container {
    background-color: #111;
    border-radius: 10px;
    color: white;
    flex: 7;
    overflow-y: auto;
    box-sizing: border-box;
    padding: 15px;
}
button {
    border: 1px solid #555;
    border-radius: 10px;
    width: 100%;
    background: transparent;
    color: white;
    padding: 10px;
    margin-bottom: 10px;
}
</style>