<?php
class ListItem extends AbstractHTMLContainer
{
    public function __construct(string | HTMLMarkup $content = "")
    {
        parent::__construct("li");
        $this->withChild(is_string($content) ? new RawText($content) : $content);
    }

    public function withChild(HTMLMarkup $markup): ListItem
    {
        return parent::withChild($markup);
    }
}
