<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\InputParam;

use Attribute;

/**
 * 查询参数
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class QueryParam implements ParamSourceInterface {}
