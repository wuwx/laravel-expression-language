<?php

namespace Wuwx\LaravelExpressionLanguage\Test;

class ExpressionLanguageTest extends TestCase
{
    /** @test */
    public function testEvaluate()
    {
        $this->assertEquals(app('expressionLanguage')->evaluate("1+1"), 2);
    }
}