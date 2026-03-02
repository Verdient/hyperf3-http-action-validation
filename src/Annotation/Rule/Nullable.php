<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段可以为 null
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Nullable extends AbstractRuleAnnotation
{
    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['nullable'];
    }
}