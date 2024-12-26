<script setup>
defineProps({
handleBreadcrumbAction: {
    type: Function,
    default: {}
}});
</script>

<template>
<div class="breadcrumbs">
    <div class="breadcrumb-box" @click="handleBreadcrumbAction(1)">
        <font-awesome-icon :icon="['fas', 'angle-left']" />
    </div>
    <div class="breadcrumb-box" @click="handleBreadcrumbAction(2)">
        <font-awesome-icon :icon="['fas', 'angle-right']" />
    </div>
</div>
</template>

<style scoped>
.breadcrumbs {
  margin-top: auto;
  color: #888;
  font-size: 20px;
  display: flex;
}
.breadcrumb-box {
  padding: 10px;
  background: #222;
  transition: all .3s ease;
  cursor: pointer;
}
.breadcrumb-box:hover {
  background: #333;
  color: white;
}
</style>