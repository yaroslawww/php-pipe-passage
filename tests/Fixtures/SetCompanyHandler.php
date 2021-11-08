<?php

namespace PipePassage\Tests\Fixtures;

use PipePassage\PipeSection;

class SetCompanyHandler implements PipeSection
{
    protected string $company;

    /**
     * @param string $company
     */
    public function __construct(string $company = 'default')
    {
        $this->company = $company;
    }

    public function handle($entity, \Closure $next)
    {
        $entity->company = $this->company;

        return $next($entity);
    }
}
