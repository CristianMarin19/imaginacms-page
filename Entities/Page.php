<?php

namespace Modules\Page\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\NamespacedEntity;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Tag\Contracts\TaggableInterface;
use Modules\Tag\Traits\TaggableTrait;
use Modules\Isite\Traits\Typeable;
use Modules\Core\Icrud\Traits\hasEventsWithBindings;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Modules\Ifillable\Traits\isFillable;

class Page extends Model implements TaggableInterface
{
  use Translatable, TaggableTrait, NamespacedEntity, MediaRelation, hasEventsWithBindings, Typeable, BelongsToTenant, isFillable;
  
  protected $table = 'page__pages';
  public $translatedAttributes = [
    'page_id',
    'title',
    'slug',
    'status',
    'body',
    'meta_title',
    'meta_description',
    'og_title',
    'og_description',
    'og_image',
    'og_type',
    'content',
  ];
  protected $fillable = [
    'is_home',
    'template',
    'organization_id',
    // Translatable fields
    'page_id',
    'record_type',
    'type',
    'system_name',
    'internal',
    'options',
  ];
  
  protected $casts = [
    'is_home' => 'boolean',
    'options' => 'array',
  ];
  
  protected $with = [
    'fields'
  ];
  protected static $entityNamespace = 'asgardcms/page';
  
  public function getCanonicalUrl(): string
  {
    if ($this->is_home === true) {
      return url('/');
    }
    
    return route('page', $this->slug);
  }
  
  public function getEditUrl(): string
  {
    return route('admin.page.page.edit', $this->id);
  }
  
  public function __call($method, $parameters)
  {
    #i: Convert array to dot notation
    $config = implode('.', ['asgard.page.config.relations', $method]);
    
    #i: Relation method resolver
    if (config()->has($config)) {
      $function = config()->get($config);
      
      return $function($this);
    }
    
    #i: No relation found, return the call to parent (Eloquent) to handle it.
    return parent::__call($method, $parameters);
  }
  
  public function getImageAttribute()
  {
    $thumbnail = $this->files()->where('zone', 'mainimage')->first();
    
    if ($thumbnail === null) {
      return '';
    }
    
    return $thumbnail;
  }
  
  public function getUrlAttribute()
  {
    return \LaravelLocalization::localizeUrl( '/' . $this->slug);
  }
  
  public function getOptionsAttribute($value)
  {
    try {
      return json_decode(json_decode($value));
    } catch (\Exception $e) {
      return json_decode($value);
    }
  }
}
