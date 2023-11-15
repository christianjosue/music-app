<script setup>
import { computed, onMounted, ref } from 'vue';
import IconPlay from './icons/IconPlay.vue';
import IconPause from './icons/IconPause.vue';
import IconNextTrack from './icons/IconNextTrack.vue';
import IconPrevTrack from './icons/IconPrevTrack.vue';
import IconFavourite from './icons/IconFavourite.vue';

const tracks = ref([
    {
        title: 'Dejavu',
        artist: 'JC Reyes',
        src: '/audio/dejavu.mp3',
        thumbnail: 'https://is1-ssl.mzstatic.com/image/thumb/Music126/v4/98/f2/e6/98f2e60a-d2ac-c20d-4ac2-e7d2f5811ed5/190296284182.jpg/600x600bf-60.jpg'
    },
    {
        title: 'Baby Nueva',
        artist: 'Bad Bunny',
        src: '/audio/baby-nueva.mp3',
        thumbnail: 'https://upload.wikimedia.org/wikipedia/en/7/74/Bad_Bunny_-_Nadie_Sabe_Lo_Que_Va_a_Pasar_Ma%C3%B1ana.png'
    }
]);
var audio;
const progress = ref(0);
const currentIndex = ref(0);
const currentTrack = ref({});
const currentTime = ref(0);
const index = ref(0);
const barWidth = ref(0);
const duration = ref(0);
const circleLeft = ref(0);
const isPlaying = ref(false);
const transition = ref('');
const volume = ref(0);
const colorVolume = ref('#fff');
const volumeIcon = computed(() => {
    switch (true) {
        case volume.value === 0:
            return 'xmark';
        case volume.value > 0 && volume.value <= 25:
            return 'off';
        case volume.value > 25 && volume.value <= 75:
            return 'low';
        case volume.value > 75:
            return 'high';
    }
});

onMounted(() => {
    audio = new Audio();
    progress.value.focus();
    currentTrack.value = tracks.value[index.value];
    audio.src = currentTrack.value.src;
    audio.ontimeupdate = () => {
        generateTime();
    }
    audio.onloadedmetadata = () => {
        generateTime();
    }
    audio.onended = () => {
        nextTrack();
        isPlaying.value = true;
    }
    volume.value = audio.volume * 100;
})

function playMusic() {
    if (audio.paused) {
        audio.play();
        isPlaying.value = true;
    } else {
        audio.pause();
        isPlaying.value = false;
    }
}

function generateTime() {
    let width = 100 / audio.duration * audio.currentTime;
    barWidth.value = `${width}%`;
    circleLeft.value = `${width}%`;
    let durmin = Math.floor(audio.duration / 60);
    let dursec = Math.floor(audio.duration - durmin * 60);
    let curmin = Math.floor(audio.currentTime / 60);
    let cursec = Math.floor(audio.currentTime - curmin * 60);
    if (durmin < 10) {
        durmin = "0" + durmin;
    }
    if (dursec < 10) {
        dursec = "0" + dursec;
    }
    if (curmin < 10) {
        curmin = "0" + curmin;
    }
    if (cursec < 10) {
        cursec = "0" + cursec;
    }
    duration.value = `${durmin}:${dursec}`;
    currentTime.value = `${curmin}:${cursec}`;
}

function updateProgressBar(x) {
    let maxduration = audio.duration;
    let position = x - progress.value.offsetLeft;
    let percentage = (100 * position) / progress.value.offsetWidth;
    if (percentage > 100) {
    percentage = 100;
    }
    if (percentage < 0) {
    percentage = 0;
    }
    barWidth.value = `${percentage}%`;
    circleLeft.value = `${percentage}%`;
    audio.currentTime = (maxduration * percentage) / 100;
    audio.play();
}

function clickProgress(e) {
    isPlaying.value = true;
    audio.pause();
    updateProgressBar(e.pageX);
}

function prevTrack() {
    transition.value = 'scale-in';
    if (currentIndex.value > 0) {
        currentIndex.value--;
    } else {
        currentIndex.value = tracks.value.length - 1;
    }
    currentTrack.value = tracks.value[currentIndex.value];
    resetPlayer();
}

function nextTrack() {
    transition.value = 'scale-out';
    if (currentIndex.value < tracks.value.length - 1) {
        currentIndex.value++;
    } else {
        currentIndex.value = 0;
    }
    currentTrack.value = tracks.value[currentIndex.value];
    resetPlayer();
}

function resetPlayer() {
    barWidth.value = 0;
    circleLeft.value = 0;
    audio.currentTime = 0;
    audio.src = currentTrack.value.src;
    setTimeout(() => {
        if (isPlaying.value) {
            audio.play();
        } else {
            audio.pause();
        }
    }, 300);
}

function changeVolume(e) {
    const range = e.target.value;
    volume.value = range / e.target.max * 100;
    audio.volume = volume.value / 100;
}

function changeColor() {
    colorVolume.value = (colorVolume.value == '#fff') ? '#1db954' : '#fff';
}
</script>

<template>
    <div class="container">
        <div class="track-container">
            <div class="track-thumbnail" :style="{ backgroundImage: `url(${currentTrack.thumbnail})` }"></div>
            <div class="track-data">
                <div class="track-title">{{ currentTrack.title }}</div>
                <div class="track-artist">{{ currentTrack.artist }}</div>
            </div>
            <div class="track-favourite">
                <IconFavourite />
            </div>
        </div>
        <div class="player">
            <div class="player-buttons">
                <IconPrevTrack @click="prevTrack" />
                <div @click="playMusic" class="play-btn">
                    <IconPlay v-show="!isPlaying" />
                    <IconPause v-show="isPlaying" />
                </div>
                <IconNextTrack @click="nextTrack"/>
            </div>
            <div class="progress" ref="progress">
                <div class="progress-time current-time">{{ currentTime }}</div>
                <div class="progress-bar" @click="clickProgress">
                    <div class="progress-current" :style="{ width: barWidth }"></div>
                </div>
                <div class="progress-time total-time">{{ duration }}</div>
            </div>
        </div>
        <div class="player-options">
            <font-awesome-icon :icon="['fas', `volume-${volumeIcon}`]" style="color: #ffffff;" />
            <div class="range">
                <input @input="changeVolume" @mouseover="changeColor" @mouseleave="changeColor" 
                    :style="{ background: `linear-gradient(to right, ${colorVolume} ${volume}%, #838383 ${volume}%)` }"
                    type="range" min="0" max="100" :value="volume" id="range2" class="range-input" />
            </div>
        </div>
    </div>
</template>

<style scoped>
    .container {
        display: flex;
        justify-content: space-between;
        height: 100%;
    }
    .player {
        flex: 4;
    }
    .player-options {
        flex: 3;
        display: flex;
        justify-content: right;
        align-items: center;
        margin-top: 15px;
        margin-right: 10px;
    }
    .play-btn {
        width: 35px;
        height: 35px;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }

    .play-btn:hover {
        transform: scale(1.05);
    }

    .player-buttons {
        width: 100%;
        margin-top: 20px;
        margin-bottom: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .progress {
        display: flex;
        align-items: center;
    }

    .progress-bar {  
        height: 4px;
        width: 100%;
        cursor: pointer;
        background-color: #838383;
        display: inline-block;
        border-radius: 10px;
    }

    .progress-current {
        height: inherit;
        width: 0%;
        background-color: white;
        border-radius: 10px;
    }
    .progress-bar:hover > .progress-current {
        background-color: #1db954;
    }

    .progress-time {
        margin-top: 2px;
        color: white;
        font-weight: 400;
        font-size: 12px;
        opacity: 0.7;
    }
    
    .current-time {
        margin-right: 10px;
    }

    .total-time {
        margin-left: 10px;
    }

    .track-container {
        flex: 3;
        display: flex;
        align-items: center;
        margin-top: 10px;
        margin-left: 5px;
    }

    .track-data {
        margin-right: 20px;
        margin-left: 20px;
    }

    .track-title {
        color: white;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .track-artist {
        color: #838383;
        font-size: 12px;
    }

    .track-thumbnail {
        width: 70px;
        height: 70px;
        background-size: cover;
        background-repeat: no-repeat;
        border-radius: 5px;
    }

    .range-input {
        -webkit-appearance: none;
        appearance: none; 
        width: 100px;
        cursor: pointer;
        outline: none;
        border-radius: 15px;
        height: 4px;
        background: #ccc;
        cursor: auto;
    }

    .range-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none; 
        display: none;
        height: 13px;
        width: 13px;
        background-color: #fff;
        border-radius: 50%;
        border: none;
        transition: .2s ease-in-out;
    }

    .range-input:hover::-webkit-slider-thumb {
        display: block;
    }

    .range-input::-moz-range-thumb {
        height: 15px;
        width: 15px;
        background-color: #f50;
        border-radius: 50%;
        border: none;
        transition: .2s ease-in-out;
    }

    .value2, .value3, .value4 {
        font-size: 26px;    
        width: 50px;
        text-align: center;
    }
    .range {
        display: flex;
        align-items: center;
        margin-left: 10px;
    }
</style>