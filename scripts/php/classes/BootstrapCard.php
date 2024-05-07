<?php
class BootstrapCard extends AbstractAttributedMarkup
{
    private readonly RawText $title;
    private array $bodyContent = [];
    private array $footerContent = [];

    public function __construct(string $title)
    {
        parent::__construct("div");
        $this->title = new RawText($title);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $cardElem = parent::toDOMNode($document);
        $cardElem->setAttribute("class", "card");

        $cardBodyElem = $document->createElement("div");
        $cardBodyElem->setAttribute("class", "card-body");
        $cardElem->appendChild($cardBodyElem);

        $cardInfoElem = $document->createElement("div");
        $cardInfoElem->setAttribute("class", "card-info");
        $cardBodyElem->appendChild($cardInfoElem);

        $cardTitleElem = $document->createElement("h5");
        $cardTitleElem->appendChild($this->title->toDOMNode($document));
        $cardInfoElem->appendChild($cardTitleElem);

        foreach ($this->bodyContent as $markup)
            $cardBodyElem->appendChild($markup->toDOMNode($document));

        $cardFooterElem = $document->createElement("div");
        $cardFooterElem->setAttribute("class", "card-inner-footer");
        $cardBodyElem->appendChild($cardFooterElem);
        foreach ($this->footerContent as $markup)
            $cardFooterElem->appendChild($markup->toDOMNode($document));

        return $cardElem;
    }

    public function withRandomId(): BootstrapCard
    {
        return parent::withRandomId();
    }

    public function withBodyContent(HTMLMarkup $markup): BootstrapCard
    {
        array_push($this->bodyContent, $markup);
        return $this;
    }

    public function withFooterContent(HTMLMarkup $markup): BootstrapCard
    {
        array_push($this->footerContent, $markup);
        return $this;
    }
}
