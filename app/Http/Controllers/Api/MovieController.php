<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Movie\UpdateMovieRequest;
use App\Http\Resources\User\MovieResource;
use App\Models\Movie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $movies = Movie::query();
            $movies->when($request->search, function ($query) use ($request) {
                return $query->where('title', 'like', "%" . $request->search . "%");
            });
            $movies = $movies->orderBy('id', 'desc')->paginate(10);
            $movies = MovieResource::collection($movies)->response()->getData(true);
            if ($movies['meta']['total'] <= 0)
                return apiResponse(false, "movies not found", [], 404);
            return apiResponse(true, "movies found", $movies, 200);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $movie = Movie::find($id);
            if (!$movie)
                return apiResponse(false, "movie not found", [], 404);
            return apiResponse(true, "movie found", $movie, 200);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function update(UpdateMovieRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $movie = Movie::find($id);
            if (!$movie)
                return apiResponse(true, "movie not found", [], 404);
            $movie->title = $request->title;
            $movie->episode_id = $request->episode_id;
            $movie->opening_crawl = $request->opening_crawl;
            $movie->director = $request->director;
            $movie->producer = $request->producer;
            $movie->release_date = $request->release_date;
            $movie->url = $request->url;
            $movie->save();
            DB::commit();
            return apiResponse(true, "movie updated", $movie, 200);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $movie = Movie::find($id);
            if (!$movie)
                return apiResponse(true, "movie not found", [], 404);
            $movie->delete();
            DB::commit();
            return apiResponse(true, "movie deleted", [], 200);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }
}
