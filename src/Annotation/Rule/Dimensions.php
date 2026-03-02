<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

use Attribute;
use Override;

/**
 * 验证的图片尺寸必须满足该规定参数指定的约束条件
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Dimensions extends AbstractRuleAnnotation
{
    /**
     * @param array $parameters 参数
     *
     * @author Verdient。
     */
    public function __construct(protected array $parameters = []) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function toRule(): array
    {
        $params = [];
        foreach ($this->parameters as $key => $value) {
            $params[] = $key . '=' . $value;
        }

        return ['dimensions', ...$params];
    }
}