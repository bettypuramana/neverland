<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class CurrentTime extends Component
{
    public $time;

    public function mount()
    {
        $this->updateTime();
    }

    public function updateTime()
    {
        $this->time = Carbon::now('Asia/Kolkata')->format('d-M-Y h:i:s A');
    }
    public function render()
    {
        $this->updateTime();
        return view('livewire.current-time');
    }
}
