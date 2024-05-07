<?php
class BootstrapModal extends AbstractAttributedMarkup implements BootstrapTarget
{
    private readonly RawText $title;
    private array $bodyContent = [];
    private array $footerContent = [];

    public function __construct(string $title)
    {
        parent::__construct("div");
        parent::withRandomId();
        $this->title = new RawText($title);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $modalElem = parent::toDOMNode($document);
        $modalElem->setAttribute("class", "modal fade");
        $modalElem->setAttribute("tabindex", -1);
        $modalElem->setAttribute("aria-modal", "true");
        $modalElem->setAttribute("role", "dialog");

        $modalDialogElem = $document->createElement("div");
        $modalDialogElem->setAttribute("class", "modal-dialog modal-dialog-centered");
        $modalElem->appendChild($modalDialogElem);

        $modalContentElem = $document->createElement("div");
        $modalContentElem->setAttribute("class", "modal-content");
        $modalContentElem->appendChild($this->createHeaderElement($document));
        $modalContentElem->appendChild($this->createBodyElement($document));
        $modalContentElem->appendChild($this->createFooterElement($document));
        $modalDialogElem->appendChild($modalContentElem);

        return $modalElem;
    }

    private function createHeaderElement(DOMDocument $document): DOMElement
    {
        $modalHeaderElem = $document->createElement("div");
        $modalHeaderElem->setAttribute("class", "modal-header");
        $modalTitleElem = $document->createElement("h1");
        $modalTitleElem->appendChild($this->title->toDOMNode($document));
        $modalTitleElem->setAttribute("class", "modal-title fs-5");
        $modalHeaderElem->appendChild($modalTitleElem);

        $closeBtnElem = $document->createElement("button");
        $closeBtnElem->setAttribute("type", "button");
        $closeBtnElem->setAttribute("class", "btn-close");
        $closeBtnElem->setAttribute("data-bs-dismiss", "modal");
        $closeBtnElem->setAttribute("aria-label", "Close");
        $modalHeaderElem->appendChild($closeBtnElem);

        return $modalHeaderElem;
    }

    private function createBodyElement(DOMDocument $document): DOMElement
    {
        $modalBodyElem = $document->createElement("div");
        $modalBodyElem->setAttribute("class", "modal-body");
        foreach ($this->bodyContent as $markup)
            $modalBodyElem->appendChild($markup->toDOMNode($document));
        return $modalBodyElem;
    }

    private function createFooterElement(DOMDocument $document): DOMElement
    {
        $modalFooterElem = $document->createElement("div");
        $modalFooterElem->setAttribute("class", "modal-footer");
        foreach ($this->footerContent as $markup)
            $modalFooterElem->appendChild($markup->toDOMNode($document));
        return $modalFooterElem;
    }

    public function withId(string $id): BootstrapModal
    {
        if (isset($this->attributes["id"]))
            throw new InvalidArgumentException("Modal ID cannot be changed due to relational functionality reasons");

        return parent::withId($id);
    }

    public function withRandomId(): BootstrapModal
    {
        throw new InvalidArgumentException("Modal ID cannot be changed due to relational functionality reasons");
    }

    public function withClassList(string ...$classList): BootstrapModal
    {
        return parent::withClassList();
    }

    public function withAttribute(string $name, string $value = ""): BootstrapModal
    {
        return parent::withAttribute($name, $value);
    }

    public function withBodyContent(HTMLMarkup $markup): BootstrapModal
    {
        array_push($this->bodyContent, $markup);
        return $this;
    }

    public function withFooterContent(HTMLMarkup $markup): BootstrapModal
    {
        array_push($this->footerContent, $markup);
        return $this;
    }

    public function getName(): string
    {
        return "modal";
    }
}
