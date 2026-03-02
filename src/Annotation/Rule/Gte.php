<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须大于等于给定 field 字段
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Gte extends AbstractRuleAnnotation
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
        return ['gte', $this->field];
    }
}