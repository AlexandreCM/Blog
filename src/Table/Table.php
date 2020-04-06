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

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_CLASS, $this->class);
    }

    protected function createTable(array $data): int
    {
        $sqlFields = [];
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET " . implode(', ', $sqlFields));
        $result = $query->execute($data);
        if (!$result) {
            throw new Exception("Impossible de creer l'enregistrement dans la table {$this->table}");
        }
        return (int) $this->pdo->lastInsertId();
    }

    protected function updateTable(array $data, int $id): void
    {
        $sqlFields = [];
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sqlFields) . " WHERE id = :id";
        $query = $this->pdo->prepare($sql);
        $result = $query->execute(array_merge($data, ['id' => $id]));
        if (!$result) {
            throw new Exception("Impossible de modifier l'enregistrement dans la table {$this->table}");
        }
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?;");
        $result = $query->execute([$id]);
        if (!$result) {
            throw new Exception("Impossible de supprimer l'enregistrement #$id dans la table {$this->table}");
        }
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