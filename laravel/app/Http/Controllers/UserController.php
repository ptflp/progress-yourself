<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
 
    public function show($id)
    {
        return User::find($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        return User::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $article = User::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = User::findOrFail($id);
        $article->delete();

        return 204;
    }
    
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'photo' => 'required',
            'uid' => 'required'
        ]);
        $params = $request->all();
        $id = $params['uid'];
        $image = $params['photo'];
        $path = '/images/user/' . $id;
        $destinationPath = public_path($path);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $name = time().'.png';
        $dataPath = $path . "/" . $name;
        $this->base64_to_jpeg($image, public_path($dataPath));
        $user = User::findOrFail($id);
        $user->avatar = $dataPath;
        $user->save();
        return $user;
    }
    
    public function base64_to_jpeg($base64_string,  $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 
    
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );
    
        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    
        // clean up the file resource
        fclose( $ifp ); 
    
        return $output_file; 
    }
}
