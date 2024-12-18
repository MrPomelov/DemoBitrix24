<?php
namespace Models;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;

/**
 * Class ClientsTable
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> name string(50) optional
 * <li> sex text optional
 * <li> birth_date date optional
 * <li> inn string(50) optional
 * <li> contact_id int optional
 * </ul>
 *
 * @package Bitrix\
 **/

class ClientsTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'clients';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
        //Serialization of 'Closure' is not allowed (0)
        // $cache = Cache::createInstance();
        // $cacheDir = 'orm_table/clients';
        // $multipleValuesTableClass = static::getMultipleValuesTableClass();
        // static::initMultipleValuesTableClass();

        // if ($cache->initCache(3600, md5($cacheDir), $cacheDir)) {
        //     $map = $cache->getVars();
        //     $map['fromCache'] = true;

        // } else {
        //     $cache->startDataCache();

            $map = [
                'id' => (new IntegerField('id',
                        []
                    ))->configureTitle(Loc::getMessage('_ENTITY_ID_FIELD'))
                            ->configurePrimary(true)
                            ->configureAutocomplete(true)
                ,
                'name' => (new StringField('name',
                        [
                            'validation' => function()
                            {
                                return[
                                    new LengthValidator(null, 50),
                                ];
                            },
                        ]
                    ))->configureTitle(Loc::getMessage('_ENTITY_NAME_FIELD'))
                ,
                'sex' => (new TextField('sex',
                        []
                    ))->configureTitle(Loc::getMessage('_ENTITY_SEX_FIELD'))
                ,
                'birth_date' => (new DateField('birth_date',
                        []
                    ))->configureTitle(Loc::getMessage('_ENTITY_BIRTH_DATE_FIELD'))
                ,
                'inn' => (new StringField('inn',
                        [
                            'validation' => function()
                            {
                                return[
                                    new LengthValidator(null, 50),
                                ];
                            },
                        ]
                    ))->configureTitle(Loc::getMessage('_ENTITY_INN_FIELD'))
                ,
                'contact_id' => (new IntegerField('contact_id',
                        []
                    ))->configureTitle(Loc::getMessage('_ENTITY_CONTACT_ID_FIELD'))
                ,
                (new ReferenceField(
                    'CONTACT',
                    \Bitrix\CRM\ContactTable::class,
                    Join::on('this.contact_id', 'ref.ID'))
                )->configureJoinType('inner'),
            ];

        //     if (empty($map)) {
        //         $cache->abortDataCache();
        //     } else {
        //         $cache->endDataCache($map);
        //     }
        // }
		return $map;
	}
}