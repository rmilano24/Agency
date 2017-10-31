# Accordions

## Markup structure

    <div class="brix-accordion brix-component" role="tablist">
        <div class="brix-toggle">
            <div role="tab" id="brix-accordion-1" class="brix-toggle-trigger" aria-controls="brix-accordion-panel-1"></div>
            <div class="brix-toggle-content" role="tabpanel" aria-labelledby="brix-accordion-1"></div>
        </div>
        <div class="brix-toggle">
            <div role="tab" id="brix-accordion-2" class="brix-toggle-trigger" aria-controls="brix-accordion-panel-2"></div>
            <div class="brix-toggle-content" role="tabpanel" aria-labelledby="brix-accordion-2"></div>
        </div>
        <div class="brix-toggle">
            <div role="tab" id="brix-accordion-3" class="brix-toggle-trigger" aria-controls="brix-accordion-panel-3"></div>
            <div class="brix-toggle-content" role="tabpanel" aria-labelledby="brix-accordion-3"></div>
        </div>
    </div>

## Notes

* A `brix-active` class is applied to toggles in order to trigger their appearance.