<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Odor;
use App\User;
use App\Comment;
use Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id_odor' => 'required|exists:odors,id',
            'id_user' => 'required|exists:users,id',
            'comment' => 'required|max:1024',
        ]);

        if ( $validator->passes() ) {
        
            $comment = new Comment($request->all());
            $comment->save();

            return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'created' => true,
                    'message' => "Comment added to Odor(".$request->get('id_odor').")",
                    'object' => $comment,
                ]
            ], 200);
        }

        $aux = $validator->errors();
        if ($aux->has('id_odor')) { $errors['id_odor'] = $aux->get('id_odor'); }
        if ($aux->has('id_user')) { $errors['id_user'] = $aux->get('id_user'); }
        if ($aux->has('comment')) { $errors['comment'] = $aux->get('comment'); }

        return response()->json(
        [
            'status_code' => 400,
            'data' => [
                'created' => false,
                'message' => "There are some errors in the form data.",
                'errors' => $errors,
            ]
        ], 400);
    }

    public function hide(Request $request, $id){
        $comment = Comment::find($id);

        $comment->hidden = 1;
        $comment->save();

        return response()->json(
            [
                'status_code' => 200,
                'data' => [
                    'message' => "Comment ".$id." deleted",
                    'comment' => $comment,
                ]
            ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
