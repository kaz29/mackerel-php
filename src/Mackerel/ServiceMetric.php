<?php
namespace Mackerel;

/**
 * Class ServiceMetric
 * @package Mackerel
 */
class ServiceMetric
{
    public $name = null;
    public $epock = null;
    public $value = null;

    /**
     * ServiceMetric constructor.
     * @param string $name
     * @param int $epock
     * @param int $value
     */
    public function __construct(string $name, int $epock, int $value)
    {
        $this->name = $name;
        $this->epock = $epock;
        $this->value = $value;
    }
}