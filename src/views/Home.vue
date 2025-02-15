<template>
	<div style="display: flex; flex-direction: column;">
		<div style="display: flex;">
			<NcTextField :value.sync="text1"
				label="Name">
				<BookOpen :size="20" />
			</NcTextField>
			<NcCheckboxRadioSwitch :checked.sync="read">
				ReadCCCCCCCC7
			</NcCheckboxRadioSwitch>
		</div>
		<div>
			<NcButton
				aria-label="Save"
				type="primary"
				@click="$emit('save-book', text1, read)">
				Save
			</NcButton>
		</div>
	</div>
</template>

<script>

import BookOpen from 'vue-material-design-icons/BookOpenBlankVariant.vue'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcTextField from '@nextcloud/vue/dist/Components/NcTextField.js'
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'

export default {
	name: 'Home',

	components: {
		BookOpen,
		NcTextField,
		NcButton,
		NcCheckboxRadioSwitch,
	},

	props: {
	},

	data() {
		return {
			text1: '',
			read: false,
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
