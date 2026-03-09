<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段的值必须大于或等于最小值
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Min extends AbstractRuleAnnotation
{
    /**
     * @param int|string $value 最小值
     *
     * @author Verdient。
     */
    public function __construct(protected int|string $value) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['min', (string) $this->value];
    }
}
