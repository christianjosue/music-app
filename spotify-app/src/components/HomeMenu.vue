<script setup>
defineProps({
    color: {
        type: String,
        default() {
            return '#2e114f';
        }
    }
});
</script>

<template>
    <div class="menu" :style="{ background: color }">

    </div>
</template>

<style scoped>
    .menu {
        width: 100%;
        height: 60px;
        border-radius: 10px 10px 0 0;
        /* transition: background 1s ease; */
    }
</style>

