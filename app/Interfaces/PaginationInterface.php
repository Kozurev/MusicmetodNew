<?php


namespace App\Interfaces;


interface PaginationInterface
{
    /**
     * IPagination constructor.
     * @param int $currentPage
     * @param int $onPage
     */
    public function __construct(int $currentPage, int $onPage);

    /**
     * @return int
     */
    public function getCurrentPage(): int;

    /**
     * @return int
     */
    public function getPerPage(): int;
}
