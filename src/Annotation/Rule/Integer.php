<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须是整型
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Integer extends AbstractRuleAnnotation
{
    /**
     * @param bool $strict 严格模式
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
        $result = ['integer'];

        if ($this->strict) {
            $result[] = 'strict';
        }

        return $result;
    }
}