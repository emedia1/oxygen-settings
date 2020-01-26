<?php

namespace EMedia\Settings\Entities;

use Storage;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'setting_value',
        'description',
        'input_type',
        'input_options',
    ];

    /**
     * Generates a readable name using the setting_key
     * @return string Readable name
     */
    public function getNameAttribute()
    {
        return ucwords(strtolower(str_replace('_', ' ', $this->setting_key)));
    }

    /**
     * Returns formatted value of the setting
     * @return mixed
     */
    public function getValueAttribute()
    {
        switch ($this->input_type) {
            case 'file':
            case 'image':
            case 'pdf':
            case 'audio':
            case 'video':
                return Storage::disk('public')->url($this->setting_value);
                break;
            case 'list':
                return empty($this->setting_value) ? null : explode("\n", preg_replace("/[\r\n]+/", "\n", $this->setting_value));
                break;
            case 'radio':
            case 'text':
            case 'textarea':
            default:
                return $this->setting_value;
                break;
        }

        return null;
    }

    /**
     * Returns true if the input type is a file
     * @return bool
     */
    public function isFileInput()
    {
        return in_array($this->input_type, ['file', 'image', 'pdf', 'audio', 'video']);
    }

    /**
     * Returns true if the input type should be a textarea
     * @return bool
     */
    public function isTextareaInput()
    {
        return in_array($this->input_type, ['textarea', 'list', 'editor']);
    }
}
