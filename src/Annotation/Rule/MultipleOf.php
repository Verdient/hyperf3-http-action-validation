<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证的字段必须是 value 的倍数
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class MultipleOf extends AbstractRuleAnnotation
{
    /**
     * @param int $value 值
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
        return ['multiple_of', (string) $this->value];
    }
}