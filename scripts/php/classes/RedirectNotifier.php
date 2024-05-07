<?php
class RedirectNotifier
{
    private readonly SmartyDataDisplay $display;
    private readonly DOMDocument $document;

    public function __construct(SmartyDataDisplay $display, DOMDocument $document)
    {
        $this->display = $display;
        $this->document = $document;
    }

    public function notify()
    {
        if (was_redirected()) {
            $breakdown = get_redirect_breakdown();

            if (isset($breakdown["response"])) {
                $response = $breakdown["response"];
                $this->display->assign($response->prepareVisual($this->document));
            }
        }
    }
}
