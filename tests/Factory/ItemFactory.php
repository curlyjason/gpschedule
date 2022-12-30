<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ItemFactory
 *
 * @method \App\Model\Entity\Item getEntity()
 * @method \App\Model\Entity\Item[] getEntities()
 * @method \App\Model\Entity\Item|\App\Model\Entity\Item[] persist()
 * @method static \App\Model\Entity\Item get(mixed $primaryKey, array $options = [])
 */
class ItemFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Items';
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
                'flow_id' => $faker->numberBetween(10000, 20000),
                'status' => $faker->word,
                'turnaround' => $faker->numberBetween(3,15) . ' days',
                'quantity' => $faker->numberBetween(250, 5000),
                'press' => $faker->word,
                'item_code' => $faker->numberBetween(10000, 20000),
                'item_description' => $faker->sentence(5),
                'due_date' => $faker->dateTimeThisMonth,
                'customer_name' => $faker->company
            ];
        });
    }
}
