<?php
class NoTagsValidation implements DataValidation
{
    public function validate(string $requestString): bool
    {
        return $requestString == strip_tags($requestString);
    }

    public function getMessage(): string
    {
        return "Characters enclosed with \"<\" and \">\" are not allowed.";
    }
}
