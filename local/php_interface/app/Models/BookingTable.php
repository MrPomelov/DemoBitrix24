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

use Models\ClientsTable as ClientsTable;
use Models\Lists\DoctorsPropertyValuesTable as DoctorsPropertyValuesTable;
use Models\Lists\ProceduresPropertyValuesTable as ProceduresPropertyValuesTable;
/**
 * Class Table
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> date date optional
 * <li> client_id int optional
 * <li> doctor_id int optional
 * <li> procedur_id int optional
 * </ul>
 *
 * @package Bitrix\
 **/

class BookingTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'booking';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'id' => (new IntegerField('id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_ID_FIELD'))
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
			'date' => (new DateField('date',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_DATE_FIELD'))
			,
			'client_id' => (new IntegerField('client_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_CLIENT_ID_FIELD'))
						->configureSize(1)
			,
            (new ReferenceField(
                'CLIENT',
                ClientsTable::class,
                Join::on('this.client_id', 'ref.id'))
            )->configureJoinType('left'),

			'doctor_id' => (new IntegerField('doctor_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_DOCTOR_ID_FIELD'))
						->configureSize(1)
			,
            // (new ReferenceField(
            //     'DOCTOR',
            //     DoctorsPropertyValuesTable::class,
            //     Join::on('=this.doctor_id', 'ref.IBLOCK_ELEMENT_ID'))
            // )->configureJoinType('inner'),
            'DOCTOR' => new ReferenceField(
                'DOCTOR', 
                DoctorsPropertyValuesTable::class, 
                ['=this.doctor_id' => 'ref.IBLOCK_ELEMENT_ID']
            ),

			'procedur_id' => (new IntegerField('procedur_id',
					[]
				))->configureTitle(Loc::getMessage('_ENTITY_PROCEDUR_ID_FIELD'))
			,
            // (new ReferenceField(
            //     'procedur',
            //     ProceduresPropertyValuesTable::class,
            //     Join::on('this.procedur_id', 'ref.ID'))
            // )->configureJoinType('inner'),
            'PROCEDUR' => new ReferenceField(
                'PROCEDUR', 
                ProceduresPropertyValuesTable::class, 
                ['=this.procedur_id' => 'ref.IBLOCK_ELEMENT_ID']
            ),
		];
	}
}