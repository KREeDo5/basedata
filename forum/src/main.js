import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './style.css'
import './reset.css'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import https from 'https'

const axiosInstance = axios.create({
  baseURL: 'https://79.137.184.176:8443', //http://79.137.184.176:8001
  httpsAgent: new https.Agent({
    rejectUnauthorized: false, // Игнорирование ошибок SSL
  }),
})

const app = createApp(App)

// Добавление экземпляра Axios в глобальные свойства Vue
app.config.globalProperties.$axios = axiosInstance

app.use(router)
app.use(createPinia())
app.mount('#app')
