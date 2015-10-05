<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Database;

use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Exceptions\DatabaseException;

class Factory {

    /**
     * Database instance.
     * @var object
     */
    public static $db;

    /**
     * Do query without waiting 
     * until execute's call.
     * @var const
     */
    const QUERY_DIRECT  = 0x00;
    /**
     * Prepare the query and wait 
     * for execute's call.
     * @var const
     */
    const QUERY_WAIT    = 0x01;
    /**
     * Error, field(s) already exist(s)
     * in the database, skip the insert.
     * @var const
     */
    const ERRNO_FIELD_EXISTS    = 0xFE;

    /**
     * Variables for chained methods.
     * @var string|array
     */
    protected static $_table        = null;
    protected static $_where        = null;
    protected static $_unique       = null;
    protected static $_fields       = null;
    protected static $_join         = null;

    protected static $_logical_opt  = 'AND';


    /**
     * Create the database instance.
     *
     * @access  public
     * @param   void
     * @return  object
     */
    public static function register_handler() {

        // Get the database driver.
        $_driver = Environment::get_env('database.driver');
        
        // Set the drivers namespace.
        $_namespace = 'Bootstrap\\Database\Drivers\\';

        try {
            switch ($_driver) {
                case 'mysql':
                case 'postgresql':
                case 'sqlite':  $_class = $_namespace . 'pdo_driver';       break;
                case 'mongodb': $_class = $_namespace . 'mongodb_driver';   break;
                default:
                    Tracer::add("[[Database:]] the driver [[$_driver]] is not supported");
                    throw new DatabaseException("the driver [[$_driver]] is not supported");
                    break;
            }

            try {
                // Create the driver instance.
                if (@class_exists($_class)) {
                    static::$db = new $_class($_driver);
                }
            }
            catch (DatabaseException $exception) {
                echo $exception->get_formatted_exception();
            }
        }
        catch (DatabaseException $exception) {
            echo $exception->get_formatted_exception();
        }

    }


    /**
     * Chaining methods.
     */


    /**
     * Set table name.
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public static function table($_name = '') {

        /**
         * Freeing the database chaining variables
         * for next database action.
         */
        static::$_where  = 
        static::$_unique = 
        static::$_fields = null;
        static::$_join   = null;

        static::$_logical_opt = 'AND';

        static::$_table = $_name;
        return new static;

    }

    /**
     * Select statement method.
     *
     * @access  public
     * @param   array
     * @return  mixed
     */
    public static function select($_fields = []) {

        static::$_fields = $_fields;
        return new static;

    }

    /**
     * Join statement method.
     *
     * @access  public
     * @param   array
     * @return  mixed
     */
    public static function join($_fields = [], $_logical_opt = 'AND') {

        static::$_join = $_fields;
        static::$_logical_opt = $_logical_opt;

        return new static;

    }

    /**
     * Database where condition.
     *
     * @access  public
     * @param   array
     * @return  mixed
     */
    public static function where($_fields = [], $_logical_opt = 'AND') {

        static::$_where = $_fields;
        static::$_logical_opt = $_logical_opt;

        return new static;

    }

    /**
     * Before insert, check if the fields
     * already exists on database.
     *
     * @access  public
     * @param   array
     * @param   string
     * @return  mixed
     */
    public static function unique($_fields = [], $_logical_opt = 'AND') {

        static::$_unique = $_fields;
        static::$_logical_opt = $_logical_opt;

        return new static;

    }

    /**
     * Get last inserted id.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function last_id() {

        return static::inserted_id();

    }

    /**
     * Return the total affected rows 
     * for update or delete statements.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function affected() {

        return static::affected_rows();

    }

    /**
     * Insert statement method.
     *
     * @access  public
     * @param   array
     * @return  mixed
     */
    public static function insert($_fields = []) {

        // Check for empty values.
        if (is_null(static::$_table) or empty($_fields)) {
            return false;
        }

        // Check if field(s) already exist(s).
        if (!is_null(static::$_unique) and !empty(static::$_unique)) {

            // Set unique fields in the where statement.
            static::$_where = static::$_unique;

            // Do the field count.
            $_total = static::count();
            if ($_total) {
                return static::ERRNO_FIELD_EXISTS;
            }
        }

        // Generate columns list for prepare statement.
        $_prepare_columns_list = implode(',', array_keys($_fields));

        // Generate columns values for prepare statement.
        $_prepare_columns_values = implode(',', array_map(function($_x) {
            return ':' . $_x;
        }, array_keys($_fields)));

        // Generate the final safe query.
        $_query = 'INSERT INTO `' . static::$_table . '` (' . $_prepare_columns_list . ') VALUES (' . $_prepare_columns_values . ')';

        // Execute the prepare statement.
        // The 'QUERY_WAIT' prevents the execute() call.
        $_prepare = static::query($_query, static::QUERY_WAIT);

        // Return the query execution with safe parameters.
        return static::execute($_fields);

    }

    /**
     * Update statement method.
     *
     * @access  public
     * @param   array
     * @return  mixed
     */
    public static function update($_fields = []) {

        // Check for empty values.
        if (is_null(static::$_table) or empty($_fields)) {
            return false;
        }

        // Generate columns values for prepare statement.
        $_prepare_columns_list = implode(',', array_map(function($_x) {
            return $_x . '=?';
        }, array_keys($_fields)));

        // Generate the query.
        $_query = 'UPDATE `' . static::$_table . '` SET ' . $_prepare_columns_list . 
        static::_prepare_join_list() .
        static::_prepare_where_list();

        // Add where statements to query if exists.
        if (!is_null(static::$_where) and !empty(static::$_where)) {

            // Merge fields with where statements values.
            $_fields = static::_merge_associative($_fields, static::$_where);
        }

        // Execute the prepare statement.
        // The 'QUERY_WAIT' prevents the execute() call.
        $_prepare = static::query($_query, static::QUERY_WAIT);

        // Return the query execution with safe parameters.
        return static::execute(array_values($_fields));

    }

    /**
     * Insert statement method.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function delete() {

        // Add where statements to query if exists.
        if (is_null(static::$_where) or empty(static::$_where)) {
            return false;
        }

        // Generate the query.
        $_query = 'DELETE FROM `' . static::$_table . '`' . 
        static::_prepare_join_list() .
        static::_prepare_where_list();

        // Execute the prepare statement.
        // The 'QUERY_WAIT' prevents the execute() call.
        $_prepare = static::query($_query, static::QUERY_WAIT);

        // Return the query execution with safe parameters.
        return static::execute(array_values(static::$_where));

    }

    /**
     * Return all rows from the last select query.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function rows($_return_type = 'object') {

        // Check for empty values.
        if (is_null(static::$_table) or is_null(static::$_fields)) {
            return false;
        }

        // Generate columns values for prepare statement.
        $_prepare_columns_list = '*';
        if (static::$_fields != '*') {
            $_prepare_columns_list = implode(',', array_map(function($_x) {
                return $_x;
            }, static::$_fields));
        }

        // Generate the query.
        $_query = 'SELECT ' . $_prepare_columns_list . '  FROM `' . static::$_table . '`' . 
        static::_prepare_join_list() .
        static::_prepare_where_list();

        // Execute the prepare statement.
        // The 'QUERY_WAIT' prevents the execute() call.
        $_prepare = static::query($_query, static::QUERY_WAIT);

        // Return the query execution with safe parameters.
        if (static::execute(array_values(static::$_where)) === true) {
            return static::mfetch($_return_type);
        }

        return false;

    }

    /**
     * Return the first row from the last select query.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function row($_return_type = 'object') {

        // Check for empty values.
        if (is_null(static::$_table) or is_null(static::$_fields)) {
            return false;
        }

        // Generate columns values for prepare statement.
        $_prepare_columns_list = '*';
        if (static::$_fields != '*') {
            $_prepare_columns_list = implode(',', array_map(function($_x) {
                return '`' . $_x . '`';
            }, static::$_fields));
        }

        // Generate the query.
        $_query = 'SELECT ' . $_prepare_columns_list . '  FROM `' . static::$_table . '`' . 
        static::_prepare_join_list() .
        static::_prepare_where_list();

        // Execute the prepare statement.
        // The 'QUERY_WAIT' prevents the execute() call.
        $_prepare = static::query($_query, static::QUERY_WAIT);

        // Return the query execution with safe parameters.
        if (static::execute(array_values(static::$_where)) === true) {
            return static::fetch($_return_type);
        }

        return false;

    }

    /**
     * Count the result of a select statement.
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public static function count($_what = '*') {

        // Add where statements to query if exists.
        if (is_null(static::$_where) or empty(static::$_where)) {
            return false;
        }

        // Generate the query.
        $_query = 'SELECT COUNT(' . $_what . ') FROM `' . static::$_table . '`' . 
        static::_prepare_join_list() .
        static::_prepare_where_list();

        // Execute the prepare statement.
        // The 'QUERY_WAIT' prevents the execute() call.
        $_prepare = static::query($_query, static::QUERY_WAIT);

        // Check the query execution with safe parameters.
        if (static::execute(array_values(static::$_where)) === true) {
            return current(static::fetch('object'));
        }

        return false;

    }

    /**
     * Generate the where list.
     *
     * @access  private
     * @param   void
     * @return  string
     */
    private static function _prepare_where_list() {

        $_where = '';

        // Add where statements to query if exists.
        if (!is_null(static::$_where) and !empty(static::$_where)) {

            // Enable logical operators only if
            // multiple where fields exists.
            if (count(static::$_where) <= 1) {
                static::$_logical_opt = '';
            }

            // Generate columns values for prepare statement.
            $_prepare_where_list = implode(' ' . static::$_logical_opt . ' ', array_map(function($_x) {
                if ($_x[0] == '!') {
                    return $_x . '!=?';
                }
                else {
                    return $_x . '=?';
                }
            }, array_keys(static::$_where)));

            // Append where statement.
            $_where = ' WHERE ' . $_prepare_where_list;

        }

        return $_where;

    }

    /**
     * Generate the join list.
     *
     * @access  private
     * @param   void
     * @return  string
     */
    private static function _prepare_join_list() {

        $_join = '';

        // Add where statements to query if exists.
        if (!is_null(static::$_join) and !empty(static::$_join)) {

            // Enable logical operators only if
            // multiple where fields exists.
            if (count(static::$_join) <= 1) {
                static::$_logical_opt = '';
            }

            // Generate columns values for prepare statement.
            $_prepare_join_list = implode(' ' . static::$_logical_opt . ' ', array_map(function($_x) {
                return $_x;
            }, static::$_join[2]));

            // Append where statement.
            $_join = ' ' . strtoupper(static::$_join[0]) . ' JOIN ' . static::$_join[1] . ' ON ' . $_prepare_join_list;
        }

        return $_join;

    }


    /**
     * Normal methods.
     */


    /**
     * Prepare the query.
     *
     * @access  public
     * @param   string
     * @param   const
     * @return  mixed
     */
    public static function query($_string, $_query_type = Factory::QUERY_DIRECT) {

        if (static::$db) {
            return static::$db->query($_string, $_query_type);
        }

    }

    /**
     * Bind the parameters.
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   variable
     * @param   string
     * @return  void
     */
    public static function bind($_type, $_param, $_value, $_data_type = null) {

        if (static::$db) {
            static::$db->bind($_type, $_param, $_value, $_data_type);
        }

    }

    /**
     * Execute the query.
     *
     * @access  public
     * @param   array
     * @return  bool
     */
    public static function execute($_params) {

        if (static::$db) {
            return static::$db->execute($_params);
        }

    }

    /**
     * Return the row of the query result.
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public static function fetch($_type = 'object') {

        if (static::$db) {
            return static::$db->fetch($_type);
        }

    }

    /**
     * Return the rows of the query result
     *
     * @access  public
     * @param   string
     * @return  mixed
     */
    public static function mfetch($_type = 'object') {

        if (static::$db) {
            return static::$db->mfetch($_type);
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
    public static function affected_rows() {

        if (static::$db) {
            return static::$db->affected_rows();
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
    public static function inserted_id() {

        if (static::$db) {
            return static::$db->inserted_id();
        }

    }

    /**
     * Begin the transaction procedure.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function transaction() {

        if (static::$db) {
            return static::$db->transaction();
        }

    }

    /**
     * Commit the transaction.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function commit() {

        if (static::$db) {
            return static::$db->commit();
        }

    }

    /**
     * Rollback the transaction.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public static function rollback() {

        if (static::$db) {
            return static::$db->rollback();
        }

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
    public static function params_dump() {

        if (static::$db) {
            return static::$db->params_dump();
        }

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
    public static function errors_dump() {

        if (static::$db) {
            return static::$db->errors_dump();
        }

    }

    /**
     * Get database table.
     *
     * @access  public
     * @param   string
     * @return  string
     */
    public static function get_table($_table = '') {

        if (strlen($_table)) {
            return Environment::get_env('database.tables.' . $_table);
        }

    }

    /**
     * Merge associative arrays.
     *
     * @access  public
     * @param   array
     * @param   array
     * @return  array
     */
    public static function _merge_associative($arr1, $arr2) {
        
        $merged = $arr1;

        // Use custom key instead of real key.
        $x = 0;

        foreach ($arr2 as $k => $v) {
            if (is_array($v) && is_array($arr1[$k])) {
                $merged[$x] = static::_merge_associative($arr1[$k], $v);
            }
            else {
                $merged[$x] = $v;
            }
        
            $x++;
        }

        return $merged;
    
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