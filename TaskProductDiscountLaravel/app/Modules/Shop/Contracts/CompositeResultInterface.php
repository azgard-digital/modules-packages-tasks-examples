<?php

namespace App\Modules\Shop\Contracts;
/**
 * Interface CompositeResultInterface
 * @package App\Modules\Shop\Contracts
 * @property array $data
 * @property int $status
 */
interface CompositeResultInterface
{
    /**
     * Response status
     * @return int
     */
    public function getStatus(): int;

    /**
     * Response data
     * @return array
     */
    public function getData(): array;
}
