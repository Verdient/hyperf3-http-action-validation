<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须是给定日期之后的一个值，日期将会通过 PHP 函数 strtotime 传递
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class After extends AbstractRuleAnnotation
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
        return ['after', $this->date];
    }
}
