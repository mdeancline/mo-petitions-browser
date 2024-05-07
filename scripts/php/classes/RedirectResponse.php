<?php
interface RedirectResponse
{
    public function prepareVisual(DOMDocument $document): HTMLVariable;
}
