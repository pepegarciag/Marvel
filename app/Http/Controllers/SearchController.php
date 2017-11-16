<?php

namespace App\Http\Controllers;

use App\Marvel\Contracts\MarvelContract;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{

    /**
     * @var \App\Marvel\Contracts\MarvelContract
     */
    protected $marvel;

    public function __construct(MarvelContract $marvel)
    {
        $this->marvel = $marvel;
    }

    /**
     * Handle search based on user submitted input.
     *
     * @param \Illuminate\Http\Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function handle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'character' => 'required',
        ], [
            'character.required' => 'El campo personaje es obligatorio.',
        ]);

        if ($validator->fails()) {
            return redirect('/')->withErrors($validator);
        }

        // Get character info.
        $characterName = $request->get('character');
        $response = $this->marvel->getCharacterByName($characterName);
        $character = reset($response['data']['results']);

        if (empty($character)) {
            $request->session()->flash('character', "No hay personajes con el nombre: {$characterName}");
            return redirect('/');
        }

        // Get character comics info.
        $current_page = LengthAwarePaginator::resolveCurrentPage();
        $items_per_page = 4;
        $response = $this->marvel->getCharacterComics($character['id'], $items_per_page * $current_page);
        $comics = $response['data']['results'];

        // Build paginator.
        $paginatedResults = new LengthAwarePaginator($comics, $response['data']['total'], $items_per_page, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('character', ['character' => $character, 'comics' => $paginatedResults]);
    }

}
