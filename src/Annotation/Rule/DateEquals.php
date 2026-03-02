<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须等于给定日期，日期会被传递到 PHP strtotime 函数
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class DateEquals extends AbstractRuleAnnotation
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
        return ['date_equals', $this->date];
    }
}