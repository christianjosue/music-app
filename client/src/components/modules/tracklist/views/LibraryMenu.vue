<script setup>

const props = defineProps({
    tracklist: {
        type: Object,
        default: {}
    },
    isActive: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
<div :class="['menu-item', { active: isActive }]">
    <img :src="tracklist.thumbnail_image.src" alt="thumbnail">
    <div class="menu-content">
        <h6>{{ tracklist.name }}</h6>
        <p>List · Admin</p>
    </div>
</div>
</template>

<style scoped>
.menu-item {
  font-size: 17px;
  font-weight: 400;
  box-sizing: border-box;
  padding: 10px !important;
  border-radius: 5px;
  display: flex;
  align-items: center;
  cursor: pointer;
}

.menu-item:hover {
    background: rgba(255, 255, 255, .1);
    transition: all .3s ease;
}

.menu-item h6 {
    color: white;
    font-size: 14px;
    font-weight: 400;
    margin-bottom: 3px;
}

.menu-item p {
    color: gray;
    font-size: 14px;
}

.menu-content {
    margin-left: 15px;
}

img {
    width: 50px;
    border-radius: 5px;
}

h6, p {
    margin: 0;
    padding: 0;
}

.active {
    background: rgba(255, 255, 255, .1);
}
</style>