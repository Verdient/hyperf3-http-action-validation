<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须是指定日期之前的一个数值，日期将会传递给 PHP strtotime 函数
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Before extends AbstractRuleAnnotation
{
    /**
     * @param string $date 日期
     *
     * @author Verdient。
     */
    public function __construct(protected string $date) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['before', $this->date];
    }
}
