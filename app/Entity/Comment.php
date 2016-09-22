<?php

namespace SlimDemo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="SlimDemo\Repository\CommentRepository")
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * @var string
     * @ORM\Column(name="id", type="string", length=24)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    protected $date;

    /**
     * @var string
     * @ORM\Column(name="comment", type="string", nullable=false)
     */
    protected $comment;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Comment
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode([
            'id' => $this->getId(),
            'date' => $this->getDate()->format('Y-m-d H:i:s'),
            'comment' => $this->getComment()
        ]);
    }
}
