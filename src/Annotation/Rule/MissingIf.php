<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 如果 anotherfield 字段等于任何 value ，则验证的字段必须不存在
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class MissingIf extends AbstractRuleAnnotation
{
    /**
     * @param string $anotherField 另一个字段
     * @param mixed $values 值
     *
     * @author Verdient。
     */
    public function __construct(
        protected string $anotherField,
        protected mixed $values
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['missing_if', $this->anotherField, $this->values];
    }
}