<script setup>
    import { ref } from 'vue';

</script>

<template>
    <div class="menu">

    </div>
    <div class="main"></div>
</template>

<style scoped>
    h1 {
        color: white;
    }

    .menu {
        width: 100%;
        height: 60px;
        background: #2e114f;
        border-radius: 10px 10px 0 0;
    }

    .main {
        width: 100%;
        height: calc(100% - 60px);
        background: linear-gradient(to bottom, #2e114f 0%, #111 30%);
        border-radius: 0 0 10px 10px;
        box-sizing: border-box;
        padding: 15px;
    }
</style>