<?php
class LandSurveyInputsSection extends DivisionContainer implements HTMLDataDrivenMarkup
{
    private const EMPTY = [["section" => 0, "township" => 0, "range" => 0]];
    private const EMPTY_ENCODED = '[{"section": 0, "township": 0, "range": 0}]';

    public function __construct()
    {
        parent::__construct("form-row", "block");
        $this->withId("landSurveyInputsSection");
    }

    public function update(array $requestResult)
    {
        $landSurveysFormData = get_form_data()["extras"]["landSurveys"] ?? null;
        $landSurveysResult = $requestResult["data"][0]["landSurveys"] ?? self::EMPTY_ENCODED;
        $landSurveys = ($landSurveysFormData ?? json_decode($landSurveysResult, true))
            ?: self::EMPTY;

        $this->withAttribute("data-defaults", $landSurveysResult)
            ->withAttribute("data-saved", json_encode($landSurveys));

        for ($i = 0; $i < count($landSurveys); $i++) {
            $landSurvey = $landSurveys[$i];

            $this->appendLandSurveyRow(is_string($landSurvey)
                ? json_decode($landSurvey, true)
                : $landSurvey, $i);
        }
    }

    private function appendLandSurveyRow(array $landSurvey, int $index)
    {
        $inputGroup = new DivisionContainer("input-group");

        $row = (new DivisionContainer("land-survey-row"))
            ->withAttribute("data-index", $index)
            ->withChild($inputGroup);

        if ($index != 0)
            $row->withClassList("closable")->withChild(BootstrapButton::forClosing());

        foreach ($landSurvey as $key => $value) {
            $capitalized = ucwords($key);

            $input = $this->createInput($capitalized, $value == 0 ? "" : $value);
            $inputContainer = (new DivisionContainer("form-floating"))
                ->withChild($input)
                ->withChild(new RawHTML("<label>{$capitalized}</label>"));

            $inputGroup->withChild($inputContainer);
        }

        $this->withChild($row);
    }

    private function createInput(string $placeholder, string $value): Input
    {
        return (new Input(InputType::NUMBER))
            ->withClassList("form-control")
            ->withPlaceholder($placeholder)
            ->withValue($value);
    }
}
