<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RecommendationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'seedTracks' => ['array'],
            'seedTracks.*' => 'exists:spotify_tracks',
            'limit' => 'integer,min:1,max:100',
            'acousticness' => ['array'],
            'acousticness.min' => 'numeric,min:0,max:1',
            'acousticness.max' => 'numeric,min:0,max:1',
            'acousticness.target' => 'numeric,min:0,max:1',
            'danceability' => ['array'],
            'danceability.min' => 'numeric,min:0,max:1',
            'danceability.max' => 'numeric,min:0,max:1',
            'danceability.target' => 'numeric,min:0,max:1',
            'duration_ms' => ['array'],
            'duration_ms.min' => 'numeric,min:0,max:9999999999',
            'duration_ms.max' => 'numeric,min:0,max:9999999999',
            'duration_ms.target' => 'numeric,min:0,max:9999999999',
            'energy' => ['array'],
            'energy.min' => 'numeric,min:0,max:1',
            'energy.max' => 'numeric,min:0,max:1',
            'energy.target' => 'numeric,min:0,max:1',
            'instrumentalness' => ['array'],
            'instrumentalness.min' => 'numeric,min:0,max:1',
            'instrumentalness.max' => 'numeric,min:0,max:1',
            'instrumentalness.target' => 'numeric,min:0,max:1',
            'mode' => ['array'],
            'mode.min' => 'numeric,min:0,max:1',
            'mode.max' => 'numeric,min:0,max:1',
            'mode.target' => 'numeric,min:0,max:1',
            'popularity' => ['array'],
            'popularity.min' => 'numeric,min:0,max:100',
            'popularity.max' => 'numeric,min:0,max:100',
            'popularity.target' => 'numeric,min:0,max:100',
            'speechiness' => ['array'],
            'speechiness.min' => 'numeric,min:0,max:1',
            'speechiness.max' => 'numeric,min:0,max:1',
            'speechiness.target' => 'numeric,min:0,max:1',
            'tempo' => ['array'],
            'tempo.min' => 'numeric,min:50,max:240',
            'tempo.max' => 'numeric,min:50,max:240',
            'tempo.target' => 'numeric,min:50,max:240',
            'valence' => ['array'],
            'valence.min' => 'numeric,min:0,max:1',
            'valence.max' => 'numeric,min:0,max:1',
            'valence.target' => 'numeric,min:0,max:1',
        ];
    }
}
