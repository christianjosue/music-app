<script setup>
// Just testing
</script>

<template>
  <div class="container">
    <div class="menu">
        <div class="menu-item active">
            <a href="#" class="active"><font-awesome-icon icon="fa-solid fa-house" class="icon" />Inicio</a>
        </div>
        <div class="menu-item">
            <a href="#"><font-awesome-icon icon="fa-solid fa-magnifying-glass" class="icon" />Buscar</a>
        </div>
    </div>
    <div class="main"></div>
  </div>
</template>

<style scoped>
    .container {
        display: flex;
        width: 100%;
        height: 100%;
        border-radius: 20px;
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

    .menu-item a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: rgb(190, 190, 190);
    }

    .menu-item a:hover {
        color: white;
        transition: all .3s ease;
    }

    .icon {
        margin-right: 18px;
        height: 22px;
    }

    .active {
        color: white !important;
    }
</style>
