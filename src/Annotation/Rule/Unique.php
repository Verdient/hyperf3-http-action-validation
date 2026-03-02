<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段在给定数据表上必须是唯一的
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Unique extends AbstractRuleAnnotation
{
    /**
     * @param string $table 表名
     * @param string $column 字段名
     * @param mixed $except 排除值
     * @param string $idColumn ID字段名
     *
     * @author Verdient。
     */
    public function __construct(
        protected string $table,
        protected string $column = 'id',
        protected mixed $except = null,
        protected string $idColumn = 'id'
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        $result = ['unique', $this->table, $this->column];

        if ($this->except !== null) {
            $result[] = (string) $this->except;
            $result[] = $this->idColumn;
        }

        return $result;
    }
}