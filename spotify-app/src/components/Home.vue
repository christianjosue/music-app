<script setup>
import { ref } from "vue";
import HomeMenu from "./HomeMenu.vue";
import HomeMain from "./HomeMain.vue";
import SoundView from "./SoundView.vue";
defineProps({
  activeSoundView: {
    type: Boolean,
    default: false,
  },
});

const color = ref("#2e114f");

function changeGradientColor() {
  Vibrant.from("/img/maldives.jpg").getPalette((err, palette) => {
    color.value = `rgb(${palette.Vibrant._rgb[0]}, ${palette.Vibrant._rgb[1]}, ${palette.Vibrant._rgb[2]})`;
  });
}
</script>

<template>
  <div class="home-container">
    <div class="main-part">
      <HomeMenu :color="color" />
      <HomeMain @change-background="changeGradientColor" :color="color" />
    </div>
    <SoundView v-show="activeSoundView" />
  </div>
</template>

<style scoped>
.home-container {
  width: 100%;
  height: 100%;
  display: flex;
}
.main-part {
  height: 100%;
  flex: 3;
}
</style>
