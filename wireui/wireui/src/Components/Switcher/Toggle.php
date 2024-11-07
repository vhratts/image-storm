<?php

namespace WireUi\Components\Switcher;

use Illuminate\Contracts\View\View;
use WireUi\Traits\Components\InteractsWithColor;
use WireUi\Traits\Components\InteractsWithRounded;
use WireUi\Traits\Components\InteractsWithSize;
use WireUi\Traits\Components\InteractsWithWrapper;
use WireUi\View\WireUiComponent;

class Toggle extends WireUiComponent
{
    use InteractsWithColor;
    use InteractsWithRounded;
    use InteractsWithSize;
    use InteractsWithWrapper;

    protected array $props = [
        'icon' => null,
        'iconless' => false,
        'right-icon' => null,
    ];

    protected function exclude(): array
    {
        return ['type'];
    }

    protected function blade(): View
    {
        return view('wireui-switcher::toggle');
    }
}
