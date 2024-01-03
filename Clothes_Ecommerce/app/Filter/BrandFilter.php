<?php

namespace App\Filter;

use Illuminate\Http\Request;
use App\Filter\ApiFilter;

class BrandFilter extends ApiFilter{
    protected $safeParms = [
        'name'=>['eq'],
        'email'=>['eq'],
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