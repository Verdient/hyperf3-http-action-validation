<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 给定字段必须与验证字段匹配
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Same extends AbstractRuleAnnotation
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
        return ['same', $this->field];
    }
}
