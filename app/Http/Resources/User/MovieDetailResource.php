<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'episode_id'    => $this->episode_id,
            'title'         => $this->title,
            'opening_crawl' => $this->opening_crawl,
            'director'      => $this->director,
            'producer'      => $this->producer,
            'release_date'  => $this->release_date,
            'url'           => $this->url,
            "created_at"    => $this->created_at,
            "updated_at"    => $this->updated_at,
            "planets"       => $this->planets,
            "characters"    => $this->characters,
            "vehicles"      => $this->vehicles,
            "starships"     => $this->starships,
            "spacies"       => $this->spacies,
        ];
    }
}
