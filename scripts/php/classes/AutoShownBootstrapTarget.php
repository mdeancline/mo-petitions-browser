<?php
class AutoShownBootstrapTarget implements BootstrapTarget
{
    private readonly BootstrapTarget $innerWindow;

    public function __construct(BootstrapTarget $innerWindow)
    {
        $this->innerWindow = $innerWindow;
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $name = ucwords($this->getName());
        $element = $this->innerWindow->toDOMNode($document);
        $element->setAttribute("id", "autoShown{$name}");
        return $element;
    }

    public function withId(string $id): BootstrapTarget
    {
        return $this->innerWindow->withId($id);
    }

    public function withRandomId(): BootstrapTarget
    {
        return $this->innerWindow->withRandomId();
    }

    public function withClassList(string ...$classList): BootstrapTarget
    {
        return $this->innerWindow->withClassList(...$classList);
    }

    public function getAttribute(string $name): string
    {
        return $this->innerWindow->getAttribute($name);
    }

    public function withAttribute(string $name, string $value = ""): BootstrapTarget
    {
        return $this->innerWindow->withAttribute($name, $value);
    }

    public function getName(): string
    {
        return $this->innerWindow->getName();
    }
}
