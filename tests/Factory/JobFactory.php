<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * JobFactory
 *
 * @method \App\Model\Entity\Job getEntity()
 * @method \App\Model\Entity\Job[] getEntities()
 * @method \App\Model\Entity\Job|\App\Model\Entity\Job[] persist()
 * @method static \App\Model\Entity\Job get(mixed $primaryKey, array $options = [])
 */
class JobFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Jobs';
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
                'due_date' => $faker->dateTimeThisMonth,
            ];
        });
    }
}
