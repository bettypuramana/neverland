<?php

namespace App\Http\Livewire;

use App\Models\Sale_main;
use Livewire\Component;

class VisitorLiveTable extends Component
{
    public $livevisitors;

    public function mount()
    {
        $this->loadVisitors();
    }
    public function loadVisitors()
    {
        $this->livevisitors = Sale_main::where('exit_status',0)->orderBy('id', 'DESC')->get();
    }
    public function markFloatyReturned($id)
    {
        $visitor = Sale_main::find($id);
        if ($visitor) {
            $visitor->floaty_status = 0;
            $visitor->save();
        }

        // Refresh the list
        $this->loadVisitors();
    }
    public function exitWithPayment($id, $method, $amount)
    {
        $visitor = Sale_main::find($id);

        if ($visitor) {
            // Store payment info (example)
            $visitor->paid_amount = $amount;
            $visitor->payment_method = $method;
            $visitor->exit_status = 1;
            $visitor->save();
        }

        // Refresh the visitors list
        $this->loadVisitors();
    }
    public function markExit($id)
    {
        $visitor = Sale_main::find($id);

        if ($visitor) {
            $visitor->exit_status = 1;
            $visitor->save();
        }

        $this->loadVisitors();
    }
    public function render()
    {
        return view('livewire.visitor-live-table');
    }
}
