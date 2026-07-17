<?php

namespace Modules\Website\Classes\Support;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Modules\Website\Models\Page;
use Modules\Website\Models\PageSection;

class PageRenderer
{
    public function __construct(
        protected ThemeManager $themes,
    ) {}

    /**
     * @return array{page: Page, sections: Collection<int, PageSection>, themes: ThemeManager, pageLayout: ?string}
     */
    public function forPage(Page $page): array
    {
        $sections = $page->sections()
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();

        $pageLayout = $this->themes->resolveLayout($page->layout);

        return [
            'page' => $page,
            'sections' => $sections,
            'themes' => $this->themes,
            'pageLayout' => $pageLayout,
        ];
    }

    public function render(Page $page): View
    {
        $data = $this->forPage($page);

        return view($this->themes->pageViewName($page->layout), $data);
    }
}
