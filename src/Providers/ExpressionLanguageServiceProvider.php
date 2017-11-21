<?php
namespace Wuwx\LaravelExpressionLanguage\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

class ExpressionLanguageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('expressionLanguage', function () {
            $expressionLanguage = new ExpressionLanguage();
        });
    }
}
