<?php

declare(strict_types=1);

namespace App\Doctrine\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\PathExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Date extends FunctionNode
{
    public ?PathExpression $field = null;

    public ?string $alias = null;

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf(
            'DATE_FORMAT(%s, "%%Y-%%m-%%d")',
            $this->field->dispatch($sqlWalker)
        );
    }

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->field = $parser->SingleValuedPathExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
