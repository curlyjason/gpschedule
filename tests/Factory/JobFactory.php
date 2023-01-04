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
                'due_date' => '2022-12-30 22:20:48',
                'job_number' => '23-10045',
                'created' => '2022-12-30 22:20:48',
                'modified' => '2022-12-30 22:20:48',
            ];
        })
        ->withItems();
    }

    /**
     * @param array|callable|null|int $parameter
     * @return JobFactory
     */
    public function withItems($parameter = null): JobFactory
    {
        return $this->with(
            'Items',
            ItemFactory::make($parameter)
        );
    }

    /**
     * @param array|callable|null|int $parameter
     * @param int $n
     * @return JobFactory
     */
    public function withProcesses($parameter = null, int $n = 1): JobFactory
    {
        return $this->with(
            'Processes',
            ProcessFactory::make($parameter, $n)
        );
    }
}
