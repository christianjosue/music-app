<script setup>
import { ref } from "vue";
</script>

<template>
  <div class="search-view-container">
    <input @keyup="search" type="text" />
  </div>
</template>

<style scoped>
h1,
p {
  color: white;
}
.search-view-container {
  flex: 7;
}
</style>
