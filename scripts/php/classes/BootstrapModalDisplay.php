<?php
class BootstrapModalDisplay implements DataDisplay
{
    private readonly string $closeModalBtnText;
    private readonly ?BootstrapModalDisplayListener $listener;

    public function __construct(string $closeModalBtnText = "OK", BootstrapModalDisplayListener $listener = null)
    {
        $this->closeModalBtnText = $closeModalBtnText;
        $this->listener = $listener;
    }

    public function show(array $requestResult)
    {
        $success = $requestResult["success"];
        $title = $success ? "Success" : "Error";

        $modal = (new BootstrapModal($title))
            ->withBodyContent(new Paragraph($requestResult["message"]));

        $modal->withFooterContent(BootstrapButton::forClosing($modal, $this->closeModalBtnText));

        if ($success && !is_null($this->listener))
            $this->listener->onShow($modal, $requestResult);

        redirect_to_previous(new BootstrapTargetResponse($modal));
    }
}
