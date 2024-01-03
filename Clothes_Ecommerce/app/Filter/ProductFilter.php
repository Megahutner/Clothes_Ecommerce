<?php

namespace App\Filter;

use Illuminate\Http\Request;
use App\Filter\ApiFilter;

class ProductFilter extends ApiFilter{
    protected $safeParms = [
        'name'=>['eq'],
        
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