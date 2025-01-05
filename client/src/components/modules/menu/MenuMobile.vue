<script setup>
import { HOME_VIEW, SEARCH_VIEW, LIBRARY_LIST_VIEW } from '../../../../config';

const props = defineProps({
    currentView: {
        type: Number,
        default: false
    },
    setCurrentView: {
        type: Function,
        default: {}
    }
});
</script>

<template>
<div class="menu-mobile">
    <div class="menu-item-mobile" @click="setCurrentView(HOME_VIEW)">
        <div :class="['menu-item-content-mobile', { active: currentView == HOME_VIEW }]">
            <font-awesome-icon icon="fa-solid fa-house" class="icon" />
        </div>
    </div>
    <div class="menu-item-mobile" @click="setCurrentView(SEARCH_VIEW)">
        <div :class="['menu-item-content-mobile', { active: currentView == SEARCH_VIEW }]">
            <font-awesome-icon icon="fa-solid fa-magnifying-glass" class="icon" />
        </div>
    </div>
    <div class="menu-item-mobile" @click="setCurrentView(LIBRARY_LIST_VIEW)">
        <div :class="['menu-item-content-mobile', { active: currentView == LIBRARY_LIST_VIEW }]">
            <font-awesome-icon icon="fa-solid fa-headphones" class="icon" />
        </div>
    </div>
</div>
</template>

<style scoped>
.menu-mobile {
  display: flex;
  padding: 15px;
  margin-top: 20px;
  background: #111;
  justify-content: space-around;
  border-radius: 10px;
}
.active {
  color: white !important;
}
.menu-item-mobile {
  font-size: 17px;
  font-weight: 400;
  box-sizing: border-box;
}
.menu-item-content-mobile {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: rgb(175, 175, 175);
  cursor: pointer;
  transition: all 0.3s ease;
}
.menu-item-content-mobile:hover {
  color: white;
}
.icon {
  height: 22px;
}
</style>