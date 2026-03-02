<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation;

use Attribute;
use Verdient\Hyperf3\HttpAction\Validation\ObjectInputParameterInterface;

/**
 * 类型
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Type
{
    /**
     * 字符串
     *
     * @author Verdient。
     */
    const STRING = 'string';

    /**
     * 整型
     *
     * @author Verdient。
     */
    const INT = 'int';

    /**
     * 浮点型
     *
     * @author Verdient。
     */
    const FLOAT = 'float';

    /**
     * 布尔型
     *
     * @author Verdient。
     */
    const BOOL = 'bool';

    /**
     * 数组
     *
     * @author Verdient。
     */
    const ARRAY = 'array';

    /**
     * 真值
     *
     * @author Verdient。
     */
    const TRUE = 'true';

    /**
     * 假值
     *
     * @author Verdient。
     */
    const FALSE = 'false';

    /**
     * 空
     *
     * @author Verdient。
     */
    const NULL = 'null';

    /**
     * @param string|class-string<ObjectInputParameterInterface> $type 类型
     *
     * string | int | float | bool | array | true | false | null | ObjectInputParameterInterface
     *
     * @author Verdient。
     */
    public function __construct(public readonly string $type) {}
}
