<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Services\MovieService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    protected $movies;

    public function __construct()
    {
        $this->movies = new MovieService();
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = $this->movies->getMovies();
        $new = json_decode($movies);
        $movieList = [];
        foreach ($new->results as $key => $movie) {
            $movieList[] = [
                'title'         => $movie->title,
                'episode_id'    => $movie->episode_id,
                'opening_crawl' => $movie->opening_crawl,
                'director'      => $movie->director,
                'producer'      => $movie->producer,
                'release_date'  => $movie->release_date,
                'url'           => $movie->url,
                'created_at'    => Carbon::now(),
            ];
        }
        // dd($movieList);
        Movie::insert($movieList);
    }
}
