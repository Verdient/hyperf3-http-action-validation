<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation;

use Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule\Max;
use Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule\Min;

/**
 * 分页参数
 *
 * @author Verdient。
 */
trait PaginationParams
{
    /**
     * 页码
     *
     * @author Verdient。
     */
    #[Min(0)]
    protected ?int $page;

    /**
     * 分页大小
     *
     * @author Verdient。
     */
    #[Min(0), Max(5000)]
    protected ?int $pageSize;
}
