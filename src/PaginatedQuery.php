<?php
namespace App;

use Exception;
use PDO;

class PaginatedQuery {

    private string $query;
    private string $queryCount;
    private PDO $pdo;
    private int $perPage;
    private int $count = -1;
    private array $items;

    public function __construct(string $query, string $queryCount, ?PDO $pdo = null, int $perPage = 12)
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->pdo = $pdo ?: Connection::getPDO();
        $this->perPage = $perPage;
    }

    private function getCurrentPage(): int
    {
        return URL::getPositiveInt('page', 1);
    }

    private function getPages(): int
    {
        if ($this->count < 0) {
            $this->count = (int) $this->pdo
                ->query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0];
        }
        return ceil($this->count / $this->perPage);
    }

    public function getItems(string $classMapping): array
    {
        if (empty($this->items)) {
            $currentPage = $this->getCurrentPage();
            $pages = $this->getPages();
            if ($currentPage > $pages) {
                throw new Exception('Cette page n\'existe pas');
            }
            $offset = $this->perPage * ($currentPage - 1);
            $this->items = $this->pdo
                ->query($this->query . " LIMIT {$this->perPage} OFFSET $offset;")
                ->fetchAll(PDO::FETCH_CLASS, $classMapping);
        }
        return $this->items;
    }

    public function previousPage(string $link)
    {
        $currentPage = $this->getCurrentPage();
        if ($currentPage <= 1) return null;
        if ($currentPage > 2) $link .= '?page=' . ($currentPage - 1);
        return <<<HTML
            <a href="{$link}" class="btn btn-primary">&laquo; Page précédente</a>
        HTML;
    }

    public function nextPage(string $link)
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if ($currentPage >= $pages) return null;
        $link .= '?page=' . ($currentPage + 1);
        return <<<HTML
            <a href="{$link}" class="btn btn-primary ml-auto">Page suivante &raquo</a>
        HTML;
    }


}