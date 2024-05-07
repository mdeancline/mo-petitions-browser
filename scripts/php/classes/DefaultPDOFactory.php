<?php
class DefaultPDOFactory implements PDOFactory
{
    private const DATABASE_FILE = "db.sqlite";
    private static PDO $pdo;

    private static DefaultPDOFactory $instance;

    private function __construct()
    {
    }

    public static function getInstance(): DefaultPDOFactory
    {
        return self::$instance = self::$instance ?? new DefaultPDOFactory();
    }

    public function create(): PDO
    {
        return self::$pdo = self::$pdo ?? $this->createNew();
    }

    private function createNew()
    {
        $dsn = sprintf("sqlite:%s%s%s", $_SERVER["DOCUMENT_ROOT"], "/", self::DATABASE_FILE);
        $pdo = new PDO($dsn, null, null);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $this->addSqliteFunctions($pdo);

        return $pdo;
    }

    private function addSqliteFunctions(PDO $pdo)
    {
        //https://stackoverflow.com/a/50538682
        $pdo->sqliteCreateFunction("JSON_CONTAINS", function ($json, $val, $path = null) {
            $array = json_decode($json, true);
            $val = json_decode(trim($val, '"'), true);

            if ($path)
                return $array[$path] == $val;
            else
                return in_array($val, $array);
        });
    }
}
