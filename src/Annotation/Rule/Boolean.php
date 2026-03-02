<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须可以被转化为布尔值，接收 true, false, 1, 0, "1" 和 "0" 等输入
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Boolean extends AbstractRuleAnnotation
{
    /**
     * @param bool $strict 严格模式，仅接收 true 和 false
     *
     * @author Verdient。
     */
    public function __construct(protected bool $strict = false) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        $result = ['boolean'];

        if ($this->strict) {
            $result[] = 'strict';
        }

        return $result;
    }
}