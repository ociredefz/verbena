<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Database\Drivers;

use PDO;
use PDOException;
use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Database\Factory;
use Bootstrap\Exceptions\DatabaseException;

class PDO_Driver extends PDO {

    protected static $_statement;
    protected static $_error = null;


    /**
     * Extend this class to PDO extension.
     *
     * @access  public
     * @param   string
     * @return  object
     */
    public function __construct($_driver) {

        // Get database access parameters.
        $_hostname      = Environment::get_env("database.$_driver.hostname");
        $_port          = Environment::get_env("database.$_driver.port");
        $_database      = Environment::get_env("database.$_driver.database");
        $_username      = Environment::get_env("database.$_driver.username");
        $_password      = Environment::get_env("database.$_driver.password");
        $_persistent    = Environment::get_env("database.$_driver.persistent");
        $_file          = Environment::get_env("database.$_driver.file");

        // Database options.
        $_options = [
            // Persistent connection to database.
            PDO::ATTR_PERSISTENT        => $_persistent, 
            // Error-Handling mode.
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION
        ];

        try {
            // Create PDO instance.
            switch ($_driver) {
                case 'mysql':
                    parent::__construct("mysql:host=$_hostname;port=$_port;dbname=$_database", 
                    $_username, $_password, $_options);
                    break;
                case 'postgresql':
                    parent::__construct("pgsql:host=$_hostname;port=$_port;dbname=$_database", 
                    $_username, $_password, $_options);
                    break;
                case 'sqlite':
                    parent::__construct("sqlite:$_file", null, null, $_options);
                    break;
            }
        }
        catch (PDOException $exception) {
            Tracer::add('[[Database:]] ' . $exception->getMessage());
            throw new DatabaseException($exception->getMessage());
        }

    }

    /**
     * Prepare the query.
     *
     * @access  public
     * @param   string
     * @param   const
     * @return  function
     */
    public function query($_string, $_query_type = Factory::QUERY_DIRECT) {

        static::$_error = null;

        try {
            static::$_statement = parent::prepare($_string);

            if ($_query_type === Factory::QUERY_DIRECT) {
                return $this->execute();
            }
            else {
                return static::$_statement;
            }
        }
        catch (PDOException $_error) {
            static::$_error = $_error;
            return false;
        }

    }

    /**
     * Bind parameters.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   variable
     * @param   string
     * @return  void
     */
    public function bind($_type, $_param, $_value, $_data_type = '') {

        static::$_error = null;

        $_param_types = ['int', 'bool', 'null', 'str'];

        // Auto-check the value type.
        if (empty($_data_type)) {
            /**
             * TODO: Add the is_numeric() instead of is_int()
             * to identify the float / double parameter type?
             */
            $_param_type = [is_int($_value), is_bool($_value), is_null($_value), is_string($_value)];
        
            foreach ($_param_type as $_key => $_val) {
                if ($_val) {
                    $_data_type = $_param_types[$_key];
                    break;
                }
            }

            // No parameter type found, return error.
            if (empty($_data_type)) {
                Tracer::add("[[Database:]] unknown parameter type [['$_param']]");
                return false;
            }
        }

        // Result-set return type.
        switch ($_data_type) {
            case 'int':
                $_param_type = PDO::PARAM_INT;
                break;
            case 'bool':
                $_param_type = PDO::PARAM_BOOL;
                break;
            case 'null':
                $_param_type = PDO::PARAM_NULL; 
                break;
            case 'str':
            default:
                $_param_type = PDO::PARAM_STR;  
                break;
        }

        // Bind method type.
        try {
            switch ($_type) {
                case 'param':
                    static::$_statement->bindParam($_param, $_value, $_param_type);
                    break;
                case 'value':
                    static::$_statement->bindValue($_param, $_value, $_param_type);
                    break;
            }
        }
        catch (PDOException $_error) {
            static::$_error = $_error;
            return false;
        }

    }

    /**
     * Execute the query.
     *
     * @access  public
     * @param   void
     * @return  bool
     */
    public function execute() {

        static::$_error = null;

        return static::$_statement->execute();

    }

    /**
     * Return the row of the query result.
     * $_type means the result-set return type:
     * object, array, indexed and both (name, 0-indexed).
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public function fetch($_type = 'object') {

        $this->execute();
        return static::$_statement->fetch($this->_fetch_type_parser($_type));

    }

    /**
     * Return the rows of the query result.
     * $_type means the result-set return type:
     * object, array, indexed and both (name, 0-indexed).
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public function mfetch($_type = 'object') {

        $this->execute();
        return static::$_statement->fetchAll($this->_fetch_type_parser($_type));

    }

    /**
     * Parse the type return of result-set.
     * $_type means the result-set return type:
     * object, array, indexed and both (name, 0-indexed).
     *
     * @access  private
     * @param   string
     * @return  mixed
     */
    private function _fetch_type_parser($_type) {

        switch ($_type) {
            case 'object':
                return PDO::FETCH_OBJ;
                break;
            case 'array':
                return PDO::FETCH_ASSOC;
                break;
            case 'indexed':
                return PDO::FETCH_NUM;
                break;
            case 'both':
                return PDO::FETCH_BOTH;
                break;
            default:
                return PDO::FETCH_OBJ;
                break;
        }

    }

    /**
     * Return the affected row count.
     * It returns the count of the affected rows on
     * the last insert, update or delete.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function affected_rows() {

        return static::$_statement->rowCount();

    }

    /**
     * Return the affected row count.
     * It returns the count of the affected rows on
     * the last insert, update or delete.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function inserted_id() {

        return static::$_statement->lastInsertId();

    }

    /**
     * Begin the transaction procedure.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function transaction() {

        return static::$_statement->beginTransaction();

    }

    /**
     * Commit the transaction.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function commit() {

        return static::$_statement->commit();

    }

    /**
     * Rollback the transaction.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function rollback() {

        return static::$_statement->rollBack();

    }

    /**
     * Debug the prepare parameters.
     * This method dumps the information that
     * was contained in the prepared statement.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function params_dump() {

        return static::$_statement->debugDumpParams();

    }

    /**
     * Return the possible errors.
     * This method dumps the error information 
     * about the last operation.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function errors_dump() {

        return static::$_error;

    }

    /**
     * Magic method that will be called when trying
     * to call a class method that doesn't exists.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  exception
     */
    public function __call($name, $arguments) {

        Tracer::add("[[Database:]] called an undefined method [['$name']]");

        try {
            throw new DatabaseException("called an undefined method [['$name']]");
        }
        catch (DatabaseException $exception) {
            echo $exception->get_formatted_exception();
        }
    
    }

    /**
     * Magic method that will be called when trying
     * to call statically a class method that 
     * doesn't exists.
     *
     * @access  public
     * @param   string
     * @param   array
     * @return  exception
     */
    public static function __callStatic($name, $arguments) {
    
        Tracer::add("[[Database:]] called an undefined method [['$name']]");

        try {
            throw new DatabaseException("called an undefined method [['$name']]");
        }
        catch (DatabaseException $exception) {
            echo $exception->get_formatted_exception();
        }

    }
    
}
