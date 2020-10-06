<?php

namespace Modules\Page\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Page\Repositories\PageRepository;

class PageDatabaseSeeder extends Seeder
{
    /**
     * @var PageRepository
     */
    private $page;

    public function __construct(PageRepository $page)
    {
        $this->page = $page;
    }

    public function run()
    {
        Model::unguard();
  
      $page = $this->page->findByAttributes(["is_home" => 1]);
      
      if(!isset($page->id)){
        $data = [
          'template' => 'default',
          'is_home' => 1,
          'en' => [
            'title' => 'Home page',
            'slug' => 'home',
            'body' => '<p><strong>You made it!</strong></p>
<p>You&#39;ve installed AsgardCMS and are ready to proceed to the <a href="/en/backend">administration area</a>.</p>
<h2>What&#39;s next ?</h2>
<p>Learn how you can develop modules for AsgardCMS by reading our <a href="https://github.com/AsgardCms/Documentation">documentation</a>.</p>
',
            'meta_title' => 'Home page',
          ],
          'es' => [
            'title' => 'Página de Inicio',
            'slug' => 'inicio',
            'body' => '<p><strong>Lo lograste!</strong></p>
<p>has instalado el ImaginaCMS, vé ahora al <a href="/iadmin">área de administración</a>.</p>',
            'meta_title' => 'Página de Inicio',
          ],
        ];
        $this->page->create($data);
      }
      
    }
}
