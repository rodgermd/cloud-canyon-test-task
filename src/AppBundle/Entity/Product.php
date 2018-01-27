<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 11:56
 */

namespace AppBundle\Entity;

use AppBundle\Traits\ClassConstantsTrait;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Validator\ProductConstraint;


/**
 * Class Product
 *
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ProductConstraint()
 * @Serializer\ExclusionPolicy("ALL")
 */
class Product
{
    use ClassConstantsTrait;

    const TYPE_ONE = 1;
    const TYPE_TWO = 2;

    /**
     * @var integer
     * @ORM\Column(type="bigint", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose()
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, length=255)
     * @Assert\NotBlank(message="The name should not be blank")
     * @Serializer\Expose()
     */
    protected $name;

    /**
     * Product type
     *
     * Column name changed due to possible exceptions in MySQL, type can be a reserved word
     *
     * @var int
     *
     * @ORM\Column(name="product_type", type="smallint", nullable=false)
     * @Assert\NotBlank(message="The type should be defined")
     * @Assert\Choice(callback="getTypeChoices")
     * @Serializer\Expose()
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Serializer\Expose()
     */
    protected $color;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Expose()
     */
    protected $texture;
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min=0, minMessage="The height value should be positive")
     * @Serializer\Expose()
     */
    protected $height;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min=0, minMessage="The width value should be positive")
     * @Serializer\Expose()
     */
    protected $width;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     * @Serializer\Expose()
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Expose()
     */
    protected $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

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

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public static function getTypeChoices(): array
    {
        return static::namedConstants('TYPE');
    }
}