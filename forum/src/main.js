import { createApp } from 'vue'
import './style.css'
import './reset.css'
import App from './App.vue'
import router from './router'
import axios from 'axios'

const app = createApp(App)

axios.defaults.headers.common['X-XCSRF-TOKEN'] =
  'eyJpdiI6IjJjUUJySHFZck1kOU53VEdvYXRaY1E9PSIsInZhbHVlIjoiVGIrSFdhQXpkSkhWdmtCQm5YSjV2NFRCeGVyWEZhUVYrU1RrbmZSMk9PM2ptNU9leUhQNUV4NmtlL3IxU05lSEc1SUJQVnpNRzBNRU96ZU1sa1RINWJYaTh4bEFNbXdPWWk1cVBqYjVGVlhIQk5VSVU0Rytzei84OVlwYUF5YnUiLCJtYWMiOiI4NzgyZjkwMDY5ZDVhZTQ3NjQwNjI3ODllNzQxMTdmNGMwODI4MDg0ODlhNTRjZDY1NGQ4MGVhZmIyMGI4YmU4In0'

app.use(router)

app.mount('#app')
