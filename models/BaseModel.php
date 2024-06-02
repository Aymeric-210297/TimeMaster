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
            exit();
        } else {
            throw $error;
        }
    }

    protected function executeQuery($query, $params = [], $errorMessage = null)
    {
        try {
            $sth = $this->dbh->prepare($query);

            foreach ($params as $key => $value) {
                if (is_array($value) && count($value) == 2 && is_int($value[1])) {
                    $sth->bindValue($key, $value[0], $value[1]);
                } else {
                    $sth->bindValue($key, $value);
                }
            }

            $sth->execute();
            return $sth;
        } catch (PDOException $e) {
            $this->handleError($e, $errorMessage);
        }
    }
}
