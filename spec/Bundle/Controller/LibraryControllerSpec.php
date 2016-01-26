<?php

namespace spec\App\Bundle\Controller;

use App\Domain\Isbn;
use App\Domain\LibraryInterface;
use App\Domain\SearchResults;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LibraryControllerSpec extends ObjectBehavior
{
    function let(LibraryInterface $library, EngineInterface $templatingEngine)
    {
        $this->beConstructedWith($library, $templatingEngine);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Bundle\Controller\LibraryController');
    }

    function it_renders_empty_results_by_default(
        Request $request,
        ParameterBag $requestQueryParameters,
        EngineInterface $templatingEngine,
        Response $response
    ) {
        $request->query = $requestQueryParameters;
        $requestQueryParameters->has('isbn')->willReturn(false);

        $templatingEngine->renderResponse('search.html.twig', ['results' => SearchResults::asEmpty()])->willReturn($response);

        $this->searchByIsbnAction($request)->shouldReturn($response);
    }

    function it_renders_library_search_by_isbn_results(
        Request $request,
        ParameterBag $requestQueryParameters,
        LibraryInterface $library,
        SearchResults $searchResults,
        EngineInterface $templatingEngine,
        Response $response
    ) {
        $request->query = $requestQueryParameters;
        $requestQueryParameters->has('isbn')->willReturn(true);
        $requestQueryParameters->get('isbn')->willReturn('978-1-56619-909-4');

        $library->searchByIsbn(new Isbn('978-1-56619-909-4'))->willReturn($searchResults);
        $templatingEngine->renderResponse('search.html.twig', ['results' => $searchResults])->willReturn($response);
        
        $this->searchByIsbnAction($request)->shouldReturn($response);
    }
}
