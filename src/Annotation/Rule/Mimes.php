<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证文件的 MIME 类型必须是该规则列出的扩展类型中的一个
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Mimes extends AbstractRuleAnnotation
{
    /**
     * @param array $extensions 扩展名列表
     *
     * @author Verdient。
     */
    public function __construct(protected array $extensions) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['mimes', ...array_values($this->extensions)];
    }
}