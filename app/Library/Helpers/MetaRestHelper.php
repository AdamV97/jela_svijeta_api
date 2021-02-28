<?php

namespace App\Library\Helpers;

use App\Models\Meal;
use Illuminate\Http\Request;

class MetaRestHelper
{
    private $currentPage;
    private $itemsPerPage;
    private $totalItems;

    public function __construct(Request $request)
    {
        $this->currentPage = $request->get('page') === null ? 1 : (int) $request->get('page');
        $this->itemsPerPage = $request->get('per_page') === null ? 'all' : (int) $request->get('per_page');
        $this->setTotalItems();
    }

    public function getTotalItems()
    {
        return $this->totalItems;
    }

    /**
     * Inner setter not callable from outside
     */
    private function setTotalItems()
    {
        $this->totalItems = Meal::all()->count();
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
}
