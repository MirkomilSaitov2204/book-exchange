<?php

namespace App\Interfaces;

interface RequestInterface
{
    public function query();

    public function get($query);

    public function store(\App\Http\Requests\BookRequestedRequest $request);
}
