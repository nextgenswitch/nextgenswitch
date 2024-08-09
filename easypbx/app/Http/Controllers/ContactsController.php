<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Func;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Schema;

class ContactsController extends Controller {

    /**
     * Display a listing of the contacts.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-campaign']);  
    } 
    public function index( Request $request ) {
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';
        $contact = Contact::where( 'organization_id', '=', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $contact->where( 'tel_no', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );

            if (  ! empty( $filtera[1] ) ) {

                if ( $filtera[0] == 'contact_group_id' ) {
                    $contact->whereRaw( 'FIND_IN_SET(' . $filtera[1] . ',contact_groups)' );
                } else {
                    $contact->where( $filtera[0], '=', $filtera[1] );
                }

            }

        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $contact->orderBy( $sorta[0], $sorta[1] );
        } else {
            $contact->orderBy( 'created_at', 'DESC' );
        }

        $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        $contacts = $contact->paginate( $perPage );

        $contacts->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'contacts.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['tel_no', 'name', 'contact_group']; // specify columns if need

            $callback = function () use ( $contacts, $columns ) {
                $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();
                $file           = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $contacts as $contact ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'tel_no' ) {
                            $row[$column] = $contact->cc . $contact->{$column};
                        } else

                        if ( $column == 'contact_group' ) {
                            $cg = [];


                            foreach ( $contact->contact_groups as $group ) {
                                if ( isset( $contact_groups[$group] ) ) {
                                    $cg[] = $contact_groups[$group];
                                }
                            }

                            $row[$column] = implode( ",", $cg );

                        } else {
                            $row[$column] = $contact->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $destinations = [];
        $functions    = Func::getFuncList();

        if ( $request->ajax() ) {
            return view( 'contacts.table', compact( 'contacts', 'contact_groups' ) );
        }

        return view( 'contacts.index', compact( 'contacts', 'contact_groups', 'functions', 'destinations' ) );

    }

    /**
     * Show the form for creating a new contact.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {

        $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'contacts.form', compact( 'contact_groups' ) )->with( ['action' => route( 'contacts.contact.store' ), 'contact' => null, 'method' => 'POST'] );
        } else {
            return view( 'contacts.create', compact( 'contact_groups' ) );
        }

    }

    /**
     * Store a new contact in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;
        Contact::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'contacts.contact.index' )
            ->with( 'success_message', 'Contact was successfully added.' );

    }

    public function upload( Request $request ) {

        $rules = [
            'file' => 'required|mimes:csv,txt|max:40960',
        ];

        $data = $request->validate( $rules );

        if ( $request->file() ) {

            $path   = $request->file( 'file' )->getRealPath();
            $data   = array_map( 'str_getcsv', file( $path ) );
            $ccodes = config( 'enums.tel_codes' );
            krsort( $ccodes );

            $n              = 0;
            $contact_groups = array_flip( ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all() );

            foreach ( $data as $c ) {

                if (  ! empty( $c[0] ) ) {

                    $cc_code = '';
                    $tel_no  = preg_replace( "/[^\d]/", "", $c[0] );

                    foreach ( $ccodes as $key => $value ) {

                        if ( substr( $tel_no, 0, strlen( $key ) ) == $key ) {
                            $cc_code = $key;
                            break;
                        }

                    }

                    if ( $cc_code == '' ) {
                        // $tel_no =
                    } else {
                        $tel_no = substr( $tel_no, strlen( $cc_code ) );
                    }

                    $cg = [];

                    if ( isset( $c[2] ) && ! empty( $c[2] ) ) {
                        $cong = explode( ",", $c[2] );

                        foreach ( $cong as $g ) {

                            if ( isset( $contact_groups[$g] ) ) {
                                $cg[] = $contact_groups[$g];
                            } else {

                                if (  ! empty( $g ) ) {
                                    $contact_group = ContactGroup::create( ['name' => $g, 'organization_id' => auth()->user()->organization_id] );

                                    if ( $contact_group ) {
                                        $cg[] = $contact_group->id;
                                    }

                                    $contact_groups = array_flip( ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all() );
                                }

                            }

                        }

                    }

                    $name = (  ! isset( $c[1] ) || empty( $c[1] ) ) ? "unnamed" : $c[1];

                    $c_data = ['tel_no' => $tel_no, 'name' => $name, 'organization_id' => auth()->user()->organization_id, 'contact_groups' => $cg];

                    $validator = Validator::make( $c_data, [
                        'organization_id' => 'required|numeric',
                        'name'            => 'required|string|min:1|max:100',
                        'tel_no'          => 'required|regex:/[0-9]/|not_regex:/[a-z]/|min:9',
                        // 'cc'              => 'nullable|string|min:1|max:7',
                        'contact_groups'  => 'nullable|min:0|max:100',
                        
                    ] );

                    if ( $validator->fails() ) {
                        continue;
                    } else {

                        $contact = Contact::where( "tel_no", "=", $c_data['tel_no'] )->where( "organization_id", "=", $c_data['organization_id'] )->first();

                        if ( $contact ) {
                            $contact->update( $validator->validated() );
                        } else {
                            Contact::create( $validator->validated() );
                        }

                        $n++;

                    }

                }

            }

        }

        return redirect()->route( 'contacts.contact.index' )
            ->with( 'success_message', $n . ' Contacts successfully uploaded.' );

    }

    /**
     * Show the form for editing the specified contact.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if (  ! Contact::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
            return back();
        }

        $contact = Contact::findOrFail( $id );

        $contact_groups = ContactGroup::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'contacts.form', compact( 'contact', 'contact_groups' ) )->with( ['action' => route( 'contacts.contact.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'contacts.edit', compact( 'contact', 'contact_groups' ) );
        }

    }

    /**
     * Update the specified contact in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request, $id);

        if (  ! Contact::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
            return back();
        }

        $contact = Contact::findOrFail( $id );
        $contact->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'contacts.contact.index' )
            ->with( 'success_message', 'Contact was successfully updated.' );

    }

    /**
     * Remove the specified contact from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {

            if (  ! Contact::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
                return back();
            }

            $contact = Contact::findOrFail( $id );
            $contact->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'contacts.contact.index' )
                    ->with( 'success_message', 'Contact was successfully deleted.' );
            }

        } catch ( Exception $exception ) {

            if ( $request->ajax() ) {
                return response()->json( ['success' => false] );
            } else {
                return back()->withInput()
                    ->withErrors( ['unexpected_error' => 'Unexpected error occurred while trying to process your request.'] );
            }

        }

    }

    /**
     * update the specified contact for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {

            if (  ! Contact::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
                return back();
            }

            $contact = Contact::findOrFail( $id );

            $contact->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified contact for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                Contact::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if ( $field == 'contact_groups' ) {
                        $val = implode( ',', $val );
                    }

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn( ( new Contact )->getTable(), $field ) ) {
                        Contact::whereIn( 'id', $ids )->update( [$field => $val] );
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
    protected function getData( Request $request, $id = 0) {

        $rules = [
            'first_name'     => 'nullable|string|min:1|max:100',
            'last_name'      => 'nullable|string|min:1|max:100',
            'email'          => 'nullable|email',
            'gender'         => 'nullable|string',
            // 'cc'             => 'nullable|string|min:1|max:7',
            'tel_no'         => 'required|regex:/[0-9]/|not_regex:/[a-z]/|min:4|unique:contacts,tel_no',
            'contact_groups' => 'nullable|min:0|max:100',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string',
            'state'          => 'nullable|string',
            'post_code'      => 'nullable|string',
            'country'        => 'nullable|string',
            'notes' => 'nullable|string'
        ];

        if($id > 0){
            $rules['tel_no'] = 'required|regex:/[0-9]/|not_regex:/[a-z]/|min:4|unique:contacts,tel_no,' . $id;
        }

        $data = $request->validate( $rules );

        return $data;
    }

}
