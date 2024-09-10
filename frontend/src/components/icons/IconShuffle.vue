<script setup>
import { inject } from 'vue';

const randomMode = inject('randomMode');

</script>

<template>
    <font-awesome-icon :icon="['fas', 'shuffle']" :class="['shuffleIcon', { active: randomMode }]" />
</template>

<style scoped>
.shuffleIcon {
    color: white;
    height: 18px;
    margin-right: 25px;
    cursor: pointer;
    transition: all ease .3s;
}
.shuffleIcon:hover {
    transform: scale(1.05);
}
.active {
    color: #1db954;
}
</style>