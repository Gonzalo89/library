import App from './views/App.vue'
import Vue from 'vue'
import router from './router.js'

Vue.mixin({ methods: { t, n } })

const VueApp = Vue.extend(App)
new VueApp({
	router,
}).$mount('#content')
