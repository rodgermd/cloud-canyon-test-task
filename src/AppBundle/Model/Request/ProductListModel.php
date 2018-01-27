<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 14:47
 */

namespace AppBundle\Model\Request;

use AppBundle\Traits\FromArrayTrait;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class ProductListModel
{
    use FromArrayTrait;

    /**
     * @var integer
     * @Assert\NotBlank(message="The limit must be defined")
     * @Assert\Range(min=1, minMessage="The limit must be greater than 0")
     * @Serializer\Type("integer")
     *
     */
    protected $limit;

    /**
     * @var integer
     * @Assert\NotNull(message="The offset must be defined")
     * @Assert\Range(min=0, minMessage="The offset should be equal or greater than 0")
     * @Serializer\Type("integer")
     */
    protected $offset = 0;

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }
}