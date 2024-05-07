<?php
class TableCell extends AbstractHTMLContainer
{
    public function __construct(string | HTMLMarkup $content = null)
    {
        parent::__construct("td");
        if ($content != null)
            $this->withChild(is_string($content) ? new RawText($content) : $content);
    }

    public function withChild(HTMLMarkup $markup): TableCell
    {
        return parent::withChild($markup);
    }
}
