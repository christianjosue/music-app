<script setup>
import SearchSongs from './SearchSongs.vue';
import { ref, watch } from 'vue';
const props = defineProps({
  showModal: {
    type: Boolean,
    default: false
  },
  closeModal: {
    type: Function
  }
});
const dialog = ref(null);

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
</script>

<template>
    <dialog id="dialog" ref="dialog">
      <h2>Add songs</h2>
      <SearchSongs :show-modal="props.showModal" />
      <button @click.prevent="closeModal" aria-label="close" class="x">❌</button>
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

form {
  width: 400px;
}

@keyframes fadeIn {
	from {
		opacity: 0;
	}
	to {
		opacity: 1;
	}
}

@media screen and (max-width: 800px) {
	dialog {
		max-width: 300px;
	}
	form {
		width: 300px;
	}
}

@media screen and (max-width: 400px) {
	dialog {
		max-width: 200px;
		padding: 20px;
	}
	form {
		width: 200px;
	}
}
</style>