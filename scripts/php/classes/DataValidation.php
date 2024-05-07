<?php
interface DataValidation
{
    public function validate(string $requestString): bool;
    public function getMessage(): string;
}
