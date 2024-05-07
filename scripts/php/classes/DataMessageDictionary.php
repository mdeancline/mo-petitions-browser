<?php
class DataMessageDictionary
{
    private static $empty;

    private readonly string $successMessage;
    private readonly string $failMessage;
    private readonly string $errorMessage;

    public function __construct(string $successMessage, string $failMessage, string $errorMessage)
    {
        $this->successMessage = $successMessage;
        $this->failMessage = $failMessage;
        $this->errorMessage = $errorMessage;
    }

    public static function emptyDictionary(): DataMessageDictionary
    {
        return self::$empty ?? self::$empty = new DataMessageDictionary("", "", "");
    }

    public function getMessage(array $requestResult)
    {
        return $requestResult["success"]
            ? $this->successMessage
            : (isset($requestResult["error"]) && $requestResult["error"]
                ? $this->errorMessage
                : $this->failMessage);
    }
}
