<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation;

use BackedEnum;
use Hyperf\Collection\Collection;
use Hyperf\Contract\Arrayable;
use Override;
use UnitEnum;
use Verdient\Hyperf3\HttpAction\CollectionInputParameterInterface;

use function Hyperf\Support\make;

/**
 * 抽象对象数组输入参数
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends Collection<TKey,TValue>
 * @implements CollectionInputParameterInterface<TKey,TValue>
 *
 * @author Verdient。
 */
abstract class AbstractCollectionInputParameter extends Collection implements CollectionInputParameterInterface
{
    /**
     * @author Verdient。
     */
    #[Override]
    public function toArray(): array
    {
        $result = [];

        foreach ($this->all() as $item) {
            if ($item instanceof Arrayable) {
                $result[] = $item->toArray();
            } else if ($item instanceof BackedEnum) {
                $result[] = $item->value;
            } else if ($item instanceof UnitEnum) {
                $result[] = $item->name;
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public static function create(array $objects): static
    {
        return make(static::class, [$objects]);
    }
}
