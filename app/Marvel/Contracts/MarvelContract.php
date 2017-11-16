<?php

namespace App\Marvel\Contracts;

interface MarvelContract
{

  /**
   * Get a character by his name.
   *
   * @param string $name
   * @return mixed
   */
  public function getCharacterByName(string $name);

  /**
   * Get character comics.
   *
   * @param int $characterId
   * @return mixed
   */
  public function getCharacterComics(int $characterId);
}