<?php
namespace Wuwx\LaravelExpressionLanguage\Providers;

use Illuminate\Support\ServiceProvider;
use Wuwx\LaravelExpressionLanguage\Managers\ExpressionLanguageManager;

class ExpressionLanguageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('expressionLanguage', function () {
            $expressionLanguage = new ExpressionLanguageManager();
            return $expressionLanguage;
        });
    }
}
