<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须是数值类型，并且必须包含指定的小数位数
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Decimal extends AbstractRuleAnnotation
{
    /**
     * @param int $min 最小小数位数
     * @param int|null $max 最大小数位数
     *
     * @author Verdient。
     */
    public function __construct(
        protected int $min,
        protected ?int $max = null
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        $result = ['decimal', (string) $this->min];

        if ($this->max !== null) {
            $result[] = (string) $this->max;
        }

        return $result;
    }
}