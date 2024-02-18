<template>
	<NcContent app-name="library">
		<MyNavigation :notes="displayedNotesById"
			:selected-note-id="state.selected_note_id"
			@click-note="onClickNote"
			@export-note="onExportNote"
			@create-note="onCreateNote"
			@add-book="onAddBook"
			@delete-note="onDeleteNote" />
		<NcAppContent>
			<div class="main-content">
				<router-view @save-book="onSaveBook"></router-view>
			</div>
		</NcAppContent>
	</NcContent>
</template>

<script>
import NcContent from '@nextcloud/vue/dist/Components/NcContent.js'
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import MyNavigation from '../components/MyNavigation.vue'

import axios from '@nextcloud/axios'
import { generateOcsUrl, generateUrl } from '@nextcloud/router'
import { showSuccess, showError, showUndo } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'

import { Timer } from '../utils.js'

export default {
	name: 'App',

	components: {
		NcContent,
		NcAppContent,
		MyNavigation,
	},

	data() {
		return {
			state: loadState('library', 'notes-initial-state'),
		}
	},

	computed: {
		allNotes() {
			return this.state.notes
		},
		notesToDisplay() {
			return this.state.notes.filter(n => !n.trash)
		},
		displayedNotesById() {
			const nbi = {}
			this.notesToDisplay.forEach(n => {
				nbi[n.id] = n
			})
			return nbi
		},
		notesById() {
			const nbi = {}
			this.allNotes.forEach(n => {
				nbi[n.id] = n
			})
			return nbi
		},
		selectedNote() {
			return this.displayedNotesById[this.state.selected_note_id]
		},
	},

	watch: {
	},

	mounted() {
	},

	beforeDestroy() {
	},

	methods: {
		onEditNote(noteId, content) {
			const options = {
				content,
			}
			const url = generateOcsUrl('apps/library/api/v1/notes/{noteId}', { noteId })
			axios.put(url, options).then(response => {
				this.notesById[noteId].content = content
				this.notesById[noteId].last_modified = response.data.ocs.data.last_modified
			}).catch((error) => {
				showError(t('notebook', 'Error saving note content'))
				console.error(error)
			})
		},
		onCreateNote(name) {
			console.debug('create note', name)
			const options = {
				name,
			}
			const url = generateOcsUrl('apps/library/api/v1/notes')
			axios.post(url, options).then(response => {
				this.state.notes.push(response.data.ocs.data)
				this.onClickNote(response.data.ocs.data.id)
			}).catch((error) => {
				showError(t('notebook', 'Error creating note'))
				console.error(error)
			})
		},
		onAddBook() {
		},
		onSaveBook(bookName) {
			console.debug(bookName, 'onSaveBook')
			axios.post(
				generateUrl('apps/library/book/add'), { name: bookName }).then(response => {
				console.debug(response.data.ocs.data, 'response OK')
			}).catch((error) => {
				showError(t('library', 'Error SavingBook'))
				console.error(error)
			})
		},
		onDeleteNote(noteId) {
			console.debug('delete note', noteId)
			this.$set(this.notesById[noteId], 'trash', true)
			// cancel or delete
			const deletionTimer = new Timer(() => {
				this.deleteNote(noteId)
			}, 10000)
			showUndo(
				t('library', '{name} deleted', { name: this.notesById[noteId].name }),
				() => {
					deletionTimer.pause()
					this.notesById[noteId].trash = false
				},
				{ timeout: 10000 },
			)
		},
		deleteNote(noteId) {
			const url = generateOcsUrl('apps/library/api/v1/notes/{noteId}', { noteId })
			axios.delete(url).then(response => {
				const indexToDelete = this.state.notes.findIndex(n => n.id === noteId)
				if (indexToDelete !== -1) {
					this.state.notes.splice(indexToDelete, 1)
				}
			}).catch((error) => {
				showError(t('notebook', 'Error deleting note'))
				console.error(error)
			})
		},
		onClickNote(noteId) {
			console.debug('click note', noteId)
			this.state.selected_note_id = noteId
			const options = {
				values: {
					selected_note_id: noteId,
				},
			}
			const url = generateUrl('apps/library/config')
			axios.put(url, options).then(response => {
			}).catch((error) => {
				showError(t('notebook', 'Error saving selected note'))
				console.error(error)
			})
		},
		onExportNote(noteId) {
			const url = generateOcsUrl('apps/library/api/v1/notes/{noteId}/export', { noteId })
			axios.get(url).then(response => {
				showSuccess(t('notebook', 'Note exported in {path}', { path: response.data.ocs.data }))
			}).catch((error) => {
				showError(t('notebook', 'Error deleting note'))
				console.error(error)
			})
		},
	},
}
</script>

<style scoped lang="scss">
.main-content {
	height: 100%;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;

	.content-editable {
		min-width: 600px;
		min-height: 200px;
	}
}
</style>
