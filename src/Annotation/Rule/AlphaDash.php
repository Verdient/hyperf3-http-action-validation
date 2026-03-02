<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证字段可以包含字母(包含中文)和数字，以及破折号和下划线。为了将此验证规则限制在 ASCII 范围内的字符（a-z 和 A-Z），你可以为验证规则提供 ascii 选项
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class AlphaDash extends AbstractRuleAnnotation
{
    /**
     * @param string $mode 模式
     *
     * @author Verdient。
     */
    public function __construct(protected ?string $mode = null) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        $result = ['alpha_dash'];

        if ($this->mode) {
            $result[] = $this->mode;
        }

        return $result;
    }
}
