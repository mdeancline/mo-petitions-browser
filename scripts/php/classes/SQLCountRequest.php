<?php
class SQLCountRequest extends SQLSelectRequest
{
    public function __construct(string $tableName)
    {
        parent::__construct($tableName);
    }

    public function submit(DataMessageDictionary $dictionary): array
    {
        $requestResult = parent::submit($dictionary);
        $requestResult["rowCount"] = $requestResult["data"][0]["COUNT(*)"];
        unset($requestResult["data"]);
        return $requestResult;
    }

    function createPreparedQuery(): string
    {
        return str_replace("*", "COUNT(*)", parent::createPreparedQuery());
    }
}
