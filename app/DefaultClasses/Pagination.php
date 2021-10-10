<?php


namespace App\DefaultClasses;



use App\Interfaces\PaginationInterface;

class Pagination implements PaginationInterface
{
    /**
     * @var int
     */
    protected int $currentPage;

    /**
     * @var int
     */
    protected int $perPage;

    /**
     * Pagination constructor.
     * @param int $currentPage
     * @param int $perPage
     */
    public function __construct(int $currentPage, int $perPage)
    {
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
