<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 当指定的字段不存在或为空时，验证的字段必须存在且不为空
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class RequiredWithout extends AbstractRuleAnnotation
{
    /**
     * @param array $fields 字段列表
     *
     * @author Verdient。
     */
    public function __construct(protected array $fields) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['required_without', ...array_values($this->fields)];
    }
}
