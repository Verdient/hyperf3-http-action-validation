<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;
use UnitEnum;

/**
 * 验证字段必须以某个给定值开头
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class StartsWith extends AbstractRuleAnnotation
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

        return ['starts_with', ...array_values($values)];
    }
}
