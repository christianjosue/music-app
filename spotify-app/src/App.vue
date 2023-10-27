<script setup>
// Just testing
import { ref } from 'vue';
import Home from './components/Home.vue';
import Search from './components/Search.vue';

const currentTab = ref('Home');
const tabs = {
    Home,
    Search
};
const icons = {
    Home: 'fa-house',
    Search: 'fa-magnifying-glass'
};
const changeTab = (tab) => {
    currentTab.value = tab;
};
</script>

<template>
  <div class="container">
    <div class="menu">
        <div
            v-for="(component, name) in tabs"
            :key="name"
            class="menu-item"
            @click="changeTab(name)"
        >
            <div :class="['menu-item-content', { active: currentTab === name }]">
                <font-awesome-icon :icon="['fa-solid ', icons[name]]" class="icon" />{{ name }}
            </div>
        </div>
    </div>
    <div class="main">
        <component :is="tabs[currentTab]" />
    </div>
  </div>
</template>

<style scoped>
    .container {
        display: flex;
        width: 100%;
        height: 100%;
    }

    .menu {
        width: 22%;
        background-color: #111;
        border-radius: 10px;
        margin-right: 10px;
        box-sizing: border-box;
        padding: 25px;
    }

    .main {
        width: 78%;
        background-color: #111;
        border-radius: 10px;
    }

    .menu-item {
        font-size: 17px;
        font-weight: 400;
        box-sizing: border-box;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
    }

    .menu-item-content {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: rgb(175, 175, 175);
    }

    .menu-item-content:hover {
        color: white;
        transition: all .3s ease;
        cursor: pointer;
    }

    .icon {
        margin-right: 18px;
        height: 22px;
    }

    .active {
        color: white !important;
    }
</style>
