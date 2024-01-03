<?php

namespace App\Filter;

use Illuminate\Http\Request;
use App\Filter\ApiFilter;

class CategoryFilter extends ApiFilter{
    protected $safeParms = [
        'name'=>['eq'],
        'id'=>['eq'],
        'address'=>['eq'],
        'city'=>['eq'],
    ];
    protected $columnMap =[

    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
}