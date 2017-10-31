# Tabs

## Markup structure

    <div class="brix-tabs brix-component">
        <ul class="brix-tabs-nav brix-vertical brix-align-$alignment" role="tablist">
            <li><a id="brix-tab-1" role="tab" aria-controls="brix-tab-panel-1" class="brix-tab-trigger" href="#"></a></li>
            <li><a id="brix-tab-2" role="tab" aria-controls="brix-tab-panel-2" class="brix-tab-trigger" href="#"></a></li>
            <li><a id="brix-tab-3" role="tab" aria-controls="brix-tab-panel-3" class="brix-tab-trigger" href="#"></a></li>
        </ul>
        
        <div class="brix-tab-container">
            <div aria-labelledby="brix-tab-1" id="brix-tab-panel-1" class="brix-tab" role="tabpanel"></div>
            <div aria-labelledby="brix-tab-2" id="brix-tab-panel-2" class="brix-tab"></div>
            <div aria-labelledby="brix-tab-3" id="brix-tab-panel-3" class="brix-tab"></div>
        </div>
    </div>

## Notes

* A `brix-active` class is applied to tab triggers as well as tab panels in order to trigger their appearance.
* The `brix-align-$alignment` CSS class is applied to the tabs navigation in order to tweak its appearance. Possible value for the alignment CSS class are:
    - `brix-align-left`
    - `brix-align-right`
    - `brix-align-center`
* The `brix-vertical` CSS class is applied to the tabs navigation in order to tweak the component's appearance.