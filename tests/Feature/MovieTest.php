<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_movies_found_list()
    {
        Movie::create([
            'title' => 'dummy title',
            'episode_id' => 1,
            'opening_crawl' => 'dummy opening_crawl',
            'director' => 'dummy director',
            'producer' => 'dummy producer',
            'release_date' =>  'dummy release_date',
            'url' =>  'dummy url'
        ]);
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => "2023-07-07T15:09:51.000000Z",
            'status' => User::ACTIVE,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/movies');
        $response->assertStatus(200);
    }

    public function test__find_movie_by_id()
    {
        $movie = Movie::create([
            'title' => 'dummy title',
            'episode_id' => 1,
            'opening_crawl' => 'dummy opening_crawl',
            'director' => 'dummy director',
            'producer' => 'dummy producer',
            'release_date' =>  'dummy release_date',
            'url' =>  'dummy url'
        ]);
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => "2023-07-07T15:09:51.000000Z",
            'status' => User::ACTIVE,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/movies/' . $movie->episode_id);
        $response->assertStatus(200);
    }

    public function test_movie_not_found_by_id()
    {
        $movie = Movie::create([
            'title' => 'dummy title',
            'episode_id' => 1,
            'opening_crawl' => 'dummy opening_crawl',
            'director' => 'dummy director',
            'producer' => 'dummy producer',
            'release_date' =>  'dummy release_date',
            'url' =>  'dummy url'
        ]);
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => "2023-07-07T15:09:51.000000Z",
            'status' => User::ACTIVE,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/movies/' . 21212);
        $response->assertStatus(404)->assertJson([
            'success' => false,
            'message' => 'movie not found',
            'data' => [],
        ]);;
    }

    public function test_update_movie()
    {
        $movie = Movie::create([
            'title' => 'dummy title',
            'episode_id' => 1,
            'opening_crawl' => 'dummy opening_crawl',
            'director' => 'dummy director',
            'producer' => 'dummy producer',
            'release_date' =>  'dummy release_date',
            'url' =>  'dummy url'
        ]);
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => "2023-07-07T15:09:51.000000Z",
            'status' => User::ACTIVE,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;
        $data = [
            "title"         => "A New Hope",
            "episode_id"    => "4",
            "opening_crawl" => "It is a period of civil war.\r\nRebel spaceships, striking\r\nfrom a hidden base, have won\r\ntheir first victory against\r\nthe evil Galactic Empire.\r\n\r\nDuring the battle, Rebel\r\nspies managed to steal secret\r\nplans to the Empire's\r\nultimate weapon, the DEATH\r\nSTAR, an armored space\r\nstation with enough power\r\nto destroy an entire planet.\r\n\r\nPursued by the Empire's\r\nsinister agents, Princess\r\nLeia races home aboard her\r\nstarship, custodian of the\r\nstolen plans that can save her\r\npeople and restore\r\nfreedom to the galaxy....",
            "director"      => "George Lucas",
            "producer"      => "Gary Kurtz, Rick McCallum",
            "release_date"  => "1977-05-25",
            "url"           => "https://swapi.dev/api/films/1/"
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/movies/' . $movie->episode_id, $data);
        $response->assertStatus(200);
    }

    public function test_update_movie_validation_error()
    {
        $movie = Movie::create([
            'title' => 'dummy title',
            'episode_id' => 1,
            'opening_crawl' => 'dummy opening_crawl',
            'director' => 'dummy director',
            'producer' => 'dummy producer',
            'release_date' =>  'dummy release_date',
            'url' =>  'dummy url'
        ]);
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => "2023-07-07T15:09:51.000000Z",
            'status' => User::ACTIVE,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;
        $data = [
            "title"         => "",
            "episode_id"    => "4",
            "opening_crawl" => "It is a period of civil war.\r\nRebel spaceships, striking\r\nfrom a hidden base, have won\r\ntheir first victory against\r\nthe evil Galactic Empire.\r\n\r\nDuring the battle, Rebel\r\nspies managed to steal secret\r\nplans to the Empire's\r\nultimate weapon, the DEATH\r\nSTAR, an armored space\r\nstation with enough power\r\nto destroy an entire planet.\r\n\r\nPursued by the Empire's\r\nsinister agents, Princess\r\nLeia races home aboard her\r\nstarship, custodian of the\r\nstolen plans that can save her\r\npeople and restore\r\nfreedom to the galaxy....",
            "director"      => "George Lucas",
            "producer"      => "Gary Kurtz, Rick McCallum",
            "release_date"  => "1977-05-25",
            "url"           => "https://swapi.dev/api/films/1/"
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/movies/' . $movie->episode_id, $data);
        $response->assertStatus(422)->assertJson([
            'success' => false,
            'message' => 'The title field is required.',
            'data' => [],
        ]);;
    }

    public function test_delete_movie()
    {
        $movie = Movie::create([
            'title' => 'dummy title',
            'episode_id' => 1,
            'opening_crawl' => 'dummy opening_crawl',
            'director' => 'dummy director',
            'producer' => 'dummy producer',
            'release_date' =>  'dummy release_date',
            'url' =>  'dummy url'
        ]);
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => "2023-07-07T15:09:51.000000Z",
            'status' => User::ACTIVE,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/movies/' . $movie->episode_id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'movie deleted',
            'data' => [],
        ]);;
    }
}
