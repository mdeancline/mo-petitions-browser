<?php
class RequestDisplayer
{
    private readonly DataRequest $request;
    private readonly DataMessageDictionary $dictionary;

    public function __construct(DataRequest $request, DataMessageDictionary $dictionary)
    {
        $this->request = $request;
        $this->dictionary = $dictionary;
    }

    public function show()
    {
        $result = null;

        try {
            $result = $this->request->submit($this->dictionary);
        } catch (Throwable $e) {
            log_error($e);

            $result = [
                "success" => false,
                "error" => true
            ];
            $result["message"] = $this->dictionary->getMessage($result);
        } finally {
            echo json_encode($result);
        }
    }
}
