<script setup>
import { onMounted, ref } from 'vue';
import IconPlay from './icons/IconPlay.vue';
import IconPause from './icons/IconPause.vue';
import IconNextTrack from './icons/IconNextTrack.vue';
import IconPrevTrack from './icons/IconPrevTrack.vue';

const tracks = ref([
    {
        title: 'Dejavu',
        artist: 'JC Reyes',
        src: '/audio/dejavu.mp3'
    },
    {
        title: 'Baby Nueva',
        artist: 'Bad Bunny',
        src: '/audio/baby-nueva.mp3'
    }
]);
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
var audio;

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
</script>

<template>
    <div class="player-buttons">
        <IconPrevTrack @click="prevTrack" />
        <div @click="playMusic" class="play-btn">
            <IconPlay v-show="!isPlaying" />
            <IconPause v-show="isPlaying" />
        </div>
        <IconNextTrack @click="nextTrack"/>
    </div>
    <div class="progress" ref="progress">
        <div class="progress-bar" @click="clickProgress">
            <div class="progress-current" :style="{ width: barWidth }"></div>
        </div>
        <div class="progress-time">{{ currentTime }}</div>
        <p>{{ currentTrack.title }}</p>
        <p>{{ currentTrack.artist }}</p>
    </div>
</template>

<style scoped>
    .play-btn {
        width: 40px;
        height: 40px;
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
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .progress-bar {  
        height: 6px;
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

    .progress-time {
        margin-top: 2px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        opacity: 0.7;
    }

    p {
        color: white;
    }
</style>