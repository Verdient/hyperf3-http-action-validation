<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule;

/**
 * 规则注解接口
 *
 * @author Verdient。
 */
interface RuleAnnotationInterface
{
    /**
     * 将注解转换为规则
     *
     * @author Verdient。
     */
    public function toRule(): array;
}
