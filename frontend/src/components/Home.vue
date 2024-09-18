<script setup>
import { ref } from "vue";
import MenuOptions from "./MenuOptions.vue";
import HomeMain from "./HomeMain.vue";

const color = ref("#2e114f");

// Changes the gradient color of the background depending on the main color of the track's thumbnail
function changeGradientColor() {
  Vibrant.from("/img/maldives.jpg").getPalette((err, palette) => {
    color.value = `rgb(${palette.Vibrant._rgb[0]}, ${palette.Vibrant._rgb[1]}, ${palette.Vibrant._rgb[2]})`;
  });
}
</script>

<template>
  <div class="home-container">
    <div class="main-part">
      <MenuOptions :color="color" />
      <HomeMain @change-background="changeGradientColor" :color="color" />
    </div>
  </div>
</template>

<style scoped>
.home-container {
  flex: 7;
  height: 100%;
  display: flex;
}
.main-part {
  height: 100%;
  flex: 3;
}
</style>
