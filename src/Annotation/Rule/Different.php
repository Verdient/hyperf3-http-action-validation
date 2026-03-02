<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须是一个和指定字段不同的值
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Different extends AbstractRuleAnnotation
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
        return ['different', $this->field];
    }
}