<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation;

use Verdient\Hyperf3\HttpAction\ObjectInputParameterInterface;
use Verdient\Hyperf3\HttpAction\ToArray;

/**
 * 抽象对象输入参数
 *
 * @author Verdient。
 */
abstract class AbstractObjectInputParameter implements ObjectInputParameterInterface
{
    use ToArray;
}
