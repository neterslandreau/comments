<?php
namespace Comments\Model\Entity;

use Cake\ORM\Entity;

/**
 * Comment Entity
 *
 * @property string $id
 * @property string $parent_id
 * @property string $foreignKey
 * @property string $user_id
 * @property int $lft
 * @property int $right
 * @property string $model
 * @property bool $approved
 * @property string $title
 * @property string $slug
 * @property string $body
 * @property string $author_name
 * @property string $author_url
 * @property string $author_email
 * @property string $language
 * @property string $is_spam
 * @property string $comment_type
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Comments\Model\Entity\ParentComment $parent_comment
 * @property \Comments\Model\Entity\User $user
 * @property \Comments\Model\Entity\ChildComment[] $child_comment
 * @property \Comments\Model\Entity\Phinxlog[] $phinxlog
 */
class Comment extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
