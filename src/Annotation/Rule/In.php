<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;
use UnitEnum;

/**
 * 验证字段值必须在给定的列表中
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class In extends AbstractRuleAnnotation
{
    /**
     * @param array|class-string<UnitEnum> $values 值列表
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

        return ['in', ...array_values($values)];
    }
}
