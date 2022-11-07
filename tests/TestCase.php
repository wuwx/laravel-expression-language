<?php

namespace Wuwx\LaravelExpressionLanguage\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Wuwx\LaravelExpressionLanguage\Providers\ExpressionLanguageServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ExpressionLanguageServiceProvider::class,
        ];
    }
}