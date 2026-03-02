<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\InputParam;

use Attribute;

/**
 * 消息体参数
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class BodyParam implements ParamSourceInterface {}
