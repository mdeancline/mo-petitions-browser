<?php
class DataRequestAggregate implements DataRequest
{
    private array $requests = [];

    public function submit(DataMessageDictionary $dictionary): array
    {
        $requestResult = [];

        foreach ($this->requests as $request) {
            $result = $request->submit($dictionary);
            // echo print_r($result), "<br>";

            foreach ($result as $key => $value) {
                // if (isset($requestResult[$key]))
                //     throw new RuntimeException(sprintf("Overlapping result key (key name: %s)", $key));

                $requestResult[$key] = $value;
            }
        }

        return $requestResult;
    }

    public function __toString(): string
    {
        $requestStrings = [];

        foreach ($this->requests as $request)
            array_push($requestStrings, (string)$request);

        return join(", ", $requestStrings);
    }

    public function addRequest(DataRequest $request)
    {
        array_push($this->requests, $request);
    }
}
