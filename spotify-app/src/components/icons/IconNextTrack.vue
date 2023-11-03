<script setup>
import { ref } from 'vue';

const color = ref('rgb(225, 225, 225)');
function changeColor() {
    if (color.value == 'rgb(225, 225, 225)') {
        color.value = '#fff';
    } else {
        color.value = 'rgb(225, 225, 225)';
    }
}
</script>

<template>
    <svg @mouseover="changeColor" @mouseout="changeColor" viewBox="0 0 600 540" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M25.3337 429.623L25.3337 110.621C25.3337 45.2881 96.3337 4.29146 153 36.9581L291.334 116.625L429.668 196.625C486.335 229.291 486.335 310.958 429.668 343.623L291.334 423.623L153 503.289C96.3337 535.956 25.3337 495.29 25.3337 429.623Z" fill="white" stroke="white" stroke-width="50" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M574.667 64.2882L574.667 476.289" stroke="white" stroke-width="50" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</template>

<style scoped>
svg {
    width: 22px;
    margin-left: 20px;
}
</style>