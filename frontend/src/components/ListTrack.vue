<script setup>

</script>

<template>
    <tr class="track">
        <td class="list-number">1</td>
        <td>
            <div class="title-section">
                <div class="thumbnail" style="background-image: url('https://m.media-amazon.com/images/I/71elKeWZL-L._UF894,1000_QL80_.jpg');"></div>
                <div class="title-content">
                    <p class="track-name">Attack on Titan</p>
                    <p class="artist">Hiroyuki Sawano</p>
                </div>
            </div>
        </td>
        <td class="album">TV Anime "Attack On Titan" Original Soundtrack</td>
        <td class="date">17 nov 2023</td>
        <td class="time">4:22</td>
    </tr>
</template>

<style scoped>
tr:first-child {
  border-bottom: 1px solid rgba(255, 255, 255, .2);
}
th {
    padding-bottom: 10px;
    color: #838383;
    font-weight: 400;
    text-align: left;
}
th:first-child,
td:first-child,
th:last-child,
td:last-child {
    text-align: center;
}
td {
    padding: 15px 0;
}
.title-section {
    display: flex;
    align-items: center;
}
.title-content {
    margin-left: 15px;
}
.title-section p {
    margin: 0;
}
.track-name {
    margin-bottom: 3px !important;
}
.artist,
.album,
.list-number,
.date,
.time {
    color: #838383;
    font-size: 14px;
}
.thumbnail {
    width: 50px;
    height: 50px;
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    border-radius: 5px;
}
.track:hover {
    background: rgba(255, 255, 255, .1);
}
</style>