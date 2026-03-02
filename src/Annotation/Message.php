<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation;

use Attribute;

/**
 * 错误信息
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Message
{
    /**
     * @param string $ruleName 规则名称
     * @param string $message 错误信息
     *
     * @author Verdient。
     */
    public function __construct(
        public readonly string $ruleName,
        public readonly string $message
    ) {}
}
