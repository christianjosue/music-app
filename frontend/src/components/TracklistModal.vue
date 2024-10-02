<script setup>
import { ref, watch } from 'vue';
const props = defineProps({
    showModal: {
        type: Boolean,
        default: false
    }
});
const dialog = ref(null);
const tracklistName = ref({});
const privacy = ref(0);
const thumbnailUrl = ref('');
const thumbnailFile = ref(null);

watch(
  () => props.showModal,
  () => {
    if (props.showModal) {
        dialog.value.showModal();
    } else if (dialog.value != null) {
		dialog.value.close();
	}
  },
  { immediate: true }
);

// When image changes, thumbnail's preview also changes
function onThumbnailChanges(e) {
	const file = e.target.files[0];
	thumbnailFile.value = file;
    thumbnailUrl.value = URL.createObjectURL(file);
}
// Sends form data to parent's component to process and store it into the database
function sendData() {
	let name = tracklistName.value != null ? tracklistName.value.value : "";
	let privacyOption = privacy.value != null ? privacy.value : 0;
	let thumbnailImage = thumbnailFile.value != null ? thumbnailFile.value : 0;

	return {
		'tracklistName': name,
		'privacy': privacyOption,
		'thumbnail': thumbnailImage
	};
}
</script>

<template>
    <dialog id="dialog" ref="dialog">
        <h2>Create Tracklist</h2>
        <form>
            <label for="tracklistName">Tracklist's name</label>
            <input id="tracklistName" name="tracklistName" type="text" ref="tracklistName">
            <div class="privacy-container">
                <label class="privacy-label" for="privacy" ref="privacy">Privacy</label>
                <input id="privacy" name="privacy" type="checkbox">
            </div>
            <label for="tracklistName">Thumbnail</label>
            <input @change="onThumbnailChanges" type="file" accept=".jpeg, .jpg, .png">
            <div class="thumbnail-container">
                <img v-if="thumbnailUrl != ''" :src="thumbnailUrl" alt="Thumbnail's preview" ref="thumbnail">
            </div>
            <div @click="$emit('createTracklist', sendData())" class="createBtn">Create</div>
        </form>
        <button @click.prevent="$emit('closeDialog')" aria-label="close" class="x">❌</button>
    </dialog>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");
:root {
	--vs-primary: 29 92 255;
}

dialog {
	padding: 1rem 3rem;
	background: #222;
    color: white;
	max-width: 400px;
	padding-top: 2rem;
	border-radius: 20px;
	border: 0;
	box-shadow: 0 5px 30px 0 rgb(0 0 0 / 10%);
	animation: fadeIn 1s ease both;
	&::backdrop {
		animation: fadeIn 1s ease both;
		background: rgb(255 255 255 / 40%);
		z-index: 2;
		backdrop-filter: blur(20px);
	}
	.x {
		filter: grayscale(1);
		border: none;
		background: none;
		position: absolute;
		top: 15px;
		right: 10px;
		transition: ease filter, transform 0.3s;
		cursor: pointer;
		transform-origin: center;
		&:hover {
			filter: grayscale(0);
			transform: scale(1.1);
		}
	}
	h2 {
		font-weight: 600;
		font-size: 2rem;
		padding-bottom: 1rem;
	}
	p {
		font-size: 1rem;
		line-height: 1.3rem;
		padding: 0.5rem 0;
		a {
			&:visited {
				color: rgb(var(--vs-primary));
			}
		}
	}
}

body {
	height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	font-family: Poppins, sans-serif;
	background: url(https://images.unsplash.com/photo-1574169208507-84376144848b?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=879&q=80)
		no-repeat center center;
	background-size: cover;
}
button.primary {
	display: inline-block;
	font-size: 0.8rem;
	color: #fff !important;
	background: rgb(var(--vs-primary) / 100%);
	padding: 13px 25px;
	border-radius: 17px;
	transition: background-color 0.1s ease;
	box-sizing: border-box;
	transition: all 0.25s ease;
	border: 0;
	cursor: pointer;
	box-shadow: 0 10px 20px -10px rgb(var(--vs-primary) / 50%);
	&:hover {
		box-shadow: 0 20px 20px -10px rgb(var(--vs-primary) / 50%);
		transform: translateY(-5px);
	}
}

@keyframes fadeIn {
	from {
		opacity: 0;
	}
	to {
		opacity: 1;
	}
}

input[type=text] {
    border: 1px solid gray;
    border-bottom: 2px solid #1db954;
    margin-bottom: 25px;
    outline: none;
    padding: 10px;
    display: block;
    width: 80%;
    border-radius: 5px;
}
input[type=file] {
    display: block;
    width: 100%;
    margin-bottom: 25px;
}

label {
    display: block;
    margin-bottom: 10px;
    width: 100%;
}

.privacy-container {
    margin-bottom: 25px;
    display: flex;
    align-items: center;
}

.privacy-label {
    display: inline !important;
    width: 60px;
    margin-bottom: 0px;
}

.createBtn {
    display: inline-block;
    background-color: #1db954;
    padding: 10px 20px;
    margin-bottom: 20px;
    cursor: pointer;
    transition: all .3s ease;
    border: none;
    border-radius: 10px;
}

.createBtn:hover {
    transform: scale(1.05);
}

.thumbnail-container {
    width: 100%;
    margin-bottom: 25px;
}

img {
  max-width: 100%;
  max-height: 250px;
}

</style>