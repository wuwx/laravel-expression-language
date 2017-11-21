<?php

namespace Wuwx\LaravelExpressionLanguage\Facades;

use Illuminate\Support\Facades\Facade;

class ExpressionLanguageFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'expressionLanguage';
    }
}
