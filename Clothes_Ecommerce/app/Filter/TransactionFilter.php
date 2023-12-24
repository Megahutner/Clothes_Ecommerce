<?php

namespace App\Filter;

use Illuminate\Http\Request;
use App\Filter\ApiFilter;

class TransactionFilter extends ApiFilter{
    protected $safeParms = [
        'customerId'=>['eq'],
        'total'=>['eq','gt','lt','gte','lte'],
        'status'=>['eq','ne'],
        'payment'=>['eq','ne'],
        'createdDate'=>['eq','gt','lt','gte','lte'],
        'updatedDate'=>['eq','gt','lt','gte','lte'],
    ];
    protected $columnMap =[
        'createdDate'=>'created_at',
        'updatedDate'=>'updated_at',
        'customerId'=>'customer_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
    ];

}