<script setup>
import { inject } from 'vue';

const activeLyricsIcon = inject('activeLyricsIcon');
</script>

<template>
    <div class="lyricsButton">
        <font-awesome-icon :class="['lyricsIcon', { active: activeLyricsIcon }]" :icon="['fas', 'microphone']" />
    </div>
</template>

<style scoped>
.lyricsButton {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 5px;
    margin-right: 10px;
}

.lyricsIcon {
    color: #838383;
    height: 19px;
    transition: all ease .3s;
    cursor: pointer;
}

.lyricsIcon:hover {
    color: white;
    transform: scale(1.05);
}

.active {
    color: #1db954 !important;
}
</style>