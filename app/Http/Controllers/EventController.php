<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Event;
use App\http\resources\EventCollection;
use App\http\resources\EventResource;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public $user_data;
    public $destination;

    public function __construct(){
        $this->destination = 'upload';
        $this->user_data = Auth::user();
    }

    public function create(Request $request)
    {
        if($request->api_token == $this->user_data['api_token']){
            try {
                
                if(!empty($request->file('image'))){
                    $file = $request->file('image');
                    $file->move($this->upload,$file->getClientOriginalName());
                }
                
                $data = new Event();
                $data->name = $request->name;  
                $data->address = $request->name;  
                $data->date = $request->date;  
                $data->description = $request->description;  
                
                if(!empty($request->file('image'))){
                    $data->image_url = $destination.'/'.$file->getClientOriginalName();
                }

                $data->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil disimpan',
                    'data' => $data
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data gagal disimpan',
                    'data' => $e
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
        }
    }

    public function update(Request $request , $id)
    {
        if($request->api_token == $this->user_data['api_token']){
            $data = Event::find(id);

            if(!empty($request->file('image'))){
                if(!empty($data->image_url)){
                    File::delete(public_path().'/'.$data->image_url);
                }

                $file = $request->file('image');
                $file->move($this->upload,$file->getClientOriginalName());

            }

            $data->name = $request->name;  
            $data->address = $request->name;  
            $data->date = $request->date;  
            $data->description = $request->description;  
            
            if(!empty($request->file('image'))){
                $data->image_url = $destination.'/'.$file->getClientOriginalName();
            }

            $data->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $data
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
        }
        
        
    }

    public function destroy(Request $request, $id){
        if($request->api_token == $this->user_data['api_token']){
            $data = Event::find($id);
            
            if(!empty($data->image_url)){
                File::delete(public_path().'/'.$data->image_url);
            }
            
            Event::destroy($id);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di hapus'
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
        }
    }

    public function index()
    {
        $data = Event::all();

        return response()->json([
            'success' => true,
            'data' => new EventCollection($data)
        ]);
    }

    public function join(Request $request)
    {
        if($request->api_token == $this->user_data['api_token']){
           
            $data = Event::where('id', $request->event_id);
            $data->Users->attach($request->user_id);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di simpan'
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ]);
        }
    }

    public function getEvent(Request $request, $id)
    {
        if($request->api_token == $this->user_data['api_token']){
            $data = Event::where('id', $id)->first();
 
            return new EventResource($data);
 
         }else{
             return response()->json([
                 'success' => false,
                 'message' => 'Unauthenticated'
             ]);
         }
    }
}
