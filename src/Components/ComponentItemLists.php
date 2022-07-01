<?php

namespace Partymeister\Accounting\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Motor\CMS\Models\PageVersionComponent;
use Partymeister\Accounting\Models\ItemType;

/**
 * Class ComponentItemLists
 */
class ComponentItemLists
{
    /**
     * @var PageVersionComponent
     */
    protected $pageVersionComponent;

    /**
     * @var
     */
    protected $itemTypes;

    /**
     * ComponentItemLists constructor.
     *
     * @param  PageVersionComponent  $pageVersionComponent
     */
    public function __construct(PageVersionComponent $pageVersionComponent)
    {
        $this->pageVersionComponent = $pageVersionComponent;
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $this->itemTypes = ItemType::where('is_visible', true)
                                   ->orderBy('sort_position', 'ASC')
                                   ->get();

        return $this->render();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view(config('motor-cms-page-components.components.'.$this->pageVersionComponent->component_name.'.view'), ['itemTypes' => $this->itemTypes]);
    }
}
