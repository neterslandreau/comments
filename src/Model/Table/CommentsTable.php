<?php
namespace Comments\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use CakeDC\Users\Model\Table as Users;

/**
 * Comment Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentComment
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $ChildComment
 * @property \Cake\ORM\Association\BelongsToMany $Phinxlog
 *
 * @method \Comments\Model\Entity\Comment get($primaryKey, $options = [])
 * @method \Comments\Model\Entity\Comment newEntity($data = null, array $options = [])
 * @method \Comments\Model\Entity\Comment[] newEntities(array $data, array $options = [])
 * @method \Comments\Model\Entity\Comment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Comments\Model\Entity\Comment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Comments\Model\Entity\Comment[] patchEntities($entities, array $data, array $options = [])
 * @method \Comments\Model\Entity\Comment findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CommentsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('comments');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');
        $this->addBehavior('Sluggable');

        $this->belongsTo('ParentComments', [
            'className' => 'Comments.Comments',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Users.Users'
        ]);
        $this->hasMany('ChildComment', [
            'className' => 'Comments.Comments',
            'foreignKey' => 'parent_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->uuid('foreignKey')
            ->requirePresence('foreignKey', 'create')
            ->notEmpty('foreignKey');

        $validator
            ->add('lft', 'valid', ['rule' => 'numeric'])
            ->notEmpty('lft');

        $validator
            ->add('rght', 'valid', ['rule' => 'numeric'])
            ->notEmpty('rght');

        $validator
            ->requirePresence('model', 'create')
            ->notEmpty('model');

        $validator
            ->boolean('approved')
            ->requirePresence('approved', 'create')
            ->notEmpty('approved');

        $validator
            ->allowEmpty('title');

        $validator
            ->allowEmpty('slug');

        $validator
            ->notEmpty('body');

        $validator
            ->allowEmpty('author_name');

        $validator
            ->allowEmpty('author_url');

        $validator
            ->allowEmpty('author_email');

        $validator
            ->allowEmpty('language');

        $validator
            ->requirePresence('is_spam', 'create')
            ->notEmpty('is_spam');

        $validator
            ->requirePresence('comment_type', 'create')
            ->notEmpty('comment_type');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentComments'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
