<script setup>
const props = defineProps({
    artist: {
        type: Object,
        default: {}
    }
});
</script>

<template>
<div class="artist-view-container">
    {{ artist.name }}
</div>
</template>

<style scoped>
.artist-view-container {
    background-color: #111;
    border-radius: 10px;
    color: white;
    flex: 7;
    overflow-y: auto;
}
</style>