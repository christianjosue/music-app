<script setup>
import { inject, onBeforeUnmount, onMounted, ref, watch } from 'vue';
const props = defineProps({
    showModal: {
        type: Boolean,
        default: false
    }
});
const addSongToTracklist = inject('addSongToTracklist');
const currentTracklist = inject('currentTracklist');
const getAudioTrack = inject('getAudioTrack');
const removeSongFromTracklist = inject('removeSongFromTracklist');
const searchedSongs = inject('searchedSongs');
const searchSong = inject('searchSong');
const setCurrentTrack = inject('setCurrentTrack');
const songs = ref(null);
const songInput = ref(null);
const emptySongs = ref(true);
const noResults = ref(false);
const playSong = ref(false);
const progressCircle = ref(null);
const idPlayingTrack = ref(0);
const duration = 15;
const radius = 15;
const circumference = 2 * Math.PI * radius;
var audio = new Audio();
var timeout = setTimeout(() => {}, 0);

// Pauses the audio song
const pauseSong = () => {
    audio.pause();
    playSong.value = false;
}
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
        // Every time the input for searching songs changes, the audio song is paused
        pauseSong();
        audio.currentTime = 0;
        idPlayingTrack.value = 0;
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
// Handles whether the song has to be added or removed from the playlist
const handleTrack = (song) => {
    let success = !song.trackInCurrentTracklist ? addSongToTracklist(song.idTrack) : removeSongFromTracklist(song.idTrack);
    // If the operation goes well, the value of the variable that handles whether the track is in the tracklist
    // or not changes
    if (success) {
        song.trackInCurrentTracklist = !song.trackInCurrentTracklist;
    }
}
// Set circle progress
const setProgress = (percent) => {
    if (progressCircle.value) {
        if (progressCircle.value.length > 0) {
            if (!progressCircle.value[0].style.strokeDasharray) {
                progressCircle.value[0].style.strokeDasharray = circumference;
            }
            const offset = circumference - (percent / 100) * circumference;
            progressCircle.value[0].style.strokeDashoffset = offset;
        }
    }
}
// Handles the extract of the song that is going to be played or paused
const handleTrackPreview = async (song) => {
    if (idPlayingTrack.value == 0 || (idPlayingTrack.value > 0 && idPlayingTrack.value != song.idTrack)) {
        audio.src = await getAudioTrack(song.src);
        idPlayingTrack.value = song.idTrack;
    }

    if (audio.paused) {
        // Stop the main player in order to avoid having multiple songs playing at the same time
        setCurrentTrack(0, 0);
        // Plays the preview of the selected song
        playSong.value = true;
        audio.play();
    } else {
        // Pauses the preview of the selected song
        pauseSong();
    }
}
// Update the progress of the circle which indicates how many time of the playing song has past
const updateProgress = () => {
    const progress = (audio.currentTime / duration) * 100;
    setProgress(progress);

    if (audio.currentTime >= duration) {
        pauseSong();
        audio.currentTime = 0;
        setProgress(0);
    }
}
onMounted(() => {
    audio.addEventListener('timeupdate', updateProgress);
    setProgress(0);
});
onBeforeUnmount(() => {
    audio.removeEventListener('timeupdate', updateProgress);
});
// Watch changes on opening and closing modal
watch(
    () => props.showModal,
    () => {
        if (!props.showModal) {
            cleanFields();
            pauseSong();
            setProgress(0);
            idPlayingTrack.value = 0;
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
                    <div class="player-container">
                        <div 
                            class="play-pause-btn"
                            @click="handleTrackPreview(song)"
                        >
                            <font-awesome-icon :icon="['fas', playSong && idPlayingTrack == song.idTrack ? 'pause' : 'play']" />
                        </div>
                        <svg v-if="idPlayingTrack == song.idTrack" class="progress-ring" width="35" height="35">
                            <circle class="progress-ring-background" cx="17.5" cy="17.5" r="15" />
                            <circle ref="progressCircle" class="progress-ring-progress" cx="17.5" cy="17.5" r="15" />
                        </svg>
                    </div>
                    <div 
                        :class="['add-remove-btn', { 'in-tracklist': song.trackInCurrentTracklist }]"
                        @click="handleTrack(song)"
                    >
                        <div class="line horizontal-line"></div>
                        <div class="line vertical-line"></div>
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
    align-items: center;
    flex: 2;
    box-sizing: border-box;
    margin-right: 10px;
}
.player-container {
    position: relative;
    width: 35px;
    height: 35px;
}
.play-pause-btn {
    cursor: pointer;
    transition: all .3s ease;
    color: #888;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: none;
    border: none;
    z-index: 1;
}
.play-pause-btn:hover {
    color: white;
}
.progress-ring {
    position: absolute;
    top: 0;
    left: 0;
    transform: rotate(-90deg);
}
.progress-ring-background,
.progress-ring-progress {
    fill: transparent;
    stroke-width: 2;
}
.progress-ring-background {
    stroke: transparent;
}
.progress-ring-progress {
    stroke: #1db954;
    stroke-linecap: round;
    stroke-dasharray: 94.2;
    stroke-dashoffset: 94.2;
    transition: stroke-dashoffset 0.1s linear;
}
.add-remove-btn {
    display: inline-block;
    width: 20px;
    height: 20px;
    position: relative;
    cursor: pointer;
    transition: transform 0.3s ease;
    margin-left: 10px;
}
.add-remove-btn:hover {
    transform: scale(1.1);
}
.line {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 2px;
    background-color: white;
    transition: transform 0.3s ease;
}
.horizontal-line {
    transform: translate(-50%, -50%);
}
.vertical-line {
    transform: translate(-50%, -50%) rotate(90deg);
}
.add-remove-btn.in-tracklist .horizontal-line {
    transform: translate(-50%, -50%) rotate(45deg);
}
.add-remove-btn.in-tracklist .vertical-line {
    transform: translate(-50%, -50%) rotate(-45deg);
}
.add-remove-btn.in-tracklist .line {
    background-color: red;
}
</style>