<?php

namespace Partymeister\Accounting\Http\Controllers\Backend;

use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Accounting\Forms\Backend\BookingForm;
use Partymeister\Accounting\Grids\BookingGrid;
use Partymeister\Accounting\Http\Requests\Backend\BookingRequest;
use Partymeister\Accounting\Models\Booking;
use Partymeister\Accounting\Services\BookingService;

/**
 * Class BookingsController
 */
class BookingsController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \ReflectionException
     */
    public function index()
    {
        $grid = new BookingGrid(Booking::class);

        $service = BookingService::collection($grid);
        $grid->setFilter($service->getFilter());
        $paginator = $service->getPaginator();

        return view('partymeister-accounting::backend.bookings.index', compact('paginator', 'grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $form = $this->form(BookingForm::class, [
            'method' => 'POST',
            'route' => 'backend.bookings.store',
            'enctype' => 'multipart/form-data',
        ]);

        return view('partymeister-accounting::backend.bookings.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(BookingRequest $request)
    {
        $form = $this->form(BookingForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        BookingService::createWithForm($request, $form);

        flash()->success(trans('partymeister-accounting::backend/bookings.created'));

        return redirect('backend/bookings');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Booking $record)
    {
        $form = $this->form(BookingForm::class, [
            'method' => 'PATCH',
            'url' => route('backend.bookings.update', [$record->id]),
            'enctype' => 'multipart/form-data',
            'model' => $record,
        ]);

        return view('partymeister-accounting::backend.bookings.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(BookingRequest $request, Booking $record)
    {
        $form = $this->form(BookingForm::class);

        // It will automatically use current request, get the rules, and do the validation
        if (! $form->isValid()) {
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        BookingService::updateWithForm($record, $request, $form);

        flash()->success(trans('partymeister-accounting::backend/bookings.updated'));

        return redirect('backend/bookings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Booking $record)
    {
        BookingService::delete($record);

        flash()->success(trans('partymeister-accounting::backend/bookings.deleted'));

        return redirect('backend/bookings');
    }
}
