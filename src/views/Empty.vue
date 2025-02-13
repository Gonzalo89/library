<template>
	<NcEmptyContent
		:title="t('tutorial_5', 'Select a note')">
		<template #icon>
			<ul>
				<li v-for="book in books" :key="book.id">{{ book.name }}</li>
			</ul>
		</template>
	</NcEmptyContent>
</template>

<script>

import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
	name: 'Empty',

	components: {
		NcEmptyContent,
	},

	props: {
	},

	data() {
		return {
			books: []
		}
	},

	computed: {

	},

	watch: {
	},

	mounted() {
		this.fetchData()
	},

	beforeDestroy() {
	},

	methods: {
		fetchData() {
			axios.get(generateUrl('apps/library/books'))
				.then(response => {
					this.books = response.data.ocs.data
				})
				.catch(error => {
					console.error('Hubo un error al recuperar los libros: ', error)
				})
		}
	},
}
</script>

<style scoped lang="scss">
// nothing yet
</style>
