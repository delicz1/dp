<?php
/**
 * @author necas
 */

namespace Proj\Base\Dql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\InputParameter;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class IsEqual
 *
 * IsNullFunction ::=
 *     "ISEQUAL(" StringPrimary ", " StringPrimary ")"
 *
 * @package Proj\Base\Dql
 */
class IsEqual extends FunctionNode {

    /**
     * @var InputParameter
     */
    private $a;

    /**
     * @var InputParameter
     */
    private $b;

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser) {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->a = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->b = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker) {
        return '(' . $this->a->dispatch($sqlWalker).' = ' . $this->b->dispatch($sqlWalker) . ')';
    }

}