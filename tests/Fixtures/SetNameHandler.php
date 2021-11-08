<?php

namespace PipePassage\Tests\Fixtures;

use PipePassage\PipeSection;

class SetNameHandler implements PipeSection
{
    protected string $name;

    /**
     * @param string $name
     */
    public function __construct(string $name = 'default')
    {
        $this->name = $name;
    }

    public function handle($entity, \Closure $next)
    {
        $entity->name = $this->name;

        return $next($entity);
    }
}
