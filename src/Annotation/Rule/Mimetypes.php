<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证文件必须匹配给定的 MIME 文件类型之一
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Mimetypes extends AbstractRuleAnnotation
{
    /**
     * @param array $types MIME类型列表
     *
     * @author Verdient。
     */
    public function __construct(protected array $types) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        return ['mimetypes', ...array_values($this->types)];
    }
}