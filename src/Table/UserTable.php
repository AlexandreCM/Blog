<?php
namespace App\Table;

use App\Model\User;
use App\PaginatedQuery;
use App\Table\Exception\NotFoundException;
use PDO;

final class UserTable extends Table {

    protected $table = "user";
    protected $class = User::class;

    public function findByUsername(string $username)
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        $query->execute(['username' => $username]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if ($result == false) {
            throw new NotFoundException($this->table, $username);
        }
        return $result;
    }

    public function create(User $user): void
    {
        $id = $this->createTable([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword()
        ]);
        $user->setId($id);
    }

    public function update(User $user): void
    {
        $this->updateTable([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword()
        ], $user->getId());
    }

}