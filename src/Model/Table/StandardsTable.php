<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Standards Model
 *
 * @property \App\Model\Table\DepartmentsTable&\Cake\ORM\Association\BelongsTo $Departments
 * @property \App\Model\Table\ProcessesTable&\Cake\ORM\Association\HasMany $Processes
 * @property \App\Model\Table\TemplatesTable&\Cake\ORM\Association\BelongsToMany $Templates
 *
 * @method \App\Model\Entity\Standard newEmptyEntity()
 * @method \App\Model\Entity\Standard newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Standard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Standard get($primaryKey, $options = [])
 * @method \App\Model\Entity\Standard findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Standard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Standard[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Standard|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Standard saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Standard[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Standard[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Standard[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Standard[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StandardsTable extends AppTable
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

        $this->setTable('standards');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Departments', [
            'foreignKey' => 'department_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Processes', [
            'foreignKey' => 'standard_id',
        ]);
        $this->belongsToMany('Templates', [
            'foreignKey' => 'standard_id',
            'targetForeignKey' => 'template_id',
//            'joinTable' => 'standards_templates',
            'through' => 'standards_templates'
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
            ->scalar('process_code')
            ->maxLength('process_code', 255)
            ->allowEmptyString('process_code');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->scalar('uom')
            ->maxLength('uom', 255)
            ->allowEmptyString('uom');

        $validator
            ->numeric('units_per_hour')
            ->allowEmptyString('units_per_hour');

        $validator
            ->integer('daily_capacity')
            ->allowEmptyString('daily_capacity');

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

        return $rules;
    }
}
