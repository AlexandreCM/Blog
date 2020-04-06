<?php
namespace App\Table;

use App\Table\Exception\NotFoundException;
use Exception;
use PDO;

abstract class Table {

    protected PDO $pdo;
    protected $table = null;
    protected $class = null;

    public function __construct(PDO $pdo)
    {
        if ($this->table === null) {
            throw new Exception("La classe ". get_class($this) . " n'a pas de propriété \$table");
        }
        if ($this->class === null) {
            throw new Exception("La classe ". get_class($this) . " n'a pas de propriété \$class");
        }
        $this->pdo = $pdo;
    }

    public function find(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if ($result == false) {
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }

    public function hasThisDataInTable(string $field, $value, ?int $except = null): bool
    {
        $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
        $sqlParams = [$value];
        if ($except !== null) {
            $sql .= " AND id != ?";
            $sqlParams[] = $except;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute($sqlParams);
        return (int) $query->fetch(PDO::FETCH_NUM)[0] > 0;
    }

}