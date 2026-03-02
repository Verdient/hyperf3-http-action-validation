<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须匹配给定正则表达式
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Regex extends AbstractRuleAnnotation
{
    /**
     * @param string $pattern 正则表达式
     *
     * @author Verdient。
     */
    public function __construct(protected string $pattern) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['regex', $this->pattern];
    }
}