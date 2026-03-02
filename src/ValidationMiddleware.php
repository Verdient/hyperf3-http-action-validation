<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\HttpAction\Validation;

use BackedEnum;
use FastRoute\Dispatcher;
use Hyperf\Collection\Arr;
use Hyperf\Context\Context;
use Hyperf\Context\ResponseContext;
use Hyperf\Contract\ValidatorInterface;
use Hyperf\Di\ReflectionManager;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Server\Exception\ServerException;
use Hyperf\Stringable\Str;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Middleware\ValidationMiddleware as MiddlewareValidationMiddleware;
use Hyperf\Validation\ValidationException;
use Override;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnitEnum;
use Verdient\Hyperf3\HttpAction\ActionAttribute;
use Verdient\Hyperf3\HttpAction\ActionInterface;
use Verdient\Hyperf3\HttpAction\AttributeManager;
use Verdient\Hyperf3\HttpAction\CollectionInputParameterInterface;
use Verdient\Hyperf3\HttpAction\CollectionTypeManager;
use Verdient\Hyperf3\HttpAction\ObjectInputParameterInterface;
use Verdient\Hyperf3\HttpAction\Validation\Annotation\InputParam\BodyParam;
use Verdient\Hyperf3\HttpAction\Validation\Annotation\InputParam\ParamSourceInterface;
use Verdient\Hyperf3\HttpAction\Validation\Annotation\InputParam\QueryParam;
use Verdient\Hyperf3\HttpAction\Validation\Annotation\Message;
use Verdient\Hyperf3\HttpAction\Validation\Annotation\Name;
use Verdient\Hyperf3\HttpAction\Validation\Annotation\Rule\RuleAnnotationInterface;
use Verdient\Hyperf3\Validation\ValidatedData;

/**
 * 验证中间件
 *
 * @author Verdient。
 */
class ValidationMiddleware implements MiddlewareInterface
{
    /**
     * 内建的规则映射
     *
     * @author Verdient。
     */
    const BUILT_IN_RULE_MAP = [
        'string' => [['string']],
        'int' => [['integer']],
        'float' => [['numeric']],
        'bool' => [['boolean']],
        'array' => [['array']],
        'true' => [['in', true, 1, '1']],
        'false' => [['in', false, 0, '0']],
        'null' => [['prohibited']]
    ];

    /**
     * 缓存的验证参数
     *
     * @author Verdient。
     */
    protected array $cachedValidationParams = [];

    /**
     * 认证中间件
     *
     * @author Verdient。
     */
    protected MiddlewareValidationMiddleware $middleware;

    /**
     * 构造函数
     *
     * @param ContainerInterface $container 容器
     *
     * @author Verdient。
     */
    public function __construct(protected ContainerInterface $container)
    {
        $this->middleware = $this->container->get(MiddlewareValidationMiddleware::class);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        Context::set(ServerRequestInterface::class, $request);

        if (!$actionClass = $this->getActionClass($request)) {
            return $this->middleware->process($request, $handler);
        }

        $validator = $this->newValidator($request, $actionClass);

        if ($validator->fails()) {
            throw new ValidationException($validator, ResponseContext::get()->withStatus(422));
        }

        Context::set(ValidatedData::class, $validator->validated());

        return $handler->handle($request);
    }

    /**
     * 获取派发对象
     *
     * @param ServerRequestInterface $request 请求对象
     *
     * @author Verdient。
     */
    protected function getDispached(ServerRequestInterface $request): Dispatched
    {
        $dispatched = $request->getAttribute(Dispatched::class);

        if (!$dispatched instanceof Dispatched) {
            throw new ServerException(sprintf('The dispatched object is not a %s object.', Dispatched::class));
        }

        return $dispatched;
    }

    /**
     * 获取动作类
     *
     * @param ServerRequestInterface $request 请求对象
     *
     * @return ?class-string<ActionInterface>
     *
     * @author Verdient。
     */
    protected function getActionClass(ServerRequestInterface $request): ?string
    {
        $dispatched = $this->getDispached($request);

        if (
            $dispatched->status === Dispatcher::FOUND
            && is_array($dispatched->handler->callback)
            && is_subclass_of($dispatched->handler->callback[0], ActionInterface::class)
        ) {
            return $dispatched->handler->callback[0];
        }

        return null;
    }

    /**
     * 移除不必要的参数
     *
     * @param array $params 参数
     * @param array $paramKeys 键名
     *
     * @author Verdient。
     */
    protected function removeUnnecessaryParams(array &$params, array $paramKeys): void
    {
        $patterns = array_map(function ($key) {
            $escaped = preg_quote($key, '#');
            return '#^' . str_replace('\*', '[^.]+', $escaped) . '(\..*)?$#';
        }, $paramKeys);

        foreach ($params as $key => $value) {
            $matched = false;
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $key)) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                unset($params[$key]);
            }
        }
    }

    /**
     * 格式化规则值
     *
     * @param mixed $value 待格式化的值
     *
     * @author Verdient。
     */
    protected function normalizeRuleValue(mixed $value): mixed
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->normalizeRuleValue($item);
            }
        } else {
            if ($value instanceof BackedEnum) {
                $value = (string) $value->value;
            } else if ($value instanceof UnitEnum) {
                $value = $value->name;
            } else {
                $value = (string) $value;
            }
        }

        return $value;
    }

    /**
     * 格式化规则
     *
     * @param array $value 待格式化的值
     *
     * @author Verdient。
     */
    protected function normalizeRule(array $value): array
    {
        foreach ($value as $key => $item) {
            $value[$key] = $this->normalizeRuleValue($item);
        }

        return $value;
    }

    /**
     * 获取基础验证规则
     *
     * @param ActionAttribute $attribute 动作属性
     *
     * @author Verdient。
     */
    protected function getBaseRules(ActionAttribute $attribute): array
    {
        if ($attribute->allowsNull || $attribute->hasDefault) {
            return [$attribute->name => [['nullable']]];
        }
        return [$attribute->name => [['required']]];
    }

    /**
     * 获取内置的验证规则
     *
     * @param ActionAttribute $attribute 动作属性
     *
     * @author Verdient。
     */
    protected function getBuiltInRules(ActionAttribute $attribute): array
    {
        if (!isset(static::BUILT_IN_RULE_MAP[$attribute->type])) {
            return [];
        }

        return [
            $attribute->name => static::BUILT_IN_RULE_MAP[$attribute->type]
        ];
    }

    /**
     * 获取枚举的验证规则
     *
     * @param ActionAttribute $attribute 动作属性
     *
     * @author Verdient。
     */
    protected function getBackedEnumRules(ActionAttribute $attribute): array
    {
        if (!is_subclass_of($attribute->type, BackedEnum::class)) {
            return [];
        }

        $enumClass = $attribute->type;

        $cases = $enumClass::cases();

        if (empty($cases)) {
            return [];
        }

        $isInt = is_int($cases[0]->value);

        $values = [];

        foreach ($cases as $case) {
            $values[] = (string) $case->value;
        }

        $values = array_values(array_unique($values));

        return [
            $attribute->name => [$isInt ? ['integer'] : ['string'], ['in', ...$values]]
        ];
    }

    /**
     * 获取对象输入参数的验证规则
     *
     * @param ActionAttribute $attribute 动作属性
     *
     * @author Verdient。
     */
    protected function getObjectInputParameterRules(ActionAttribute $attribute): array
    {
        if (!is_subclass_of($attribute->type, ObjectInputParameterInterface::class)) {
            return [];
        }

        $result = [
            $attribute->name => [['array']]
        ];

        foreach ($this->getRules($attribute->type, $attribute->name . '.') as $ruleName => $attributeRules) {
            if (!isset($result[$ruleName])) {
                $result[$ruleName] = [];
            }
            foreach ($attributeRules as $attributeRule) {
                $result[$ruleName][] = $attributeRule;
            }
        }

        return $result;
    }

    /**
     * 获取集合输入参数的验证规则
     *
     * @param ActionAttribute $attribute 动作属性
     *
     * @author Verdient。
     */
    protected function getCollectionInputParameterRules(ActionAttribute $attribute): array
    {
        if (!is_subclass_of($attribute->type, CollectionInputParameterInterface::class)) {
            return [];
        }

        $result = [
            $attribute->name => [['array']]
        ];

        $elementRules = [];

        foreach (ReflectionManager::reflectClass($attribute->type)->getAttributes() as $reflectionAttribute) {
            if (is_subclass_of($reflectionAttribute->getName(), RuleAnnotationInterface::class)) {
                $elementRules[] = $reflectionAttribute->newInstance()->toRule();
            }
        }

        $collectionType = CollectionTypeManager::get($attribute->type)->type;

        if (isset(static::BUILT_IN_RULE_MAP[$collectionType])) {
            $result[$attribute->name . '.*'] = [['required'], ...$elementRules, ...static::BUILT_IN_RULE_MAP[$collectionType]];
        } else if (is_subclass_of($collectionType, BackedEnum::class)) {
            $result[$attribute->name . '.*'] = [['required'], ...$elementRules];
            foreach (
                $this->getBackedEnumRules(new ActionAttribute(
                    name: $attribute->name . '.*',
                    type: $collectionType,
                    allowsNull: false,
                    hasDefault: false,
                    defaultValue: null,
                    annotations: []
                )) as $ruleName => $backedEnumRules
            ) {
                $result[$ruleName] = [['required'], ...$elementRules, ...$backedEnumRules];
            }
        } else {
            $result[$attribute->name . '.*'] = [['array'], ...$elementRules];

            foreach ($this->getRules($collectionType, $attribute->name . '.*.') as $ruleName => $attributeRules) {
                if (!isset($result[$ruleName])) {
                    $result[$ruleName] = [];
                }
                foreach ($attributeRules as $attributeRule) {
                    $result[$ruleName][] = $attributeRule;
                }
            }
        }

        return $result;
    }

    /**
     * 获取上传文件的验证规则
     *
     * @param ActionAttribute $attribute 动作属性
     *
     * @author Verdient。
     */
    protected function getUploadedFileRules(ActionAttribute $attribute): array
    {
        if ($attribute->type !== UploadedFile::class && !is_subclass_of($attribute->type, UploadedFile::class)) {
            return [];
        }

        return [$attribute->name => ['file']];
    }

    /**
     * 获取注解验证规则
     *
     * @param ActionAttribute $attribute 动作属性
     *
     * @author Verdient。
     */
    protected function getAnnotationRules(ActionAttribute $attribute): array
    {
        $attributeRules = [];

        foreach ($attribute->annotations as $annotation) {
            if ($annotation instanceof RuleAnnotationInterface) {
                $rule = $this->normalizeRule($annotation->toRule());
                $attributeRules[] = $rule;
            }
        }

        return [$attribute->name => $attributeRules];
    }

    /**
     * 对规则进行去重
     *
     * @param array $rules 待去重的规则
     *
     * @author Verdient。
     */
    protected function uniqueRules(array $rules): array
    {
        $result = [];

        foreach ($rules as $rule) {
            $result[$rule[0]] = $rule;
        }

        return array_values($result);
    }

    /**
     * 获取验证规则
     *
     * @param class-string<InputParameterInterface> $class 类名
     * @param ?string $parentName 父级元素名称
     *
     * @author Verdient。
     */
    protected function getRules(string $class, ?string $parentName = null): array
    {
        $result = [];

        foreach (AttributeManager::get($class) as $attribute) {
            foreach (
                [
                    $this->getBaseRules($attribute),
                    $this->getBuiltInRules($attribute),
                    $this->getBackedEnumRules($attribute),
                    $this->getObjectInputParameterRules($attribute),
                    $this->getCollectionInputParameterRules($attribute),
                    $this->getUploadedFileRules($attribute),
                    $this->getAnnotationRules($attribute)
                ] as $partRules
            ) {
                foreach ($partRules as $name => $attributeRules) {
                    $ruleName = $parentName ? $parentName . $name : $name;

                    if (!isset($result[$ruleName])) {
                        $result[$ruleName] = [];
                    }

                    foreach ($attributeRules as $attributeRule) {
                        $result[$ruleName][] = $attributeRule;
                    }
                }
            }
        }

        foreach ($result as $ruleName => $attributeRules) {
            $result[$ruleName] = $this->uniqueRules($attributeRules);
        }

        return $result;
    }

    /**
     * 获取提示消息
     *
     * @param string $class 类名
     * @param ?string $parentName 父级元素名称
     *
     * @author Verdient。
     */
    protected function getMessages(string $class, ?string $parentName = null): array
    {
        $result = [];

        foreach (AttributeManager::get($class) as $attribute) {
            $attributeName = $parentName ? $parentName . $attribute->name : $attribute->name;

            foreach ($attribute->annotations as $annotation) {
                if ($annotation instanceof Message) {
                    $result[$attributeName . '.' . $annotation->ruleName] = $annotation->message;
                }
            }

            if (is_subclass_of($attribute->type, ObjectInputParameterInterface::class)) {
                foreach ($this->getMessages($attribute->type, $attributeName . '.') as $name => $message) {
                    $result[$name] = $message;
                }
            }

            if (is_subclass_of($attribute->type, CollectionInputParameterInterface::class)) {
                $type = CollectionTypeManager::get($attribute->type)->type;

                if (!isset(static::BUILT_IN_RULE_MAP[$type])) {
                    foreach ($this->getMessages($type, $attributeName . '.*.') as $name => $message) {
                        $result[$name] = $message;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 获取属性名称
     *
     * @param string $class 类名
     * @param ?string $parentName 父级元素名称
     *
     * @author Verdient。
     */
    protected function getAttributes(string $class, ?string $parentName = null): array
    {
        $result = [];

        foreach (AttributeManager::get($class) as $attribute) {
            $attributeName = $parentName ? $parentName . $attribute->name : $attribute->name;

            foreach ($attribute->annotations as $annotation) {
                if ($annotation instanceof Name) {
                    $result[$attributeName] = $annotation->name;
                }
            }

            if (is_subclass_of($attribute->type, ObjectInputParameterInterface::class)) {
                foreach ($this->getAttributes($attribute->type, $attributeName . '.') as $name => $message) {
                    $result[$name] = $message;
                }
            }

            if (is_subclass_of($attribute->type, CollectionInputParameterInterface::class)) {
                $type = CollectionTypeManager::get($attribute->type)->type;

                if (!isset(static::BUILT_IN_RULE_MAP[$type])) {
                    foreach ($this->getAttributes($type, $attributeName . '.*.') as $name => $message) {
                        $result[$name] = $message;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 获取属性的数据源
     *
     * @param ActionAttribute $attribute 动作属性
     * @param ?string $prefix 前缀
     * @param ?string $inheritedSource 继承的来源
     *
     * @author Verdient。
     */
    protected function getAttributeSources(
        ActionAttribute $attribute,
        string $prefix = '',
        ?string $inheritedSource = null
    ): array {
        $result = [];
        $name = $prefix . $attribute->name;

        $currentSource = null;
        foreach ($attribute->annotations as $annotation) {
            if ($annotation instanceof ParamSourceInterface) {
                $currentSource = $annotation::class;
                break;
            }
        }

        $effectiveSource = $currentSource ?? $inheritedSource ?? '';

        $result[$effectiveSource][] = $name;

        $subPrefix = $name . '.';

        if (is_subclass_of($attribute->type, ObjectInputParameterInterface::class)) {
            foreach (AttributeManager::get($attribute->type) as $attribute2) {
                $subResult = $this->getAttributeSources($attribute2, $subPrefix, $effectiveSource);
                foreach ($subResult as $key => $value) {
                    $result[$key] = array_merge($result[$key] ?? [], $value);
                }
            }
        } else if (is_subclass_of($attribute->type, CollectionInputParameterInterface::class)) {
            $collectionType = CollectionTypeManager::get($attribute->type)->type;
            $subPrefix .= '*.';

            if (is_subclass_of($collectionType, ObjectInputParameterInterface::class)) {
                foreach (AttributeManager::get($collectionType) as $attribute2) {
                    $subResult = $this->getAttributeSources($attribute2, $subPrefix, $effectiveSource);
                    foreach ($subResult as $key => $value) {
                        $result[$key] = array_merge($result[$key] ?? [], $value);
                    }
                }
            } else {
                $result[$effectiveSource][] = $name . '.*';
            }
        }

        return $result;
    }

    /**
     * 获取数据源
     *
     * @param class-string<ActionInterface> $actionClass 动作类
     *
     * @author Verdient。
     */
    protected function getSources(string $actionClass): array
    {
        $result = [];

        foreach (AttributeManager::get($actionClass) as $actionAttribute) {
            foreach ($this->getAttributeSources($actionAttribute) as $key => $value) {
                $result[$key] = array_merge($result[$key] ?? [], $value);
            }
        }

        return $result;
    }

    /**
     * 处理验证参数
     *
     * @param class-string<ActionInterface> $actionClass 动作类
     *
     * @author Verdient。
     */
    protected function resolveValidationParams(string $actionClass): ValidationParams
    {
        if (!isset($this->cachedValidationParams[$actionClass])) {
            $validationParams = new ValidationParams(
                rules: $this->getRules($actionClass),
                messages: $this->getMessages($actionClass),
                attributes: $this->getAttributes($actionClass),
                sources: $this->getSources($actionClass)
            );
            $this->cachedValidationParams[$actionClass] = $validationParams;
        }

        return $this->cachedValidationParams[$actionClass];
    }

    /**
     * 格式化输入的数据
     *
     * @param array $data 待格式化的数据
     *
     * @author Verdient。
     */
    protected function normalizeInputData(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_string($key) && str_contains($key, '_')) {
                $result[Str::camel($key)] = is_array($value) ? $this->normalizeInputData($value) : $value;
            } else {
                if (!array_key_exists($key, $result)) {
                    $result[$key] = is_array($value) ? $this->normalizeInputData($value) : $value;
                }
            }
        }

        return $result;
    }

    /**
     * 获取用于验证的数据
     *
     * @param ServerRequestInterface $request 请求对象
     * @param ValidationParams $validationParams 校验参数
     *
     * @author Verdient。
     */
    protected function validationData(ServerRequestInterface $request, ValidationParams $validationParams): array
    {
        $sources = $validationParams->sources;

        if (isset($sources[''])) {
            $class = match ($request->getMethod()) {
                'GET' => QueryParam::class,
                'HEAD' => QueryParam::class,
                'POST' => BodyParam::class,
                'PUT' => BodyParam::class,
                'PATCH' => BodyParam::class,
                'DELETE' => BodyParam::class,
                'OPTIONS' => BodyParam::class
            };

            if (isset($sources[$class])) {
                $sources[$class] = array_merge($sources[$class], $sources['']);
            } else {
                $sources[$class] = $sources[''];
            }

            unset($sources['']);
        }

        $ruleKeys = array_keys($validationParams->rules);

        if (!empty($sources[QueryParam::class])) {
            $queryParams = Arr::dot($this->normalizeInputData($request->getQueryParams()));
            $this->removeUnnecessaryParams($queryParams, array_intersect($ruleKeys, $sources[QueryParam::class]));
        } else {
            $queryParams = [];
        }

        if (!empty($sources[BodyParam::class])) {
            $bodyParams = array_merge(
                Arr::dot($this->normalizeInputData($request->getParsedBody())),
                Arr::dot($this->normalizeInputData($request->getUploadedFiles()))
            );
            $this->removeUnnecessaryParams($bodyParams, array_intersect($ruleKeys, $sources[BodyParam::class]));
        } else {
            $bodyParams = [];
        }

        return Arr::undot(array_merge($this->getDispached($request)->params, $queryParams, $bodyParams));
    }

    /**
     * 创建新的验证器
     *
     * @param ServerRequestInterface $request 请求对象
     * @param class-string<ActionInterface> $actionClass 动作类
     *
     * @author Verdient。
     */
    protected function newValidator(ServerRequestInterface $request, string $actionClass): ValidatorInterface
    {
        /** @var ValidatorFactoryInterface */
        $factory = $this->container->get(ValidatorFactoryInterface::class);

        $validationParams = $this->resolveValidationParams($actionClass);

        $validator = $factory->make(
            $this->validationData($request, $validationParams),
            $validationParams->rules,
            $validationParams->messages,
            $validationParams->attributes
        );

        return $validator;
    }
}
