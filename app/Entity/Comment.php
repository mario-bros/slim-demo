<?php

namespace SlimDemo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class Comment implements \JsonSerializable
{
    /**
     * @var string
     * @ORM\Column(name="id", type="guid", length=24)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="create_at", type="date", nullable=false)
     */
    protected $createAt;

    /**
     * @var string
     * @ORM\Column(name="comment", type="string", nullable=false)
     */
    protected $comment;

    /**
     * @param string|null    $uuid
     * @param \DateTime|null $createAt
     */
    public function __construct(string $uuid = null, \DateTime $createAt = null)
    {
        $this->id = $uuid ?? Uuid::uuid4();
        $this->createAt = $createAt ?? new \DateTime();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt(): \DateTime
    {
        return $this->createAt;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getCreateAt()->format('Y-m-d H:i:s'),
            'comment' => $this->getComment(),
        ];
    }
}
