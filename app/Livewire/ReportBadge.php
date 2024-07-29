<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\ReportRequest;

class ReportBadge extends Component
{
    public $count;

    public function mount()
    {
        $this->count = ReportRequest::where('status', 'pending')->count();
    }

    public function render()
    {
        return view('livewire.report-badge');
    }
}
