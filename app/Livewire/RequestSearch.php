<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Service; // Make sure this is the correct path to your Service model

class RequestSearch extends Component
{
    public $search = '';

    public function render()
    {
        // Fetch requests based on the search input using the search method on the model
        $requests = Service::search($this->search)->get();

        return view('livewire.request-search', compact('requests'));
    }
}
