<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须是有效的 JSON 字符串
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Json extends AbstractRuleAnnotation
{
    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['json'];
    }
}
