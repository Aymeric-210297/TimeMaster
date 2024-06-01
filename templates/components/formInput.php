<?php

function getFormInput($formViolations, $name, $label, $attributes = [], $indication = null)
{
    if (!empty($_POST[$name])) {
        $attributes['value'] = out($_POST[$name]);
    }

    if (isset($formViolations[$name])) {
        $attributes['class'] = "error";
    }

    $attributesString = "";

    foreach ($attributes as $attribute => $value) {
        if (empty($value)) {
            continue;
        }

        if ($value === true) {
            $attributesString .= " " . $attribute;
        } else {
            $attributesString .= " " . $attribute . '="' . $value . '"';
        }
    }

    $indicationHTML = "";
    if (!empty($indication)) {
        $indicationHTML = "<p>$indication</p>";
    }

    $errorIndicationHTML = "";
    if (isset($formViolations[$name])) {
        $errorIndicationHTML = '<p class="error">';
        if (count($formViolations[$name]) > 1) {
            foreach ($formViolations[$name] as $violation) {
                $message = out($violation->getMessage());
                $errorIndicationHTML .= "- $message\n";
            }
        } else {
            $message = out($formViolations[$name][0]->getMessage());
            $errorIndicationHTML .= "$message";
        }
        $errorIndicationHTML .= "</p>";
    }

    $label = $label . ($attributes['required'] ?? false ? " *" : "");

    return <<<HTML
        <div>
            <label for="$name">$label</label>
            $indicationHTML
            <input name="$name" id="$name"$attributesString>
            $errorIndicationHTML
        </div>
    HTML;
}
