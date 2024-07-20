import { createApp } from 'vue'
import App from './App.vue'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
//import { VDataTable } from 'vuetify/labs/VDataTable'

//materials icon
import '@mdi/font/css/materialdesignicons.css'

//Router
import {router} from './router/index.js'

const vuetify = createVuetify({
  components,
  directives
  /* VDataTable */
})

createApp(App).use(vuetify, {  iconfont: 'mdi'}).use(router).mount('#app')
