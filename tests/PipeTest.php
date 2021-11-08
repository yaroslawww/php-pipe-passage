<?php

namespace PipePassage\Tests;

use PipePassage\Pipe;
use PipePassage\Tests\Fixtures\SetCompanyHandler;
use PipePassage\Tests\Fixtures\SetNameHandler;

class PipeTest extends TestCase
{

    /** @test */
    public function pipe_passage()
    {
        $pipe = Pipe::make([
            function ($entity, \Closure $next) {
                $entity->test = 'test value';

                return $next($entity);
            },
            function ($entity, \Closure $next) {
                $entity->test2 = 'second test value';

                return $next($entity);
            },
        ])
                    ->next(SetNameHandler::class)
                    ->next(new SetCompanyHandler('web company'))
                    ->next(function ($entity, \Closure $next) {
                        $entity->test3 = 'third test value';

                        return $next($entity);
                    });

        $entityObject = $pipe->pass(new \stdClass());
        $this->assertEquals('test value', $entityObject->test);
        $this->assertEquals('second test value', $entityObject->test2);
        $this->assertEquals('third test value', $entityObject->test3);
        $this->assertEquals('default', $entityObject->name);
        $this->assertEquals('web company', $entityObject->company);
    }

    /** @test */
    public function pipe_empty_sections()
    {
        $entity       = new \stdClass();
        $entity->foo  = 'bar';
        $entityObject = Pipe::make()->pass($entity);
        $this->assertEquals('bar', $entityObject->foo);
    }
}
