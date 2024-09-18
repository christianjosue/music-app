<script setup>
const props = defineProps({
  isActive: {
    type: Boolean,
    default: false
  }
})
defineEmits(['activeSoundView']);
</script>

<template>
  <div :class="['svg-container', { show: isActive }]">
    <svg
      @click="$emit('activeSoundView')"
      width="20"
      height="20"
      viewBox="0 0 668 668"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M634 167.333V567.333C634 604.153 604.153 634 567.333 634H167.333M234 217.333V317.333L334 267.333L234 217.333ZM500.667 434V100.667C500.667 63.8477 470.82 34 434 34H100.667C63.8477 34 34 63.8477 34 100.667V434C34 470.82 63.8477 500.667 100.667 500.667H434C470.82 500.667 500.667 470.82 500.667 434Z"
        stroke="white"
        stroke-width="66.6667"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </svg>
  </div>
</template>

<style scoped>
.svg-container {
  transition: all .3s ease;
  cursor: pointer;
}
.svg-container:hover {
  transform: scale(1.05);
}
.svg-container.show path {
  stroke: #1db954;
}
</style>
