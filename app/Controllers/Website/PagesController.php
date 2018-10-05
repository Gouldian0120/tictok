<?php

namespace Bpocallaghan\Titan\Http\Controllers\Website;

use Bpocallaghan\Titan\Models\Changelog;
use Bpocallaghan\Titan\Models\Testimonial;
use Redirect;
use Bpocallaghan\Titan\Http\Requests;
use Bpocallaghan\Titan\Models\Page;
use Illuminate\Http\Request;
use Bpocallaghan\Titan\Http\Controllers\Admin\AdminController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PagesController extends WebsiteController
{
    /**
     * Display a listing of page.
     *
     * @param      $slug1
     * @param null $slug2
     * @param null $slug3
     * @return Response
     */
    public function index($slug1, $slug2 = null, $slug3 = null)
    {
        $url = $this->getCurrentUrl();

        $page = Page::with('components.component')->where('url', $url)->first();
        if (!$page) {
            throw new NotFoundHttpException();
        }

        // find out if its a 'main page' and get the children
        $children = $this->findChildrenPages($page);

        return $this->view('pages.page')
            ->with('activePage', $page)
            ->with('childrenPages', $children);
    }

    /**
     * Get the children pages
     * @param Page $page
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function findChildrenPages(Page $page)
    {
        $pages = Page::where('parent_id', $page->id)->orderBy('header_order')->get();

        return $pages;
    }
}
