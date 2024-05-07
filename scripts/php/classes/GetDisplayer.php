<?php
class GetDisplayer
{
    private readonly DataRequest $request;
    private readonly DataDisplay $display;
    private readonly DataMessageDictionary $dictionary;

    public function __construct(DataDisplay $display, DataRequest $request = null, DataMessageDictionary $dictionary = null)
    {
        $this->display = $display;
        $this->request = $request == null ? $this->createEmptyRequest() : $request;
        $this->dictionary = $dictionary == null ? DataMessageDictionary::emptyDictionary() : $dictionary;
    }

    private function createEmptyRequest()
    {
        return new class implements DataRequest
        {
            public function submit(DataMessageDictionary $dictionary): array
            {
                return [];
            }

            public function __toString(): string
            {
                return "";
            }
        };
    }

    public function show()
    {
        try {
            $this->display->show($this->request->submit($this->dictionary));
        } catch (Throwable $e) {
            log_error($e);

            $requestResult = [
                "success" => false,
                "error" => true
            ];
            $requestResult["message"] = $this->dictionary->getMessage($requestResult);

            $this->display->show($requestResult);
        }
    }
}
