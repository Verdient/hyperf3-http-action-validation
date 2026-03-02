<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段的值必须小于或等于最大值
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Max extends AbstractRuleAnnotation
{
    /**
     * @param int $value 最大值
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
        return ['max', (string) $this->value];
    }
}