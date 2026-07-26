<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PartnerWall extends Component
{
    public array $partners;

    public function __construct()
    {
        // Only surface partners with an on-file permission (active = true).
        $this->partners = array_values(array_filter(
            config('partners', []),
            fn ($p) => ($p['active'] ?? false) === true,
        ));
    }

    public function render(): View
    {
        return view('components.partner-wall');
    }
}
