import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from './views/Home.vue'
import Empty from './views/Empty.vue'

Vue.use(VueRouter)

const routes = [
	{
		path: '/',
		component: Empty,
	},
	{
		path: '/Home',
		component: Home,
	},
]

export default new VueRouter({
	routes,
})
