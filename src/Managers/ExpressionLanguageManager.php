<?php
namespace Wuwx\LaravelExpressionLanguage\Managers;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

class ExpressionLanguageManager
{
    private $expressionLanguage;

    public function __construct()
    {
        $this->expressionLanguage = new ExpressionLanguage();
    }

    public function evaluate($expression, $values = array())
    {
        try {
            return $this->expressionLanguage->evaluate($expression, $values);
        } catch (SyntaxError $error) {
        }
    }
}
