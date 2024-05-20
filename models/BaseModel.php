<?php

class BaseModel
{
    protected $dbh;
    protected $errorCallback;

    public function __construct($dbh, $errorCallback = null)
    {
        $this->dbh = $dbh;
        if (isset($errorCallback) && !is_callable($errorCallback)) {
            throw new Exception("Error callback is not callable");
        }
        $this->errorCallback = $errorCallback;
    }

    protected function handleError($error, $message = null)
    {
        logError($error, $message);
        if (isset($this->errorCallback)) {
            call_user_func_array($this->errorCallback, [$error, $message]);
        } else {
            throw $error;
        }
    }
}
