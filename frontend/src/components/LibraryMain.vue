<script setup>
const test = 'https://is1-ssl.mzstatic.com/image/thumb/Music126/v4/d9/1f/38/d91f3855-1536-3197-eb2f-95fab4038503/PCSP_05459_A.jpg/1200x1200bb.jpg';
</script>

<template>
<div class="tracklist-container">
    <div class="thumbnail" :style="{ backgroundImage: `url(${test})` }"></div>
    <div class="tracklist-content">
        <h5>Private List</h5>
        <h1>Shingeki</h1>
        <div class="tracklist-details">
            <div style="font-weight: bold;">josemijmrl14&nbsp;&nbsp;·&nbsp;&nbsp;</div>
            <div>1 like&nbsp;&nbsp;·&nbsp;&nbsp;</div>
            <div>83 tracks, 6h 9min</div>
        </div>
    </div>
</div>
</template>

<style scoped>
.tracklist-container {
    display: flex;
    align-items: end;
    box-sizing: border-box;
    padding: 0 25px 25px 25px;
    color: white;
    height: fit-content;
}

h1 {
    font-size: 80px;
    margin: 0;
    margin-bottom: 30px;
}

h5 {
    font-size: 15px;
    font-weight: 400;
    margin: 0;
}

.thumbnail {
    width: 230px;
    height: 230px;
    border-radius: 4px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
}

.tracklist-content {
    margin-left: 20px;
}

.tracklist-details {
    display: flex;
    font-size: 14px;
}
</style>