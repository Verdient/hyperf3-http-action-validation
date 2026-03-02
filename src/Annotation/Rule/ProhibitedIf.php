<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 如果 anotherfield 字段等于任何 value，则需要验证的字段必须不存在或为空。如果符合以下条件之一，字段将被认为是 "空"：
 * 值为 null。
 * 值为空字符串。
 * 值为空数组或空的可计数对象。
 * 值为上传文件，但文件路径为空。
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class ProhibitedIf extends AbstractRuleAnnotation
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
        return ['prohibited_if', $this->anotherField, $this->values];
    }
}
