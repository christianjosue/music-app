<script setup>
import MenuOptions from '../../header/MenuOptions.vue';
import LibraryMain from './LibraryMain.vue';
import LibraryList from './LibraryList.vue';
import IconPreloaderAnimation from '../../../icons/IconPreloaderAnimation.vue';
defineEmits(['playTrack', 'pauseTrack']);
const props = defineProps({
  tracklist: {
    type: Object,
    default: {}
  }
});
</script>

<template>
  <div class="library-container">
    <MenuOptions />
    <div v-if="Object.keys(tracklist).length > 0" class="container">
      <LibraryMain :tracklist="tracklist" />
      <LibraryList :tracklist="tracklist"/>
    </div>
    <IconPreloaderAnimation v-if="!Object.keys(tracklist).length" />
  </div>
</template>

<style scoped>
.library-container {
  flex: 7;
  overflow: hidden;
}

h1, h3 {
    color: white;
}

body {
  background:linear-gradient(90deg, #262626,#2E2F33);
}

.container {
  background: linear-gradient(to bottom, #2e114f 0%, #111 60%);
  border-radius: 0 0 10px 10px;
  height: calc(100% - 60px);
  overflow-y: auto;
  overflow-x: hidden;
}
</style>