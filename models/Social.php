<?php

namespace MainSocial;
use \Model\Model;


defined('ROOT') or die ("Direct script access denied.");

class Social extends Model
{
    protected $table = 'social_links';

    public $primary_key = 'id';

    protected $allowedColumns = [
        'title', 
        'slug',
        'icon',
        'image',
        'disabled',
        'permission',
        'list_order',
    ];
    protected $allowedUpdateColumns = [
        'title', 
        'slug',
        'icon',
        'image',
        'disabled',
        'permission',
        'list_order',
    ];

    public function validate_insert(array $data) : bool
    {
        if (empty($data['title'])) {
            $this->errors['title'] = 'Title is required';
        } else 
        if ($this->first(['title'=>$data['title']])) {
            $this->errors['title'] = 'That title is already in use';
        } else 
        if (!preg_match("/^[a-zA-Zá-žÁ-Ž0-9,.@ \-]+$/u", trim($data['title']))) {
            $this->errors['title'] = 'Title does not allowed special characters';
        } 
        return empty($this->errors);
    }
    public function validate_update(array $data) :bool
    {
        $email_arr = ['title'=>$data['title']];
        $email_arr_not = [$this->primary_key=>$data[$this->primary_key] ?? 0];

        if (empty($data['title'])) {
            $this->errors['title'] = 'Title is required';
        } else 
        if ($this->first($email_arr, $email_arr_not)) {
            $this->errors['title'] = 'That title is already in use';
        } else 
        if (!preg_match("/^[a-zA-Zá-žÁ-Ž0-9,.@ \-]+$/u", trim($data['title']))) {
            $this->errors['title'] = 'Title does not allowed special characters';
        } 
        return empty($this->errors); 
    }

}