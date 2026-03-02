<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 除非另一个正在验证的字段等于指定的值，否则验证中的字段必须存在且不为空
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class RequiredUnless extends AbstractRuleAnnotation
{
    /**
     * @param string $anotherField 另一个字段
     * @param mixed $value 值
     *
     * @author Verdient。
     */
    public function __construct(
        protected string $anotherField,
        protected mixed $value
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['required_unless', $this->anotherField, $this->value];
    }
}