<?php
class FallbackedDisplay implements DataDisplay
{
    private readonly DataDisplay $primaryDisplay;
    private readonly DataDisplay $fallbackDisplay;

    public function __construct(DataDisplay $primaryDisplay, DataDisplay $fallbackDisplay)
    {
        $this->primaryDisplay = $primaryDisplay;
        $this->fallbackDisplay = $fallbackDisplay;
    }

    public function show(array $requestResult)
    {
        if ($requestResult["success"]) $this->primaryDisplay->show($requestResult);
        else $this->fallbackDisplay->show($requestResult);
    }
}
