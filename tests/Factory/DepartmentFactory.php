<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * DepartmentFactory
 *
 * @method \App\Model\Entity\Department getEntity()
 * @method \App\Model\Entity\Department[] getEntities()
 * @method \App\Model\Entity\Department|\App\Model\Entity\Department[] persist()
 * @method static \App\Model\Entity\Department get(mixed $primaryKey, array $options = [])
 */
class DepartmentFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Departments';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'name' => $faker->word
            ];
        });
    }
}
