<?php

namespace SaKanjo\EasyMetrics;

use ArrayAccess;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class Result implements ArrayAccess, Responsable
{
    public array $container;

    public function __construct(
        protected array $data,
        protected array $labels,
        protected null|float|array $growthRate
    ) {
        $this->container = [$labels, $data, $growthRate];
    }

    public static function make(array $data, array $labels, null|float|array $growthRate): static
    {
        return App::make(static::class, [
            'data' => $data,
            'labels' => $labels,
            'growthRate' => $growthRate,
        ]);
    }

    public function getOptions(): array
    {
        return array_combine($this->labels, $this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getGrowthRate(): null|float|array
    {
        return $this->growthRate;
    }

    public function toResponse($request): Response
    {
        return new Response(
            $this->getData()
        );
    }

    public function offsetSet($offset, $value): void
    {
        throw new \Exception('Result is immutable');
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset): void
    {
        throw new \Exception('Result is immutable');
    }

    public function offsetGet($offset): mixed
    {
        return $this->container[$offset] ?? null;
    }
}
