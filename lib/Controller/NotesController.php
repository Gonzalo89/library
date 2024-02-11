<?php

namespace OCA\Library\Controller;

use Exception;
use OCA\Library\Db\NoteMapper;
use OCA\Library\Service\NoteService;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use Throwable;

class NotesController extends OCSController {

    public function __construct(
        string             $appName,
        IRequest           $request,
        private NoteMapper $noteMapper,
        private NoteService $noteService,
        private ?string    $userId
    ) {
        parent::__construct($appName, $request);
    }

    /**
     * @NoAdminRequired
     *
     * @return DataResponse
     */
    public function getUserNotes(): DataResponse {
        try {
            return new DataResponse($this->noteMapper->getNotesOfUser($this->userId));
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

    /**
     * @NoAdminRequired
     *
     * @param string $name
     * @param string $content
     * @return DataResponse
     */
    public function addUserNote(string $name, string $content = ''): DataResponse {
        try {
            $note = $this->noteMapper->createNote($this->userId, $name, $content);
            return new DataResponse($note);
        } catch (Exception | Throwable $e) {
         //   echo "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
         //   file_put_contents('/tmp/file1', print_r($e, true), FILE_APPEND);
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

    /**
     * @NoAdminRequired
     *
     * @param int $id
     * @param string|null $name
     * @param string|null $content
     * @return DataResponse
     */
    public function editUserNote(int $id, ?string $name = null, ?string $content = null): DataResponse {
        try {
            $note = $this->noteMapper->updateNote($id, $this->userId, $name, $content);
            return new DataResponse($note);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

    /**
     * @NoAdminRequired
     *
     * @param int $id
     * @return DataResponse
     */
    public function deleteUserNote(int $id): DataResponse {
        try {
            $note = $this->noteMapper->deleteNote($id, $this->userId);
            return new DataResponse($note);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

    /**
     * @param int $id
     * @return DataResponse
     */
    public function exportUserNote(int $id): DataResponse {
        try {
            $path = $this->noteService->exportNote($id, $this->userId);
            return new DataResponse($path);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }
}
