<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 处理数组时，验证字段不能包含重复值（组内）
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class DistinctInGroup extends AbstractRuleAnnotation
{
    public function __construct(protected bool $global = false) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['distinct_in_group'];
    }
}
