<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须匹配指定格式，可以使用 PHP 函数 date 或 date_format 验证该字段
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class DateFormat extends AbstractRuleAnnotation
{
    /**
     * @param string $format 日期格式
     *
     * @author Verdient。
     */
    public function __construct(protected string $format) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['date_format', $this->format];
    }
}
