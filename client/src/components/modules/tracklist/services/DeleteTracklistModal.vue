<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    handleCloseDialog: {
        type: Function,
        default: {}
    },
    deleteTracklist: {
        type: Function,
        default: {}
    },
    showDeleteTracklistModal: {
        type: Boolean,
        default: false
    }
});

const dialog = ref(null);

watch(
  () => props.showDeleteTracklistModal,
  () => {
    if (props.showDeleteTracklistModal) {
        dialog.value.showModal();
    } else if (dialog.value != null) {
		dialog.value.close();
	}
  },
  { immediate: true }
);
</script>

<template>
    <dialog id="dialog" ref="dialog">
        <p>Are you sure you want to remove this tracklist?</p>
        <div class="buttons-container">
            <div @click="handleCloseDialog" class="btn cancel-btn">Cancel</div>
            <div @click="deleteTracklist" class="btn delete-btn">Delete</div>
        </div>
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
	max-width: 300px;
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
@keyframes fadeIn {
	from {
		opacity: 0;
	}
	to {
		opacity: 1;
	}
}
p {
    font-size: 18px;
}
.buttons-container {
    display: flex;
    justify-content: right;
    gap: 15px;
}
.btn {
    color: white;
    padding: 10px 15px;
    border-radius: 10px;
    cursor: pointer;
    transition: all .3s ease;
}
.btn:hover {
    transform: scale(1.05);
}
.delete-btn {
    background: red;
}
.cancel-btn {
    background: rgb(171, 171, 171);
}
.cancel-btn:hover {
    background: white;
    color: black;
}

@media screen and (max-width: 500px) {
	dialog {
		max-width: 200px;
	}
}
</style>