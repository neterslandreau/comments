<?php
namespace Comments\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

/**
 * Comment Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentComment
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $ChildComment
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

        $this->table(Configure::read('Comments.table'));
        $this->displayField(Configure::read('Comments.displayField'));
        $this->primaryKey(Configure::read('Comments.primaryKey'));

        $this->belongsTo('ParentComments', [
            'className' => Configure::read('Comments.class'),
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsTo('Comments', [
            'className' => Configure::read('Comments.class'),
            'foreignKey' => 'foreign_key',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'counterCache' => true,
            'dependent' => false
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => Configure::read('Comments.usersClass'),
            'conditions' => '',
            'fields' => '',
            'counterCache' => true,
            'order' => '',

        ]);
        $this->hasMany('ChildComment', [
            'className' => Configure::read('Comments.class'),
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Comments', [
            'className' => Configure::read('Comments.class'),
            'foreignKey' => 'foreign_key',
            'unique' => true,
            'conditions' => true,
            'fields' => '',
            'dependent' => true,
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''

        ]);

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');
        $this->addBehavior('Sluggable');

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
            ->uuid('foreign_key')
            ->requirePresence('foreign_key', 'create')
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

    /**
     * Determine if a comment is owned by a user
     *
     * @param $commendId
     * @param $userId
     */
    public function isOwnedBy($commentId, $userId)
    {
        return $this->exists(['id' => $commentId, 'user_id' => $userId]);
    }
}
