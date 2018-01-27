<?php declare(strict_types=1);

namespace BehatTests\Traits;

use JsonSpec\Behat\JsonProvider\JsonHolder;

trait JsonHolderTrait
{
    /** @var JsonHolder */
    private $jsonHolder;

    /**
     * @param JsonHolder $holder
     */
    public function setJsonHolder(JsonHolder $holder): void
    {
        $this->jsonHolder = $holder;
    }
}
