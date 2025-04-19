<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artifact;

class ArtifactController extends Controller
{
    //Get All Artifacts

    public function index()
    {
        return response()->json([
            'message'=>'All artifacts retrieved successsfully',
            'data'=>Artifact::all(),
        ],200);
    }

     //Get  Artifacts in Egypt
     public function egyptArtifact()
     {
       $artifacts=Artifact::where('location','egypt')->get();    

         return response()->json([
             'message'=>'All artifacts in Egypt retrieved successfully',
             'data'=>$artifacts,
         ],200);
     }

          //Get  Artifacts outside Egypt
          public function outsideArtifact()
          {
            $artifacts=Artifact::where('location','outside')->get();    
     
              return response()->json([
                  'message'=>'All artifacts outside Egypt retrieved successfully',
                  'data'=>$artifacts,
              ],200);
          }
          
          

        //Show Determined  Artifact
        public function show($id)
        {
            $artifact=Artifact::findOrFail($id);  

            return response()->json([
                'message'=>'Artifact retrieved successfully',
                'data'=>$artifact,
            ],200);
        }





        //store  Artifacts 

        public function store(Request $request)
        {
            $validatedData=$request->validate(
                [
                    'name'=>'required|string|max:255',
                    'description'=>'required|string',
                    'image'=>'required|image|mimes:jpg,jpeg,png,gif|max:2048',
                    'location'=>'required|in:egypt,outside',
                ]
                );
               
                $artifact = new Artifact($validatedData);

                //Handle image file
                if($request->hasFile('image'))
                {
                    $fileName=time() . '.' . $request->image->extension();
                    $request->image->move(public_path('uploads/artifacts/'),$fileName);
                    $artifact->image='uploads/artifacts/' . $fileName;
                }

                // else
                // {
                //     $noImage='uploads/artifacts/' . 'not_found.jpg';
                // }

                 $artifact->save();
    
            return response()->json([
                'message'=>'Artifact created successfully',
                'data'=>$artifact,
            ],201);
        }




          //store  Artifacts 

          public function update(Request $request ,$id)
          {

            $artifact=Artifact::findOrFail($id);
              $validatedData=$request->validate(
                  [
                      'name'=>'required|string|max:255',
                      'description'=>'required|string',
                      'image'=>'required|image|mimes:jpg,jpeg,png,gif|max:2048',
                      'location'=>'required|in:egypt,outside',
                  ]
                  );
                 
                //   $artifact=Artifact::update($validatedData);
  
                  //Handle image file
                  if($request->hasFile('image'))
                  {           
                    //Delete Old Image
                    if(!empty($artifact->image && file_exists(public_path($artifact->image))))
                    {
                      unlink(public_path($artifact->image));
                    }
                      $fileName=time() . '.' . $request->image->extension();
                      $request->image->move(public_path('uploads/artifacts/'),$fileName);
                      $validatedData['image']='uploads/artifacts/' . $fileName;
                  }
  
                   $artifact->update($validatedData);
      
              return response()->json([
                  'message'=>'Artifact Updated successfully',
                  'data'=>$artifact,
              ],200);
          }






      //Show Determined  Artifact

        public function destroy($id)
        {
            $artifact=Artifact::findOrFail($id);  

            //Delete Old Image
            if(!empty($artifact->image && file_exists(public_path($artifact->image))))
            {
                unlink(public_path($artifact->image));
            }

            $artifact->delete();
            return response()->json([
                'message'=>'Artifact deleted successfully',
                'data'=>$artifact,
            ],200);
        }

}
