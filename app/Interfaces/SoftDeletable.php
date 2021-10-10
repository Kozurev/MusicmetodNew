<?php

namespace App\Interfaces;

interface SoftDeletable
{
    /**
     * @return string
     */
    public function getDeletedColumn(): string;

    /**
     * @return int|string
     */
    public function getDeletedValue(): int|string;
}
