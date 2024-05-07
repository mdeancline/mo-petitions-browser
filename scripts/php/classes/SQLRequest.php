<?php
abstract class SQLRequest implements DataRequest
{
    private static PDOFactory $defaultFactory;

    protected readonly string $tableName;
    protected array $absoluteClauses = [];
    protected array $alternatedClauses = [];

    private readonly PDOFactory $factory;

    public function __construct(string $tableName, PDOFactory $factory = null)
    {
        $this->tableName = $tableName;
        $this->factory = $factory ?? (self::$defaultFactory = self::$defaultFactory ?? DefaultPDOFactory::getInstance());
    }

    public function submit(DataMessageDictionary $dictionary): array
    {
        $pdo = $this->factory->create();
        $stmt = $pdo->prepare($this->createPreparedQuery());
        $values = [];

        foreach ($this->absoluteClauses as $clause)
            array_push($values, $clause->getValue());

        foreach ($this->alternatedClauses as $clause)
            array_push($values, $clause->getValue());

        $executionSuccess = $stmt->execute($values);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $requestResult = [
            "success" => $executionSuccess && (count($data) > 0 || $stmt->rowCount() > 0),
            "data" => $data
        ];
        $requestResult["message"] = $dictionary->getMessage($requestResult);

        return $requestResult;
    }

    protected final function appendRawAbsoluteClauses(string $queryString): string
    {
        $queryString .= "(%s)";
        $rawAbsoluteClauses = [];

        foreach ($this->absoluteClauses as $clause)
            array_push($rawAbsoluteClauses, $clause->prepare());

        return sprintf($queryString, join(" AND ", $rawAbsoluteClauses));
    }

    protected final function appendRawAlternatedClauses(string $queryString): string
    {
        $queryString .= "(%s)";
        $rawAlternatedClauses = [];

        foreach ($this->alternatedClauses as $clause)
            array_push($rawAlternatedClauses, $clause->prepare());

        return sprintf($queryString, join(" OR ", $rawAlternatedClauses));
    }

    protected abstract function createPreparedQuery(): string;

    public final function __toString(): string
    {
        $string = $this->createPreparedQuery();
        $search = "/\?/";

        foreach ($this->absoluteClauses as $clause)
            $string = preg_replace($search, $clause->getValue(), $string, 1);

        foreach ($this->alternatedClauses as $clause)
            $string = preg_replace($search, $clause->getValue(), $string, 1);

        return $string;
    }
}
