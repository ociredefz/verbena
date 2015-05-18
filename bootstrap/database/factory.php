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

    public static $db;

    /**
     * Do query without waiting 
     * until execute's call.
     */
    const QUERY_DIRECT  = 0x00;
    /**
     * Prepare the query and wait 
     * for execute's call.
     */
    const QUERY_WAIT    = 0x01;


    /**
     * Create the database instance.
     * (supported: PDO extensions, MongoDB)
     *
     * @access  public
     * @param   void
     * @return  object
     */
    public static function register_database() {

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
     * @param   void
     * @return  bool
     */
    public static function execute() {

        if (static::$db) {
            return static::$db->execute();
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
    public function transaction() {

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
    public function commit() {

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
    public function rollback() {

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
    public function params_dump() {

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
    public function errors_dump() {

        if (static::$db) {
            return static::$db->errors_dump();
        }

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