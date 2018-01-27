<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: rodger
 * Date: 27.01.18
 * Time: 12:27
 */

namespace AppBundle\Validator;

use AppBundle\Entity\Product;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Product) {
            return;
        }

        if (!$constraint instanceof ProductConstraint) {
            return;
        }

        /** @var Product $value */
        /** @var ProductConstraint $constraint */

        switch ($value->getType()) {
            case Product::TYPE_ONE:
                if (!$value->getColor()) {
                    $this->context->buildViolation($constraint->requiredColorMessage)->atPath('color')->addViolation();
                }

                if (!$value->getTexture()) {
                    $this->context->buildViolation($constraint->requiredTextureMessage)->atPath('texture')->addViolation();
                }
                break;
            case Product::TYPE_TWO:
                if (!$value->getWidth()) {
                    $this->context->buildViolation($constraint->requiredWidthMessage)->atPath('width')->addViolation();
                }
                if (!$value->getHeight()) {
                    $this->context->buildViolation($constraint->requiredHeightMessage)->atPath('height')->addViolation();
                }
                break;
        }
    }
}