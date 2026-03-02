<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 如果 anotherfield 等于 value ，validate 和 validated 方法中会排除掉当前验证的字段
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class ExcludeIf extends AbstractRuleAnnotation
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
        return ['exclude_if', $this->anotherField, $this->value];
    }
}
