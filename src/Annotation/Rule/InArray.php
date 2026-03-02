<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须在另一个字段值中存在
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class InArray extends AbstractRuleAnnotation
{
    /**
     * @param string $field 需要比较的字段
     *
     * @author Verdient。
     */
    public function __construct(protected string $field) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['in_array', $this->field];
    }
}