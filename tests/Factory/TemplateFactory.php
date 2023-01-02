<?php
declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * templateFactory
 *
 * @method \App\Model\Entity\Template getEntity()
 * @method \App\Model\Entity\Template[] getEntities()
 * @method \App\Model\Entity\Template|\App\Model\Entity\Template[] persist()
 * @method static \App\Model\Entity\Template get(mixed $primaryKey, array $options = [])
 */
class TemplateFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'templates';
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
                // set the model's default values
                // For example:
                // 'name' => $faker->lastName
            ];
        });
    }

    /**
     * @param array|callable|null|int $parameter
     * @param int $n
     * @return TemplateFactory
     */
    public function withStandards($parameter = null, int $n = 1): TemplateFactory
    {
        return $this->with(
            'Standards',
            \App\Test\Factory\StandardFactory::make($parameter, $n)
                ->without('templates')
                ->withDepartments()
        );
    }
}
