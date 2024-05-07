<?php
class PostDisplayer
{
    private readonly DataDisplay $display;
    private readonly DataRequest $request;
    private readonly DataMessageDictionary $dictionary;
    private readonly DataValidation $validation;

    public function __construct(DataDisplay $display, DataRequest $request, DataMessageDictionary $dictionary, DataValidation $validation = null)
    {
        $this->request = $request;
        $this->display = $display;
        $this->dictionary = $dictionary;
        $this->validation = $validation ?? $this->createEmptyValidation();
    }

    private function createEmptyValidation()
    {
        return new class implements DataValidation
        {
            public function validate(string $requestString): bool
            {
                return true;
            }

            public function getMessage(): string
            {
                return "";
            }
        };
    }

    public function show()
    {
        try {
            $this->doShow();
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

    private function doShow()
    {
        if ($this->validation->validate((string)$this->request)) {
            $result = $this->request->submit($this->dictionary);

            if ($result["success"] && (is_post_request() || was_post_request()) && isset($_SERVER["HTTP_REFERER"]))
                remove_form_data($_SERVER["HTTP_REFERER"]);

            $this->display->show($result);
        } else {
            $response = new BootstrapTargetResponse(new Dialog("Error", $this->validation->getMessage()));
            redirect_to_previous($response);
        }
    }
}
