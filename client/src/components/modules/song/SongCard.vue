<script setup>
import { inject, ref, watch } from 'vue';
import IconMusicPlayingAnimation from '../../icons/IconMusicPlayingAnimation.vue';

const props = defineProps({
  song: {
    type: Object,
    default: {}
  },
  handlePlayTrack: {
    type: Function,
    default: {}
  },
  idTracklist: {
    type: Number,
    default: 0
  }
});

const addSongToTracklist = inject('addSongToTracklist');
const checkPlayingTracklist = inject('checkPlayingTracklist');
const currentActionsSongId = inject('currentActionsSongId');
const handleAlbumView = inject('handleAlbumView');
const handleArtistView = inject('handleArtistView');
const idPlayingTrack = inject('idPlayingTrack');
const openCreatePlaylistModal = inject('openCreatePlaylistModal');
const isMobileView = inject('isMobileView');
const playlists = ref({});
const playlistsObtained = ref(false);
const playTrack = inject('isPlaying');
const showActionsContent = ref(false);
const showPlaylistsContainer = ref(false);
const tracklists = inject('tracklists');
const updateCurrentActionsSongId = inject('updateCurrentActionsSongId');

// Handles actions to execute when put the mouse hover the add playlist button
const handleAddToPlaylistHover = () => {
  if (!playlistsObtained.value) {
    playlists.value = tracklists.value.filter(
      (tracklist) => !tracklist.tracks.some((track) => track.idTrack === props.song.idTrack)
    );
    playlistsObtained.value = true;
  }
  showPlaylistsContainer.value = true;
}
// Handle actions content
const handleActionsContent = () => {
  showActionsContent.value = !showActionsContent.value;
  // If actions content is going to be showed, we update the current actions song id
  if (showActionsContent.value) {
    updateCurrentActionsSongId(props.song.idTrack);
  }
}
// Handle song playback when we are in mobile view
const handlePlayTrackMobile = (idTrack) => {
  if (isMobileView) {
    props.handlePlayTrack(idTrack);
  }
}

// Every time tracklists change, we have to reload the playlists available to add the current song
watch(
  () => tracklists.value,
  () => {
    playlistsObtained.value = false;
  },
  { immediate: true }
);
// Every time one song action display, hide all the others songs actions modals
watch(
  () => currentActionsSongId.value,
  () => {
    if (currentActionsSongId.value != props.song.idTrack) {
      showActionsContent.value = false;
    }
  },
  { immediate: true }
);
</script>

<template>
    <div @click="handlePlayTrackMobile(song.idTrack)" class="song-card">
        <div class="song-thumbnail">
            <img :src="song.thumbnail" alt="thumbnail">
        </div>
        <div class="song-info">
            <div class="song-title">{{ song.title }}</div>
            <div class="song-artist">{{ song.artists[0]?.name }}</div>
          </div>
        <IconMusicPlayingAnimation  v-if="playTrack && idPlayingTrack == song.idTrack && checkPlayingTracklist(idTracklist)" />
        <div 
          v-if="!isMobileView"
          class="play-pause-btn"
        >
          <font-awesome-icon 
            v-if="!playTrack || idPlayingTrack != song.idTrack || (idPlayingTrack == song.idTrack && !checkPlayingTracklist(idTracklist))" 
            :icon="['fas', 'play']" 
            @click="handlePlayTrack(song.idTrack)"
          />
          <font-awesome-icon 
            v-else 
            :icon="['fas', 'pause']" 
            @click="handlePlayTrack(0)"
          />
        </div>
        <div v-if="!isMobileView" class="song-duration">{{ song.duration }}</div>
        <div class="song-actions">
          <div @click="handleActionsContent" class="song-actions-btn">...</div>
          <div v-if="showActionsContent" class="song-actions-content">
            <div 
              @mouseover="handleAddToPlaylistHover"
              @mouseleave="showPlaylistsContainer = false"
              class="add-to-playlist">
              <font-awesome-icon :icon="['fas', 'arrow-left']" style="margin-right: 5px;" />
              Add to playlist
              <div v-if="showPlaylistsContainer" 
                class="playlists-container"
                @mouseover="handleAddToPlaylistHover"
              >
                <div class="new-playlist" @click="openCreatePlaylistModal">
                  <font-awesome-icon :icon="['fas', 'plus']" />
                  New Playlist
                </div>
                <div 
                  v-for="playlist in playlists" 
                  class="playlists-to-add"
                  :key="playlist.idTracklist" 
                  @click="addSongToTracklist(song.idTrack, playlist.idTracklist)"
                >
                  <img :src="playlist.thumbnail_image.src" alt="thumbnail">
                  <div class="playlist-name">{{ playlist.name }}</div>
                </div>
              </div>
            </div>
            <div class="album" @click="handleAlbumView(song.idAlbum)">
              <div class="album-icon">
                <font-awesome-icon :icon="['fas', 'headphones']" />
              </div>
              Go to album
            </div>
            <div class="artist" @click="handleArtistView(song.artists[0]?.idArtist)">
              <div class="artist-icon">
                <font-awesome-icon :icon="['fas', 'user']" />
              </div>
              Go to artist
            </div>
          </div>
        </div>
    </div>
</template>

<style scoped>
/* Song card styles */
.song-card {
  display: flex;
  align-items: center;
  transition: all .3s ease;
  padding: 10px;
  border-radius: 10px;
}
.song-card:hover {
  background: #333;
}
.song-thumbnail img {
  width: 60px;
  height: 60px;
  border-radius: 10px;
}
.song-info {
  flex: 6;
  margin-left: 20px;
}
.song-artist {
  font-size: 14px;
  color: #888;
  margin-top: 5px;
}
.song-duration,
.song-actions {
  color: #888;
  display: flex;
  justify-content: center;
}
.song-duration {
  flex: 1;
}
.play-pause-btn {
  padding-left: 15px;
}
/* Actions button */
.song-actions {
  position: relative;
  padding-right: 20px;
}
.song-actions-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all .3s ease;
}
.song-actions-btn:hover {
  color: white;
  transform: scale(1.05);
}
.song-actions-content {
  position: absolute;
  top: 100%;
  right: 100%;
  background: #222;
  white-space: nowrap;
  border-radius: 5px;
}
.add-to-playlist,
.artist,
.album {
  padding: 15px;
}
.artist,
.album {
  display: flex;
  flex-wrap: wrap;
  cursor: pointer;
}
.artist-icon,
.album-icon {
  width: 25px;
}
.add-to-playlist {
  cursor: pointer;
  position: relative;
}
.artist:hover,
.album:hover,
.add-to-playlist:hover {
  color: white;
}
.playlists-container {
  position: absolute;
  top: 0;
  right: 100%;
  white-space: nowrap;
  border-radius: 5px;
  background-color: #222;
}
.new-playlist {
  color: #888;
  padding: 15px;
  cursor: pointer;
  transition: all .3s ease;
}
.new-playlist:hover {
  color: white;
}
.playlists-to-add {
  display: flex;
  align-items: center;
  color: #888;
  padding: 10px;
  transition: all .3s ease;
  border-radius: 10px;
}
.playlists-to-add img {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  margin-right: 10px;
}
.playlists-to-add:hover {
  color: white;
  background: #333;
}

@media screen and (max-width: 800px) {
  .song-actions-btn {
    font-size: 18px;
  }
  .song-actions {
    margin-left: 20px;
  }
}
</style>