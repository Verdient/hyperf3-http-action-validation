<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段大小在给定的最小值和最大值之间，字符串、数字、数组和文件都可以像使用 size 规则一样使用该规则
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Between extends AbstractRuleAnnotation
{
    /**
     * @param int $int 最小值
     * @param int $max 最大值
     *
     * @author Verdient。
     */
    public function __construct(protected int $min, protected int $max) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['between', (string) $this->min, (string) $this->max];
    }
}
