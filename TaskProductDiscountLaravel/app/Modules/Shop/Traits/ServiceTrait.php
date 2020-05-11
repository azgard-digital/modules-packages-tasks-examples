<?php

namespace App\Modules\Shop\Traits;

use App\Modules\Shop\Contracts\CompositeResultInterface;

trait ServiceTrait
{
    /**
     * Create common response format object
     * @param array $data
     * @param int $status
     * @return CompositeResultInterface
     */
    protected function getCompositeResult(array $data = [], int $status = 200)
    {
        return (new class($status, $data) implements CompositeResultInterface {
            /**
             * @var int
             */
            private $status;

            /**
             * @var array
             */
            private $data;

            /**
             *  constructor.
             * @param int $status
             * @param array $data
             */
            public function __construct(int $status, array $data)
            {
                $this->status = $status;
                $this->data = $data;
            }

            /**
             * @inheritDoc
             */
            public function __get($name)
            {
                if (property_exists($this, $name)) {
                    return $this->{$name};
                }

                return null;
            }

            /**
             * @inheritDoc
             */
            public function getStatus(): int
            {
                return $this->status;
            }

            /**
             * @inheritDoc
             */
            public function getData(): array
            {
                return $this->data;
            }

        });
    }
}