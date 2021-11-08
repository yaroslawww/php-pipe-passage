<?php

namespace PipePassage;

interface PipeSection
{
    public function handle($entity, \Closure $next);
}
