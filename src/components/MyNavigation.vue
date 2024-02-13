<template>
	<NcAppNavigation>
		<template #list>
			<router-link :to="'/Empty'">
				<NcAppNavigationNew
					:text="t('library', 'Add Book')" @click="$emit('add-book', $event)"
				>
					<template #icon><plus-icon :size="20" /> </template>
				</NcAppNavigationNew>
			</router-link>
			<NcAppNavigationNewItem :title="t('notebook', 'Create note')" @new-item="$emit('create-note', $event)">
				<template #icon>
					<PlusIcon />
				</template>
			</NcAppNavigationNewItem>
			<h2 v-if="loading" class="icon-loading-small loading-icon" />
			<NcEmptyContent v-else-if="sortedNotes.length === 0" :title="t('notebook', 'No notes yet')">
				<template #icon>
					<NoteIcon :size="20" />
				</template>
			</NcEmptyContent>
			<NcAppNavigationItem v-for="note in sortedNotes"
				:key="note.id"
				:name="note.name"
				:class="{ selectedNote: note.id === selectedNoteId }"
				:force-display-actions="true"
				:force-menu="false"
				@click="$emit('click-note', note.id)">
				<template #icon>
					<NoteIcon />
				</template>
				<template #actions>
					<NcActionButton :close-after-click="true" @click="$emit('export-note', note.id)">
						<template #icon>
							<FileExportIcon />
						</template>
						{{ t('notebook', 'Export to file') }}
					</NcActionButton>
					<NcActionButton :close-after-click="true" @click="$emit('delete-note', note.id)">
						<template #icon>
							<DeleteIcon />
						</template>
						{{ t('notebook', 'Delete') }}
					</NcActionButton>
				</template>
			</NcAppNavigationItem>
		</template>
	</NcAppNavigation>
</template>

<script>
import FileExportIcon from 'vue-material-design-icons/FileExport.vue'
import PlusIcon from 'vue-material-design-icons/Plus.vue'
import DeleteIcon from 'vue-material-design-icons/Delete.vue'

import NoteIcon from './icons/NoteIcon.vue'

import NcAppNavigation from '@nextcloud/vue/dist/Components/NcAppNavigation.js'
import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import NcAppNavigationItem from '@nextcloud/vue/dist/Components/NcAppNavigationItem.js'
import NcActionButton from '@nextcloud/vue/dist/Components/NcActionButton.js'
import NcAppNavigationNewItem from '@nextcloud/vue/dist/Components/NcAppNavigationNewItem.js'
import NcAppNavigationNew from '@nextcloud/vue/dist/Components/NcAppNavigationNew.js'
import ClickOutside from 'vue-click-outside'

export default {
	name: 'MyNavigation',

	components: {
		NoteIcon,
		NcAppNavigation,
		NcEmptyContent,
		NcAppNavigationItem,
		NcActionButton,
		NcAppNavigationNewItem,
		NcAppNavigationNew,
		PlusIcon,
		DeleteIcon,
		FileExportIcon,
	},

	directives: {
		ClickOutside,
	},

	props: {
		notes: {
			type: Object,
			required: true,
		},
		selectedNoteId: {
			type: Number,
			default: 0,
		},
		loading: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			creating: false,
		}
	},
	computed: {
		sortedNotes() {
			return Object.values(this.notes).sort((a, b) => {
				const { tsA, tsB } = { tsA: a.last_modified, tsB: b.last_modified }
				return tsA > tsB
					? -1
					: tsA < tsB
						? 1
						: 0
			})
		},
	},
	beforeMount() {
	},
	methods: {
		onCreate(value) {
			console.debug('create new note')
		},
	},
}
</script>
<style scoped lang="scss">
.addNoteItem {
	position: sticky;
	top: 0;
	z-index: 1000;
	border-bottom: 1px solid var(--color-border);

	:deep(.app-navigation-entry) {
		background-color: var(--color-main-background-blur, var(--color-main-background));
		backdrop-filter: var(--filter-background-blur, none);

		&:hover {
			background-color: var(--color-background-hover);
		}
	}
}

:deep(.selectedNote) {
	>.app-navigation-entry {
		background: var(--color-primary-light, lightgrey);
	}

	>.app-navigation-entry a {
		font-weight: bold;
	}
}
</style>
