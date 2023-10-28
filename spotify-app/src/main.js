import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { faHouse } from '@fortawesome/free-solid-svg-icons'
import { faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons'
import * as Vibrant from 'node-vibrant'

/* add icons to the library */
library.add(faUserSecret, faHouse, faMagnifyingGlass)

createApp(App)
.component('font-awesome-icon', FontAwesomeIcon)
.mount('#app')
