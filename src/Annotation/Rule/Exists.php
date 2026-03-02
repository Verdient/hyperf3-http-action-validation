<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段值在数据库中存在
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Exists extends AbstractRuleAnnotation
{
    /**
     * @param string $table 表名
     * @param string $column 字段名
     *
     * @author Verdient。
     */
    public function __construct(
        protected string $table,
        protected string $column = 'id'
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['exists', $this->table, $this->column];
    }
}