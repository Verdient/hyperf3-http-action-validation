<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;
use UnitEnum;

/**
 * 验证的字段不能以给定值之一开头
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class DoesntStartWith extends AbstractRuleAnnotation
{
    /**
     * @param array|class-string<UnitEnum|UnitEnum> $values 值列表
     *
     * @author Verdient。
     */
    public function __construct(protected array|string $values) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        if (is_string($this->values)) {
            $values = $this->values::cases();
        } else {
            $values = $this->values;
        }

        return ['doesnt_start_with', ...array_values($values)];
    }
}
