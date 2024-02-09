<?php

class ExampleModel
{
    protected $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function getExample($exampleId)
    {
        try {
            $query = "SELECT * ";
            $query .= "FROM example ";
            $query .= "WHERE exampleId = :exampleId";

            $sth = $this->dbh->prepare($query);
            $sth->execute([
                ":exampleId" => $exampleId
            ]);

            return $sth->fetch();
        } catch (PDOException $e) {
            error_log("Impossible de récupérer l'exemple: " . $e->getMessage());
            render("out", "errors/500", [], 500);
        }
    }
}
