<?php

namespace App\Models;

use Core\Model;
use PDO;

class Ticket extends Model
{


    private $title;
    private $description;
    private $status;

    private $projectId;
    private $ownerId;

    private $createdAt;
    private $updatedAt;

    /**
     * @param $title
     * @param $description
     * @param $status
     * @param $projectId
     * @param $ownerId
     * @param $createdAt
     * @param $updatedAt
     */
    public function __construct($title, $description, $status, $projectId, $ownerId, $createdAt, $updatedAt)
    {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->projectId = $projectId;
        $this->ownerId = $ownerId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param mixed $projectId
     */
    public function setProjectId($projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @param mixed $ownerId
     */
    public function setOwnerId($ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public static function getAll($userId)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM tickets WHERE open_by_id = ?');
        $stmt->bindValue(1, $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);


    }

    public static function create(Ticket $ticketObj): bool
    {
        $db = static::getDB();
        $q = $db->prepare('INSERT INTO tickets (title, description,status,open_by_id,project_id,created_at,updated_at) VALUES (:title, :description, :status, :open_by_id, :project_id, :created_at, :updated_at)');
        $q->bindValue('title', $ticketObj->getTitle());
        $q->bindValue('description', $ticketObj->getDescription());
        $q->bindValue('status', $ticketObj->getStatus());
        $q->bindValue('open_by_id', $ticketObj->getOwnerId());
        $q->bindValue('project_id', $ticketObj->getProjectId());
        $q->bindValue('created_at', $ticketObj->getCreatedAt());
        $q->bindValue('updated_at', $ticketObj->getUpdatedAt());
        $res = $q->execute();
        if ($res) {
            return true;
        }
        return false;
    }


}
