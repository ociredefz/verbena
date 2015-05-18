<?php

/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

namespace Bootstrap\Database\Drivers;

use MongoClient;
use Bootstrap\Environment\Tracer;
use Bootstrap\Environment\Environment;
use Bootstrap\Exceptions\DatabaseException;

class MongoDB_Driver extends MongoClient {

    protected static $_statement;
    protected static $_error;


    /**
     * Extend this class to Mongo extension.
     *
     * @access  public
     * @param   string
     * @return  object
     */
    public function __construct($_driver) {

        // Get database access parameters.
        $_hostname  = Environment::get_env("database.$_driver.hostname");
        $_port      = Environment::get_env("database.$_driver.port");
        $_options   = Environment::get_env("database.$_driver.options");

        try {
            parent::__construct("mongodb://$_hostname:$_port", $_options);
        }
        catch (MongoConnectionException $exception) {
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
    public function bind($_type, $_param, $_value, $_data_type = null) {

    }

    /**
     * Execute the query.
     *
     * @access  public
     * @param   void
     * @return  bool
     */
    public function execute() {

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

    }

    /**
     * Return the affected row count.
     * It returns the count of affected rows on
     * the last insert, update or delete.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function affected_rows() {

    }

    /**
     * Return the affected row count.
     * It returns the count of affected rows on
     * the last insert, update or delete.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function inserted_id() {

    }

    /**
     * Begin the transaction procedure.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function transaction() {

    }

    /**
     * Commit the transaction.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function commit() {

    }

    /**
     * Rollback the transaction.
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function rollback() {

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