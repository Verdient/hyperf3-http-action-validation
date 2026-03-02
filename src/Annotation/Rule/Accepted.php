<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段的值必须是 yes、on、1 或 true
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Accepted extends AbstractRuleAnnotation
{
    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['accepted'];
    }
}
