import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
/* import vue-toastification library and the css */
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* Import specific icons */
import {
    faUserSecret,
    faHouse,
    faMagnifyingGlass,
    faCirclePlay,
    faVolumeXmark,
    faVolumeOff,
    faVolumeLow,
    faVolumeHigh,
    faXmark,
    faHeadphones,
    faShuffle,
    faPlus,
    faCircleDown,
    faEllipsis,
    faBars,
    faClock,
    faPlay,
    faPause,
    faMicrophone,
    faRepeat,
    faTrash,
    faPencil,
    faMusic,
    faCirclePlus,
    faCircleXmark,
    faArrowUp,
    faArrowDown,
    faArrowLeft,
    faUser,
} from '@fortawesome/free-solid-svg-icons';

import * as Vibrant from 'node-vibrant';

/* Group icons in an array */
const icons = [
    faUserSecret,
    faHouse,
    faMagnifyingGlass,
    faCirclePlay,
    faVolumeXmark,
    faVolumeOff,
    faVolumeLow,
    faVolumeHigh,
    faXmark,
    faHeadphones,
    faShuffle,
    faPlus,
    faCircleDown,
    faEllipsis,
    faBars,
    faClock,
    faPlay,
    faPause,
    faMicrophone,
    faRepeat,
    faTrash,
    faPencil,
    faMusic,
    faCirclePlus,
    faCircleXmark,
    faArrowUp,
    faArrowDown,
    faArrowLeft,
    faUser,
];

/* Add icons to the library */
library.add(...icons);

createApp(App)
.component('font-awesome-icon', FontAwesomeIcon)
.use(Toast)
.mount('#app')
