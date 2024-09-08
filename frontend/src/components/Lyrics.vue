<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';

const props = defineProps({
    lyrics: {
        type: Object,
        default: {}
    },
    currentLine: {
        type: String,
        default: ""
    }
});
const lyricsContainer = ref(null);

// Scrolls to the is actually playing
function scrollToActiveLine() {
  nextTick(() => {
    const container = lyricsContainer.value;
    const activeLine = container.querySelector(".active");

    if (activeLine) {
      const containerHeight = container.clientHeight;
      const lineHeight = activeLine.clientHeight;
      const scrollPosition = activeLine.offsetTop - containerHeight / 2 + lineHeight / 2;

      container.scrollTo({
        top: scrollPosition,
        behavior: "smooth"
      });
    }
  });
}

watch(
  () => props.currentLine,
  () => {
    scrollToActiveLine();
  }
);

onMounted(() => {
  // Set dynamic padding to adjust the centre
  const container = lyricsContainer.value;
  const containerHeight = container.clientHeight;
  
  // Adding dynamic padding up and down the container
  container.style.paddingTop = `${containerHeight / 2}px`;
  container.style.paddingBottom = `${containerHeight / 2}px`;

  scrollToActiveLine();
});

</script>

<template>
    <div class="main-container">
        <div class="lyrics-container" ref="lyricsContainer">
            <div v-for="(line, time) in lyrics" :key="time">
                <div :class="{ active: currentLine == time}" class="lyrics-line">
                    {{ line }}
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.main-container {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    background-color: #111;
    display: flex;
    justify-content: center;

    mask-image: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 1) 25%, rgba(255, 255, 255, 1) 75%, transparent);
    -webkit-mask-image: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 1) 25%, rgba(255, 255, 255, 1) 75%, transparent);
}

.lyrics-container {
  width: 65%;
  height: 100%;
  color: rgb(205, 205, 205);
  font-size: 30px;
  overflow-y: scroll;
  overflow-x: hidden;
  position: relative;
  box-sizing: border-box;
  padding-left: 80px;
  padding-right: 80px;
}

.lyrics-container::-webkit-scrollbar {
  display: none;
}

.lyrics-line {
  padding: 10px 0;
  text-align: center;
  transition: filter 0.5s, color 0.5s;
  filter: blur(2px);
}

.active {
  color: white !important;
  font-weight: bold;
  filter: blur(0);
  transform: scale(1.1);
}
</style>