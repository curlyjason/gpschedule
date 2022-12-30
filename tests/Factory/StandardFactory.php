<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * StandardFactory
 *
 * @method \App\Model\Entity\Standard getEntity()
 * @method \App\Model\Entity\Standard[] getEntities()
 * @method \App\Model\Entity\Standard|\App\Model\Entity\Standard[] persist()
 * @method static \App\Model\Entity\Standard get(mixed $primaryKey, array $options = [])
 */
class StandardFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Standards';
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
                'process_code' => $faker->numberBetween(1000,2000),
                'name' => $faker->word
            ];
        });
    }
}
