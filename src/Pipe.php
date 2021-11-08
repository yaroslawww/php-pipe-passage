<?php

namespace PipePassage;

class Pipe
{

    /**
     * Collection of sections.
     *
     * @var array
     */
    protected array $sections = [];

    /**
     * Section key.
     *
     * @var int
     */
    protected int $currentSectionKey = 0;

    /**
     * @param array $sections
     */
    public function __construct(array $sections = [])
    {
        foreach ($sections as $section) {
            $this->next($section);
        }
    }


    /**
     * Static initialisation helper.
     *
     * @return static
     */
    public static function make(...$arguments): static
    {
        return new static(...$arguments);
    }

    /**
     * Add next section to pipe.
     *
     * @param $section
     *
     * @return $this
     */
    public function next($section): static
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * @param object $entity
     *
     * @return object
     */
    public function pass(object $entity): object
    {
        if (empty($this->sections)) {
            return $entity;
        }
        $this->currentSectionKey = -1;

        $next = $this->createCallback();
        $next($entity);

        return $entity;
    }

    /**
     * @return \Closure
     */
    protected function createCallback(): \Closure
    {
        $this->currentSectionKey++;
        $handler = $this->sections[ $this->currentSectionKey ] ?? null;

        return function ($entity) use ($handler) {
            if ($handler instanceof PipeSection) {
                return call_user_func([ $handler, 'handle' ], $entity, $this->createCallback());
            }

            if (is_string($handler) && is_a($handler, PipeSection::class, true)) {
                return call_user_func([ new $handler(), 'handle' ], $entity, $this->createCallback());
            }

            if (is_callable($handler)) {
                return call_user_func($handler, $entity, $this->createCallback());
            }

            return $entity;
        };
    }
}
