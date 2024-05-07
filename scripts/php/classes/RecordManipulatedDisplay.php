<?php
class RecordManipulatedDisplay extends SmartyDataDisplay
{
    private readonly string $emptyDataMessage;

    public function __construct(Smarty $smarty, string $tplPath, string $failMessage)
    {
        parent::__construct($smarty, $tplPath, $tplPath);
        $this->emptyDataMessage = "This is a test.";
    }

    function createTplVariableData(array $requestResult): array
    {
        return ["id" => $requestResult["data"][0]["id"]];
    }
}
