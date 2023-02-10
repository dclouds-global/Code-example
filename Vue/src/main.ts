import { createApp } from 'vue'
import IconTemplate from '@/components/global/IconTemplate.vue'
import 'virtual:svg-icons-register'

import '@/styles/index.scss'

import App from '@/App.vue'

const app = createApp(App)

app.component('IconTemplate', IconTemplate)

app.mount('#app')
