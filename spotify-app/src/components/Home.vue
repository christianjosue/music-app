<script setup>
    import { ref } from 'vue';
    import HomeMenu from './HomeMenu.vue';
    import HomeMain from './HomeMain.vue';
    import Player from './Player.vue';

    const color = ref('#2e114f');

    function changeGradientColor () {
        Vibrant.from('/img/maldives.jpg').getPalette((err, palette) => {
            color.value = `rgb(${palette.Vibrant._rgb[0]}, ${palette.Vibrant._rgb[1]}, ${palette.Vibrant._rgb[2]})`;
        });
    }
</script>

<template>
    <HomeMenu :color="color" />
    <HomeMain @change-background="changeGradientColor" :color="color" />
</template>

<style scoped>

    .main {
        width: 100%;
        height: calc(100% - 60px);
        border-radius: 0 0 10px 10px;
        box-sizing: border-box;
        padding: 15px;
    }

    img {
        width: 570px;
        margin-top: 400px;
    }
</style>