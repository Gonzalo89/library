<?php

namespace OCA\Library\Controller;

use Exception;
use OCA\Library\Db\BookMapper;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use Throwable;

class BookController extends OCSController {

    public function __construct(
        string             $appName,
        IRequest           $request,
        private BookMapper $bookMapper,
        private ?string    $userId
    ) {
        parent::__construct($appName, $request);
    }

    /**
     * @NoAdminRequired
     *
     * @return DataResponse
     */
    public function getUserBooks(): DataResponse {
        try {
            return new DataResponse($this->bookMapper->getBooksOfUser($this->userId));
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

    /**
     * @NoAdminRequired
     *
     * @param string $name
     * @return DataResponse
     */
    public function addUserBook(string $name, bool $read): DataResponse {
        try {
            $book = $this->bookMapper->createBook($this->userId, $name, $read);
            return new DataResponse($book);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }

    /**
     * @NoAdminRequired
     *
     * @param int $id
     * @param string|null $name
     * @return DataResponse
     */
    public function editUserBook(int $id, ?string $name = null, ?bool $read = null): DataResponse {
        try {
            $book = $this->bookMapper->updateBook($id, $this->userId, $name, $read);
            return new DataResponse($book);
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
    public function deleteUserBook(int $id): DataResponse {
        try {
            $book = $this->bookMapper->deleteBook($id, $this->userId);
            return new DataResponse($book);
        } catch (Exception | Throwable $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
        }
    }
}
