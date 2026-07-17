<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Enums\MenuItemType;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;
use Modules\Website\Models\ContactSubmission;
use Modules\Website\Models\GalleryAlbum;
use Modules\Website\Models\GalleryItem;
use Modules\Website\Models\Menu;
use Modules\Website\Models\MenuItem;
use Modules\Website\Models\Page;
use Modules\Website\Models\PageSection;
use Modules\Website\Models\Partner;
use Modules\Website\Models\Post;
use Modules\Website\Models\TeamMember;
use Tests\TestCase;

class WebsiteCmsModelsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requireModule('Website');
        $this->migrateModules(['Website']);
    }

    public function test_page_factory_creates_page_with_ordered_sections(): void
    {
        $page = Page::factory()->create([
            'type' => PageType::Home,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'type' => SectionType::Cta,
            'payload' => ['heading' => 'Second'],
            'sort_order' => 2,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'type' => SectionType::Hero,
            'payload' => ['heading' => 'First'],
            'sort_order' => 1,
        ]);

        $sections = $page->fresh()->sections;

        $this->assertCount(2, $sections);
        $this->assertSame(SectionType::Hero, $sections->first()->type);
        $this->assertSame(1, $sections->first()->sort_order);
        $this->assertSame(2, $sections->last()->sort_order);
    }

    public function test_page_published_scope_filters_unpublished(): void
    {
        Page::factory()->create(['is_published' => false]);
        $published = Page::factory()->published()->create();

        $results = Page::query()->published()->get();

        $this->assertTrue($results->contains('id', $published->id));
        $this->assertCount(1, $results->where('is_published', true));
        $this->assertSame(0, Page::query()->published()->where('is_published', false)->count());
    }

    public function test_post_team_member_partner_and_contact_factories(): void
    {
        $post = Post::factory()->published()->create();
        $member = TeamMember::factory()->published()->create();
        $partner = Partner::factory()->create();
        $submission = ContactSubmission::factory()->create();

        $this->assertTrue($post->exists);
        $this->assertNotNull($post->published_at);
        $this->assertTrue($member->is_published);
        $this->assertTrue($partner->exists);
        $this->assertNull($submission->read_at);
    }

    public function test_gallery_album_has_ordered_items(): void
    {
        $album = GalleryAlbum::query()->create([
            'title' => 'Campus',
            'slug' => 'campus',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        GalleryItem::query()->create([
            'album_id' => $album->id,
            'image_path' => 'galleries/b.jpg',
            'sort_order' => 2,
        ]);

        GalleryItem::query()->create([
            'album_id' => $album->id,
            'image_path' => 'galleries/a.jpg',
            'sort_order' => 1,
        ]);

        $items = $album->fresh()->items;

        $this->assertCount(2, $items);
        $this->assertSame('galleries/a.jpg', $items->first()->image_path);
    }

    public function test_menu_items_relate_to_pages(): void
    {
        $page = Page::factory()->create(['type' => PageType::About]);
        $menu = Menu::query()->create([
            'name' => 'Primary',
            'location' => 'primary',
        ]);

        $item = MenuItem::query()->create([
            'menu_id' => $menu->id,
            'label' => 'About',
            'type' => MenuItemType::Page,
            'page_id' => $page->id,
            'sort_order' => 1,
        ]);

        $this->assertTrue($menu->items->contains($item));
        $this->assertTrue($item->page->is($page));
        $this->assertSame(MenuItemType::Page, $item->type);
    }
}
