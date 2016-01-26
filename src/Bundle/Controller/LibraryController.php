<?php

namespace App\Bundle\Controller;

use App\Domain\Isbn;
use App\Domain\LibraryInterface;
use App\Domain\SearchResults;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;

class LibraryController
{
    private $library;
    private $templatingEngine;

    public function __construct(LibraryInterface $library, EngineInterface $templatingEngine)
    {
        $this->library = $library;
        $this->templatingEngine = $templatingEngine;
    }

    public function searchByIsbnAction(Request $request)
    {
        $searchResults = SearchResults::asEmpty();

        if ($request->query->has('isbn')) {
            $searchResults = $this->library->searchByIsbn(new Isbn($request->query->get('isbn')));
        }

        return $this->templatingEngine->renderResponse('search.html.twig', ['results' => $searchResults]);
    }
}
