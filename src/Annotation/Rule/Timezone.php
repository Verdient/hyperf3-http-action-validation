<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字符必须是基于 PHP 函数 timezone_identifiers_list 的有效时区标识
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Timezone extends AbstractRuleAnnotation
{
    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['timezone'];
    }
}