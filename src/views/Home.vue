<template>
	<div>
		<NcTextField :value.sync="text1"
			label="Name">
			<Magnify :size="20" />
		</NcTextField>
		<NcButton
			aria-label="Save"
			type="primary"
			@click="$emit('save-book', text1)">
			Save
		</NcButton>
	</div>
</template>

<script>

import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcTextField from '@nextcloud/vue/dist/Components/NcTextField.js'

export default {
	name: 'Home',

	components: {
		NcTextField,
		NcButton,
	},

	props: {
	},

	data() {
		return {
			text1: '',
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
