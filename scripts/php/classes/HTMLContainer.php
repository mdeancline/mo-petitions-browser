<?php
interface HTMLContainer extends AttributedMarkup
{
    #[\Override]
    public function withAttribute(string $name, string $value = ""): HTMLContainer;
    public function withChild(HTMLMarkup $markup): HTMLContainer;
}
