<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Processes Model
 *
 * @property \App\Model\Table\DepartmentsTable&\Cake\ORM\Association\BelongsTo $Departments
 * @property \App\Model\Table\StandardsTable&\Cake\ORM\Association\BelongsTo $Standards
 * @property \App\Model\Table\JobsTable&\Cake\ORM\Association\BelongsTo $Jobs
 *
 * @method \App\Model\Entity\Process newEmptyEntity()
 * @method \App\Model\Entity\Process newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Process[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Process get($primaryKey, $options = [])
 * @method \App\Model\Entity\Process findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Process patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Process[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Process|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Process saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Process[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Process[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Process[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Process[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProcessesTable extends AppTable
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

        $this->setTable('processes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Departments', [
            'foreignKey' => 'department_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Standards', [
            'foreignKey' => 'standard_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Jobs', [
            'foreignKey' => 'job_id',
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
            ->nonNegativeInteger('department_id')
            ->notEmptyString('department_id');

        $validator
            ->nonNegativeInteger('standard_id')
            ->notEmptyString('standard_id');

        $validator
            ->scalar('process_code')
            ->maxLength('process_code', 255)
            ->allowEmptyString('process_code');

        $validator
            ->dateTime('start_date')
            ->allowEmptyDateTime('start_date');

        $validator
            ->integer('duration')
            ->allowEmptyString('duration');

        $validator
            ->integer('prereq')
            ->allowEmptyString('prereq');

        $validator
            ->integer('department_priority')
            ->allowEmptyString('department_priority');

        $validator
            ->allowEmptyString('complete');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->nonNegativeInteger('job_id')
            ->notEmptyString('job_id');

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
        $rules->add($rules->existsIn('department_id', 'Departments'), ['errorField' => 'department_id']);
        $rules->add($rules->existsIn('standard_id', 'Standards'), ['errorField' => 'standard_id']);
        $rules->add($rules->existsIn('job_id', 'Jobs'), ['errorField' => 'job_id']);

        return $rules;
    }
}
