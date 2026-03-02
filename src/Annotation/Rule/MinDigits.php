<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证的整数必须具有至少 value 位数
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class MinDigits extends AbstractRuleAnnotation
{
    /**
     * @param int $value 最小位数
     *
     * @author Verdient。
     */
    public function __construct(protected int $value) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['min_digits', (string) $this->value];
    }
}