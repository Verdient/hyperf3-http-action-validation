<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation;

/**
 * 验证参数
 *
 * @author Verdient。
 */
class ValidationParams
{
    /**
     * 构造函数
     *
     * @param array $rules 验证规则
     * @param array $messages 错误消息
     * @param array $attributes 属性名称
     * @param array $sources 数据源
     *
     * @author Verdient。
     */
    public function __construct(
        public readonly array $rules,
        public readonly array $messages,
        public readonly array $attributes,
        public readonly array $sources
    ) {}
}
