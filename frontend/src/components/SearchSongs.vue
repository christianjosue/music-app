<script setup>
import { inject, ref, watch } from 'vue';
const props = defineProps({
    showModal: {
        type: Boolean,
        default: false
    }
});
const searchedSongs = inject('searchedSongs');
const searchSong = inject('searchSong');
const currentTracklist = inject('currentTracklist');
const addSongToTracklist = inject('addSongToTracklist');
const songs = ref(null);
const songInput = ref(null);
const emptySongs = ref(true);
const noResults = ref(false);
var timeout = setTimeout(() => {}, 0);

// Handles the message to display depending on the results
const handleResultsValues = () => {
    if (songs.value.length > 0) {
        noResults.value = false;
        if (songInput.value != null && songInput.value.value != "") {
            emptySongs.value = false;
        } else if (songInput.value != null && songInput.value.value == "") {
            emptySongs.value = true;
            songs.value = [];
        }
    } else {
        emptySongs.value = true;
        if (songInput.value != null && songInput.value.value != "") {
            noResults.value = true;
        } else if (songInput.value != null && songInput.value.value == "") {
            noResults.value = false;
        }
    }
}
// Handles whether it's necessary to search a song or not
const handleSearchSong = () => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        if (songInput.value != null && songInput.value.value != "") {
            await searchSong(songInput.value.value);
        }
        handleResultsValues();
    }, 250);
}
// Clean up search fields
const cleanFields = () => {
    songs.value = null;
    noResults.value = false;
    emptySongs.value = true;
    if (songInput.value != null) {
        songInput.value.value = "";
    }
}
// Watch changes on opening and closing modal
watch(
    () => props.showModal,
    () => {
        if (!props.showModal) {
            cleanFields();
        }
    },
    { immediate: true }
);
// Watch changes on the searched songs
watch(
    () => searchedSongs,
    () => {
        // Make a deep copy of the searched songs
        let tracks = JSON.parse(JSON.stringify(searchedSongs.value));
        if (tracks.length > 0) {
            // Check for every single track if it's already in the current tracklist or not
            tracks.forEach(track => {
                track.trackInCurrentTracklist = false;
                if (track.tracklists != null && track.tracklists.length != 0) {
                    track.tracklists.forEach(tracklist => {
                        if (tracklist.idTracklist == currentTracklist.value.idTracklist) {
                            track.trackInCurrentTracklist = true;
                        }
                    });
                }
            });
        }
        songs.value = tracks;
    },
    { immediate: true, deep: true }
);
</script>

<template>
    <form>
        <div class="search-container">
            <font-awesome-icon :icon="['fas', 'magnifying-glass']" class="magnifying-glass-search" />
            <input
                @keyup="handleSearchSong()"
                ref="songInput"
                id="trackName"
                type="text"
                placeholder="Search for a song"
            >
        </div>
        <div class="songs">
            <h4 v-if="!emptySongs">Results for your search</h4>
            <div v-for="song in songs" :key="song.idTrack" class="song">
                <div class="song-thumbnail-container">
                    <div class="song-thumbnail" :style="{ backgroundImage: `url(${song.thumbnail})`}"></div>
                </div>
                <div class="song-data">
                    <div class="song-title">{{ song.title }}</div>
                    <div class="song-artist">{{ song.artists[0].name }}</div>
                </div>
                <div class="buttons-container">
                    <div class="play-btn"><font-awesome-icon :icon="['fas', 'play']" /></div>
                    <div class="add-song-btn" 
                        v-if="!song.trackInCurrentTracklist"
                        @click="addSongToTracklist(song.idTrack)"
                    >
                        <font-awesome-icon :icon="['fas', 'circle-plus']" />
                    </div>
                    <div class="remove-song-btn" v-if="song.trackInCurrentTracklist">
                        <font-awesome-icon :icon="['fas', 'circle-xmark']" />
                    </div>
                </div>
            </div>
            <div v-if="noResults">There is no songs for your search</div>
        </div>
    </form>
</template>

<style scoped>
button.primary {
	display: inline-block;
	font-size: 0.8rem;
	color: #fff !important;
	background: rgb(var(--vs-primary) / 100%);
	padding: 13px 25px;
	border-radius: 17px;
	transition: background-color 0.1s ease;
	box-sizing: border-box;
	transition: all 0.25s ease;
	border: 0;
	cursor: pointer;
	box-shadow: 0 10px 20px -10px rgb(var(--vs-primary) / 50%);
	&:hover {
		box-shadow: 0 20px 20px -10px rgb(var(--vs-primary) / 50%);
		transform: translateY(-5px);
	}
}
input[type=text] {
    border: 1px solid gray;
    outline: none;
    padding: 10px;
    display: block;
    width: 80%;
    border-radius: 5px;
    border-bottom: 2px solid #1db954;
}
label {
    display: block;
    margin-bottom: 10px;
    width: 100%;
}
.search-container {
    display: flex;
    align-items: center;
    margin-bottom: 7px;
}
.magnifying-glass-search {
    font-size: 18px;
    margin-right: 10px;
}
.song {
    width: 100%;
    height: 60px;
    border-radius: 10px;
    border: 1px solid #666;
    display: flex;
    align-items: center;
    background-color: #222;
    transition: all .3s ease;
    margin-bottom: 10px;
}
.song:hover {
    transform: scale(1.02);
    background-color: #333;
}
.song-thumbnail {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    background-size: cover;
    background-repeat: no-repeat;
    border-radius: 5px;
}
.song-thumbnail-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex: 2;
    box-sizing: border-box;
    /* border-right: 1px solid #888; */
    height: 100%;
}
.song-data {
    box-sizing: border-box;
    margin-left: 10px;
    flex: 6;
}
.song-artist {
    margin-top: 2px;
    font-size: 14px;
    color: #888;
}
.buttons-container {
    display: flex;
    justify-content: center;
    flex: 2;
    box-sizing: border-box;
}
.add-song-btn,
.remove-song-btn {
    margin-left: 15px;
}
.play-btn,
.add-song-btn,
.remove-song-btn {
    cursor: pointer;
    transition: all .3s ease;
    color: #888;
}
.play-btn:hover,
.add-song-btn:hover,
.remove-song-btn:hover {
    transform: scale(1.1);
    color: white;
}
</style>