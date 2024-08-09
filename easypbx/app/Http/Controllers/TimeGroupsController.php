<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TimeGroup;
use Exception;
use Illuminate\Http\Request;
use Schema;

class TimeGroupsController extends Controller {

    /**
     * Display a listing of the time groups.
     *
     * @return Illuminate\View\View
     */

    public function __construct(){
        config(['menu.group' => 'menu-incoming']);  
    } 
    public function index( Request $request ) {

        $q         = $request->get( 'q' ) ?: '';
        $perPage   = $request->get( 'per_page' ) ?: 10;
        $filter    = $request->get( 'filter' ) ?: '';
        $sort      = $request->get( 'sort' ) ?: '';
        $timeGroup = TimeGroup::where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $timeGroup->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $timeGroup->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $timeGroup->orderBy( $sorta[0], $sorta[1] );
        } else {
            $timeGroup->orderBy( 'created_at', 'DESC' );
        }

        $timeGroups = $timeGroup->paginate( $perPage );

        $timeGroups->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'timeGroups.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name','time_zone','schedules']; // specify columns if need

            // $columns = Schema::getColumnListing(  ( new TimeGroup )->getTable() );

            $callback = function () use ( $timeGroups, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $timeGroups as $timeGroup ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $timeGroup->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'time_groups.table', compact( 'timeGroups' ) );
        }

        return view( 'time_groups.index', compact( 'timeGroups' ) );

    }

    /**
     * Show the form for creating a new time group.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {

        if ( $request->ajax() ) {
            return view( 'time_groups.form' )->with( ['action' => route( 'time_groups.time_group.store' ), 'timeGroup' => null, 'method' => 'POST'] );
        } else {
            return view( 'time_groups.create' );
        }

    }

    function packScheule($schedules){
        $schedule_data = [];
        //dd($schedules);
        foreach ( $schedules as $key => $schedule ) {

            $row = [
                'start_time' => empty($schedule['start_time']) ?'00:00':$schedule['start_time'],
                'end_time'   => empty($schedule['end_time']) ?'23:59':$schedule['end_time'],
                'week_days'  => isset( $schedule['week_days'] ) ? implode( ',', $schedule['week_days'] ) : '',
                'days'       => isset( $schedule['days'] ) ? implode( ',', $schedule['days'] ) : '',
                'months'     => isset( $schedule['months'] ) ? implode( ',', $schedule['months'] ) : '',
            ];

            if ( $this->shouldInsert( $row ) ) {
                $schedule_data[] = $row;
            }

        }
        return $schedule_data;
    }

    /**
     * Store a new time group in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data = $this->getData( $request );

        $schedules  = $request->input( 'schedule' );

        $schedule_data = $this->packScheule($schedules);

        $data['schedules'] = json_encode( $schedule_data );

        $data['organization_id'] = auth()->user()->organization_id;
        TimeGroup::create( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'time_groups.time_group.index' )
            ->with( 'success_message', __( 'Time Group was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified time group.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if(! TimeGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $timeGroup = TimeGroup::findOrFail( $id );

        if ( $request->ajax() ) {
            return view( 'time_groups.form', compact( 'timeGroup' ) )->with( ['action' => route( 'time_groups.time_group.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'time_groups.edit', compact( 'timeGroup' ) );
        }

    }

    /**
     * Update the specified time group in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {
        if(! TimeGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $timeGroup = TimeGroup::findOrFail( $id );

        $data = $this->getData( $request );

        $schedules  = $request->input( 'schedule' );
        $schedule_data = $this->packScheule($schedules);
     

        $data['schedules'] = json_encode( $schedule_data );


        $timeGroup->update( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'time_groups.time_group.index' )
            ->with( 'success_message', __( 'Time Group was successfully updated.' ) );

    }

    /**
     * Remove the specified time group from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! TimeGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $timeGroup = TimeGroup::findOrFail( $id );
            $timeGroup->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'time_groups.time_group.index' )
                    ->with( 'success_message', __( 'Time Group was successfully deleted.' ) );
            }

        } catch ( Exception $exception ) {

            if ( $request->ajax() ) {
                return response()->json( ['success' => false] );
            } else {
                return back()->withInput()
                    ->withErrors( ['unexpected_error' => __( 'Unexpected error occurred while trying to process your request.' )] );
            }

        }

    }

    /**
     * update the specified time group for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! TimeGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
            $timeGroup = TimeGroup::findOrFail( $id );

            $timeGroup->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified time group for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );
            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                TimeGroup::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new TimeGroup )->getTable(), $field ) ) {
                        TimeGroup::whereIn( 'id', $ids )->update( [$field => $val] );
                    }

                }

            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData( Request $request ) {
        $rules = [
            'name'      => 'required|string|min:1|max:191',
            'time_zone' => 'required|string|min:1|max:191',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

    public function shouldInsert( $row ) {

        foreach ( $row as $item ) {
            if ( isset( $item ) && $item != "" ) {
                return true;
            }

        }

        return false;
    }

}
