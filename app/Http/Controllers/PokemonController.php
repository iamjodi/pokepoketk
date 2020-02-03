<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Mail;
use Carbon\Carbon;

use Illuminate\Support\Facades\Crypt;

class PokemonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
        return view('welcome');
    }

    public function catchPokemon(Request $request)
    {   
        $data = array(
            'moves' => $request->moves,
            'nickname' => $request->nickname,
            'owned' => 1,
            'created_date' => Carbon::now()
        );

        $result = DB::connection('mysql')->table('pokemon')->where('id',$request->id)->update($data);

        return response()->json($result);
    }

    public function releasePokemon($id)
    {   
        $data = array(
            'owned' => 0,
            'last_updated_date' => Carbon::now()
        );

        $result = DB::connection('mysql')->table('pokemon')->where('id',$id)->update($data);

        return response()->json($result);
    }

    public function getList(Request $request) {

		$res = self::CallAPI('GET','https://pokeapi.co/api/v2/pokemon/?offset=0&limit=1000');
    	$temp = json_decode($res);

    	$temp = $temp->results;

    	for($i=0; $i<count($temp); $i++){

    		$listdata = DB::connection('mysql')->table('pokemon')->where('name',$temp[$i]->name)->get();
    		
    		if(count($listdata)==0){

    			$data = array(
		    		'name' => $temp[$i]->name
		    	);

	    		$result = DB::connection('mysql')->table('pokemon')->insert($data);
    		}

    	}

		return response()->json($temp);

    }

    public function getDetail($id) {

        $search = DB::connection('mysql')->table('pokemon')->where('id',$id)->first();

		$res = self::CallAPI('GET','https://pokeapi.co/api/v2/pokemon/'.$search->name);
    	$temp = json_decode($res);

    	$moves = array();
        $move = '';

    	for($i=0;$i<count($temp->moves);$i++){
            $move = $move.$temp->moves[$i]->move->name.', ';
    	}

    	$data = array(
            'id' => $id,
    		'name' => $temp->name,
    		'moves' => $move,
            'nickname' => $search->nickname,
            'owned' => $search->owned
    	);

		return response()->json($data);

    }

    public function getDetailMyPokemon($id) {

        $search = DB::connection('mysql')->table('pokemon')->where('id',$id)->first();

        $data = array(
            'id' => $id,
            'name' => $search->name,
            'moves' => $search->moves,
            'owned' => $search->owned,
            'nickname' => $search->nickname,
            'date' => $search->created_date
        );

        return response()->json($data);

    }

    public function dataPaginate(Request $request) {
    	$sort = $request->sort;
        $sort = explode('|', $sort);
        $sortBy = $sort[0];
        $sortDirection = $sort[1];
        $perPage = $request->per_page;

        $search = $request->pokemon;

    	$data = DB::connection('mysql')->table('pokemon');
    	
        if($search){
            $like = "%{$search}%";
            $data = $data->where('name','LIKE',$like);
        }

        $data = $data->orderBy($sortBy, $sortDirection)
            ->paginate(request('limit', $perPage));

		return response()->json($data);
    	
    }

    public function getMyPokemonPaginate(Request $request) {
        $sort = $request->sort;
        $sort = explode('|', $sort);
        $sortBy = $sort[0];
        $sortDirection = $sort[1];
        $perPage = $request->per_page;

        $search = $request->pokemon;

        $data = DB::connection('mysql')->table('pokemon');
        
        if($search){
            $like = "%{$search}%";
            $data = $data->where('name','LIKE',$like);
        }

        $data = $data
            ->where('owned','1')
            ->orderBy($sortBy, $sortDirection)
            ->paginate(request('limit', $perPage));

        return response()->json($data);
        
    }

    function CallAPI($method, $url, $data = false)
	{
	    $curl = curl_init();

	    switch ($method)
	    {
	        case "POST":
	            curl_setopt($curl, CURLOPT_POST, 1);

	            if ($data)
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	            break;
	        case "PUT":
	            curl_setopt($curl, CURLOPT_PUT, 1);
	            break;
	        default:
	            if ($data)
	                $url = sprintf("%s?%s", $url, http_build_query($data));
	    }

	    // Optional Authentication:
	    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
	    curl_setopt($curl, CURLOPT_TIMEOUT, 3000);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

	    return $result;
	}
}
