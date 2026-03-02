<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段必须具有与给定值匹配的大小，对于字符串，value 对应字符数；对于数字，value 对应给定的整数值；对于数组，value 对应数组的 count 值；对于文件，value 对应文件大小（以KB为单位）
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Size extends AbstractRuleAnnotation
{
    /**
     * @param int $value 大小
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
        return ['size', (string) $this->value];
    }
}
