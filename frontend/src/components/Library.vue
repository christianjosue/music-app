<script setup>
import { ref, onMounted } from 'vue';

const test = ref('');
const loading = ref(true);
onMounted(async () => {
  const response = await fetch('http://127.0.0.1:8000/api/test');
  const data = await response.json();
  test.value = data.test;
  loading.value = false;
});
</script>

<template>
<h1>Library</h1>
<div v-if="loading" class="kabobloader">
  <div class="bounce1"></div>
  <div class="bounce2"></div>
  <div class="bounce3"></div>
</div>
<h3 v-else>{{ test }}</h3>
</template>

<style scoped>
h1, h3 {
    color: white;
}

body {
  background:linear-gradient(90deg, #262626,#2E2F33);
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