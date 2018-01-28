<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 28.01.18
 * Time: 14:15
 */

namespace Tests\Unit\RequestConverter\Model;


use AppBundle\Traits\FromArrayTrait;

class TestRequestModel
{
    use FromArrayTrait;

    protected $name;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}