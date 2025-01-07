<script setup>
import { onMounted, ref, watch } from 'vue';
const props = defineProps({
    showModal: {
        type: Boolean,
        default: false
    },
    tracklist: {
        type: Object,
        default: {}
    },
	thumbnails: {
		type: Object,
		default: {}
	},
    closeModal: {
        type: Function
    },
    editTracklist: {
        type: Function
    }
});

const dialog = ref(null);
const tracklistName = ref("");
const nameError = ref(false);
const nameErrorMessage = ref("");
const thumbnailError = ref(false);
const selectedThumbnail = ref(0);
const badWordsFilter = ref(null);
// Get spanish bad words string list and transform it to an array
const badWordsES = import.meta.env.VITE_BAD_WORDS_ES.replace(/\s/g, "").split(',');

onMounted(async () => {
	// Dynamic filter import
	const { default: Filter } = await import('bad-words-es');
	badWordsFilter.value = new Filter({languages: ['es', 'en']});
	// Add custom spanish bad words to the filter
	badWordsFilter.value.addWords(...badWordsES);
});

watch(
  () => props.showModal,
  () => {
    if (props.showModal) {
        dialog.value.showModal();
		selectedThumbnail.value = props.tracklist.thumbnail;
		tracklistName.value = props.tracklist.name;
    } else if (dialog.value != null) {
		dialog.value.close();
	}
  },
  { immediate: true }
);

// Sends form data to parent's component to process and store it into the database
const sendData = () => {
	return {
        'idTracklist': props.tracklist.idTracklist,
		'name': tracklistName.value,
		'thumbnail': selectedThumbnail.value
	};
}
// Validate all the fields of the form before submits
const validateForm = () => {
	let isValid = true;
	if (tracklistName.value == "" || tracklistName.value == null || tracklistName.value == undefined) {
		nameErrorMessage.value = "Name is a required field";
		nameError.value = true;
	} else if (badWordsFilter.value.isProfane(tracklistName.value)) {
		nameErrorMessage.value = "You can not use bad words";
		nameError.value = true;
	} else if (tracklistName.value.trim().length > 20) {
		nameErrorMessage.value = "You can not use more than 20 characters";
		nameError.value = true;
	} else {
		nameErrorMessage.value = "";
		nameError.value = false;
	}

	if (selectedThumbnail.value == 0) {
		thumbnailError.value = true;
	} else {
		thumbnailError.value = false;
	}

	if (thumbnailError.value || nameError.value) {
		isValid = false;
	}

	return isValid;
}
// Handle tracklist's edition
const handleEditTracklist = () => {
	// If the form is valid, tracklist is created and fields are cleaned up
	if (validateForm()) {
		const data = sendData();
		props.editTracklist(data);
		setTimeout(() => {
			cleanFields();
		}, 2000);
	}
}
// Clean up all form fields
const cleanFields = () => {
	tracklistName.value = "";
	selectedThumbnail.value = 0;
	nameError.value = false;
	thumbnailError.value = false;
}
// Handle when dialog closes
const handleCloseDialog = () => {
	cleanFields();
    props.closeModal();
}
const checkSelectedThumbnail = (id) => selectedThumbnail.value == id;
</script>

<template>
    <dialog id="dialog" ref="dialog">
        <h2>Edit Tracklist</h2>
        <form>
            <label for="tracklistName">Tracklist's name</label>
            <input id="tracklistName" name="tracklistName" type="text" v-model="tracklistName">
			<div :class="['error-message', { 'display-error': nameError }]">{{ nameErrorMessage }}</div>
            <label for="thumbnail" class="thumbnailLabel">Thumbnail</label>
            <div class="thumbnails">
				<div v-for="thumbnail in thumbnails" :key="thumbnail.idThumbnail" class="thumbnail">
					<img 
						:src="thumbnail.src"
						:class="{ active: checkSelectedThumbnail(thumbnail.idThumbnail) }"
						@click="selectedThumbnail = thumbnail.idThumbnail"
						alt="thumbnail"
					>
				</div>
				<div :class="['error-message', { 'display-error': thumbnailError }]">Thumbnail is a required field</div>
			</div>
            <div @click="handleEditTracklist" class="edit-btn">Edit</div>
        </form>
        <button @click.prevent="handleCloseDialog" aria-label="close" class="x">❌</button>
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
	margin-bottom: 7px;
    outline: none;
    padding: 10px;
    display: block;
    width: 80%;
    border-radius: 5px;
}
input[type=file] {
    display: block;
    width: 100%;
}

label {
    display: block;
    margin-bottom: 10px;
    width: 100%;
}

.edit-btn {
    display: inline-block;
    background-color: #1db954;
    padding: 10px 20px;
    margin-bottom: 20px;
    cursor: pointer;
    transition: all .3s ease;
    border: none;
    border-radius: 10px;
}

.edit-btn:hover {
    transform: scale(1.05);
}

.thumbnail-container {
    width: 100%;
    margin-bottom: 25px;
}

.error-message {
	color: red;
	font-size: 13px;
	margin-top: 7px;
	display: none;
}

.display-error {
	display: block !important;
}

.thumbnails {
	display: flex;
	flex-wrap: wrap;
	margin-bottom: 30px;
}
.thumbnail {
	width: 150px;
	height: 150px;
	margin: 0 20px 20px 0;
}
img {
	width: 100%;
	height: 100%;
	border-radius: 20px;
	transition: all .3s ease;
	cursor: pointer;
}
img:hover {
	transform: scale(1.05);
	border: 2px solid green;
}
img.active {
	border: 2px solid green;
}
.thumbnailLabel {
	margin-top: 15px;
}

@media screen and (max-width: 600px) {
	.thumbnail {
		width: 100px;
		height: 100px;
	}
	dialog {
		max-width: 250px;
	}
}
</style>