import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './style.css'
import './reset.css'
import App from './App.vue'
import router from './router'
import axios from 'axios'

// const axiosInstance = axios.create({
//   baseURL: 'https://forum.kreedo.tech:8443',
// })

const app = createApp(App)

// Добавление экземпляра Axios в глобальные свойства Vue
//app.config.globalProperties.$axios = axiosInstance

app.use(router)
app.use(createPinia())
app.mount('#app')
