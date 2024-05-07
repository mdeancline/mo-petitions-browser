<?php
class HTMLVariable implements SmartyDataVariable
{
    private readonly string $name;
    private readonly DOMDocument $document;
    private array $content = [];
    private array $dataContent = [];

    public function __construct(string $name, DOMDocument $document)
    {
        $this->name = $name;
        $this->document = $document;
    }

    public function getValue(array $requestResult): string
    {
        $source = "";

        foreach ($this->content as $markup) {
            if (in_array($markup, $this->dataContent))
                $markup->update($requestResult);

            $source .= $this->document->saveHTML($markup->toDOMNode($this->document));
        }

        return $source;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function withContent(HTMLMarkup $markup): HTMLVariable
    {
        array_push($this->content, $markup);
        return $this;
    }

    public function withContentDataDriven(HTMLDataDrivenMarkup $markup): HTMLVariable
    {
        array_push($this->dataContent, $markup);
        return $this->withContent($markup);
    }
}
