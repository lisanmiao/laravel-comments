<?php

namespace tizis\laraComments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'comment' => self::preprocessor($this->comment),
            'created_at' => $this->created_at->timestamp,
            'commenter_id' => $this->commenter_id,
            'rating' => $this->rating,
            'commenter' => [
                'id' => $this->commenter->id,
                'avatar' => self::getAvatar(),
                'name' => $this->commenter->name,
                'email' => $this->commenter->email
            ],
            'children' => self::collection($this->children)
        ];
    }

    /**
     * @param string $comment
     * @return string
     */
    protected static function preprocessor(string $comment) {
        $config = config('comments.api.get.comment.preprocessor');

        if (is_callable($config)) {
            $comment = $config($comment);
        }
        return $comment;
    }

    protected function getAvatar()
    {
        if (method_exists($this->commenter, 'getAvatar')) {
            return $this->commenter->getAvatar();
        }
        return null;
    }
}