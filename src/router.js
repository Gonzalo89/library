import Vue from 'vue'
import VueRouter from 'vue-router'
import { generateUrl } from '@nextcloud/router'
import Home from './views/Home.vue'
import Empty from './views/Empty.vue'

Vue.use(VueRouter)

const routes = [
	{
		path: '/',
		component: Home,
	},
	{
		path: '/Empty',
		component: Empty,
	},
]

export default new VueRouter({
	mode: 'history',
	base: generateUrl('/apps/library', ''),
	routes,
})
