<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\FuncTrait;
use App\Models\Func;
use App\Models\Function;
use App\Models\Survey;
use App\Models\VoiceFile;
use Exception;
use Illuminate\Http\Request;
use Schema;

class SurveysController extends Controller
{
    use FuncTrait;

    /**
     * Display a listing of the surveys.
     *
     * @return Illuminate\View\View
     */
    public function __construct()
    {
        config(['menu.group' => 'menu-campaign']);
    }
    public function index(Request $request)
    {

        $q       = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter  = $request->get('filter') ?: '';
        $sort    = $request->get('sort') ?: '';
        $survey  = Survey::with('voice')->where('organization_id', auth()->user()->organization_id);

        if (! empty($q)) {
            $survey->where('name', 'LIKE', '%' . $q . '%');
        }

        if (! empty($filter)) {
            $filtera = explode(':', $filter);
            $survey->where($filtera[0], '=', $filtera[1]);
        }

        if (! empty($sort)) {
            $sorta = explode(':', $sort);
            $survey->orderBy($sorta[0], $sorta[1]);
        } else {
            $survey->orderBy('created_at', 'DESC');
        }

        $surveys = $survey->paginate($perPage);

        $surveys->appends(['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage]);

        if (! empty($request->get('csv'))) {

            $fileName = 'surveys.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            //$column = ['name','email','password']; // specify columns if need
            $columns = Schema::getColumnListing((new Survey)->getTable());

            $callback = function () use ($surveys, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($surveys as $survey) {

                    foreach ($columns as $column) {
                        $row[$column] = $survey->{$column};
                    }

                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($request->ajax()) {
            return view('surveys.table', compact('surveys'));
        }

        return view('surveys.index', compact('surveys'));
    }

    public function destinations($function)
    {

        if (request()->ajax()) {
            return $this->dist_by_function($function);
        }

        die();
    }

    /**
     * Show the form for creating a new survey.
     *
     * @return Illuminate\View\View
     */
    public function create(Request $request)
    {
        $voices       = VoiceFile::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id')->all();
        $functions    = Func::getFuncList();
        $destinations = [];
        $func_list    = [];

        if ($request->ajax()) {
            return view('surveys.form', compact('voices', 'functions', 'destinations', 'func_list'))->with(['action' => route('surveys.survey.store'), 'survey' => null, 'method' => 'POST']);
        } else {
            return view('surveys.create', compact('voices', 'functions', 'destinations', 'func_list'));
        }
    }

    /**
     * Store a new survey in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $data = $this->getData($request);

        $data['organization_id'] = auth()->user()->organization_id;
        $func                    = Func::select('id')->where('func', $data['function_id'])->first();

        $data['function_id'] = $func->id;

        $keys     = $request->input('keys');
        $keysData = [];

        if ($data['type'] != 1) {

            if (count($keys['key'])) {

                foreach ($keys['key'] as $k => $val) {

                    $func = Func::select('id')->where('func', $data['actions']['function_id'][$k])->first();

                    $keysData[] = [
                        'key'            => $val,
                        'text'           => isset($keys['text'][$k]) ? $keys['text'][$k] : '',
                        'intent'           => isset($keys['intent'][$k]) ? $keys['intent'][$k] : '',
                        'function_id'    => $func->id,
                        'destination_id' => $data['actions']['destination_id'][$k],
                    ];
                }
            }
        }

        $data['keys'] = json_encode($keysData);

        Survey::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('surveys.survey.index')
            ->with('success_message', __('Survey was successfully added.'));
    }

    /**
     * Show the form for editing the specified survey.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id, Request $request)
    {

        if (! Survey::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists()) {
            return back();
        }

        $survey = Survey::findOrFail($id);



        $actions = json_decode($survey->keys);

        foreach ($actions as $action) {
            $function             = Func::select('func')->where('id', $action->function_id)->first();
            $action->destinations = $this->dist_by_function($function->func);
        }

        $survey->keys = json_encode($actions);

        $voices    = VoiceFile::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');
        $functions = Func::getFuncList();
        $func_list = Func::where('func_type', 0)->pluck('func', 'id')->toArray();

        $destinations = $this->dist_by_function($survey->function->func, 0, true);

        if ($request->ajax()) {
            return view('surveys.form', compact('survey', 'voices', 'functions', 'destinations', 'func_list'))->with(['action' => route('surveys.survey.update', $id), 'method' => 'PUT']);
        } else {
            return view('surveys.edit', compact('survey', 'voices', 'functions', 'destinations', 'func_list'));
        }
    }

    /**
     * Update the specified survey in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {

        $data = $this->getData($request);

        $func = Func::select('id')->where('func', $data['function_id'])->first();

        $data['function_id'] = $func->id;

        if (! Survey::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists()) {
            return back();
        }

        $survey = Survey::findOrFail($id);

        $keys     = $request->input('keys');
        $keysData = [];
        if ($data['type'] != 1) {
            if (count($keys['key'])) {

                foreach ($keys['key'] as $k => $val) {

                    if (isset($keys['key'][$k])) {
                        $func = Func::select('id')->where('func', $data['actions']['function_id'][$k])->first();

                        $keysData[] = [
                            'key'            => $val,
                            'text'           => isset($keys['text'][$k]) ? $keys['text'][$k] : '',
                            'intent'           => isset($keys['intent'][$k]) ? $keys['intent'][$k] : '',
                            'function_id'    => $func->id,
                            'destination_id' => $data['actions']['destination_id'][$k],
                        ];
                    }
                }
            }
        }

        $data['keys'] = json_encode($keysData);

        $survey->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('surveys.survey.index')
            ->with('success_message', __('Survey was successfully updated.'));
    }

    /**
     * Remove the specified survey from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        try {

            if (! Survey::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists()) {
                return back();
            }

            $survey = Survey::findOrFail($id);
            $survey->delete();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            } else {
                return redirect()->route('surveys.survey.index')
                    ->with('success_message', __('Survey was successfully deleted.'));
            }
        } catch (Exception $exception) {

            if ($request->ajax()) {
                return response()->json(['success' => false]);
            } else {
                return back()->withInput()
                    ->withErrors(['unexpected_error' => __('Unexpected error occurred while trying to process your request.')]);
            }
        }
    }

    /**
     * update the specified survey for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField($id, Request $request)
    {

        try {

            if (! Survey::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists()) {
                return back();
            }

            $survey = Survey::findOrFail($id);

            $survey->update($request->all());

            return response()->json(['success' => true]);
        } catch (Exception $exception) {
            return response()->json(['success' => false]);
        }
    }

    /**
     * update the specified survey for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction(Request $request)
    {

        try {

            $data = $request->all();
            $ids  = explode(',', $data['ids']);

            if (isset($data['mass_delete']) && $data['mass_delete'] == 1) {
                Survey::whereIn('id', $ids)->delete();
            } else {

                foreach ($data as $field => $val) {

                    if (! in_array($field, ['ids', '_token', '_method', 'mass_delete']) && Schema::hasColumn((new Survey)->getTable(), $field)) {
                        Survey::whereIn('id', $ids)->update([$field => $val]);
                    }
                }
            }

            return response()->json(['success' => true]);
        } catch (Exception $exception) {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'name'           => 'required|string|min:1|max:191',
            'intent_analyzer' => 'nullable|string',
            'voice_id'       => 'required',
            'type'           => 'required|numeric|min:0|max:3',
            'max_retry'      => 'required|numeric|min:0|max:10',
            'keys.*'         => 'nullable',
            'intent.*'       => 'nullable',
            'function_id'    => 'required',
            'destination_id' => 'required',
            'actions.*'      => 'nullable',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string',
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
