<?php

namespace App\Models;

use Core\Model;
use PDO;
class Project extends Model
{

    private $id;
    private $name;

    private $companyId;

    /**
     * @param $id
     * @param $name
     * @param $companyId
     */
    public function __construct($id, $name, $companyId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->companyId = $companyId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param mixed $companyId
     */
    public function setCompanyId($companyId): void
    {
        $this->companyId = $companyId;
    }


    public static function getAll($companyID)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM project WHERE company_id = ?');
        $stmt->bindValue(1, $companyID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
