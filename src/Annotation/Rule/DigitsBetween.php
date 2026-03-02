<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须是数字，且长度在给定的最小值和最大值之间
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class DigitsBetween extends AbstractRuleAnnotation
{
    /**
     * @param int $min 最小位数
     * @param int $max 最大位数
     *
     * @author Verdient。
     */
    public function __construct(protected int $min, protected int $max) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['digits_between', (string) $this->min, (string) $this->max];
    }
}