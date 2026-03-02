<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证的字段必须是一个数组，并且必须至少包含指定的键
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class RequiredArrayKeys extends AbstractRuleAnnotation
{
    /**
     * @param array $keys 需要包含的键
     *
     * @author Verdient。
     */
    public function __construct(
        protected array $keys
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['required_array_keys', ...array_values($this->keys)];
    }
}
