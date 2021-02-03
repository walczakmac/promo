<?php

namespace App\Application;

interface Handler
{
    /**
     * @param array<mixed> $data
     * @return mixed
     */
    public function handle(array $data);
}
