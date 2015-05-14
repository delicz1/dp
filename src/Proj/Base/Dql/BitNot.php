<?php
/**
 *
 */

namespace Proj\Base\Dql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\InputParameter;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class BitNot
 *
 * IsNullFunction ::=
 *     "BITNOT(" ArithmeticPrimary ")"
 *
 * @package Proj\Base\Dql
 */
class BitNot extends FunctionNode {

    /**
     * @var InputParameter
     */
    private $isnull;

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser) {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->isnull = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker) {
        return '~'.$this->isnull->dispatch($sqlWalker);
    }

}