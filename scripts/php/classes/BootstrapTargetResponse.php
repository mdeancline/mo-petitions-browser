<?php
class BootstrapTargetResponse implements RedirectResponse
{
    private readonly AutoShownBootstrapTarget $target;

    public function __construct(BootstrapTarget $target)
    {
        $this->target = new AutoShownBootstrapTarget($target);
    }

    public function prepareVisual(DOMDocument $document): HTMLVariable
    {
        $targetName = $this->target->getName();
        return (new HTMLVariable("{$targetName}sHtml", $document))->withContent($this->target);
    }
}
