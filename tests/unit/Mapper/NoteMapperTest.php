<?php

declare(strict_types=1);
namespace OCA\NoteBook\Tests;

use OCA\NoteBook\Db\NoteMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\IUserManager;

/**
 * @group DB
 */
class NoteMapperTest extends \Test\TestCase {

    private NoteMapper $noteMapper;
    private array $testNoteValues = [
        ['user_id' => 'user1', 'name' => 'supername', 'content' => 'supercontent'],
        ['user_id' => 'user1', 'name' => '', 'content' => 'supercontent'],
        ['user_id' => 'user1', 'name' => 'supername', 'content' => ''],
        ['user_id' => 'user1', 'name' => '', 'content' => ''],
    ];

    public function setUp(): void {
        parent::setUp();

        \OC::$server->getAppManager()->enableApp('notebook');

        $this->noteMapper = \OC::$server->get(NoteMapper::class);
    }

    public function tearDown(): void {
        $this->cleanupUser('user1');
    }

    private function cleanupUser(string $userId): void {
        /** @var IUserManager $userManager */
        $userManager = \OC::$server->get(IUserManager::class);
        if ($userManager->userExists($userId)) {
            $this->noteMapper->deleteNotesOfUser($userId);
            $user = $userManager->get($userId);
            $user->delete();
        }
    }

    public function testAddNote() {
        foreach ($this->testNoteValues as $note) {
            $addedNote = $this->noteMapper->createNote('user1', $note['name'], $note['content']);
            self::assertEquals($note['name'], $addedNote->getName());
            self::assertEquals($note['content'], $addedNote->getContent());
            self::assertEquals($note['user_id'], $addedNote->getUserId());
        }
    }

    public function testDeleteNote() {
        foreach ($this->testNoteValues as $note) {
            $addedNote = $this->noteMapper->createNote($note['user_id'], $note['name'], $note['content']);
            $addedNoteId = $addedNote->getId();
            $dbNote = $this->noteMapper->getNoteOfUser($addedNoteId, $note['user_id']);
            $deletedNote = $this->noteMapper->deleteNote($addedNoteId, $note['user_id']);
            $this->assertNotNull($deletedNote, 'error deleting note');
            $exceptionThrowed = false;
            try {
                $dbNote = $this->noteMapper->getNoteOfUser($addedNoteId, $note['user_id']);
            } catch (DoesNotExistException $e) {
                $exceptionThrowed = true;
            }
            $this->assertTrue($exceptionThrowed, 'deleted note still exists');
        }
    }

    public function testEditNote() {
        foreach ($this->testNoteValues as $note) {
            $addedNote = $this->noteMapper->createNote($note['user_id'], $note['name'], $note['content']);
            $addedNoteId = $addedNote->getId();

            $editedNote = $this->noteMapper->updateNote($addedNoteId, $note['user_id'], $note['name'] . 'AAA', $note['content'] . 'BBB');
            $this->assertNotNull($editedNote, 'error deleting note');
            self::assertEquals($note['name'] . 'AAA', $editedNote->getName());
            self::assertEquals($note['content'] . 'BBB', $editedNote->getContent());

            $dbNote = $this->noteMapper->getNoteOfUser($addedNoteId, $note['user_id']);
            self::assertEquals($note['name'] . 'AAA', $dbNote->getName());
            self::assertEquals($note['content'] . 'BBB', $dbNote->getContent());
        }
    }
}
