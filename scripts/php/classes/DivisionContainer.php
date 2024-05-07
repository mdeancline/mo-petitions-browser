<?php
class DivisionContainer extends AbstractHTMLContainer
{
    public function __construct(string ...$classes)
    {
        parent::__construct("div");
        if (!empty($classes)) parent::withClassList(...$classes);
    }

    public function withRandomId(): DivisionContainer
    {
        return parent::withRandomId();
    }
}
