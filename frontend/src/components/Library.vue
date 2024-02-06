<script setup>
import { ref, onMounted } from 'vue';
import { API_URL } from '../../config';
import MenuOptions from './MenuOptions.vue';
import LibraryMain from './LibraryMain.vue';
import LibraryList from './LibraryList.vue';

const tracklist = ref({});
onMounted(async () => {
  const response = await fetch(`${API_URL}/api/tracklist/1`);
  tracklist.value = await response.json();
  console.log(tracklist.value);
});
</script>

<template>
<MenuOptions />
<div v-if="Object.keys(tracklist).length > 0" class="container">
  <LibraryMain :tracklist="tracklist" />
  <LibraryList :tracks="tracklist.tracks" />
</div>
<div v-if="!Object.keys(tracklist).length" class="kabobloader">
  <div class="bounce1"></div>
  <div class="bounce2"></div>
  <div class="bounce3"></div>
</div>
</template>

<style scoped>
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

.kabobloader {
  margin: auto;
  top:0;
  right:0;
  bottom:0;
  left:0;
  position:absolute;
  width: 70px;
  height:70px;

}

.kabobloader > div {
  width: 18px;
  height: 18px;
  background-color: #2EBD59;
  border-radius: 100%;
  display: inline-block;
  -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}

.kabobloader .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}

.kabobloader .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}

@-webkit-keyframes sk-bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0) }
  40% { -webkit-transform: scale(1.0) }
}

@keyframes sk-bouncedelay {
  0%, 80%, 100% { 
    -webkit-transform: scale(0);
    transform: scale(0);
  } 40% { 
    -webkit-transform: scale(1.0);
    transform: scale(1.0);
  }
}
</style>