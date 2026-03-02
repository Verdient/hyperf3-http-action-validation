<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation;

use Attribute;

/**
 * 属性
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Name
{
    /**
     * @param string $name 属性名称
     *
     * @author Verdient。
     */
    public function __construct(public readonly string $name) {}
}
