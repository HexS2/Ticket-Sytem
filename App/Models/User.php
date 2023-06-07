<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    private $id;
    private $email;
    private $roleId;
    private $companyId;

    /**
     * @param $id
     * @param $email
     * @param $roleId
     * @param $companyId
     */
    public function __construct($id, $email, $roleId, $companyId)
    {
        $this->id = $id;
        $this->email = $email;
        $this->roleId = $roleId;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param mixed $roleId
     */
    public function setRoleId($roleId): void
    {
        $this->roleId = $roleId;
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


    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, name FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUser(): ?User
    {
        if (self::isLogged()) {
            return new User($_SESSION["user"]["user_id"], $_SESSION["user"]["user_email"], $_SESSION["user"]["role_id"], $_SESSION["user"]["company_id"]);
        }
        return null;
    }

    public static function register($email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $db = static::getDB();
        $q = $db->prepare('INSERT INTO users (user_email, user_password,role_id) VALUES (:user_email, :user_password, :role_id)');
        $q->bindValue('user_email', $email);
        $q->bindValue('user_password', $hashed_password);
        $q->bindValue('role_id', 1);
        $res = $q->execute();
        if ($res) {
            return true;
        }
        return false;
    }

    public static function login($email, $password)
    {
        $db = static::getDB();
        $q = $db->prepare('SELECT * FROM users WHERE user_email = :email');
        $q->bindValue('email', $email);
        $q->execute();
        $res = $q->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            $passwordHash = $res['user_password'];
            if (password_verify($password, $passwordHash)) {

                // (D2) GET PERMISSIONS
                $res["permissions"] = [];
                $query = $db->prepare(
                    "SELECT * FROM `roles_permissions` r LEFT JOIN `permissions` p
                            USING (`perm_id`) WHERE r.`role_id`=?");
                $query->bindValue(1, $res["role_id"]);
                $query->execute();
                while ($r = $query->fetch()) {
                    if (!isset($user["permissions"][$r["perm_mod"]])) {
                        $res["permissions"][$r["perm_mod"]] = [];
                    }
                    $res["permissions"][$r["perm_mod"]][] = $r["perm_id"];
                }

                // (D3) DONE
                $_SESSION["user"] = $res;
                unset($_SESSION["user"]["user_password"]);

                return true;

            } else {
                return false;
            }
        }
        return false;
    }

    public static function isLogged()
    {
        return isset($_SESSION["user"]);
    }

    function hasPermission($module, $perm)
    {
        $valid = isset($_SESSION["user"]);
        if ($valid) {
            $valid = in_array($perm, $_SESSION["user"]["permissions"][$module]);
        }
        if ($valid) {
            return true;
        } else {
            return false;
        }
    }

}
