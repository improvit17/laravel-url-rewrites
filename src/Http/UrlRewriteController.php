<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Http;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use RuthgerIdema\UrlRewrite\Repositories\Interfaces\UrlRewriteInterface;

class UrlRewriteController
{
    /** @var UrlRewriteInterface */
    protected $repository;

    public function __construct(
        UrlRewriteInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke($url = ''): object
    {
        // Een request dat naar een leeg pad decodeert (bijv. "/%2f" van scan-/probe-verkeer)
        // levert een lege of ontbrekende {url}-parameter op. Val dan netjes terug op een 404
        // in plaats van een ArgumentCountError/500.
        if ($url === '' || $url === null) {
            abort(404);
        }

        if (! $urlRewrite = $this->repository->getByRequestPath($url)) {
            abort(404);
        }

        if ($urlRewrite->isForward()) {
            return $this->forwardResponse($urlRewrite->target_path);
        }

        return redirect($urlRewrite->target_path, $urlRewrite->getRedirectType());
    }

    protected function forwardResponse($url)
    {
        $target = ltrim($url, '/');
        // Encode padsegmenten zodat spaties, komma's en andere tekens in id's
        // (bijv. AFAS-categorie "PS 4,8") niet als losse URI-tekens worden geïnterpreteerd,
        // en trim eventuele trailing whitespace/control chars die Symfony's strikte
        // Request::create()-validatie zou blokkeren.
        $target = implode('/', array_map('rawurlencode', explode('/', $target)));
        $url = trim(tenant()->route('cats.index') . '/' . $target);

        return Route::dispatch(
            Request::create(
                $url,
                request()->getMethod()
            )
        );
    }
}
