<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 如果所有其他指定的字段都存在，则验证的字段必须不存在
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class MissingWithAll extends AbstractRuleAnnotation
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
        return ['missing_with_all', ...array_values($this->fields)];
    }
}