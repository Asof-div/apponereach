<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PlayMediaResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // 'data' => $this->collection,
            "id" => $this->id,
            'application' => $this->application,
            "category" => $this->category,
            "content" => $this->content,
            "dial_code" => $this->dial_code,
            "error" => $this->error,
            "exist" => $this->exist,
            "ext" => $this->ext,
            "filename" => $this->filename,
            "mime_type" => $this->mime_type,
            "original_name" => $this->original_name,
            'path' => $this->category == 'image' || $this->category == 'audio' ? asset('storage/' .$this->path) : $this->path,
            'tenant_id' => $this->tenant_id,
            'title' => $this->title,
            'size' => $this->size(),
            'source' => $this->category == 'image' ? asset('storage/' .$this->source) : $this->source,
            'voice_code' => $this->voice_code,
            'original_content' => $this->category == 'tts' ? $this->content : $this->path,
        ];
    }
}
