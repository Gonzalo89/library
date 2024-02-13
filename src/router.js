import Vue from 'vue'
import VueRouter from 'vue-router'
import { generateUrl } from '@nextcloud/router'
import Home from './views/Home.vue'
import Empty from './views/Empty.vue'

Vue.use(VueRouter)

const routes = [
	{
		path: '/Empty',
		component: Empty,
	},
	{
		path: '/',
		component: Home,
	},
]

export default new VueRouter({
	routes,
})
