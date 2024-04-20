<?php

class ExampleModel extends BaseModel
{
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
        } catch (PDOException $error) {
            $this->handleError("Impossible de récupérer l'exemple", $error);
        }
    }
}
