<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StandardsTemplates Model
 *
 * @property \App\Model\Table\StandardsTable&\Cake\ORM\Association\BelongsTo $Standards
 * @property \App\Model\Table\TemplatesTable&\Cake\ORM\Association\BelongsTo $Templates
 *
 * @method \App\Model\Entity\StandardsTemplate newEmptyEntity()
 * @method \App\Model\Entity\StandardsTemplate newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\StandardsTemplate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StandardsTemplate get($primaryKey, $options = [])
 * @method \App\Model\Entity\StandardsTemplate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\StandardsTemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StandardsTemplate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\StandardsTemplate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StandardsTemplate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StandardsTemplate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\StandardsTemplate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\StandardsTemplate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\StandardsTemplate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class StandardsTemplatesTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('standards_templates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Standards', [
            'foreignKey' => 'standard_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Templates', [
            'foreignKey' => 'template_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('standard_id')
            ->notEmptyString('standard_id');

        $validator
            ->nonNegativeInteger('template_id')
            ->notEmptyString('template_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('standard_id', 'Standards'), ['errorField' => 'standard_id']);
        $rules->add($rules->existsIn('template_id', 'Templates'), ['errorField' => 'template_id']);

        return $rules;
    }
}
