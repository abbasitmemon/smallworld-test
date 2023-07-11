<?php

namespace App\Jobs;

use App\Models\Character;
use App\Models\Movie;
use App\Models\Planet;
use App\Models\Specie;
use App\Models\Starship;
use App\Models\Vehicle;
use App\Services\MovieService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use PhpParser\Node\Expr\BinaryOp\Spaceship;

class StoreMoviesToDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $timeout = 0;

    protected $movies;

    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $movieService = new MovieService();
        $movies = Redis::get('movies.all');
        if ($movies) {
            $new = json_decode($movies);
            $movieList = [];
            foreach ($new->results as $key => $movie) {
                $movieCreated = Movie::create([
                    'title'         => $movie->title,
                    'episode_id'    => $movie->episode_id,
                    'opening_crawl' => $movie->opening_crawl,
                    'director'      => $movie->director,
                    'producer'      => $movie->producer,
                    'release_date'  => $movie->release_date,
                    'url'           => $movie->url,
                    'created_at'    => Carbon::now(),
                ]);
                if ($movie->characters) {
                    foreach ($movie->characters as $characterUrl) {
                        $character = $movieService->getExtraData($characterUrl);
                        Character::create([
                            'name'          => $character->name,
                            'height'        => $character->height,
                            'mass'          => $character->mass,
                            'hair_color'    => $character->hair_color,
                            'skin_color'    => $character->skin_color,
                            'eye_color'     => $character->eye_color,
                            'gender'        => $character->gender,
                            'movie_id'      => $movieCreated->episode_id,
                        ]);
                    }
                }
                if ($movie->planets) {
                    foreach ($movie->planets as $planetUrl) {
                        $planet = $movieService->getExtraData($planetUrl);
                        Planet::create([
                            'name'              => $planet->name,
                            'rotation_period'   => $planet->rotation_period,
                            'orbital_period'    => $planet->orbital_period,
                            'diameter'          => $planet->diameter,
                            'climate'           => $planet->climate,
                            'gravity'           => $planet->gravity,
                            'terrain'           => $planet->terrain,
                            'surface_water'     => $planet->surface_water,
                            'population'        => $planet->population,
                            'movie_id'          => $movieCreated->episode_id,
                        ]);
                    }
                }

                if ($movie->starships) {
                    foreach ($movie->starships as $starshipUrl) {
                        $starship = $movieService->getExtraData($starshipUrl);
                        Starship::create([
                            'name'                      => $starship->name,
                            'model'                     => $starship->model,
                            'manufacturer'              => $starship->manufacturer,
                            'cost_in_credits'           => $starship->cost_in_credits,
                            'length'                    => $starship->length,
                            'max_atmosphering_speed'    => $starship->max_atmosphering_speed,
                            'crew'                      => $starship->crew,
                            'passengers'                => $starship->passengers,
                            'cargo_capacity'            => $starship->cargo_capacity,
                            'consumables'               => $starship->consumables,
                            'hyperdrive_rating'         => $starship->hyperdrive_rating,
                            'MGLT'                      => $starship->MGLT,
                            'starship_class'            => $starship->starship_class,
                            'movie_id'                  => $movieCreated->episode_id,
                        ]);
                    }
                }

                if ($movie->vehicles) {
                    foreach ($movie->vehicles as $vehicleUrl) {
                        $vehicle = $movieService->getExtraData($vehicleUrl);
                        Vehicle::create([
                            'name'                      => $vehicle->name,
                            'model'                     => $vehicle->model,
                            'manufacturer'              => $vehicle->manufacturer,
                            'cost_in_credits'           => $vehicle->cost_in_credits,
                            'length'                    => $vehicle->length,
                            'max_atmosphering_speed'    => $vehicle->max_atmosphering_speed,
                            'crew'                      => $vehicle->crew,
                            'passengers'                => $vehicle->passengers,
                            'cargo_capacity'            => $vehicle->cargo_capacity,
                            'consumables'               => $vehicle->consumables,
                            'vehicle_class'             => $vehicle->vehicle_class,
                            'movie_id'                  => $movieCreated->episode_id,
                        ]);
                    }
                }

                if ($movie->species) {
                    foreach ($movie->species as $specieUrl) {
                        $specie = $movieService->getExtraData($specieUrl);
                        Specie::create([
                            'name'              => $specie->name,
                            'classification'    => $specie->classification,
                            'designation'       => $specie->designation,
                            'average_height'    => $specie->average_height,
                            'skin_colors'       => $specie->skin_colors,
                            'hair_colors'       => $specie->hair_colors,
                            'eye_colors'        => $specie->eye_colors,
                            'average_lifespan'  => $specie->average_lifespan,
                            'language'          => $specie->language,
                            'movie_id'          => $movieCreated->episode_id,
                        ]);
                    }
                }
            }
        }
        Redis::del('movies.all');
    }
}
