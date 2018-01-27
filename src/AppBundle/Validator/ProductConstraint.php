<?php
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 12:24
 */

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class ProductConstraint
 *
 * @package AppBundle\Validator
 * @Annotation
 */
class ProductConstraint extends Constraint
{
    public $requiredColorMessage = "The color is required when type is 1";
    public $requiredTextureMessage = "The texture is required when type is 1";
    public $requiredHeightMessage = "The height is required when type is 2";
    public $requiredWidthMessage = "The width is required when type is 2";

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return ProductValidator::class;
    }
}