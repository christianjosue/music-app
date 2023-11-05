<script setup>
import { ref } from 'vue';

const token = localStorage.getItem('token');
const tracks = ref([]);
const tracksSearch = ref([]);
const idTracks = ref('');
async function fetchWebApi(endpoint, method, body) {
    const res = await fetch(`https://api.spotify.com/${endpoint}`, {
        headers: {
            Authorization: `Bearer ${token}`,
        },
        method,
        body: JSON.stringify(body)
    });
    return await res.json();
}

async function search() {
    tracksSearch.value = (await fetchWebApi('v1/search?q=babynueva&type=track', 'GET')).tracks.items;
    tracksSearch.value.forEach((track) => {
        idTracks.value += `${track.id},`;
    });
    idTracks.value.slice(0, -1);
    console.log(idTracks.value);
    tracks.value = await fetchWebApi(`v1/tracks?ids=${idTracks.value}`, 'GET');
    console.log(tracks.value);
}
</script>

<template>
    <input @keyup="search" type="text">
    <p v-for="track in tracksSearch">
        {{ track.name }}
    </p>
</template>

<style scoped>
    h1, p {
        color: white;
    }
</style>