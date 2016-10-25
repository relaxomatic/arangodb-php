<?php
/**
 * Created by PhpStorm.
 * User: Bennett.Thompson
 * Date: 25/10/2016
 * Time: 11:48 AM
 */

namespace triagens\ArangoDb;
class BatchedStatement extends Statement
{
    protected static function getCursor( $connection, $json, $options )
    {
        return new BatchedCursor( $connection, $json, $options );
    }
}