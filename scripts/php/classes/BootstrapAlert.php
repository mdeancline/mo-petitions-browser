<?php
class BootstrapAlert extends AbstractHTMLContainer implements BootstrapTarget
{
    private readonly string $text;

    public function __construct(string $text, BootstrapAlertStyle $style)
    {
        parent::__construct("div");

        parent::withRandomId()
            ->withClassList("alert", "alert-{$style->value}", "alert-dismissible", "fade", "show")
            ->withAttribute("role", "alert");

        $this->text = $text;
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $this->withText($this->text);
        $this->withChild(BootstrapButton::forClosing($this));
        return parent::toDOMNode($document);
    }

    public function getName(): string
    {
        return "alert";
    }
}
