<?php

namespace OCA\Library\Service;

use Exception;
use OC\User\NoUserException;
use OCA\Library\AppInfo\Application;
use OCA\Library\Db\NoteMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\Files\Folder;
use OCP\Files\File;
use OCP\Files\GenericFileException;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\Lock\LockedException;

class NoteService {

    public function __construct(
        string $appName,
        private IRootFolder $rootFolder,
        private NoteMapper $noteMapper
    ) {
    }

    /**
     * @param string $userId
     * @return Folder
     * @throws NotPermittedException
     * @throws NotFoundException
     * @throws NoUserException
     */
    private function createOrGetNotesDirectory(string $userId): Folder {
        $userFolder = $this->rootFolder->getUserFolder($userId);
        if ($userFolder->nodeExists(Application::NOTE_FOLDER_NAME)) {
            $node = $userFolder->get(Application::NOTE_FOLDER_NAME);
            if ($node instanceof Folder) {
                return $node;
            }
            throw new Exception('/' . Application::NOTE_FOLDER_NAME . ' exists and is not a directory');
        } else {
            return $userFolder->newFolder(Application::NOTE_FOLDER_NAME);
        }
    }

    /**
     * @param int $noteId
     * @param string $userId
     * @return string
     * @throws DoesNotExistException
     * @throws MultipleObjectsReturnedException
     * @throws NoUserException
     * @throws NotFoundException
     * @throws NotPermittedException
     * @throws \OCP\DB\Exception
     * @throws GenericFileException
     * @throws LockedException
     */
    public function exportNote(int $noteId, string $userId): string {
        $noteFolder = $this->createOrGetNotesDirectory($userId);
        $note = $this->noteMapper->getNoteOfUser($noteId, $userId);
        $fileName = $note->getName() . '.txt';
        $fileContent = $note->getContent();
        if ($noteFolder->nodeExists($fileName)) {
            $node = $noteFolder->get($fileName);
            if ($node instanceof File) {
                $node->putContent($fileContent);
                return Application::NOTE_FOLDER_NAME . '/' . $fileName;
            }
            throw new Exception('/' . Application::NOTE_FOLDER_NAME . '/' . $fileName . ' exists and is not a file');
        } else {
            $noteFolder->newFile($fileName, $fileContent);
            return Application::NOTE_FOLDER_NAME . '/' . $fileName;
        }
    }
}
