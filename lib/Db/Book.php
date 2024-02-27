<?php

declare(strict_types=1);

namespace OCA\Library\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string|null getUserId()
 * @method void setUserId(?string $userId)
 * @method string getName()
 * @method void setName(string $name)
 * @method string getContent()
 * @method void setContent(string $content)
 * @method bool getRead()
 * @method void setRead(bool $read)
 */
class Book extends Entity implements \JsonSerializable {

    /** @var string */
    protected $userId;
    /** @var string */
    protected $name;
    /** @var bool */
    protected $read;

    public function __construct() {
        $this->addType('user_id', 'string');
        $this->addType('name', 'string');
        $this->addType('read', 'bool');
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'name' => $this->name,
            'read' => $this->read,
        ];
    }
}
