<?php

namespace Webkul\Admin\Http\Controllers\Setting;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Lead\Repositories\TypeRepository;
use Webkul\Admin\DataGrids\Setting\TypeDataGrid;

class TypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected TypeRepository $typeRepository)
    {
    }

    /**
     * Display a listing of the type.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(TypeDataGrid::class)->process();
        }

        return view('admin::settings.types.index');
    }

    /**
     * Store a newly created type in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|unique:lead_types,name'
        ]);
        
        if ($validator->fails()) {
            session()->flash('error', trans('admin::app.settings.types.name-exists'));

            return redirect()->back();
        }

        Event::dispatch('settings.type.create.before');

        $type = $this->typeRepository->create(request()->all());

        Event::dispatch('settings.type.create.after', $type);

        session()->flash('success', trans('admin::app.settings.types.create-success'));

        return redirect()->route('admin.settings.types.index');
    }

    /**
     * Show the form for editing the specified type.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $type = $this->typeRepository->findOrFail($id);

        return view('admin::settings.types.edit', compact('type'));
    }

    /**
     * Update the specified type in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name' => 'required|unique:lead_types,name,' . $id,
        ]);

        Event::dispatch('settings.type.update.before', $id);

        $type = $this->typeRepository->update(request()->all(), $id);

        Event::dispatch('settings.type.update.after', $type);

        session()->flash('success', trans('admin::app.settings.types.update-success'));

        return redirect()->route('admin.settings.types.index');
    }

    /**
     * Remove the specified type from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = $this->typeRepository->findOrFail($id);

        try {
            Event::dispatch('settings.type.delete.before', $id);

            $this->typeRepository->delete($id);

            Event::dispatch('settings.type.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.settings.types.delete-success'),
            ], 200);
        } catch(\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.settings.types.delete-failed'),
            ], 400);
        }

        return response()->json([
            'message' => trans('admin::app.settings.types.delete-failed'),
        ], 400);
    }
}
