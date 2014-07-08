<?php namespace Folklore\EloquentPicturable\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    public function __construct(array $attributes = array())
    {
        $this->table = \Config::get('eloquent-picturable::database_prefix').$this->table;

        parent::__construct($attributes);
    }
}
