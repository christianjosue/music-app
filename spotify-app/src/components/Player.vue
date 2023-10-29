<script setup>
import { onMounted, ref } from 'vue';
import IconPlay from './icons/IconPlay.vue';
import IconPause from './icons/IconPause.vue';

const songs = ref([
    {
        title: 'Baby Nueva',
        artist: 'Bad Bunny',
        src: '/audio/baby-nueva.mp3'
    }
])
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
    let progress = this.$refs.progress;
    let maxduration = audio.duration;
    let position = x - progress.offsetLeft;
    let percentage = (100 * position) / progress.offsetWidth;
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
</script>

<template>
    <div @click="playMusic" class="play-btn">
        <IconPlay v-show="!isPlaying" />
        <IconPause v-show="isPlaying" />
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
</style>