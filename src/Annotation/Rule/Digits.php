<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须是数字且长度必须是指定值
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Digits extends AbstractRuleAnnotation
{
    /**
     * @param int $value 位数
     *
     * @author Verdient。
     */
    public function __construct(protected int $value) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['digits', (string) $this->value];
    }
}