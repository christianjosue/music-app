<script setup>
import { inject, ref } from 'vue';
const isActive = inject('soundView');
const color = ref('#838383');
function changeColor() {
    color.value = color.value == '#1db954' ? '#fff' : '#1db954';
}
</script>

<template>
    <div :class="['svg-container', { show: isActive }]">
        <svg @click="$emit('activeSoundView'); changeColor();" @mouseenter="color = color == '#1db954' ? '#1db954' : '#fff';" @mouseleave="color = color == '#fff' ? '#838383' : '#1db954';" width="20" height="20" viewBox="0 0 668 668" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M634 167.333V567.333C634 604.153 604.153 634 567.333 634H167.333M234 217.333V317.333L334 267.333L234 217.333ZM500.667 434V100.667C500.667 63.8477 470.82 34 434 34H100.667C63.8477 34 34 63.8477 34 100.667V434C34 470.82 63.8477 500.667 100.667 500.667H434C470.82 500.667 500.667 470.82 500.667 434Z" :stroke="color" stroke-width="66.6667" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
</template>

<style scoped>
svg:hover {
    scale: 1.1;
    cursor: pointer;
}
.svg-container {
    position: relative;
    margin-top: 1px;
}
.svg-container::after {
    content: '';
    position: absolute;
    width: 5px;
    height: 5px;
    background: #1db954;
    border-radius: 50%;
    bottom: -6px;
    right: 55%;
    left: 45%;
    display: none;
}
.show::after {
    display: block;
}
</style>