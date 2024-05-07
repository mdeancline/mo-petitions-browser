<?php
class RawHTML implements HTMLMarkup
{
    private const ENCODING_MAP = [0x80, 0x10FFFF, 0, ~0];

    private readonly string $source;

    public function __construct(string $source)
    {
        $this->source = mb_encode_numericentity(
            preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;", $source),
            self::ENCODING_MAP,
            "UTF-8"
        );
    }

    public function toDOMNode(DOMDocument $document): DOMNode
    {
        $helper = new DOMDocument;
        $helper->loadHTML($this->source);

        $fragment = $document->createDocumentFragment();
        foreach ($helper->documentElement->childNodes as $childnode)
            $fragment->appendChild($fragment->ownerDocument->importNode($childnode, true));

        return $fragment;
    }
}
