<?php
declare(strict_types=1);

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2020 Juan Pablo Ramirez and Nicolas Masson
 * @link          https://webrider.de/
 * @since         1.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace CakephpFixtureFactories\Test\TestCase\Event;

use Cake\ORM\TableRegistry;
use CakephpFixtureFactories\Event\ModelEventsHandler;
use TestApp\Model\Table\ArticlesTable;
use TestApp\Model\Table\CountriesTable;

class ModelEventsHandlerTest extends \Cake\TestSuite\TestCase
{
    /**
     * @var ArticlesTable
     */
    private $Articles;

    /**
     * @var CountriesTable
     */
    private $Countries;

    public function setUp()
    {
        $this->Articles = TableRegistry::getTableLocator()->get('Articles');
        $this->Countries = TableRegistry::getTableLocator()->get('Countries');
    }

    public function tearDown()
    {
        TableRegistry::getTableLocator()->clear();
        unset($this->Articles);
        unset($this->Countries);
        parent::tearDown();
    }

    public function testBeforeMarshalOnTable()
    {

        $country = $this->Countries->newEntity(['name' => 'Foo']);
        $this->assertTrue($country->beforeMarshalTriggered);
    }

    public function testBeforeMarshalOnTableHandled()
    {
        ModelEventsHandler::handle($this->Countries);
        $country = $this->Countries->newEntity(['name' => 'Foo']);
        $this->assertNull($country->beforeMarshalTriggered);
    }
    public function testBeforeMarshalOnTableHandledPermissive()
    {
        ModelEventsHandler::handle($this->Countries, ['Model.beforeMarshal']);
        $country = $this->Countries->newEntity(['name' => 'Foo']);
        $this->assertTrue($country->beforeMarshalTriggered);
    }

    public function testBeforeSaveInBehaviorOnTable()
    {
        $article = $this->Articles->newEntity(['title' => 'Foo']);
        $this->Articles->saveOrFail($article);
        $this->assertTrue($article->beforeSaveInBehaviorTriggered);
    }

    public function testBeforeSaveInBehaviorOnTableHandled()
    {
        ModelEventsHandler::handle($this->Articles);
        $article = $this->Articles->newEntity(['title' => 'Foo']);
        $this->Articles->saveOrFail($article);
        $this->assertNull($article->beforeSaveInBehaviorTriggered);
    }

    public function testBeforeSaveInBehaviorOnTableHandledPermissive()
    {
        ModelEventsHandler::handle($this->Articles, [], ['Sluggable']);
        $article = $this->Articles->newEntity(['title' => 'Foo']);
        $this->Articles->saveOrFail($article);
        $this->assertTrue($article->beforeSaveInBehaviorTriggered);
    }
}
