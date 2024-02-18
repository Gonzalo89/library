<template>
	<NcEmptyContent
		:title="t('tutorial_5', 'Select a note')">
		<template #icon>
			<NoteIcon :size="20" />
		</template>
	</NcEmptyContent>
</template>

<script>

import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import NoteIcon from '../components/icons/NoteIcon.vue'

export default {
	name: 'Empty',

	components: {
		NoteIcon,
		NcEmptyContent,
	},

	props: {
	},

	data() {
		return {
		}
	},

	computed: {

	},

	watch: {
	},

	mounted() {
	},

	beforeDestroy() {
	},

	methods: {

	},
}
</script>

<style scoped lang="scss">
// nothing yet
</style>
