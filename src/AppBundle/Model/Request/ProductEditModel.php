<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 14:50
 */

namespace AppBundle\Model\Request;

use AppBundle\Traits\FromArrayTrait;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Product;

class ProductEditModel
{
    use FromArrayTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * Product type
     * Column name changed due to possible exceptions in MySQL, type can be a reserved word
     *
     * @var int
     *
     * @Assert\Choice(callback={Product::class,"getTypeChoices"})
     */
    protected $type;

    /**
     * @var string
     */
    protected $color;

    /**
     * @var string
     */
    protected $texture;
    /**
     * @var integer
     */
    protected $height;

    /**
     * @var integer
     */
    protected $width;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type)
    {
        $this->type = $type;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getTexture(): ?string
    {
        return $this->texture;
    }

    public function setTexture(?string $texture): self
    {
        $this->texture = $texture;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }
}