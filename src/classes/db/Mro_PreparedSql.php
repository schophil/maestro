<?php
/**
 * @author Philippe Schottey <pschotte@mac.com>
 * @package db
 * @copyright Copyright (c) 2008, Philippe Schottey
 */

namespace Maestro\db;

use Maestro\Mro_Exception;
use Maestro\util\Mro_DateTime;

/**
 * Prepared SQL class helps creating SQL queries.
 * This class must be initialized with a SQL statement containing place holders.
 * Given a set of parameters this class will than prepare a SQL statement by
 * filling the place holders with the parameters. Based on the type of the
 * parameters correct SQL statements are generated. Example:
 * <code>
 * // initialization
 * $psql = new Mro_PreparedSQL('SELECT * FROM dummy WHERE name = {name}',true,'y-m-d');
 * $psql->prepare($arrayOfValues);
 * </code>
 */
class Mro_PreparedSql
{

    private $sql;            // the base SQL statement with tokens
    private $detectType;    // should the type automaticly be checked
    private $nodes;        // the tokens
    private $dateFormat;    // the format for the dates

    /**
     * Default constructor.
     */
    function __construct($sql, $detectType, $dateFormat)
    {
        $this->sql = $sql;
        $this->detectType = $detectType;
        $this->dateFormat = $dateFormat;
        // find the tokens in the SQL statement
        $this->nodes = $this->parseTokens($sql, true, false);
    }

    /**
     * Prepare a SQL statement with the given values.
     * @param array $values Array of values.
     * @return string The prepared SQL statement.
     * @throws Mro_Exception If an error occurred.
     */
    function prepare(array $values)
    {
        $result = null;
        if (!is_null($this->nodes)) {
            if ($this->detectType) {
                $result = $this->prepareTyped($this->sql, $this->nodes, $values);
            } else {
                $result = $this->prepareUntyped($this->sql, $this->nodes, $values);
            }
        } else {
            throw new Mro_Exception("parse exception occured in [{$this->sql}]");
        }
        return $result;
    }

    private function prepareUntyped($base, $nodes, $values)
    {
        $result = $base;
        $i = count($nodes) - 1;

        while ($i >= 0) {
            $node = $nodes[$i];

            if ($node->isToken()) {

                // processing a token
                if (array_key_exists($node->name, $values)) {
                    $value = "{$values[$node->name]}";
                    $result = substr_replace($result, $value, $node->begin, $node->length);
                }

            } elseif ($node->isClause()) {

                // processing a clause
                $content = $node->content;
                $j = count($node->tokens) - 1;
                while ($j >= 0) {
                    $token = $node->tokens[$j];
                    if ($token->isGuardian && !array_key_exists($token->name, $values)) {
                        // set empty content and break the loop
                        $content = '';
                        break;
                    } elseif ($token->isGuardian && isset($values[$token->name])) {
                        // if the guardian is known, replace it with empty string
                        $content = substr_replace($content, '', $token->begin, $token->length);
                    } elseif (array_key_exists($token->name, $values)) {
                        $value = $values[$node->name];
                        $content = substr_replace($content, $value, $token->begin, $token->length);
                    }
                    --$j;
                }
                // set the clause content in the main result
                $result = substr_replace($result, $content, $node->begin, $node->length);

            }

            --$i;
        }

        return $result;
    }

    private function prepareTyped($base, $nodes, $values)
    {
        $result = $base;
        $i = count($nodes) - 1;
        while ($i >= 0) {
            $node = $nodes[$i];

            if ($node->isToken()) {

                // processing a token
                if (array_key_exists($node->name, $values)) {
                    $value = '';
                    if ($node->ommitType) {
                        $value = $values[$node->name];
                    } else {
                        $value = $this->makeSqlValue($values[$node->name]);
                    }
                    $result = substr_replace($result, $value, $node->begin, $node->length);
                }

            } elseif ($node->isClause()) {

                // processing a clause
                $content = $node->content;
                $j = count($node->tokens) - 1;
                while ($j >= 0) {
                    $token = $node->tokens[$j];
                    if ($token->isGuardian && !array_key_exists($token->name, $values)) {
                        // set empty content and break the loop
                        $content = '';
                        break;
                    } elseif ($token->isGuardian && isset($values[$token->name])) {
                        // if the guardian is known, replace it with empty string
                        $content = substr_replace($content, '', $token->begin, $token->length);
                    } elseif (array_key_exists($token->name, $values)) {
                        $value = '';
                        if ($token->ommitType) {
                            $value = "{$values[$token->name]}";
                        } else {
                            $value = $this->makeSqlValue($values[$token->name]);
                        }
                        $content = substr_replace($content, $value, $token->begin, $token->length);
                    } else {
                        $content = '';
                        break;
                    }
                    --$j;
                }
                // set the clause content in the main result
                $result = substr_replace($result, $content, $node->begin, $node->length);

            }

            --$i;
        }

        return $result;
    }

    private function parseTokens($sql, $allowClauses, $allowGardians)
    {
        $i = 0;
        $max = strlen($sql);

        $token = null;
        $tokenName = null;

        $clause = null;
        $clauseContent = null;

        $nodes = array();

        while ($i < $max && !is_null($nodes)) {
            $current = $sql[$i];

            // support for clauses
            if ($allowClauses) {
                if (!is_null($clause) && $current == '[') {
                    throw new Mro_Exception("unexpected begin of clause in [{$sql}]");
                } elseif (!isset($clause) && $current == ']') {
                    throw new Mro_Exception("unexpected end of clause in [{$sql}]");
                } elseif ($current == '[') {
                    // start a new clause
                    $clause = new Mro_Clause($i);
                    $nodes[] = $clause;
                    ++$i;
                    continue;
                } elseif (!is_null($clause) && $current == ']') {
                    // end a clause; parse its content for tokens
                    $clause->tokens = $this->parseTokens($clauseContent, false, true);
                    $clause->length = $i - $clause->begin + 1;
                    $clause->content = $clauseContent;
                    // reset
                    $clause = null;
                    $clauseContent = null;
                    // iterator
                    ++$i;
                    continue;
                } elseif (!is_null($clause)) {
                    $clauseContent .= $current;
                    ++$i;
                    continue;
                }
            }

            if (is_null($token) && $current == '}') {
                // case: error
                throw new Mro_Exception("unexpected end of token in [{$sql}]");
            } elseif (!is_null($token) && $current == '{') {
                // case: error
                throw new Mro_Exception("unexpected begin of token in [{$sql}]");
            } elseif (!is_null($token) && $current == '}') {
                // case: end of token
                $token->length = $i - $token->begin + 1;
                if (!is_null($tokenName) && $tokenName[0] == '?') {
                    // a gardian
                    $token->isGuardian = true;
                    $token->name = substr($tokenName, 1);
                } elseif (!is_null($tokenName) && $tokenName[0] == '!') {
                    // ommit the typing
                    $token->ommitType = true;
                    $token->name = substr($tokenName, 1);
                } else {
                    $token->name = $tokenName;
                }
                $tokenName = null;
                $token = null;
            } elseif ($current == '{') {
                // case: begin of token
                $token = new Mro_Token($i);
                $nodes[] = $token;
            } elseif (!is_null($token)) {
                // case: build token
                $tokenName .= $current;
            }
            ++$i;
        }
        if (!is_null($token)) {
            // case: end of token missng
            throw new Mro_Exception("end of token missing in [{$sql}]");
        }

        return $nodes;
    }

    // creates SQL for data field
    private function makeSqlValue($value)
    {
        $result = null;
        if (is_null($value)) {
            $result = "null";
        } elseif (is_int($value)) {
            $result = "{$value}";
        } elseif (is_string($value)) {
            $escaped = addslashes($value);
            $result = "'{$escaped}'";
        } elseif (is_array($value)) {
            $result = $this->makeArraySql($value);
        } elseif (is_object($value)) {
            $result = $this->makeObjectSql($value);
        } else {
            $result = "{$value}";
        }
        return $result;
    }

    // converts an array to a SQL vlaue
    private function makeArraySql(&$arr)
    {
        $result = '(';
        foreach ($arr as $key => $value) {
            $result .= $this->makeSqlValue($value) . ',';
        }
        $result = substr($result, 0, strlen($result) - 1);
        $result .= ')';
        return $result;
    }

    // converts an object to a SQL value
    private function makeObjectSql(&$obj)
    {
        if ($obj instanceof Mro_DateTime) {
            return "'" . $obj->format($this->dateFormat) . "'";
        } else {
            return "{$obj}";
        }
    }
}
