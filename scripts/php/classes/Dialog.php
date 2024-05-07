<?php
class Dialog extends BootstrapModal
{
    public function __construct(string $title, string | InlineMarkup $content)
    {
        parent::__construct($title);
        parent::withBodyContent(is_string($content) ? new RawText($content) : $content);
        parent::withFooterContent(BootstrapButton::forClosing($this, "OK"));
    }
}
