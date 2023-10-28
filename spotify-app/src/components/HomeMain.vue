<script setup>
defineProps({
    color: {
        type: String,
        default() {
            return 'linear-gradient(to bottom, #2e114f 0%, #111 30%)';
        }
    }
})
</script>

<template>
    <div class="main" :style="{ background: `linear-gradient(to bottom, ${color} 0%, #111 30%)` }">
        <img @mouseover="$emit('changeBackground')" src="/img/maldives.jpg" alt="maldives">
    </div>
</template>

<style scoped>
    .main {
        width: 100%;
        height: calc(100% - 60px);
        border-radius: 0 0 10px 10px;
        box-sizing: border-box;
        padding: 15px;
        transition: background 2s ease;
    }

    img {
        width: 570px;
        margin-top: 400px;
    }
</style>