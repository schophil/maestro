<?php
/**
 * @author Philippe Schottey <pschotte@gmail.com>
 * @package db
 */

namespace Maestro\db;

use Maestro\Mro_Exception;
use Monolog\Registry;

/**
 * Class implementing a MySQL connection.
 */
class Mro_MysqlSession
{

    private $log;            // logging
    private ?\mysqli $conn;            // a mysql connection
    private $error;        // retains an error message
    private $transaction;    // boolean indicating transaction

    /**
     * Default constructor.
     */
    function __construct()
    {
        $this->log = Registry::getInstance('db');
        $this->transaction = false;
    }

    /**
     * Connects based on a connection data object.
     *
     * @param object $connectionData The connection data holding the data.
     */
    function connectWith($connectionData)
    {
        $this->connect($connectionData->getUser(), $connectionData->getPassword(),
            $connectionData->getDatabase(), $connectionData->getHost());
    }

    /**
     * Opens a connection to a database on this mysql session.
     *
     * @param string $user The user name of the DB account.
     * @param string $password The password for the DB account.
     * @param string $db The name of the database to connect to.
     * @param string $host The host where the MySQL server is running.
     * @throws Mro_Exception If an error occurred connecting to the database.
     */
    function connect($user, $password, $db, $host)
    {
        $this->log->debug("connect to mysql database {$db} at {$host}");
        // connect to the database
        $conn = mysqli_connect($host, $user, $password);
        if ($conn != false) {
            $this->conn = $conn;
            // select needed db
            if (!mysqli_select_db($this->conn, $db)) {
                throw new Mro_Exception("unable to connect to database {$db} at {$host}");
            }
        } else {
            throw new Mro_Exception("unable to connect to database {$db} at {$host}");
        }
    }

    /**
     * Method checking if a connection is open.
     * @return boolean
     */
    function isConnected(): bool
    {
        return !empty($this->conn) && mysqli_ping($this->conn);
    }

    /**
     * Disconnects the session if one is open.
     */
    function disconnect(): void
    {
        if ($this->isConnected()) {
            $this->log->debug('close mysql connection');
            mysqli_close($this->conn);
            $this->conn = null;
        }
    }

    /**
     * Starts a transaction on the current session.
     */
    function startTransaction()
    {
        if (!$this->transaction) {
            $this->execute('start transaction');
            $this->transaction = true;
        }
    }

    /**
     * Commits the currently running transaction.
     * @throws Mro_Exception If a transaction is not currently open.
     */
    function commit()
    {
        if ($this->transaction) {
            $this->execute('commit');
            $this->transaction = false;
        } else {
            throw new Mro_Exception('no transaction open');
        }
    }

    /**
     * Rollbacks the currently running transaction.
     * @throws Mro_Exception If a transaction is not currently open.
     */
    function rollback()
    {
        if ($this->transaction) {
            $this->execute('rollback');
            $this->transaction = false;
        } else {
            throw new Mro_Exception('no transaction open');
        }
    }

    /**
     * Checks if a transaction is currently running.
     */
    function inTransaction(): bool
    {
        return $this->transaction;
    }

    /**
     * Executes a UPDATE, DELETE or INSERT SQL statement and returns the number
     * of affected rows.
     * @param string $sql A valid SQL statement.
     * @return integer The number of affected rows by the statement.
     * @throws Mro_Exception If an error occurred.
     */
    function apply($sql)
    {
        $result = 0;
        if ($this->isConnected()) {
            $this->log->debug("mysql execute query: {$sql}");
            if (mysqli_query($this->conn, $sql)) {
                $result = mysqli_affected_rows($this->conn);
            } else {
                $error = mysqli_error($this->conn);
                throw new Mro_Exception("error executing [{$sql}]: {$error}");
            }
        } else {
            throw new Mro_Exception('not connected');
        }
        return $result;
    }

    /**
     * Executes a SQL statement with a given database lock.
     * @param string $sql The SQL query to execute.
     * @param string $lock The database lock to apply.
     * @return Mro_MysqlResultSet
     * @throws Mro_Exception
     */
    function query($sql, $lock): Mro_MysqlResultSet
    {
        $result = null;
        if ($this->isConnected()) {
            // extend query with lock
            if ($lock == LOCK_SHARE) {
                $sql = $sql . ' LOCK IN SHARE MODE';
            } elseif ($lock == LOCK_UPDATE) {
                $sql = $sql . ' FOR UPDATE';
            }
            $this->log->debug("mysql execute query: {$sql}");
            $sql_result = mysqli_query($this->conn, $sql);
            if ($sql_result) {
                $result = new Mro_MysqlResultSet($sql_result);
            } else {
                $error = mysqli_error($this->conn);
                throw new Mro_Exception("error executing [{$sql}]: {$error}");
            }
        } else {
            throw new Mro_Exception('not connected');
        }
        return $result;
    }

    /**
     * Reads the next value of a MySQL sequence.
     * @param string $sequence The name of the MySQL sequence.
     * @return integer The next unique value of the sequence.
     * @throws Mro_Exception
     */
    function nextVal($sequence)
    {
        $this->assertConnected();
        $result = -1;
        $qres = mysqli_query($this->conn, "update {$sequence} set id=LAST_INSERT_ID(id+1)");
        if ($qres) {
            $qres = mysqli_query($this->conn, 'select LAST_INSERT_ID()');
            if ($qres) {
                $id = mysqli_fetch_row($qres);
                if ($id) {
                    $result = $id[0];
                } else {
                    $error = mysqli_error($this->conn);
                    throw new Mro_Exception("error retrieving [{$sequence}]: {$error}");
                }
            } else {
                $error = mysqli_error($this->conn);
                throw new Mro_Exception("error executing [{$sequence}]: {$error}");
            }
        } else {
            $error = mysqli_error($this->conn);
            throw new Mro_Exception("error executing [{$sequence}]: {$error}");
        }

        return $result;
    }

    private function execute(string $sql)
    {
        $result = 0;
        if ($this->isConnected()) {
            $this->log->debug("mysql execute query: {$sql}");
            $result = mysqli_query($this->conn, $sql);
            if (!$result) {
                $error = mysqli_error($this->conn);
                throw new Mro_Exception("error executing [{$sql}]: {$error}");
            }
        } else {
            throw new Mro_Exception('not connected');
        }
        return $result;
    }

    private function assertConnected(): void
    {
        if (!$this->isConnected()) {
            throw new Mro_Exception('not connected');
        }
    }
}

?>