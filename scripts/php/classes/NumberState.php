<?php
class NumberState implements InputState
{
    private readonly int $minimum;
    private readonly int $maximum;

    public function __construct(int $minimum = null, int $maximum = null)
    {
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    public function applyTo(Input $input)
    {
        if ($this->minimum !== null)
            $input->withAttribute("min", $this->minimum);

        if ($this->maximum !== null)
            $input->withAttribute("max", $this->maximum);
    }

    public function getName(): string
    {
        return "number";
    }
}
