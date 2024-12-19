<?php

namespace App\Interfaces;

use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;

interface BookInterface
{
    public function query();

    public function filter($builder, Request $request);

    public function store(BookRequest $request);

    public function update($book, $request);

    public function find(int $id);

    public function paginate($builder, $paginate = 10);
}
