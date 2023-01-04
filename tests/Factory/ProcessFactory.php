<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * ProcessFactory
 *
 * @method \App\Model\Entity\Process getEntity()
 * @method \App\Model\Entity\Process[] getEntities()
 * @method \App\Model\Entity\Process|\App\Model\Entity\Process[] persist()
 * @method static \App\Model\Entity\Process get(mixed $primaryKey, array $options = [])
 */
class ProcessFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Processes';
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
                'start_date' => $faker->dateTimeThisMonth,
                'duration' => $faker->numberBetween(15, 120),
                'name' => $faker->word
            ];
        })
        ->withDepartments();
    }

    /**
     * @param array|callable|null|int $parameter
     * @return ProcessFactory
     */
    public function withDepartments($parameter = null): ProcessFactory
    {
        return $this->with(
            'Departments',
            \App\Test\Factory\DepartmentFactory::make($parameter)
        );
    }

    /**
     * @param array|callable|null|int $parameter
     * @return ProcessFactory
     */
    public function withStandards($parameter = null): ProcessFactory
    {
        return $this->with(
            'Standards',
            \App\Test\Factory\StandardFactory::make($parameter)
        );
    }

    /**
     * @param array|callable|null|int $parameter
     * @return ProcessFactory
     */
    public function withJobs($parameter = null): ProcessFactory
    {
        return $this->with(
            'Jobs',
            \App\Test\Factory\JobFactory::make($parameter)
        );
    }
}
