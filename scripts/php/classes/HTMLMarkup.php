<?php
interface HTMLMarkup
{
    public function toDOMNode(DOMDocument $document): DOMNode;
}
