<script setup>
defineProps({
    repeatMode: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <font-awesome-icon :icon="['fas', 'repeat']" :class="['icon-repeat', { active: repeatMode }]" />
</template>

<style scoped>
.icon-repeat {
    color: white;
    height: 16px;
    margin-left: 25px;
    cursor: pointer;
    transition: all .3s ease;
}
.icon-repeat:hover {
    transform: scale(1.05);
}
.active {
    color: #1db954;
}
</style>