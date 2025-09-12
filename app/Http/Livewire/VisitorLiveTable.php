<?php

namespace App\Http\Livewire;

use App\Models\Sale_main;
use Livewire\Component;

class VisitorLiveTable extends Component
{
    public $livevisitors;

    public function mount()
    {
        // Load data initially
        $this->livevisitors = Sale_main::where('exit_status',0)->orderBy('id', 'DESC')->get();
    }

    public function render()
    {
        return view('livewire.visitor-live-table');
    }
}
