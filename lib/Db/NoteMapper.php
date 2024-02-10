<?php

declare(strict_types=1);

namespace OCA\NoteBook\Db;

use DateTime;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

use OCP\AppFramework\Db\DoesNotExistException;

class NoteMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'notebook_notes', Note::class);
    }

    /**
     * @param int $id
     * @return Note
     * @throws \OCP\AppFramework\Db\DoesNotExistException
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
     */
    public function getNote(int $id): Note {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
            );

        return $this->findEntity($qb);
    }

    /**
     * @param int $id
     * @param string $userId
     * @return Note
     * @throws DoesNotExistException
     * @throws Exception
     * @throws MultipleObjectsReturnedException
     */
    public function getNoteOfUser(int $id, string $userId): Note {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
            )
            ->andWhere(
                $qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
            );

        return $this->findEntity($qb);
    }

    /**
     * @param string $userId
     * @return Note[]
     * @throws Exception
     */
    public function getNotesOfUser(string $userId): array {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
            );

        return $this->findEntities($qb);
    }

    /**
     * @param string $userId
     * @param string $name
     * @param string $content
     * @return Note
     * @throws Exception
     */
    public function createNote(string $userId, string $name, string $content): Note {
        $note = new Note();
        $note->setUserId($userId);
        $note->setName($name);
        $note->setContent($content);
        $timestamp = (new DateTime())->getTimestamp();
        $note->setLastModified($timestamp);
        return $this->insert($note);
    }

    /**
     * @param int $id
     * @param string $userId
     * @param string|null $name
     * @param string|null $content
     * @return Note|null
     * @throws Exception
     */
    public function updateNote(int $id, string $userId, ?string $name = null, ?string $content = null): ?Note {
        if ($name === null && $content === null) {
            return null;
        }
        try {
            $note = $this->getNoteOfUser($id, $userId);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }
        if ($name !== null) {
            $note->setName($name);
        }
        if ($content !== null) {
            $note->setContent($content);
        }
        $timestamp = (new DateTime())->getTimestamp();
        $note->setLastModified($timestamp);
        return $this->update($note);
    }

    /**
     * @param int $id
     * @param string $userId
     * @return Note|null
     * @throws Exception
     */
    public function deleteNote(int $id, string $userId): ?Note {
        try {
            $note = $this->getNoteOfUser($id, $userId);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }

        return $this->delete($note);
    }

    /**
     * @param string $userId
     * @return void
     * @throws Exception
     */
    public function deleteNotesOfUser(string $userId): void {
        $qb = $this->db->getQueryBuilder();

        $qb->delete($this->getTableName())
            ->where(
                $qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
            );
        $qb->executeStatement();
        $qb->resetQueryParts();
    }
}
