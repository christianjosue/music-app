<script setup>
import { onMounted, proxyRefs, ref } from 'vue';
import IconPlay from './icons/IconPlay.vue';
import IconPause from './icons/IconPause.vue';
import IconNextSong from './icons/IconNextSong.vue';

const songs = ref([
    {
        title: 'Baby Nueva',
        artist: 'Bad Bunny',
        src: '/audio/baby-nueva.mp3'
    }
])
const progress = ref(null);
const currentTrack = ref({});
const currentTime = ref(null);
const index = ref(0);
const barWidth = ref(null);
const duration = ref(null);
const circleLeft = ref(null);
const isPlaying = ref(false);
var audio = null;

onMounted(() => {
    audio = new Audio();
    progress.value.focus();
    currentTrack.value = songs.value[index.value];
    audio.src = currentTrack.value.src;
    audio.ontimeupdate = () => {
        generateTime();
    }
    audio.onloadedmetadata = () => {
        generateTime();
    }
    audio.onended = () => {
        generateTime();
    }
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
</script>

<template>
    <div class="player-buttons">
        <div @click="playMusic" class="play-btn">
            <IconPlay v-show="!isPlaying" />
            <IconPause v-show="isPlaying" />
        </div>
        <div class="next-song">
            <IconNextSong />
        </div>
    </div>
    <div class="progress" ref="progress">
        <div class="progress-bar" @click="clickProgress">
            <div class="progress-current" :style="{ width: barWidth }"></div>
        </div>
        <div class="progress-time">{{ currentTime }}</div>
    </div>
</template>

<style scoped>
    .play-btn {
        width: 40px;
        height: 40px;
        margin-top: 20px;
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
        display: flex;
        justify-content: center;
    }

    .next-song {
        width: 50px;
        height: 50px;
    }

    .next-song > * {
        width: 100%;
        height: 100%;
    }

    .progress-bar {  
        height: 6px;
        width: 100%;
        cursor: pointer;
        background-color: #d0d8e6;
        display: inline-block;
        border-radius: 10px;
    }

    .progress-current {
        height: inherit;
        width: 0%;
        background-color: #a3b3ce;
        border-radius: 10px;
    }

    .progress-time {
        margin-top: 2px;
        color: #71829e;
        font-weight: 700;
        font-size: 16px;
        opacity: 0.7;
    }
</style>