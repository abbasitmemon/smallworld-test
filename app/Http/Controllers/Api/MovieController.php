<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Movie\UpdateMovieRequest;
use App\Http\Resources\User\MovieDetailResource;
use App\Http\Resources\User\MovieResource;
use App\Jobs\StoreMoviesToDatabaseJob;
use App\Models\Character;
use App\Models\Movie;
use App\Models\Planet;
use App\Models\Specie;
use App\Models\Starship;
use App\Models\Vehicle;
use App\Services\MovieService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    protected $movies;

    public function __construct()
    {
        $this->movies = new MovieService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $RadisReponse = Redis::get('movies.all');
            $movies = Movie::all();
            if (!$movies->count() > 0 && !$RadisReponse) {
                // if movies not found then it will hit API then store response to radis and dispath queue job to store data in database
                // Calling Api Start
                $movies = $this->movies->getMovies();
                // Calling Api END

                // Storing response data to radis cache START
                Redis::set('movies.all', $movies);
                $RadisReponse = Redis::get('movies.all');

                // Storing response data to radis cache END

                //Dispatching queue job START
                StoreMoviesToDatabaseJob::dispatch()->delay(now()->addSecond(2));
                //Dispatching queue job END
            }
            if ($RadisReponse) {
                $movies = Redis::get('movies.all');
                $movies = json_decode($movies);
                $movies = Movie::hydrate($movies->results);
            }
            $movies = MovieResource::collection($movies);
            if ($request->search) {
                $movies = $movies->filter(function ($item) use ($request) {
                    return Str::contains(strtolower($item->title), strtolower($request->search));
                })->values();
            }
            if (!$movies)
                return apiResponse(false, "movies not found", [], 404);
            return apiResponse(true, "movies found", $movies, 200);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $episode_id)
    {
        try {
            $movie = Movie::where('episode_id', $episode_id)->with(['planets', 'characters', 'vehicles', 'starships', 'spacies'])->first();
            if (!$movie)
                return apiResponse(false, "movie not found", [], 404);
            return apiResponse(true, "movie found", new MovieDetailResource($movie), 200);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function update(UpdateMovieRequest $request, int $episode_id)
    {
        try {
            DB::beginTransaction();
            $movie = Movie::where('episode_id', $episode_id)->first();
            if (!$movie)
                return apiResponse(true, "movie not found", [], 404);
            $movie->title = $request->title;
            $movie->opening_crawl = $request->opening_crawl;
            $movie->director = $request->director;
            $movie->producer = $request->producer;
            $movie->release_date = $request->release_date;
            $movie->url = $request->url;
            $movie->save();
            DB::commit();
            return apiResponse(true, "movie updated", new MovieResource($movie), 200);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $episode_id)
    {
        try {
            DB::beginTransaction();
            $movie = Movie::where('episode_id', $episode_id)->first();
            if (!$movie)
                return apiResponse(true, "movie not found", [], 404);

            Character::where('movie_id', $movie->id)->delete();
            Vehicle::where('movie_id', $movie->id)->delete();
            Starship::where('movie_id', $movie->id)->delete();
            Specie::where('movie_id', $movie->id)->delete();
            Planet::where('movie_id', $movie->id)->delete();
            $movie->delete();
            DB::commit();
            return apiResponse(true, "movie deleted", [], 200);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }
}
